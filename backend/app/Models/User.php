<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_verified_at',
        'pin_hash',
        'pin_attempts',
        'pin_locked_until',
        'role_id',
        'is_active',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'pin_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'pin_locked_until' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'pin_attempts' => 'integer'
        ];
    }

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function adminProfile(): HasOne
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function partnerProfile(): HasOne
    {
        return $this->hasOne(PartnerProfile::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class, 'partner_id');
    }

    public function createdSlides(): HasMany
    {
        return $this->hasMany(Slide::class, 'created_by');
    }

    // Helper methods
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        // Check admin profile for permission overrides
        if ($this->adminProfile && $this->adminProfile->hasPermission($permission)) {
            return true;
        }

        return $this->role->hasPermission($permission);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin') || $this->hasRole('moderator');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function isPartner(): bool
    {
        return $this->hasRole('partner');
    }

    // PIN Authentication Methods

    /**
     * Set user's PIN
     */
    public function setPin(string $pin): bool
    {
        $this->pin_hash = bcrypt($pin);
        $this->pin_attempts = 0;
        $this->pin_locked_until = null;
        return $this->save();
    }

    /**
     * Verify PIN
     */
    public function verifyPin(string $pin): bool
    {
        // Check if PIN is locked
        if ($this->isPinLocked()) {
            return false;
        }

        // Verify PIN
        if (password_verify($pin, $this->pin_hash)) {
            $this->pin_attempts = 0;
            $this->pin_locked_until = null;
            $this->save();
            return true;
        }

        // Increment failed attempts
        $this->pin_attempts++;

        // Lock PIN if max attempts reached
        $maxAttempts = config('firebase.pin.max_attempts', 5);
        if ($this->pin_attempts >= $maxAttempts) {
            $lockoutMinutes = config('firebase.pin.lockout_minutes', 15);
            $this->pin_locked_until = now()->addMinutes($lockoutMinutes);
        }

        $this->save();
        return false;
    }

    /**
     * Check if PIN is locked
     */
    public function isPinLocked(): bool
    {
        if (!$this->pin_locked_until) {
            return false;
        }

        // If lockout period has passed, unlock
        if (now()->isAfter($this->pin_locked_until)) {
            $this->pin_attempts = 0;
            $this->pin_locked_until = null;
            $this->save();
            return false;
        }

        return true;
    }

    /**
     * Get remaining PIN lockout time in minutes
     */
    public function getPinLockoutTimeRemaining(): ?int
    {
        if (!$this->isPinLocked()) {
            return null;
        }

        return now()->diffInMinutes($this->pin_locked_until, false);
    }

    /**
     * Reset PIN (after OTP verification)
     */
    public function resetPin(string $newPin): bool
    {
        return $this->setPin($newPin);
    }

    // Phone Verification Methods

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified(): bool
    {
        $this->phone_verified_at = now();
        return $this->save();
    }

    /**
     * Check if phone is verified
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }
}
