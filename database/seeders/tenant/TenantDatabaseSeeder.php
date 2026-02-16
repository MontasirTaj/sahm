<?php

namespace Database\Seeders\Tenant;

use App\Models\TenantPermission;
use App\Models\TenantRole;
use App\Models\TenantSetting;
use App\Models\TenantUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure tenant share_offers table exists and is aligned
        if (! Schema::connection('tenant')->hasTable('share_offers')) {
            Schema::connection('tenant')->create('share_offers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->string('title');
                $table->string('title_ar')->nullable();
                $table->text('description');
                $table->text('description_ar')->nullable();
                $table->string('country')->nullable();
                $table->string('city');
                $table->string('address')->nullable();
                $table->unsignedBigInteger('total_shares');
                $table->unsignedBigInteger('available_shares');
                $table->unsignedBigInteger('sold_shares')->default(0);
                $table->decimal('price_per_share', 12, 2);
                $table->string('currency', 8)->default('SAR');
                $table->string('status', 32)->default('active');
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->string('cover_image')->nullable();
                $table->json('media')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // Create default permissions
        // حذف أي صلاحيات أو أدوار متعلقة بالمرفقات أو الأسهم القديمة
        $oldPerms = [
            'Attachement', 'add shares', 'edit shares', 'delete shares', 'manage shares',
            'upload_attachment', 'update_attachment', 'delete_attachment',
        ];
        foreach ($oldPerms as $perm) {
            $p = TenantPermission::where('name', $perm)->first();
            if ($p) {
                $p->delete();
            }
        }
        $oldRoles = ['shares'];
        foreach ($oldRoles as $role) {
            $r = TenantRole::where('name', $role)->first();
            if ($r) {
                $r->delete();
            }
        }

        // إضافة صلاحيات ودور Sahm
        $sahmPerms = ['Add_sahm', 'Update_sahm', 'Delete_sahm'];
        foreach ($sahmPerms as $p) {
            TenantPermission::firstOrCreate(['name' => $p, 'guard_name' => 'tenant']);
        }
        $sahmRole = TenantRole::firstOrCreate(['name' => 'Sahm', 'guard_name' => 'tenant']);
        $sahmRole->syncPermissions($sahmPerms);

        // Create admin role and assign all permissions
        $adminRole = TenantRole::firstOrCreate(['name' => 'admin', 'guard_name' => 'tenant']);
        $adminRole->syncPermissions(TenantPermission::all());

        // Bootstrap admin user (prefer per-tenant values passed via config)
        $configEmail = config('tenant.provision.admin_email');
        $configName = config('tenant.provision.admin_name');

        $adminEmail = $configEmail ?: (env('TENANT_ADMIN_EMAIL') ?: 'admin@test.test');
        $adminName = $configName ?: 'Administrator';
        // استخدم كلمة مرور ثابتة من env أو القيمة الافتراضية
        $plainPassword = env('TENANT_ADMIN_PASSWORD') ?: 'Admin@123456';

        $user = TenantUser::firstOrCreate(
            ['email' => $adminEmail],
            ['name' => $adminName, 'password' => Hash::make($plainPassword)]
        );
        // ألزمه بتغيير كلمة المرور فقط إذا تم إنشاؤه الآن لأول مرة
        if ($user->wasRecentlyCreated) {
            $user->must_change_password = true;
            $user->save();
        }
        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        // Ensure a default tenant setting exists (name/logo/color)
        TenantSetting::firstOrCreate(
            [],
            [
                'name' => config('app.name', 'Tenant Workspace'),
                'primary_color' => '#102c4f',
                'logo_path' => null,
            ]
        );
    }
}
