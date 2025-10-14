<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class EmailSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'smtp'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    /**
     * Get all settings grouped by group
     */
    public static function getGrouped(): array
    {
        return static::all()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key')->toArray();
        })->toArray();
    }

    /**
     * Apply email settings to Laravel config
     */
    public static function applyToConfig(): void
    {
        $settings = static::where('group', 'smtp')->get();

        foreach ($settings as $setting) {
            $configKey = match($setting->key) {
                'smtp_host' => 'mail.mailers.smtp.host',
                'smtp_port' => 'mail.mailers.smtp.port',
                'smtp_username' => 'mail.mailers.smtp.username',
                'smtp_password' => 'mail.mailers.smtp.password',
                'smtp_encryption' => 'mail.mailers.smtp.encryption',
                'from_address' => 'mail.from.address',
                'from_name' => 'mail.from.name',
                default => null,
            };

            if ($configKey) {
                Config::set($configKey, $setting->value);
            }
        }

        // Set mailer to smtp if settings exist
        if ($settings->isNotEmpty()) {
            Config::set('mail.default', 'smtp');
        }
    }
}
