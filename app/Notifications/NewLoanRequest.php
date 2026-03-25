<?php

namespace App\Notifications;

use App\Models\LoanRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewLoanRequest extends Notification
{
    use Queueable;

    public function __construct(public LoanRequest $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $esExterna = str_contains($this->loan->observations ?? '', '(SOLICITUD EXTERNA)');
        $tipo = $esExterna ? 'EXTERNA' : 'INTERNA';

        return (new MailMessage)
            ->subject("📋 Nueva solicitud {$tipo} #{$this->loan->id}")
            ->greeting('Hola, ' . $notifiable->name)
            ->line("Se ha recibido una nueva solicitud de préstamo {$tipo}.")
            ->line('Solicitante: ' . $this->loan->user->name)
            ->line('Folio: #' . $this->loan->id)
            ->action('Revisar solicitud', url('/admin/loans'))
            ->line('Ingresa al sistema para aprobarla o rechazarla.');
    }

    public function toArray($notifiable): array
    {
        $esExterna = str_contains($this->loan->observations ?? '', '(SOLICITUD EXTERNA)');
        $tipo = $esExterna ? 'externa' : 'interna';

        return [
            'loan_id'  => $this->loan->id,
            'message'  => "Nueva solicitud {$tipo} #" . $this->loan->id . ' de ' . $this->loan->user->name,
            'type'     => 'nueva_solicitud',
        ];
    }
}