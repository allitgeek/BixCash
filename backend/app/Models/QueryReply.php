<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueryReply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_query_id',
        'user_id',
        'message',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the customer query that this reply belongs to.
     */
    public function customerQuery(): BelongsTo
    {
        return $this->belongsTo(CustomerQuery::class);
    }

    /**
     * Get the user (admin) who sent this reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
