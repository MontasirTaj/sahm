<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RealEstateCheckpoint extends Model
{
    protected $connection = 'central';
    protected $table = 'real_estate_checkpoints';
    
    protected $fillable = [
        'offer_id',
        'checkpoint_text',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
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
     * العلاقة مع المنشئ (Admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
