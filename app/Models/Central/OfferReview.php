<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OfferReview extends Model
{
    protected $connection = 'central';
    protected $table = 'offer_reviews';
    
    protected $fillable = [
        'offer_id',
        'review_type',
        'decision',
        'notes',
        'reviewed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع العرض
     */
    public function offer()
    {
        return $this->belongsTo(ShareOffer::class, 'offer_id');
    }

    /**
     * العلاقة مع المراجع (Admin)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get formatted decision
     */
    public function getDecisionTextAttribute(): string
    {
        return match($this->decision) {
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض',
            default => $this->decision
        };
    }

    /**
     * Get review type text
     */
    public function getReviewTypeTextAttribute(): string
    {
        return match($this->review_type) {
            'initial' => 'المراجعة الأولية',
            'real_estate' => 'المراجعة العقارية',
            default => $this->review_type
        };
    }
}
