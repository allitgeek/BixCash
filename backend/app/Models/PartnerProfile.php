<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'contact_person_name',
        'logo',
        'business_type',
        'business_license',
        'business_description',
        'business_phone',
        'business_address',
        'business_city',
        'business_state',
        'business_postal_code',
        'commission_rate',
        'total_sales',
        'total_commission_paid',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'is_featured',
        'registration_date',
        'approval_notes',
        'rejection_notes',
        'is_verified',
        'verified_at',
        'verified_by',
        'bank_name',
        'account_number',
        'account_title',
        'iban',
        'withdrawal_locked_until',
        'bank_details_last_updated',
        'bank_change_otp',
        'bank_change_otp_expires_at'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_commission_paid' => 'decimal:2',
        'approved_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'withdrawal_locked_until' => 'datetime',
        'bank_details_last_updated' => 'datetime',
        'bank_change_otp_expires_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class, 'partner_id', 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
