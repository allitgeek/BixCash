<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalSettings extends Model
{
    protected $fillable = [
        'min_amount',
        'max_per_withdrawal',
        'max_per_day',
        'max_per_month',
        'min_gap_hours',
        'enabled',
        'processing_message',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_per_withdrawal' => 'decimal:2',
        'max_per_day' => 'decimal:2',
        'max_per_month' => 'decimal:2',
        'min_gap_hours' => 'integer',
        'enabled' => 'boolean',
    ];

    /**
     * Get the singleton instance of withdrawal settings
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'min_amount' => 100.00,
            'max_per_withdrawal' => 50000.00,
            'max_per_day' => 100000.00,
            'max_per_month' => 500000.00,
            'min_gap_hours' => 6,
            'enabled' => true,
            'processing_message' => 'Withdrawal requests are typically processed within 24-48 business hours.',
        ]);
    }
}
