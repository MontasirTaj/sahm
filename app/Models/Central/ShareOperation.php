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
}
