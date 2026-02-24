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
        Schema::connection('central')->create('real_estate_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->text('checkpoint_text')->comment('نص النقطة العقارية');
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->comment('Admin user ID');
            $table->timestamps();
            
            $table->index('offer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('real_estate_checkpoints');
    }
};
