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
        Schema::connection('central')->create('buyer_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('SAR');
            $table->decimal('pending_balance', 15, 2)->default(0)->comment('المبالغ المعلقة في معاملات قيد التنفيذ');
            $table->decimal('total_deposits', 15, 2)->default(0)->comment('إجمالي الإيداعات');
            $table->decimal('total_withdrawals', 15, 2)->default(0)->comment('إجمالي السحوبات');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->index('buyer_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('buyer_wallets');
    }
};
