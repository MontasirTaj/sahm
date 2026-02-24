<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantShareOffer extends Model
{
    protected $connection = 'tenant';
    protected $table = 'share_offers';
    protected $fillable = [
        'central_offer_id',
        'title','title_ar','description','description_ar','country','city','address','property_type',
        'total_shares','available_shares','sold_shares','price_per_share','currency',
        'status','starts_at','ends_at','cover_image','media','metadata',
    ];

    protected $casts = [
        'media' => 'array',
        'metadata' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

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
}
