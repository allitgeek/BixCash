<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerTransaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'partner_id',
        'customer_id',
        'brand_id',
        'invoice_amount',
        'transaction_date',
        'status',
        'confirmation_deadline',
        'confirmed_at',
        'confirmed_by_customer',
        'rejected_at',
        'rejection_reason',
        'batch_processed_at',
        'batch_id',
        'customer_profit_share',
        'partner_profit_share',
        'company_profit_share',
        'profit_calculation_details',
        'partner_device_info',
        'customer_ip_address',
        'source',
        'external_order_id',
    ];

    protected $casts = [
        'invoice_amount' => 'decimal:2',
        'customer_profit_share' => 'decimal:2',
        'partner_profit_share' => 'decimal:2',
        'company_profit_share' => 'decimal:2',
        'transaction_date' => 'datetime',
        'confirmation_deadline' => 'datetime',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'batch_processed_at' => 'datetime',
        'confirmed_by_customer' => 'boolean',
        'partner_device_info' => 'array',
        'profit_calculation_details' => 'array',
    ];

    // Relationships

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProfitBatch::class, 'batch_id');
    }

    public function purchaseHistory(): BelongsTo
    {
        return $this->belongsTo(PurchaseHistory::class, 'partner_transaction_id');
    }

    // Query Scopes

    public function scopePendingConfirmation($query)
    {
        return $query->where('status', 'pending_confirmation');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeFromApi($query)
    {
        return $query->where('source', 'api');
    }

    public function scopeFromApp($query)
    {
        return $query->where('source', 'in_app');
    }

    public function scopeUnprocessed($query)
    {
        return $query->whereNull('batch_id')
            ->where('status', 'confirmed');
    }

    // Helper Methods

    /**
     * Check if transaction confirmation has expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending_confirmation'
            && now()->isAfter($this->confirmation_deadline);
    }

    /**
     * Check if transaction is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if transaction can still be confirmed
     */
    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending_confirmation'
            && now()->isBefore($this->confirmation_deadline);
    }

    /**
     * Auto-confirm transaction (after 60 seconds)
     */
    public function autoConfirm(): bool
    {
        if (!$this->isExpired()) {
            return false;
        }

        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by_customer' => false,
        ]);

        // Create purchase history record
        $this->createPurchaseHistoryRecord('auto');

        return true;
    }

    /**
     * Confirm transaction by customer
     */
    public function confirmByCustomer(): bool
    {
        if (!$this->canBeConfirmed()) {
            return false;
        }

        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by_customer' => true,
        ]);

        // Create purchase history record
        $this->createPurchaseHistoryRecord('manual');

        return true;
    }

    /**
     * Reject transaction
     */
    public function reject(string $reason): bool
    {
        if (!$this->canBeConfirmed()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return true;
    }

    /**
     * Check if transaction has been processed in a batch
     */
    public function isProcessed(): bool
    {
        return !is_null($this->batch_id) && !is_null($this->batch_processed_at);
    }

    /**
     * Create purchase history record
     */
    private function createPurchaseHistoryRecord(string $confirmationMethod): void
    {
        PurchaseHistory::create([
            'user_id' => $this->customer_id,
            'brand_id' => $this->brand_id,
            'partner_transaction_id' => $this->id,
            'order_id' => $this->transaction_code,
            'amount' => $this->invoice_amount,
            'cashback_amount' => 0.00, // Will be calculated in batch
            'cashback_percentage' => 0.00,
            'status' => 'confirmed', // Transaction is already confirmed
            'description' => 'Purchase at ' . ($this->partner->partnerProfile->business_name ?? 'Partner Store'),
            'purchase_date' => $this->transaction_date,
            'confirmed_by_customer' => $confirmationMethod === 'manual',
            'confirmation_method' => $confirmationMethod,
        ]);
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode(): string
    {
        $year = date('Y');
        $lastTransaction = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTransaction
            ? (int) substr($lastTransaction->transaction_code, -6) + 1
            : 1;

        return 'BX' . $year . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to generate transaction code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_code) {
                $transaction->transaction_code = self::generateTransactionCode();
            }

            if (!$transaction->transaction_date) {
                $transaction->transaction_date = now();
            }

            if (!$transaction->confirmation_deadline && $transaction->source !== 'api') {
                $transaction->confirmation_deadline = now()->addSeconds(60);
            }
        });
    }
}
