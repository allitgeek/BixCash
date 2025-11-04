<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfitSharingDistribution extends Model
{
    protected $fillable = [
        'distribution_month',
        'total_amount',
        'status',
        'level_1_amount',
        'level_1_per_customer',
        'level_1_percentage',
        'level_1_recipients',
        'level_2_amount',
        'level_2_per_customer',
        'level_2_percentage',
        'level_2_recipients',
        'level_3_amount',
        'level_3_per_customer',
        'level_3_percentage',
        'level_3_recipients',
        'level_4_amount',
        'level_4_per_customer',
        'level_4_percentage',
        'level_4_recipients',
        'level_5_amount',
        'level_5_per_customer',
        'level_5_percentage',
        'level_5_recipients',
        'level_6_amount',
        'level_6_per_customer',
        'level_6_percentage',
        'level_6_recipients',
        'level_7_amount',
        'level_7_per_customer',
        'level_7_percentage',
        'level_7_recipients',
        'total_recipients',
        'created_by_admin_id',
        'dispersed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'level_1_amount' => 'decimal:2',
        'level_1_per_customer' => 'decimal:2',
        'level_1_percentage' => 'decimal:2',
        'level_2_amount' => 'decimal:2',
        'level_2_per_customer' => 'decimal:2',
        'level_2_percentage' => 'decimal:2',
        'level_3_amount' => 'decimal:2',
        'level_3_per_customer' => 'decimal:2',
        'level_3_percentage' => 'decimal:2',
        'level_4_amount' => 'decimal:2',
        'level_4_per_customer' => 'decimal:2',
        'level_4_percentage' => 'decimal:2',
        'level_5_amount' => 'decimal:2',
        'level_5_per_customer' => 'decimal:2',
        'level_5_percentage' => 'decimal:2',
        'level_6_amount' => 'decimal:2',
        'level_6_per_customer' => 'decimal:2',
        'level_6_percentage' => 'decimal:2',
        'level_7_amount' => 'decimal:2',
        'level_7_per_customer' => 'decimal:2',
        'level_7_percentage' => 'decimal:2',
        'dispersed_at' => 'datetime',
    ];

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'reference_id');
    }
}
