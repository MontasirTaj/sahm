<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('plans')) {
            return;
        }
        Schema::connection($this->connection)->create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(1);

            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');

            $table->string('name_en');
            $table->string('name_ar');
            $table->string('subtitle_en')->nullable();
            $table->string('subtitle_ar')->nullable();

            $table->text('features_en')->nullable();
            $table->text('features_ar')->nullable();
            $table->text('more_features_en')->nullable();
            $table->text('more_features_ar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasTable('plans')) {
            Schema::connection($this->connection)->dropIfExists('plans');
        }
    }
};
