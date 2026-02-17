<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class BuyerSaleOffer extends Model
{
    protected $connection = 'central';
    protected $table = 'buyer_sale_offers';
    
    protected $fillable = [
        'seller_buyer_id',
        'holding_id',
        'original_offer_id',
        'shares_count',
        'price_per_share',
        'currency',
        'status',
        'buyer_buyer_id',
        'sold_price_per_share',
        'sold_at',
        'description',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sold_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * البائع (المشتري الأصلي)
     */
    public function seller()
    {
        return $this->belongsTo(Buyer::class, 'seller_buyer_id');
    }

    /**
     * المشتري الجديد
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_buyer_id');
    }

    /**
     * الممتلكات الأصلية
     */
    public function holding()
    {
        return $this->belongsTo(BuyerHolding::class, 'holding_id');
    }

    /**
     * العرض الأصلي
     */
    public function originalOffer()
    {
        return $this->belongsTo(ShareOffer::class, 'original_offer_id');
    }

    /**
     * Scope للعروض النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}
