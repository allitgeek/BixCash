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
        // Apply email settings from database
        EmailSetting::applyToConfig();

        // Send the email
        Mail::to($this->query->email)->send(new QueryReplyMail($this->query, $this->reply));

        // Update reply with sent timestamp
        $this->reply->update(['sent_at' => now()]);
    }
}
