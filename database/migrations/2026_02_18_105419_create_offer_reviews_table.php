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
        Schema::connection('central')->create('offer_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->enum('review_type', ['initial', 'real_estate'])->comment('نوع المراجعة');
            $table->enum('decision', ['approved', 'rejected']);
            $table->text('notes')->nullable()->comment('أسباب الرفض أو ملاحظات');
            $table->unsignedBigInteger('reviewed_by')->comment('Admin user ID');
            $table->timestamps();
            
            $table->index('offer_id');
            $table->index(['offer_id', 'review_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('offer_reviews');
    }
};
