<?php

namespace App\Jobs;

use App\Mail\QueryReplyMail;
use App\Models\CustomerQuery;
use App\Models\EmailSetting;
use App\Models\QueryReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendQueryReply implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CustomerQuery $query,
        public QueryReply $reply
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Apply email settings from database
            EmailSetting::applyToConfig();

            // Load the reply relationship to ensure user is available
            $this->reply->load('user');

            // Send the email
            Mail::to($this->query->email)->send(new QueryReplyMail($this->query, $this->reply));

            // Update reply with sent timestamp only if successful
            $this->reply->update(['sent_at' => now()]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to send query reply email', [
                'query_id' => $this->query->id,
                'reply_id' => $this->reply->id,
                'customer_email' => $this->query->email,
                'error' => $e->getMessage(),
            ]);

            // Re-throw to mark job as failed
            throw $e;
        }
    }
}
