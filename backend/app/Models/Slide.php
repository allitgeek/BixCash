<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'media_type',
        'media_path',
        'target_url',
        'button_text',
        'button_color',
        'order',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->where('start_date', '<=', $now)
              ->orWhereNull('start_date');
        })->where(function ($q) use ($now) {
            $q->where('end_date', '>=', $now)
              ->orWhereNull('end_date');
        });
    }
}
