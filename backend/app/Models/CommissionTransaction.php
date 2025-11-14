<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionTransaction extends Model
{
    protected $fillable = [
        'partner_transaction_id',
        'partner_id',
        'batch_id',
        'ledger_id',
        'transaction_code',
        'invoice_amount',
        'commission_rate',
        'commission_amount',
        'is_settled',
        'settlement_id',
        'settled_at',
    ];

    protected $casts = [
        'invoice_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'is_settled' => 'boolean',
        'settled_at' => 'datetime',
    ];

    /**
     * Get the partner transaction
     */
    public function partnerTransaction(): BelongsTo
    {
        return $this->belongsTo(PartnerTransaction::class, 'partner_transaction_id');
    }

    /**
     * Get the partner (user)
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
     * Get the commission ledger
     */
    public function ledger(): BelongsTo
    {
        return $this->belongsTo(CommissionLedger::class, 'ledger_id');
    }

    /**
     * Get the settlement (if settled)
     */
    public function settlement(): BelongsTo
    {
        return $this->belongsTo(CommissionSettlement::class, 'settlement_id');
    }

    /**
     * Scope to get unsettled transactions
     */
    public function scopeUnsettled($query)
    {
        return $query->where('is_settled', false);
    }

    /**
     * Scope to get settled transactions
     */
    public function scopeSettled($query)
    {
        return $query->where('is_settled', true);
    }

    /**
     * Scope to filter by partner
     */
    public function scopeForPartner($query, int $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Scope to filter by batch
     */
    public function scopeForBatch($query, int $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope to filter by ledger
     */
    public function scopeForLedger($query, int $ledgerId)
    {
        return $query->where('ledger_id', $ledgerId);
    }

    /**
     * Mark transaction as settled
     */
    public function markAsSettled(int $settlementId): void
    {
        $this->update([
            'is_settled' => true,
            'settlement_id' => $settlementId,
            'settled_at' => now(),
        ]);
    }
}
