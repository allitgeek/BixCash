<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permission_group_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the permission group
     */
    public function permissionGroup(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    /**
     * Get all users who have this permission (via user_permissions table)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot(['granted_by', 'expires_at', 'notes'])
            ->withTimestamps();
    }

    /**
     * Scope to get only active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get permissions by group
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('permission_group_id', $groupId);
    }

    /**
     * Check if permission is a system critical permission
     */
    public function isCritical(): bool
    {
        $criticalPermissions = [
            'users.delete',
            'roles.delete',
            'settings.edit_system',
        ];

        return in_array($this->name, $criticalPermissions);
    }

    /**
     * Get the action from permission name (e.g., "customers.view" => "view")
     */
    public function getActionAttribute(): string
    {
        return explode('.', $this->name)[1] ?? '';
    }

    /**
     * Get the module from permission name (e.g., "customers.view" => "customers")
     */
    public function getModuleAttribute(): string
    {
        return explode('.', $this->name)[0] ?? '';
    }
}
