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
        Schema::connection('central')->create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('buyer_id');
            $table->enum('type', ['deposit', 'withdrawal', 'purchase', 'sale', 'refund', 'commission'])->comment('نوع المعاملة');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2)->comment('الرصيد قبل المعاملة');
            $table->decimal('balance_after', 15, 2)->comment('الرصيد بعد المعاملة');
            $table->string('currency', 3)->default('SAR');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->string('payment_method')->nullable()->comment('wallet, credit_card, bank_transfer');
            $table->text('description')->nullable();
            
            // Reference to related records
            $table->unsignedBigInteger('sale_offer_id')->nullable()->comment('مرجع عرض البيع إن وجد');
            $table->unsignedBigInteger('share_operation_id')->nullable()->comment('مرجع عملية الشراء/البيع إن وجدت');
            $table->unsignedBigInteger('related_buyer_id')->nullable()->comment('المشتري أو البائع الآخر');
            
            $table->json('metadata')->nullable()->comment('بيانات إضافية');
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('buyer_wallets')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            
            $table->index(['wallet_id', 'created_at']);
            $table->index(['buyer_id', 'type']);
            $table->index(['status', 'created_at']);
            $table->index('sale_offer_id');
            $table->index('share_operation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('wallet_transactions');
    }
};
