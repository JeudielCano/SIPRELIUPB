<?php

namespace App\Notifications;

use App\Models\LoanRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanRejected extends Notification
{
    use Queueable;

    public function __construct(
        public LoanRequest $loan,
        public string $reason = 'No se especificó un motivo.'
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('❌ Tu solicitud de préstamo fue rechazada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('Tu solicitud #' . $this->loan->id . ' ha sido rechazada.')
            ->line('**Motivo:** ' . $this->reason)
            ->action('Ver mis solicitudes', url('/loans'))
            ->line('Gracias por usar SIPRELI UPB.');
    }

    public function toArray($notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'message' => 'Tu solicitud #' . $this->loan->id . ' fue rechazada.',
            'type'    => 'rechazado',
            'reason'  => $this->reason,
        ];
    }
}