<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

// RF12 – Receber Alertas
// Notificações sobre contratos, documentos pendentes e avaliações
class AlertaAlunoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $mensagem,
        public readonly ?string $detalhe = null,
        public readonly string $tipo = 'info',  // info | aviso | urgente
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'mensagem' => $this->mensagem,
            'detalhe'  => $this->detalhe,
            'tipo'     => $this->tipo,
        ];
    }
}