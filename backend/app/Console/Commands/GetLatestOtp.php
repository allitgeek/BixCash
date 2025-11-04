<?php

namespace App\Console\Commands;

use App\Models\OtpVerification;
use Illuminate\Console\Command;

class GetLatestOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:latest {phone?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the latest OTP for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');

        $query = OtpVerification::query();

        if ($phone) {
            $query->where('phone', $phone);
        }

        $otp = $query->latest()->first();

        if (!$otp) {
            $this->error('No OTP found');
            return 1;
        }

        $this->info('Latest OTP Details:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Phone', $otp->phone],
                ['OTP Code', $otp->otp_code],
                ['Purpose', $otp->purpose],
                ['Created At', $otp->created_at->format('Y-m-d H:i:s')],
                ['Expires At', $otp->expires_at->format('Y-m-d H:i:s')],
                ['Is Expired', $otp->isExpired() ? 'Yes' : 'No'],
                ['Is Verified', $otp->is_verified ? 'Yes' : 'No'],
            ]
        );

        return 0;
    }
}
