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
}
