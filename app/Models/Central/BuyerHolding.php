<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class BuyerHolding extends Model
{
    protected $connection = 'central';
    protected $table = 'buyer_holdings';
    protected $fillable = [
        'buyer_id','offer_id','shares_owned','avg_price_per_share','last_transaction_at','metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_transaction_at' => 'datetime',
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
}
