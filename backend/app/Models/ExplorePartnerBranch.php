<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExplorePartnerBranch extends Model
{
    protected $fillable = [
        'explore_partner_id',
        'name',
        'address',
        'city',
        'phone',
        'lat',
        'lng',
        'directions_url',
        'weekly_schedule',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'weekly_schedule' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public const DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(ExplorePartner::class, 'explore_partner_id');
    }

    /**
     * Auto-generate a Google Maps directions URL from lat/lng if none was saved.
     */
    public function resolvedDirectionsUrl(): ?string
    {
        if ($this->directions_url) {
            return $this->directions_url;
        }
        if ($this->lat !== null && $this->lng !== null) {
            return 'https://www.google.com/maps/dir/?api=1&destination='.$this->lat.','.$this->lng;
        }
        return null;
    }

    /**
     * Today's schedule entry: ['open' => 'HH:MM', 'close' => 'HH:MM', 'closed' => bool] or null.
     */
    public function todaySchedule(): ?array
    {
        $key = self::DAYS[Carbon::now()->dayOfWeekIso - 1] ?? null;
        if (! $key || ! is_array($this->weekly_schedule)) {
            return null;
        }
        $entry = $this->weekly_schedule[$key] ?? null;
        if (! is_array($entry)) {
            return null;
        }
        return [
            'open' => $entry['open'] ?? null,
            'close' => $entry['close'] ?? null,
            'closed' => (bool) ($entry['closed'] ?? false),
        ];
    }

    /**
     * Human-readable hours for today, e.g., "11am – 12am". Returns null if closed today or no schedule.
     */
    public function todayHoursLabel(): ?string
    {
        $today = $this->todaySchedule();
        if (! $today || $today['closed'] || ! $today['open'] || ! $today['close']) {
            return null;
        }
        return static::formatTime($today['open']).' – '.static::formatTime($today['close']);
    }

    /**
     * Live status: 'open', 'closing_soon' (within 30 min of close), or 'closed'.
     */
    public function currentStatus(): string
    {
        $today = $this->todaySchedule();
        if (! $today || $today['closed'] || ! $today['open'] || ! $today['close']) {
            return 'closed';
        }
        $now = Carbon::now();
        $open = Carbon::createFromTimeString($today['open']);
        $close = Carbon::createFromTimeString($today['close']);
        // Support overnight schedules (close < open means close is next day)
        if ($close->lessThanOrEqualTo($open)) {
            $close->addDay();
            if ($now->lessThan($open)) {
                $now = $now->copy()->addDay();
            }
        }
        if ($now->lessThan($open) || $now->greaterThanOrEqualTo($close)) {
            return 'closed';
        }
        if ($close->diffInMinutes($now) <= 30) {
            return 'closing_soon';
        }
        return 'open';
    }

    /**
     * "10:00" → "10am", "22:30" → "10:30pm", "00:00" → "12am", "12:00" → "12pm".
     */
    public static function formatTime(string $hhmm): string
    {
        [$h, $m] = array_map('intval', explode(':', $hhmm) + [1 => 0]);
        $suffix = $h < 12 ? 'am' : 'pm';
        $display = $h % 12;
        if ($display === 0) {
            $display = 12;
        }
        return $m > 0 ? sprintf('%d:%02d%s', $display, $m, $suffix) : $display.$suffix;
    }
}
