<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'brand_name',
        'logo_path',
        'discount_type',
        'discount_value',
        'discount_text',
        'is_active',
        'order',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Boot method to set discount_text automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($promotion) {
            if (empty($promotion->discount_text)) {
                $type = ucfirst($promotion->discount_type);
                $promotion->discount_text = "{$type} {$promotion->discount_value}% Off";
            }
        });
    }

    /**
     * Get the user who created this promotion.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive promotions.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to order by order column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the full logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->logo_path, FILTER_VALIDATE_URL)) {
            return $this->logo_path;
        }

        // If it starts with storage/, it's an uploaded file
        if (str_starts_with($this->logo_path, '/storage/')) {
            return asset($this->logo_path);
        }

        // If it already starts with /images/promotions/, use as is
        if (str_starts_with($this->logo_path, '/images/promotions/')) {
            return asset($this->logo_path);
        }

        // Otherwise, assume it's just a filename in the promotions directory
        return asset('/images/promotions/' . $this->logo_path);
    }

    /**
     * Get the discount text with proper formatting.
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_text) {
            return $this->discount_text;
        }

        $type = ucfirst($this->discount_type);
        return "{$type} {$this->discount_value}% Off";
    }
}
