<?php

namespace App\Jobs;

use App\Models\CustomerQuery;
use App\Models\EmailSetting;
use App\Mail\CustomerQueryConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCustomerQueryConfirmation implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CustomerQuery $query
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

        // Send confirmation to customer
        Mail::to($this->query->email)->send(new CustomerQueryConfirmation($this->query));
    }
}
