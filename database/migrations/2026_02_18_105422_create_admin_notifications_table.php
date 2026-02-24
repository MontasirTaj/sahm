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
        Schema::connection('central')->create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('new_offer, offer_resubmitted');
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('admin_notifications');
    }
};
