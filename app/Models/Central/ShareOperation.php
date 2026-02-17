<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ShareOperation extends Model
{
    protected $connection = 'central';
    protected $table = 'share_operations';
    protected $fillable = [
        'offer_id','tenant_id','buyer_id','type','shares_count','price_per_share','amount_total','currency','status','payment_id','external_reference','metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * العلاقة مع المشتري
     */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    /**
     * العلاقة مع العرض
     */
    public function offer()
    {
        return $this->belongsTo(ShareOffer::class, 'offer_id');
    }

    /**
     * Get formatted type name
     */
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'purchase' => 'شراء',
            'sell' => 'بيع',
            'transfer' => 'تحويل',
            'dividend' => 'أرباح',
            default => $this->type
        };
    }

    /**
     * Get transaction color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'purchase' => 'success',
            'sell' => 'primary',
            'transfer' => 'info',
            'dividend' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get transaction icon
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'purchase' => 'mdi-cart-arrow-down',
            'sell' => 'mdi-cash-multiple',
            'transfer' => 'mdi-swap-horizontal',
            'dividend' => 'mdi-cash-plus',
            default => 'mdi-file-document'
        };
    }
}
