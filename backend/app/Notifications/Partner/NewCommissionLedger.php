<?php

namespace App\Notifications\Partner;

use App\Models\CommissionLedger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommissionLedger extends Notification implements ShouldQueue
{
    use Queueable;

    public $ledger;

    /**
     * Create a new notification instance.
     */
    public function __construct(CommissionLedger $ledger)
    {
        $this->ledger = $ledger;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $formattedPeriod = \Carbon\Carbon::createFromFormat('Y-m', $this->ledger->batch_period)->format('F Y');

        return (new MailMessage)
            ->subject('💰 New Commission Statement - ' . $formattedPeriod)
            ->markdown('emails.commissions.new-ledger', [
                'ledger' => $this->ledger,
                'partner' => $notifiable,
                'formattedPeriod' => $formattedPeriod,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ledger_id' => $this->ledger->id,
            'batch_period' => $this->ledger->batch_period,
            'commission_owed' => $this->ledger->commission_owed,
        ];
    }
}
