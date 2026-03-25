<?php

namespace App\Notifications;

use App\Models\LoanRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanApproved extends Notification
{
    use Queueable;

    public function __construct(public LoanRequest $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Tu solicitud de préstamo fue aprobada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu solicitud #' . $this->loan->id . ' ha sido aprobada.')
            ->line('Tu código de retiro es: **' . $this->loan->pickup_code . '**')
            ->line('Preséntalo al recoger el material.')
            ->action('Ver mi solicitud', url('/loans'))
            ->line('Gracias por usar SIPRELI UPB.');
    }

    public function toArray($notifiable): array
    {
        return [
            'loan_id'  => $this->loan->id,
            'message'  => 'Tu solicitud #' . $this->loan->id . ' fue aprobada. Código: ' . $this->loan->pickup_code,
            'type'     => 'aprobado',
        ];
    }
}