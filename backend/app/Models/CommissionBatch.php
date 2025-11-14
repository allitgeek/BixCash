<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionBatch extends Model
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
        'total_partners',
        'total_transactions',
        'total_transaction_amount',
        'total_commission_calculated',
        'calculation_log',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_transaction_amount' => 'decimal:2',
        'total_commission_calculated' => 'decimal:2',
        'calculation_log' => 'array',
    ];

    /**
     * Get the user who triggered this batch
     */
    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by_user_id');
    }

    /**
     * Get all commission ledgers for this batch
     */
    public function ledgers(): HasMany
    {
        return $this->hasMany(CommissionLedger::class, 'batch_id');
    }

    /**
     * Get all commission transactions for this batch
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(CommissionTransaction::class, 'batch_id');
    }

    /**
     * Scope to get completed batches
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get pending batches
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get batches by period
     */
    public function scopeForPeriod($query, string $period)
    {
        return $query->where('batch_period', $period);
    }

    /**
     * Check if batch is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get formatted period (e.g., "November 2025")
     */
    public function getFormattedPeriodAttribute(): string
    {
        return \Carbon\Carbon::createFromFormat('Y-m', $this->batch_period)->format('F Y');
    }
}
