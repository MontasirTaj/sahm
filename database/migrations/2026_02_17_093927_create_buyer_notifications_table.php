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
        Schema::connection('central')->create('buyer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->string('type'); // sale_completed, partial_sale, etc
            $table->string('title');
            $table->text('message');
            $table->foreignId('sale_offer_id')->nullable()->constrained('buyer_sale_offers')->onDelete('cascade');
            $table->foreignId('share_operation_id')->nullable()->constrained('share_operations')->onDelete('cascade');
            $table->foreignId('wallet_transaction_id')->nullable()->constrained('wallet_transactions')->onDelete('cascade');
            $table->json('metadata')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['buyer_id', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('buyer_notifications');
    }
};
