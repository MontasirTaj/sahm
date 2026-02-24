<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 100);
            $table->string('name_en', 100);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // إضافة بعض الأنواع الافتراضية
        DB::connection('central')->table('property_types')->insert([
            ['name_ar' => 'شقة سكنية', 'name_en' => 'Residential Apartment', 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'فيلا', 'name_en' => 'Villa', 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'عمارة سكنية', 'name_en' => 'Residential Building', 'is_active' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'محل تجاري', 'name_en' => 'Commercial Shop', 'is_active' => true, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'مكتب إداري', 'name_en' => 'Office Space', 'is_active' => true, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'مستودع', 'name_en' => 'Warehouse', 'is_active' => true, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'أرض', 'name_en' => 'Land', 'is_active' => true, 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'مزرعة', 'name_en' => 'Farm', 'is_active' => true, 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('property_types');
    }
};
