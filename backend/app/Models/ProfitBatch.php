<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfitBatch extends Model
{
    protected $fillable = [
        'batch_period',
        'period_start',
        'period_end',
        'status',
        'triggered_by',
        'triggered_by_user_id',
        'started_at',
        'completed_at',
        'total_transactions',
        'total_transaction_amount',
        'total_profit_distributed',
        'total_customer_share',
        'total_partner_share',
        'total_company_share',
        'calculation_log',
        'error_log',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_transaction_amount' => 'decimal:2',
        'total_profit_distributed' => 'decimal:2',
        'total_customer_share' => 'decimal:2',
        'total_partner_share' => 'decimal:2',
        'total_company_share' => 'decimal:2',
        'calculation_log' => 'array',
        'total_transactions' => 'integer',
    ];

    // Relationships

    public function transactions(): HasMany
    {
        return $this->hasMany(PartnerTransaction::class, 'batch_id');
    }

    public function triggeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by_user_id');
    }

    // Query Scopes

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper Methods

    /**
     * Check if batch is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if batch failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if batch is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Get transaction count
     */
    public function getTransactionCount(): int
    {
        return $this->total_transactions ?? $this->transactions()->count();
    }

    /**
     * Mark batch as processing
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark batch as completed
     */
    public function markAsCompleted(array $statistics): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_transactions' => $statistics['total_transactions'] ?? 0,
            'total_transaction_amount' => $statistics['total_transaction_amount'] ?? 0.00,
            'total_profit_distributed' => $statistics['total_profit_distributed'] ?? 0.00,
            'total_customer_share' => $statistics['total_customer_share'] ?? 0.00,
            'total_partner_share' => $statistics['total_partner_share'] ?? 0.00,
            'total_company_share' => $statistics['total_company_share'] ?? 0.00,
        ]);
    }

    /**
     * Mark batch as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_log' => $errorMessage,
        ]);
    }

    /**
     * Generate batch period string from date
     */
    public static function generateBatchPeriod(\DateTime $date): string
    {
        return $date->format('Y-m');
    }

    /**
     * Get or create batch for a period
     */
    public static function getOrCreateForPeriod(string $period, string $triggeredBy, ?int $userId = null): self
    {
        $startDate = \Carbon\Carbon::parse($period . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        return self::firstOrCreate(
            ['batch_period' => $period],
            [
                'period_start' => $startDate,
                'period_end' => $endDate,
                'status' => 'pending',
                'triggered_by' => $triggeredBy,
                'triggered_by_user_id' => $userId,
            ]
        );
    }
}
