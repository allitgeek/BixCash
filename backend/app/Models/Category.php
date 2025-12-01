<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon_path',
        'description',
        'color',
        'is_active',
        'meta_title',
        'meta_description',
        'order',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
