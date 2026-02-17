<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $connection = 'central';
    protected $table = 'wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'buyer_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'currency',
        'status',
        'payment_method',
        'description',
        'sale_offer_id',
        'share_operation_id',
        'related_buyer_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Wallet relationship
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(BuyerWallet::class, 'wallet_id');
    }

    /**
     * Buyer relationship
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    /**
     * Sale offer relationship
     */
    public function saleOffer(): BelongsTo
    {
        return $this->belongsTo(BuyerSaleOffer::class, 'sale_offer_id');
    }

    /**
     * Share operation relationship
     */
    public function shareOperation(): BelongsTo
    {
        return $this->belongsTo(ShareOperation::class, 'share_operation_id');
    }

    /**
     * Related buyer (for transfers between buyers)
     */
    public function relatedBuyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'related_buyer_id');
    }

    /**
     * Scope for deposits
     */
    public function scopeDeposits($query)
    {
        return $query->whereIn('type', ['deposit', 'sale']);
    }

    /**
     * Scope for withdrawals
     */
    public function scopeWithdrawals($query)
    {
        return $query->whereIn('type', ['withdrawal', 'purchase']);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get transaction color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'deposit', 'sale' => 'success',
            'withdrawal', 'purchase' => 'danger',
            'refund' => 'info',
            'commission' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get transaction icon
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'mdi-wallet-plus',
            'withdrawal' => 'mdi-wallet-minus',
            'purchase' => 'mdi-cart',
            'sale' => 'mdi-cash-multiple',
            'refund' => 'mdi-undo-variant',
            'commission' => 'mdi-percent',
            default => 'mdi-cash'
        };
    }

    /**
     * Get formatted type name
     */
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'إيداع',
            'withdrawal' => 'سحب',
            'purchase' => 'شراء',
            'sale' => 'بيع',
            'refund' => 'استرجاع',
            'commission' => 'عمولة',
            default => $this->type
        };
    }
}
