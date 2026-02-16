<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->create('buyer_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
            $table->foreignId('offer_id')->constrained('share_offers')->cascadeOnDelete();
            $table->unsignedBigInteger('shares_owned');
            $table->decimal('avg_price_per_share', 12, 2)->nullable();
            $table->timestamp('last_transaction_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['buyer_id','offer_id']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('buyer_holdings');
    }
};
