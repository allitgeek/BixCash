<?php

namespace App\Mail;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;

    public function __construct(WithdrawalRequest $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function build()
    {
        return $this->subject('Withdrawal Request Received - BixCash')
                    ->view('emails.withdrawals.requested');
    }
}
