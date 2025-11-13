<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroup extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'icon',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all permissions in this group
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class)->orderBy('display_name');
    }

    /**
     * Get only active permissions in this group
     */
    public function activePermissions(): HasMany
    {
        return $this->hasMany(Permission::class)->where('is_active', true)->orderBy('display_name');
    }

    /**
     * Scope to get only active groups
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }
}
