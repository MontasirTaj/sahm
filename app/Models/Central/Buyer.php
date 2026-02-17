<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Buyer extends Model
{
    protected $connection = 'central';
    protected $table = 'buyers';
    protected $fillable = [
        'user_id','full_name','email','phone','national_id','date_of_birth','country','city','address','kyc_status','metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'date_of_birth' => 'date',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * جميع عمليات المشتري (شراء، بيع، تحويل)
     */
    public function operations()
    {
        return $this->hasMany(ShareOperation::class, 'buyer_id')->orderBy('created_at', 'desc');
    }

    /**
     * الأسهم المملوكة
     */
    public function holdings()
    {
        return $this->hasMany(BuyerHolding::class, 'buyer_id');
    }

    /**
     * عمليات الشراء فقط
     */
    public function purchases()
    {
        return $this->hasMany(ShareOperation::class, 'buyer_id')
            ->where('type', 'purchase')
            ->orderBy('created_at', 'desc');
    }
}
