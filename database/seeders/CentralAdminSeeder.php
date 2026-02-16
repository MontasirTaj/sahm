<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CentralAdminSeeder extends Seeder
{
    public function run(): void
    {
        $conn = 'central';

        // Ensure base permissions exist in central DB
        $permissionNames = [
            'manage_system',
            'manage_plans',
            'manage_subscriptions',
            'manage_payments',
            'manage_tenants',
            'manage_shares',
            'manage_offers',
            'manage_buyers',
        ];

        foreach ($permissionNames as $name) {
            $existing = DB::connection($conn)->table('admin_permissions')->where('name', $name)->first();
            if (!$existing) {
                DB::connection($conn)->table('admin_permissions')->insert([
                    'name' => $name,
                    'description' => ucwords(str_replace('_', ' ', $name)),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Create superadmin role and attach all permissions
        $role = DB::connection($conn)->table('admin_roles')->where('name', 'superadmin')->first();
        if (!$role) {
            DB::connection($conn)->table('admin_roles')->insert([
                'name' => 'superadmin',
                'description' => 'Central super administrator',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $role = DB::connection($conn)->table('admin_roles')->where('name', 'superadmin')->first();
        }

        $permissions = DB::connection($conn)->table('admin_permissions')->pluck('id')->all();
        foreach ($permissions as $pid) {
            $exists = DB::connection($conn)->table('admin_permission_role')
                ->where('role_id', $role->id)->where('permission_id', $pid)->exists();
            if (!$exists) {
                DB::connection($conn)->table('admin_permission_role')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $pid,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Create admin user in central users table
        $email = 'admin@admin.admin';
        $password = 'Admin@2026';
        $user = DB::connection($conn)->table('users')->where('email', $email)->first();

        if (!$user) {
            DB::connection($conn)->table('users')->insert([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $user = DB::connection($conn)->table('users')->where('email', $email)->first();
        }

        // Attach superadmin role to admin user
        $pivotExists = DB::connection($conn)->table('admin_role_user')
            ->where('role_id', $role->id)->where('user_id', $user->id)->exists();
        if (!$pivotExists) {
            DB::connection($conn)->table('admin_role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Also ensure admin exists in default mysql connection for app login, if different
        try {
            $defaultUser = DB::connection('mysql')->table('users')->where('email', $email)->first();
            if (!$defaultUser) {
                DB::connection('mysql')->table('users')->insert([
                    'name' => 'Admin',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } catch (\Throwable $e) {
            // ignore if default connection isn't configured or table missing
        }
    }
}
