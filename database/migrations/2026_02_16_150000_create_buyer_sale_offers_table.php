<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->create('buyer_sale_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->foreignId('holding_id')->constrained('buyer_holdings')->cascadeOnDelete();
            $table->foreignId('original_offer_id')->constrained('share_offers')->cascadeOnDelete();
            
            $table->unsignedBigInteger('shares_count'); // عدد الأسهم المعروضة
            $table->decimal('price_per_share', 12, 2); // سعر البيع للسهم الواحد
            $table->string('currency', 10)->default('SAR');
            
            $table->enum('status', ['active', 'sold', 'cancelled', 'expired'])->default('active')->index();
            
            // معلومات البيع عند اكتماله
            $table->foreignId('buyer_buyer_id')->nullable()->constrained('buyers')->nullOnDelete(); // المشتري
            $table->decimal('sold_price_per_share', 12, 2)->nullable(); // السعر الفعلي للبيع
            $table->timestamp('sold_at')->nullable();
            
            // معلومات إضافية
            $table->text('description')->nullable(); // وصف من البائع
            $table->timestamp('expires_at')->nullable(); // تاريخ انتهاء العرض
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index(['seller_buyer_id', 'status']);
            $table->index(['original_offer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('buyer_sale_offers');
    }
};
