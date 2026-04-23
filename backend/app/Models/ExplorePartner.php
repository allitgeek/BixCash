<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExplorePartner extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'category_tag',
        'logo_path',
        'short_description',
        'visit_url',
        'branch_count_label',
        'is_featured',
        'is_new',
        'is_active',
        'display_order',
        'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (ExplorePartner $partner) {
            if (empty($partner->slug)) {
                $partner->slug = static::uniqueSlug($partner->name);
            }
        });

        static::updating(function (ExplorePartner $partner) {
            if ($partner->isDirty('name') && ! $partner->isDirty('slug')) {
                $partner->slug = static::uniqueSlug($partner->name, $partner->id);
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'partner';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    public function media(): HasMany
    {
        return $this->hasMany(ExplorePartnerMedia::class)->orderBy('display_order');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(ExplorePartnerBranch::class)->orderBy('display_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline($query)
    {
        return $query->where('type', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('type', 'offline');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    public function logoUrl(): ?string
    {
        return $this->logo_path ? asset('storage/'.$this->logo_path) : null;
    }

    /**
     * Card-level Open / Closing soon / Closed status — derived from the first active branch.
     * Returns 'open' | 'closing_soon' | 'closed' | null (null = no branches, treat as unknown).
     */
    public function cardStatus(): ?string
    {
        if ($this->type !== 'offline') {
            return null;
        }
        $firstBranch = $this->branches->firstWhere('is_active', true);
        return $firstBranch?->currentStatus();
    }

    /**
     * Today's display hours string, e.g., "11am – 12am", from the first active branch.
     */
    public function cardHoursLabel(): ?string
    {
        if ($this->type !== 'offline') {
            return null;
        }
        $firstBranch = $this->branches->firstWhere('is_active', true);
        return $firstBranch?->todayHoursLabel();
    }
}
