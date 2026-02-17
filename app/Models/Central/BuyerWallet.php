<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuyerWallet extends Model
{
    protected $connection = 'central';
    protected $table = 'buyer_wallets';

    protected $fillable = [
        'buyer_id',
        'balance',
        'currency',
        'pending_balance',
        'total_deposits',
        'total_withdrawals',
        'is_active',
        'last_transaction_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_deposits' => 'decimal:2',
        'total_withdrawals' => 'decimal:2',
        'is_active' => 'boolean',
        'last_transaction_at' => 'datetime',
    ];

    /**
     * Buyer relationship
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    /**
     * Transactions relationship
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    /**
     * Get available balance (balance - pending)
     */
    public function getAvailableBalanceAttribute(): float
    {
        return max(0, $this->balance - $this->pending_balance);
    }

    /**
     * Check if wallet has sufficient balance
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->getAvailableBalanceAttribute() >= $amount;
    }

    /**
     * Deposit money to wallet
     */
    public function deposit(float $amount, string $description = null, array $metadata = []): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->total_deposits += $amount;
        $this->last_transaction_at = now();
        $this->save();

        return $this->transactions()->create([
            'buyer_id' => $this->buyer_id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'currency' => $this->currency,
            'status' => 'completed',
            'description' => $description ?? 'إيداع في المحفظة',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(float $amount, string $description = null, array $metadata = []): WalletTransaction
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('الرصيد غير كافٍ للسحب');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->total_withdrawals += $amount;
        $this->last_transaction_at = now();
        $this->save();

        return $this->transactions()->create([
            'buyer_id' => $this->buyer_id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'currency' => $this->currency,
            'status' => 'completed',
            'description' => $description ?? 'سحب من المحفظة',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Process purchase payment
     */
    public function processPurchase(float $amount, int $saleOfferId, string $description = null): WalletTransaction
    {
        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('الرصيد غير كافٍ لإتمام عملية الشراء');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->last_transaction_at = now();
        $this->save();

        return $this->transactions()->create([
            'buyer_id' => $this->buyer_id,
            'type' => 'purchase',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'currency' => $this->currency,
            'status' => 'completed',
            'payment_method' => 'wallet',
            'description' => $description ?? 'شراء أسهم من السوق الثانوي',
            'sale_offer_id' => $saleOfferId,
        ]);
    }

    /**
     * Process sale payment (credit seller)
     */
    public function processSale(float $amount, int $saleOfferId, string $description = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->total_deposits += $amount;
        $this->last_transaction_at = now();
        $this->save();

        return $this->transactions()->create([
            'buyer_id' => $this->buyer_id,
            'type' => 'sale',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'currency' => $this->currency,
            'status' => 'completed',
            'payment_method' => 'wallet',
            'description' => $description ?? 'بيع أسهم في السوق الثانوي',
            'sale_offer_id' => $saleOfferId,
        ]);
    }
}
