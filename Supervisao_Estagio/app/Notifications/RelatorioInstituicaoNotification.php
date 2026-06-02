<?php

namespace App\Notifications;

use App\Models\Instituicao;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// RF42 – Emitir Relatório Institucional
// RNF08 – Auditoria: notificação ao gerar relatório institucional
class RelatorioInstituicaoNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Instituicao $instituicao,
        protected array $relatorio
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Relatório Institucional — {$this->instituicao->nome_instituicao}")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("O relatório consolidado da instituição **{$this->instituicao->nome_instituicao}** ({$this->instituicao->sigla}) foi gerado.")
            ->line("**Resumo:**")
            ->line("- Cursos: {$this->relatorio['total_cursos']}")
            ->line("- Coordenadores: {$this->relatorio['total_coordenadores']}")
            ->line("- Alunos ativos: {$this->relatorio['total_alunos_ativos']}")
            ->line("- Em estágio: {$this->relatorio['total_em_estagio']}")
            ->action('Acessar SCES', url('/'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo'            => 'relatorio_institucional',
            'instituicao_id'  => $this->instituicao->id,
            'instituicao'     => $this->instituicao->nome_instituicao,
            'total_cursos'    => $this->relatorio['total_cursos'],
            'total_alunos'    => $this->relatorio['total_alunos_ativos'],
            'gerado_em'       => now()->toDateTimeString(),
        ];
    }
}
