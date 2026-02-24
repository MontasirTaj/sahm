<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\User;

class ShareOffer extends Model
{
    protected $connection = 'central';
    protected $table = 'share_offers';
    protected $fillable = [
        'tenant_id',
        'title','title_ar','description','description_ar','country','city','address','property_type',
        'total_shares','available_shares','sold_shares','price_per_share','currency',
        'status',
        'approval_status',
        'approval_progress',
        'submitted_at',
        'first_reviewed_at',
        'real_estate_reviewed_at',
        'reviewed_by',
        'rejection_notes',
        'starts_at','ends_at','cover_image','media','metadata',
    ];

    protected $casts = [
        'media' => 'array',
        'metadata' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'submitted_at' => 'datetime',
        'first_reviewed_at' => 'datetime',
        'real_estate_reviewed_at' => 'datetime',
        'approval_progress' => 'integer',
    ];

    /**
     * العلاقة مع التينانت
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * العلاقة مع المراجعات
     */
    public function reviews()
    {
        return $this->hasMany(OfferReview::class, 'offer_id');
    }

    /**
     * العلاقة مع النقاط العقارية
     */
    public function realEstateCheckpoints()
    {
        return $this->hasMany(RealEstateCheckpoint::class, 'offer_id')->orderBy('sort_order');
    }

    /**
     * Get approval status text
     */
    public function getApprovalStatusTextAttribute(): string
    {
        return match($this->approval_status) {
            'draft' => 'مسودة',
            'pending_approval' => 'قيد المراجعة الأولية',
            'approved' => 'تمت الموافقة الأولية',
            'rejected' => 'مرفوض',
            'under_real_estate_review' => 'قيد المراجعة العقارية',
            'real_estate_approved' => 'معتمد (جاهز للنشر)',
            'real_estate_rejected' => 'مرفوض عقارياً',
            default => $this->approval_status
        };
    }

    /**
     * Get status text (ترجمة حالة العرض)
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'active' => 'نشط',
            'paused' => 'متوقف مؤقتاً',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }

    /**
     * Get approval status color
     */
    public function getApprovalStatusColorAttribute(): string
    {
        return match($this->approval_status) {
            'draft' => 'secondary',
            'pending_approval' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'under_real_estate_review' => 'primary',
            'real_estate_approved' => 'success',
            'real_estate_rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check if offer can be edited by tenant
     */
    public function canBeEditedByTenant(): bool
    {
        return in_array($this->approval_status, ['draft', 'rejected', 'real_estate_rejected']);
    }

    /**
     * Check if needs initial review
     */
    public function needsInitialReview(): bool
    {
        return $this->approval_status === 'pending_approval';
    }

    /**
     * Check if needs real estate review
     */
    public function needsRealEstateReview(): bool
    {
        return $this->approval_status === 'under_real_estate_review';
    }

    /**
     * Check if is fully approved
     */
    public function isFullyApproved(): bool
    {
        return $this->approval_status === 'real_estate_approved';
    }

    /**
     * Scope للعروض التي تحتاج مراجعة أولية
     */
    public function scopePendingInitialReview($query)
    {
        return $query->where('approval_status', 'pending_approval');
    }

    /**
     * Scope للعروض التي تحتاج مراجعة عقارية
     */
    public function scopePendingRealEstateReview($query)
    {
        return $query->where('approval_status', 'under_real_estate_review');
    }

    /**
     * Scope للعروض المعتمدة بالكامل
     */
    public function scopeFullyApproved($query)
    {
        return $query->where('approval_status', 'real_estate_approved');
    }
}
