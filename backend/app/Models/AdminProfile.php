<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminProfile extends Model
{
    protected $fillable = [
        'user_id',
        'admin_level',
        'department',
        'bio',
        'avatar',
        'last_login_at',
        'permissions_override',
        'is_active'
    ];

    protected $casts = [
        'permissions_override' => 'array',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->permissions_override && in_array($permission, $this->permissions_override)) {
            return true;
        }

        return $this->user->role ? $this->user->role->hasPermission($permission) : false;
    }
}
