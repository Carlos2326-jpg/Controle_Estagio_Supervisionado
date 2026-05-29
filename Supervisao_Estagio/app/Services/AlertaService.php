<?php

namespace App\Services;

use App\Models\AlertaPrazo;
use App\Models\ContratoEstagio;
use App\Models\Avaliacao;
use App\Models\User;
use Carbon\Carbon;

class AlertaService
{
  /**
   * Gerar alertas de vencimento de contratos
   */
  public function gerarAlertasVencimentoContratos()
  {
    // Contratos vencendo em 7, 15 e 30 dias
    $prazos = [7, 15, 30];

    foreach ($prazos as $prazo) {
      $contratos = ContratoEstagio::vencendoEm($prazo)->get();

      foreach ($contratos as $contrato) {
        $this->criarAlertaVencimentoContrato($contrato, $prazo);
      }
    }

    // Contratos vencidos
    $contratosVencidos = ContratoEstagio::where('status', 'ATIVO')
      ->where('data_fim', '<', now())
      ->get();

    foreach ($contratosVencidos as $contrato) {
      $this->criarAlertaContratoVencido($contrato);
    }
  }

  /**
   * Criar alerta específico para vencimento de contrato
   */
  private function criarAlertaVencimentoContrato($contrato, $dias)
  {
    $aluno = $contrato->getAluno();
    $coordenador = $contrato->getCoordenador();
    $supervisor = $contrato->getSupervisor();

    $mensagem = "Contrato de estágio do aluno {$aluno->user->nome} vence em {$dias} dias. " .
      "Data de término: " . $contrato->data_fim->format('d/m/Y');

    // Alertas para o aluno
    if ($aluno && $aluno->user) {
      $this->criarAlerta(
        $aluno->user->id_usuario,
        'VENCIMENTO_CONTRATO',
        $contrato->id_contrato,
        $mensagem,
        $contrato->data_fim
      );
    }

    // Alertas para o coordenador
    if ($coordenador && $coordenador->user) {
      $this->criarAlerta(
        $coordenador->user->id_usuario,
        'VENCIMENTO_CONTRATO',
        $contrato->id_contrato,
        "Atenção! {$mensagem}",
        $contrato->data_fim
      );
    }

    // Alertas para o supervisor
    if ($supervisor && $supervisor->user) {
      $this->criarAlerta(
        $supervisor->user->id_usuario,
        'VENCIMENTO_CONTRATO',
        $contrato->id_contrato,
        $mensagem,
        $contrato->data_fim
      );
    }
  }

  /**
   * Criar alerta de contrato vencido
   */
  private function criarAlertaContratoVencido($contrato)
  {
    $aluno = $contrato->getAluno();
    $coordenador = $contrato->getCoordenador();

    $mensagem = "Contrato de estágio venceu em " .
      $contrato->data_fim->format('d/m/Y') .
      ". Pendente de encerramento ou renovação.";

    if ($coordenador && $coordenador->user) {
      $this->criarAlerta(
        $coordenador->user->id_usuario,
        'VENCIMENTO_CONTRATO',
        $contrato->id_contrato,
        $mensagem,
        $contrato->data_fim
      );
    }
  }

  /**
   * Gerar alertas de avaliações pendentes
   */
  public function gerarAlertasAvaliacoesPendentes()
  {
    // Avaliações pendentes há mais de 7 dias
    $avaliacoesPendentes = Avaliacao::whereNull('parecer')
      ->where('data_avaliacao', '<', Carbon::now()->subDays(7))
      ->get();

    foreach ($avaliacoesPendentes as $avaliacao) {
      $avaliador = $avaliacao->avaliador;

      if ($avaliador) {
        $aluno = $avaliacao->contrato->getAluno();
        $mensagem = "Avaliação pendente para o aluno {$aluno->user->nome} " .
          "referente ao período {$avaliacao->periodo_referencia}. " .
          "Pendente há mais de 7 dias.";

        $this->criarAlerta(
          $avaliador->id_usuario,
          'AVALIACAO_PENDENTE',
          $avaliacao->id_avaliacao,
          $mensagem,
          Carbon::now()->addDays(3)
        );
      }
    }
  }

  /**
   * Criar alerta genérico
   */
  public function criarAlerta($usuarioId, $tipo, $referenciaId, $mensagem, $dataVencimento = null)
  {
    // Verificar se já existe alerta similar não lido
    $existe = AlertaPrazo::where('id_usuario_destino', $usuarioId)
      ->where('tipo_alerta', $tipo)
      ->where('id_referencia', $referenciaId)
      ->where('lido', false)
      ->exists();

    if (!$existe) {
      return AlertaPrazo::create([
        'id_usuario_destino' => $usuarioId,
        'tipo_alerta' => $tipo,
        'id_referencia' => $referenciaId,
        'mensagem' => $mensagem,
        'data_geracao' => now(),
        'data_vencimento' => $dataVencimento ?? now()->addDays(7),
        'lido' => false
      ]);
    }

    return null;
  }

  /**
   * Enviar notificações push/email dos alertas
   */
  public function enviarNotificacoes($usuarioId)
  {
    $alertasNaoLidos = AlertaPrazo::naoLidos()
      ->where('id_usuario_destino', $usuarioId)
      ->get();

    $notificacoes = [];

    foreach ($alertasNaoLidos as $alerta) {
      // Aqui você pode integrar com serviços de email, push notifications, etc.
      $notificacoes[] = [
        'titulo' => $alerta->getTipoDisplay(),
        'mensagem' => $alerta->mensagem,
        'icone' => $alerta->getIcone(),
        'data' => $alerta->data_geracao->format('d/m/Y H:i')
      ];
    }

    return $notificacoes;
  }

  /**
   * Limpar alertas antigos (mais de 30 dias após vencimento)
   */
  public function limparAlertasAntigos()
  {
    return AlertaPrazo::where('data_vencimento', '<', Carbon::now()->subDays(30))
      ->delete();
  }
}
