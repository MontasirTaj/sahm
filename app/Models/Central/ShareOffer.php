<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ShareOffer extends Model
{
    protected $connection = 'central';
    protected $table = 'share_offers';
    protected $fillable = [
        'tenant_id',
        'title','title_ar','description','description_ar','country','city','address',
        'total_shares','available_shares','sold_shares','price_per_share','currency',
        'status','starts_at','ends_at','cover_image','media','metadata',
    ];

    protected $casts = [
        'media' => 'array',
        'metadata' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
