<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Credit the wallet (add funds)
     */
    public function credit(float $amount, string $type, ?int $referenceId = null, ?string $description = null): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->total_earned += $amount;
        $this->save();

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'transaction_type' => $type,
            'amount' => $amount,
            'reference_id' => $referenceId,
            'description' => $description,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
        ]);
    }

    /**
     * Debit the wallet (subtract funds)
     */
    public function debit(float $amount, string $type, ?int $referenceId = null, ?string $description = null): WalletTransaction
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->total_withdrawn += $amount;
        $this->save();

        return $this->transactions()->create([
            'user_id' => $this->user_id,
            'transaction_type' => $type,
            'amount' => -$amount,
            'reference_id' => $referenceId,
            'description' => $description,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
        ]);
    }
}
