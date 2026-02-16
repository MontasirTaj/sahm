<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->create('buyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // Optional link to platform user
            $table->string('full_name');
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->nullable()->index();
            $table->string('national_id', 50)->nullable()->index();
            $table->date('date_of_birth')->nullable();
            $table->string('country', 64)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('address')->nullable();
            $table->enum('kyc_status', ['unverified','pending','verified','rejected'])->default('unverified')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['email','phone']);
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('buyers');
    }
};
