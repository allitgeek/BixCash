<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionSettlement extends Model
{
    protected $fillable = [
        'ledger_id',
        'partner_id',
        'amount_settled',
        'payment_method',
        'settlement_reference',
        'proof_of_payment',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount_settled' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the commission ledger
     */
    public function ledger(): BelongsTo
    {
        return $this->belongsTo(CommissionLedger::class, 'ledger_id');
    }

    /**
     * Get the partner (user)
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the admin who processed this settlement
     */
    public function processedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope to filter by partner
     */
    public function scopeForPartner($query, int $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Scope to filter by payment method
     */
    public function scopeByPaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope to get recent settlements
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('processed_at', 'desc')->limit($limit);
    }

    /**
     * Get formatted payment method
     */
    public function getFormattedPaymentMethodAttribute(): string
    {
        return match($this->payment_method) {
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'wallet_deduction' => 'Wallet Deduction',
            'adjustment' => 'Adjustment',
            'other' => 'Other',
            default => ucfirst($this->payment_method),
        };
    }

    /**
     * Get proof of payment URL
     */
    public function getProofUrlAttribute(): ?string
    {
        if ($this->proof_of_payment) {
            return asset('storage/' . $this->proof_of_payment);
        }
        return null;
    }
}
