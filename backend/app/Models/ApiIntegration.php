<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiIntegration extends Model
{
    protected $fillable = [
        'name',
        'partner_id',
        'api_key',
        'api_secret',
        'is_active',
        'allowed_ips',
        'rate_limit_per_minute',
        'last_used_at',
        'total_requests',
    ];

    protected $casts = [
        'allowed_ips' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'api_secret',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function verifySecret(string $plain): bool
    {
        return Hash::check($plain, $this->api_secret);
    }

    public function recordUsage(): void
    {
        $this->increment('total_requests');
        $this->update(['last_used_at' => now()]);
    }

    public static function generateCredentials(): array
    {
        return [
            'key' => Str::random(64),
            'secret' => Str::random(64),
        ];
    }
}
