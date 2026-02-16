<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'tenant';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('share_offers')) {
            return;
        }

        Schema::connection($this->connection)->create('share_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('central_offer_id')->nullable()->index();

            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('country', 64)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('address')->nullable();

            $table->unsignedBigInteger('total_shares');
            $table->unsignedBigInteger('available_shares');
            $table->unsignedBigInteger('sold_shares')->default(0);
            $table->decimal('price_per_share', 12, 2);
            $table->string('currency', 10)->default('USD');

            $table->enum('status', ['draft','active','paused','completed','cancelled'])->default('draft')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->string('cover_image')->nullable();
            $table->json('media')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('share_offers');
    }
};
