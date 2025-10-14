<?php

namespace App\Jobs;

use App\Models\CustomerQuery;
use App\Models\EmailSetting;
use App\Mail\CustomerQueryNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCustomerQueryNotification implements ShouldQueue
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

        // Get admin email from settings or use default
        $adminEmail = EmailSetting::get('admin_email', config('mail.from.address'));

        // Send notification to admin
        Mail::to($adminEmail)->send(new CustomerQueryNotification($this->query));
    }
}
