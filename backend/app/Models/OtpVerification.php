<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone',
        'otp_code',
        'purpose',
        'channel',
        'reference_id',
        'is_verified',
        'verified_at',
        'expires_at',
        'attempts',
        'ip_address',
        'user_agent',
        'is_ufone_bypass',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'attempts' => 'integer',
        'is_ufone_bypass' => 'boolean',
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Check if OTP is still valid
     */
    public function isValid(): bool
    {
        return !$this->is_verified
            && !$this->isExpired()
            && $this->attempts < config('firebase.otp.max_attempts', 3);
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified(): bool
    {
        $this->is_verified = true;
        $this->verified_at = Carbon::now();
        return $this->save();
    }

    /**
     * Increment verification attempts
     */
    public function incrementAttempts(): bool
    {
        $this->attempts++;
        return $this->save();
    }

    /**
     * Scope to get valid OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->where('attempts', '<', config('firebase.otp.max_attempts', 3));
    }

    /**
     * Scope to get OTPs for a specific phone and purpose
     */
    public function scopeForPhone($query, string $phone, string $purpose = 'login')
    {
        return $query->where('phone', $phone)
            ->where('purpose', $purpose);
    }

    /**
     * Scope to filter by channel (firebase or whatsapp)
     */
    public function scopeForChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Delete expired OTPs (for cleanup)
     */
    public static function deleteExpired(): int
    {
        return static::where('expires_at', '<', Carbon::now()->subDays(7))->delete();
    }
}
