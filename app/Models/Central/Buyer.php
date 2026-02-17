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

    /**
     * المحفظة
     */
    public function wallet()
    {
        return $this->hasOne(BuyerWallet::class, 'buyer_id');
    }

    /**
     * جميع التنبيهات
     */
    public function notifications()
    {
        return $this->hasMany(BuyerNotification::class, 'buyer_id')->orderByDesc('created_at');
    }

    /**
     * التنبيهات غير المقروءة
     */
    public function unreadNotifications()
    {
        return $this->hasMany(BuyerNotification::class, 'buyer_id')
            ->where('is_read', false)
            ->orderByDesc('created_at');
    }

    /**
     * Get or create wallet
     */
    public function getOrCreateWallet()
    {
        return $this->wallet ?? $this->wallet()->create([
            'balance' => 0,
            'currency' => 'SAR',
        ]);
    }
}
