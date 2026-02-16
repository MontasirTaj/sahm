<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->create('share_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('share_offers')->cascadeOnDelete();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('TenantID')->on('tenants')->cascadeOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('buyers')->nullOnDelete();

            $table->enum('type', ['purchase','sell','transfer'])->default('purchase')->index();
            $table->unsignedBigInteger('shares_count');
            $table->decimal('price_per_share', 12, 2);
            $table->decimal('amount_total', 14, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('status', 32)->default('pending')->index();

            // Optional linkage to payment gateway data
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
            $table->string('external_reference')->nullable()->index();

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['offer_id','buyer_id','status']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('share_operations');
    }
};
