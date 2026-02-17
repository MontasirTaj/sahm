<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('central')->create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 100)->comment('اسم المدينة بالعربية');
            $table->string('name_en', 100)->nullable()->comment('اسم المدينة بالإنجليزية');
            $table->string('region', 100)->nullable()->comment('المنطقة');
            $table->boolean('is_active')->default(true)->comment('نشط/غير نشط');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('cities');
    }
};
