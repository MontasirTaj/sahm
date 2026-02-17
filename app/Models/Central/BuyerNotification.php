<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerNotification extends Model
{
    protected $connection = 'central';
    protected $table = 'buyer_notifications';

    protected $fillable = [
        'buyer_id',
        'type',
        'title',
        'message',
        'sale_offer_id',
        'share_operation_id',
        'wallet_transaction_id',
        'metadata',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

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
     * Wallet transaction relationship
     */
    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'sale_completed' => 'mdi-check-circle',
            'partial_sale' => 'mdi-progress-check',
            'purchase_completed' => 'mdi-cart-check',
            'wallet_deposit' => 'mdi-wallet-plus',
            'wallet_withdrawal' => 'mdi-wallet-minus',
            default => 'mdi-bell'
        };
    }

    /**
     * Get notification color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'sale_completed' => 'success',
            'partial_sale' => 'info',
            'purchase_completed' => 'primary',
            'wallet_deposit' => 'success',
            'wallet_withdrawal' => 'warning',
            default => 'secondary'
        };
    }
}
