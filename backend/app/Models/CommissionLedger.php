<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionLedger extends Model
{
    protected $fillable = [
        'partner_id',
        'batch_id',
        'batch_period',
        'commission_rate_used',
        'total_transactions',
        'total_invoice_amount',
        'commission_owed',
        'amount_paid',
        'amount_outstanding',
        'status',
        'fully_settled_at',
    ];

    protected $casts = [
        'commission_rate_used' => 'decimal:2',
        'total_invoice_amount' => 'decimal:2',
        'commission_owed' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_outstanding' => 'decimal:2',
        'fully_settled_at' => 'datetime',
    ];

    /**
     * Get the partner (user) this ledger belongs to
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the commission batch
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(CommissionBatch::class, 'batch_id');
    }

    /**
     * Get all settlements for this ledger
     */
    public function settlements(): HasMany
    {
        return $this->hasMany(CommissionSettlement::class, 'ledger_id');
    }

    /**
     * Get all commission transactions for this ledger
     */
    public function commissionTransactions(): HasMany
    {
        return $this->hasMany(CommissionTransaction::class, 'ledger_id');
    }

    /**
     * Scope to get pending ledgers (with outstanding balance)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('amount_outstanding', '>', 0);
    }

    /**
     * Scope to get settled ledgers
     */
    public function scopeSettled($query)
    {
        return $query->where('status', 'settled');
    }

    /**
     * Scope to get partial ledgers
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    /**
     * Scope to filter by partner
     */
    public function scopeForPartner($query, int $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Check if ledger is fully settled
     */
    public function isSettled(): bool
    {
        return $this->status === 'settled' && $this->amount_outstanding == 0;
    }

    /**
     * Check if ledger has outstanding balance
     */
    public function hasOutstanding(): bool
    {
        return $this->amount_outstanding > 0;
    }

    /**
     * Get formatted period (e.g., "November 2025")
     */
    public function getFormattedPeriodAttribute(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->batch_period)->format('F Y');
    }

    /**
     * Record a settlement payment (supports negative amounts for adjustments/refunds)
     */
    public function recordSettlement(float $amount, array $settlementData)
    {
        // Update amounts (amount can be negative for adjustments/refunds)
        $this->amount_paid += $amount;
        $this->amount_outstanding = max(0, $this->commission_owed - $this->amount_paid);

        // Update status based on current balances
        if ($this->amount_outstanding == 0 && $this->amount_paid >= $this->commission_owed) {
            $this->status = 'settled';
            $this->fully_settled_at = now();
        } elseif ($this->amount_paid > 0 && $this->amount_outstanding > 0) {
            $this->status = 'partial';
            $this->fully_settled_at = null; // Clear if no longer fully settled
        } else {
            $this->status = 'pending';
            $this->fully_settled_at = null;
        }

        $this->save();

        // Update partner profile (negative amounts increase outstanding, positive decrease it)
        $partnerProfile = $this->partner->partnerProfile;
        if ($partnerProfile) {
            $partnerProfile->total_commission_outstanding -= $amount;
            $partnerProfile->total_commission_paid += $amount;
            $partnerProfile->last_commission_settlement_at = now();
            $partnerProfile->save();
        }

        return $this;
    }
}
