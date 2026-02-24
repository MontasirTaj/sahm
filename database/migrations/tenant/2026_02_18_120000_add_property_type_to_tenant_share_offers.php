<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'tenant';

    public function up(): void
    {
        Schema::connection($this->connection)->table('share_offers', function (Blueprint $table) {
            if (!Schema::connection($this->connection)->hasColumn('share_offers', 'property_type')) {
                $table->string('property_type', 100)->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('share_offers', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('share_offers', 'property_type')) {
                $table->dropColumn('property_type');
            }
        });
    }
};
