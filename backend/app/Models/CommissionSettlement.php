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
        'adjustment_type',
        'adjustment_reason',
        'settlement_reference',
        'proof_of_payment',
        'admin_notes',
        'processed_by',
        'processed_at',
        'voided_at',
        'voided_by',
        'void_reason',
    ];

    protected $casts = [
        'amount_settled' => 'decimal:2',
        'processed_at' => 'datetime',
        'voided_at' => 'datetime',
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

    /**
     * Check if this is an adjustment settlement
     */
    public function isAdjustment(): bool
    {
        return $this->payment_method === 'adjustment' && $this->adjustment_type !== null;
    }

    /**
     * Get formatted adjustment type
     */
    public function getFormattedAdjustmentTypeAttribute(): ?string
    {
        if (!$this->adjustment_type) {
            return null;
        }

        return match($this->adjustment_type) {
            'refund' => 'Refund',
            'correction' => 'Correction',
            'penalty' => 'Penalty',
            'bonus' => 'Bonus',
            'other' => 'Other',
            default => ucfirst($this->adjustment_type),
        };
    }

    /**
     * Get adjustment type badge config (icon, color, label)
     */
    public function getAdjustmentTypeBadgeAttribute(): ?array
    {
        if (!$this->adjustment_type) {
            return null;
        }

        return match($this->adjustment_type) {
            'refund' => ['icon' => '💸', 'color' => 'danger', 'label' => 'Refund'],
            'correction' => ['icon' => '✏️', 'color' => 'warning', 'label' => 'Correction'],
            'penalty' => ['icon' => '⚠️', 'color' => 'dark', 'label' => 'Penalty'],
            'bonus' => ['icon' => '🎁', 'color' => 'success', 'label' => 'Bonus'],
            'other' => ['icon' => '📝', 'color' => 'secondary', 'label' => 'Other'],
            default => ['icon' => '📝', 'color' => 'secondary', 'label' => ucfirst($this->adjustment_type)],
        };
    }

    /**
     * Check if settlement is voided
     */
    public function isVoided(): bool
    {
        return $this->voided_at !== null;
    }

    /**
     * Check if settlement can be voided (within 24 hours)
     */
    public function canBeVoided(): bool
    {
        return !$this->isVoided() &&
               $this->processed_at->diffInHours(now()) < 24;
    }

    /**
     * Get the admin who voided this settlement
     */
    public function voidedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    /**
     * Scope to filter non-voided settlements
     */
    public function scopeNotVoided($query)
    {
        return $query->whereNull('voided_at');
    }
}
