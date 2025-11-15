<?php

namespace App\Notifications\Admin;

use App\Models\CommissionSettlement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementProcessedConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public $settlement;

    /**
     * Create a new notification instance.
     */
    public function __construct(CommissionSettlement $settlement)
    {
        $this->settlement = $settlement;
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
        $formattedPeriod = \Carbon\Carbon::createFromFormat('Y-m', $this->settlement->ledger->batch_period)->format('F Y');

        return (new MailMessage)
            ->subject('✅ Settlement Processed - ' . $this->settlement->partner->name)
            ->markdown('emails.commissions.settlement-confirmation', [
                'settlement' => $this->settlement,
                'ledger' => $this->settlement->ledger,
                'admin' => $notifiable,
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
            'settlement_id' => $this->settlement->id,
            'partner_id' => $this->settlement->partner_id,
            'amount_settled' => $this->settlement->amount_settled,
        ];
    }
}
