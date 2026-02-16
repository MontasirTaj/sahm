<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('alerts')) {
            return;
        }
        Schema::connection($this->connection)->create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('scope', 32)->default('admin')->index(); // admin | tenant
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('type', 64)->index(); // offer_created, purchase_completed, etc.
            $table->string('title');
            $table->text('message')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('alerts');
    }
};
