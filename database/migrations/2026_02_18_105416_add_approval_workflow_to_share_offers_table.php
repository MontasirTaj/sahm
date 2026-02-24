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
        Schema::connection('central')->table('share_offers', function (Blueprint $table) {
            $table->enum('approval_status', [
                'draft',
                'pending_approval',
                'approved',
                'rejected',
                'under_real_estate_review',
                'real_estate_approved',
                'real_estate_rejected'
            ])->default('pending_approval')->after('status');
            
            $table->integer('approval_progress')->default(0)->comment('0-100%')->after('approval_status');
            $table->timestamp('submitted_at')->nullable()->after('approval_progress');
            $table->timestamp('first_reviewed_at')->nullable()->after('submitted_at');
            $table->timestamp('real_estate_reviewed_at')->nullable()->after('first_reviewed_at');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('real_estate_reviewed_at');
            $table->text('rejection_notes')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->table('share_offers', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'approval_progress',
                'submitted_at',
                'first_reviewed_at',
                'real_estate_reviewed_at',
                'reviewed_by',
                'rejection_notes'
            ]);
        });
    }
};
