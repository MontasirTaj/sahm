<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'tenant';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('share_operations')) {
            return;
        }
        Schema::connection($this->connection)->create('share_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('central_operation_id')->nullable()->index();
            $table->unsignedBigInteger('offer_id');
            $table->enum('type', ['purchase','sell','transfer'])->default('purchase')->index();
            $table->unsignedBigInteger('shares_count');
            $table->decimal('price_per_share', 12, 2);
            $table->decimal('amount_total', 14, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('status', 32)->default('pending')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['offer_id','status']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('share_operations');
    }
};
