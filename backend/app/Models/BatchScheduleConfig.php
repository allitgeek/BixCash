<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchScheduleConfig extends Model
{
    protected $table = 'batch_schedule_config';

    protected $fillable = [
        'is_enabled',
        'run_day_of_month',
        'run_time',
        'timezone',
        'notify_admin_before_hours',
        'admin_notification_emails',
        'last_run_at',
        'next_scheduled_run',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'run_time' => 'datetime:H:i:s',
        'last_run_at' => 'datetime',
        'next_scheduled_run' => 'datetime',
        'notify_admin_before_hours' => 'integer',
        'run_day_of_month' => 'integer',
    ];

    // Relationships

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper Methods

    /**
     * Get active schedule configuration
     */
    public static function getActiveSchedule(): ?self
    {
        return self::where('is_enabled', true)->first();
    }

    /**
     * Calculate next scheduled run date
     */
    public function calculateNextRun(): Carbon
    {
        $now = Carbon::now($this->timezone);
        $runTime = Carbon::parse($this->run_time);

        // Get next occurrence of run_day_of_month
        $nextRun = Carbon::create(
            $now->year,
            $now->month,
            min($this->run_day_of_month, $now->daysInMonth),
            $runTime->hour,
            $runTime->minute,
            0,
            $this->timezone
        );

        // If the date has passed this month, move to next month
        if ($nextRun->isPast()) {
            $nextRun->addMonth();
            // Adjust day if next month has fewer days
            $nextRun->day = min($this->run_day_of_month, $nextRun->daysInMonth);
        }

        return $nextRun;
    }

    /**
     * Check if batch should run now
     */
    public function shouldRunNow(): bool
    {
        if (!$this->is_enabled) {
            return false;
        }

        $now = Carbon::now($this->timezone);

        // Check if we're on the correct day of month
        if ($now->day !== $this->run_day_of_month) {
            return false;
        }

        // Check if we're within the run time window (within 5 minutes)
        $runTime = Carbon::parse($this->run_time)->setTimezone($this->timezone);
        $scheduledTime = $now->copy()->setTime($runTime->hour, $runTime->minute, 0);

        // Check if current time is within 5 minutes of scheduled time
        $timeDiff = abs($now->diffInMinutes($scheduledTime));

        if ($timeDiff > 5) {
            return false;
        }

        // Check if we haven't already run today
        if ($this->last_run_at) {
            $lastRun = Carbon::parse($this->last_run_at)->setTimezone($this->timezone);
            if ($lastRun->isToday()) {
                return false; // Already ran today
            }
        }

        return true;
    }

    /**
     * Update next scheduled run
     */
    public function updateNextScheduledRun(): void
    {
        $this->update([
            'next_scheduled_run' => $this->calculateNextRun(),
        ]);
    }

    /**
     * Mark as run
     */
    public function markAsRun(): void
    {
        $this->update([
            'last_run_at' => now(),
        ]);

        $this->updateNextScheduledRun();
    }

    /**
     * Get admin notification emails as array
     */
    public function getAdminEmailsArray(): array
    {
        if (empty($this->admin_notification_emails)) {
            return [];
        }

        return array_map('trim', explode(',', $this->admin_notification_emails));
    }

    /**
     * Get time until next run
     */
    public function getTimeUntilNextRun(): ?string
    {
        if (!$this->next_scheduled_run) {
            return null;
        }

        $now = Carbon::now();
        $nextRun = Carbon::parse($this->next_scheduled_run);

        return $now->diffForHumans($nextRun, true);
    }

    /**
     * Boot method to calculate next run on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($config) {
            if ($config->is_enabled && !$config->wasChanged('next_scheduled_run')) {
                $config->updateNextScheduledRun();
            }
        });
    }
}
