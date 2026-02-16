<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('plan_limits')) {
            return;
        }
        Schema::connection($this->connection)->create('plan_limits', function (Blueprint $table) {
            $table->id();
            $table->string('plan', 50)->unique();
            $table->unsignedInteger('max_users')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasTable('plan_limits')) {
            Schema::connection($this->connection)->dropIfExists('plan_limits');
        }
    }
};
