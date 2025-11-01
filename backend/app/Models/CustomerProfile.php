<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'phone_verified',
        'last_otp_sent_at',
        'otp_attempts_today',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'bix_cash_balance',
        'total_earnings',
        'total_spent',
        'referral_code',
        'referred_by',
        'avatar',
        'is_verified',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'phone_verified' => 'boolean',
        'last_otp_sent_at' => 'datetime',
        'otp_attempts_today' => 'integer',
        'bix_cash_balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(CustomerProfile::class, 'referred_by', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->referral_code) {
                $customer->referral_code = 'BIX' . strtoupper(uniqid());
            }
        });
    }
}
