<?php

namespace App\Notifications\Admin;

use App\Models\CommissionBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyCommissionCalculated extends Notification implements ShouldQueue
{
    use Queueable;

    public $batch;

    /**
     * Create a new notification instance.
     */
    public function __construct(CommissionBatch $batch)
    {
        $this->batch = $batch;
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
        $formattedPeriod = \Carbon\Carbon::createFromFormat('Y-m', $this->batch->batch_period)->format('F Y');

        return (new MailMessage)
            ->subject('📊 Monthly Commission Calculation Complete - ' . $formattedPeriod)
            ->markdown('emails.commissions.monthly-calculated', [
                'batch' => $this->batch,
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
            'batch_id' => $this->batch->id,
            'batch_period' => $this->batch->batch_period,
            'total_commission' => $this->batch->total_commission_calculated,
        ];
    }
}
