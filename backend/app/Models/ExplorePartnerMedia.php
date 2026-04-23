<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExplorePartnerMedia extends Model
{
    protected $table = 'explore_partner_media';

    protected $fillable = [
        'explore_partner_id',
        'kind',
        'image_path',
        'video_url',
        'caption',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(ExplorePartner::class, 'explore_partner_id');
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    /**
     * Return a YouTube/Vimeo embed URL from a watch URL, or the original if already embed.
     */
    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }
        $url = $this->video_url;

        if (preg_match('#youtube\.com/watch\?v=([^&]+)#', $url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }
        if (preg_match('#youtu\.be/([^?&]+)#', $url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }
        if (preg_match('#vimeo\.com/(\d+)#', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }
        return $url;
    }
}
