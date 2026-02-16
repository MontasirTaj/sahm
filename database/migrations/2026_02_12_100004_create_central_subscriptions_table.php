<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('subscriptions')) {
            return;
        }
        Schema::connection($this->connection)->create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('type');
            $table->string('stripe_id')->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'stripe_status']);
        });
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasTable('subscriptions')) {
            Schema::connection($this->connection)->dropIfExists('subscriptions');
        }
    }
};
