<?php

namespace App\Notifications\Partner;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutstandingCommissionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $partner;
    public $totalOutstanding;
    public $oldestPeriod;
    public $ledgerCount;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $partner, $totalOutstanding, $oldestPeriod, $ledgerCount)
    {
        $this->partner = $partner;
        $this->totalOutstanding = $totalOutstanding;
        $this->oldestPeriod = $oldestPeriod;
        $this->ledgerCount = $ledgerCount;
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
        $formattedPeriod = \Carbon\Carbon::createFromFormat('Y-m', $this->oldestPeriod)->format('F Y');

        return (new MailMessage)
            ->subject('⏰ Reminder: Outstanding Commission Payment - BixCash')
            ->markdown('emails.commissions.outstanding-reminder', [
                'partner' => $this->partner,
                'totalOutstanding' => $this->totalOutstanding,
                'oldestPeriod' => $formattedPeriod,
                'ledgerCount' => $this->ledgerCount,
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
            'total_outstanding' => $this->totalOutstanding,
            'oldest_period' => $this->oldestPeriod,
            'ledger_count' => $this->ledgerCount,
        ];
    }
}
