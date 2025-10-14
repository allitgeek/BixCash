<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'platform',
        'url',
        'icon',
        'icon_file',
        'is_enabled',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope to get only enabled links
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope to get links ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get the default icon for a platform
     */
    public static function getDefaultIcon(string $platform): string
    {
        return match(strtolower($platform)) {
            'facebook' => 'fab fa-facebook-f',
            'twitter' => 'fab fa-twitter',
            'instagram' => 'fab fa-instagram',
            'linkedin' => 'fab fa-linkedin-in',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'pinterest' => 'fab fa-pinterest',
            'whatsapp' => 'fab fa-whatsapp',
            default => 'fas fa-link',
        };
    }
}
