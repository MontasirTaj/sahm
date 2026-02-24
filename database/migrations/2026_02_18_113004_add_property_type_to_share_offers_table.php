<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'central';

    public function up(): void
    {
        Schema::connection($this->connection)->table('share_offers', function (Blueprint $table) {
            $table->string('property_type', 100)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('share_offers', function (Blueprint $table) {
            $table->dropColumn('property_type');
        });
    }
};
