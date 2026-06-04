<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AlertaCoordenadorNotification extends Notification
{
    use Queueable;

    protected string $mensagem;
    protected ?string $justificativa;

    public function __construct(string $mensagem, ?string $justificativa = null)
    {
        $this->mensagem      = $mensagem;
        $this->justificativa = $justificativa;
    }

    /**
     * Canais de envio: banco de dados e e-mail
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Representação por e-mail
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Notificação — Sistema de Estágio')
            ->line($this->mensagem);

        if ($this->justificativa) {
            $mail->line('Justificativa: ' . $this->justificativa);
        }

        return $mail->line('Acesse o sistema para mais detalhes.')
                    ->action('Acessar Sistema', url('/'));
    }

    /**
     * Representação no banco de dados (para alertas internos)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mensagem'     => $this->mensagem,
            'justificativa' => $this->justificativa,
        ];
    }
}