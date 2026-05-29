<?php

namespace App\Services;

use App\Models\ContratoEstagio;
use App\Models\RegistroAtividade;
use App\Models\Documento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContratoService
{
  protected $alertaService;

  public function __construct(AlertaService $alertaService)
  {
    $this->alertaService = $alertaService;
  }

  /**
   * Obter estatísticas de contratos
   */
  public function getEstatisticas($usuario)
  {
    $contratos = $usuario->getContratos();

    return [
      'total' => $contratos->count(),
      'ativos' => $contratos->filter(function ($c) {
        return $c->isAtivo();
      })->count(),
      'encerrados' => $contratos->filter(function ($c) {
        return $c->isEncerrado();
      })->count(),
      'cancelados' => $contratos->filter(function ($c) {
        return $c->isCancelado();
      })->count(),
      'vencendo_30dias' => $contratos->filter(function ($c) {
        return $c->isAtivo() && $c->getDiasRestantes() <= 30 && $c->getDiasRestantes() > 0;
      })->count(),
      'horas_total' => $contratos->sum(function ($c) {
        return $c->getHorasCumpridas();
      }),
      'percentual_medio' => $contratos->avg(function ($c) {
        return $c->getPercentualConclusao();
      })
    ];
  }

  /**
   * Registrar atividade no estágio
   */
  public function registrarAtividade($contratoId, $dados)
  {
    DB::beginTransaction();

    try {
      $registro = RegistroAtividade::create([
        'id_contrato' => $contratoId,
        'data_atividade' => $dados['data_atividade'],
        'hora_inicio' => $dados['hora_inicio'],
        'hora_fim' => $dados['hora_fim'],
        'horas_computadas' => $dados['horas_computadas'],
        'descricao' => $dados['descricao'],
        'validado_supervisor' => false
      ]);

      DB::commit();
      return $registro;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Validar atividade pelo supervisor
   */
  public function validarAtividade($registroId, $validado, $observacao = null)
  {
    $registro = RegistroAtividade::findOrFail($registroId);
    $registro->validado_supervisor = $validado;
    $registro->observacao_supervisor = $observacao;
    $registro->save();

    return $registro;
  }

  /**
   * Encerrar contrato
   */
  public function encerrarContrato($contratoId, $dataEncerramento = null)
  {
    $contrato = ContratoEstagio::findOrFail($contratoId);

    $contrato->data_fim_real = $dataEncerramento ?? now();
    $contrato->status = 'ENCERRADO';
    $contrato->save();

    return $contrato;
  }

  /**
   * Renovar contrato
   */
  public function renovarContrato($contratoAntigoId, $novaDataFim)
  {
    $contratoAntigo = ContratoEstagio::findOrFail($contratoAntigoId);

    DB::beginTransaction();

    try {
      // Encerrar contrato antigo
      $contratoAntigo->status = 'RENOVADO';
      $contratoAntigo->data_fim_real = now();
      $contratoAntigo->save();

      // Criar novo contrato
      $novoContrato = ContratoEstagio::create([
        'id_solicitacao' => $contratoAntigo->id_solicitacao,
        'numero_contrato' => $this->gerarNumeroContrato(),
        'data_inicio' => now(),
        'data_fim' => $novaDataFim,
        'valor_bolsa' => $contratoAntigo->valor_bolsa,
        'valor_auxilio_transporte' => $contratoAntigo->valor_auxilio_transporte,
        'status' => 'ATIVO'
      ]);

      DB::commit();
      return $novoContrato;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Gerar número único de contrato
   */
  private function gerarNumeroContrato()
  {
    $ano = date('Y');
    $ultimo = ContratoEstagio::whereYear('data_inicio', $ano)->count();
    $sequencial = str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    return "CTR/{$ano}/{$sequencial}";
  }

  /**
   * Upload de documento do contrato
   */
  public function uploadDocumento($contratoId, $arquivo, $tipo)
  {
    $contrato = ContratoEstagio::findOrFail($contratoId);

    $path = $arquivo->store("contratos/{$contratoId}", 'public');

    return Documento::create([
      'id_contrato' => $contratoId,
      'tipo_documento' => $tipo,
      'nome_arquivo' => $arquivo->getClientOriginalName(),
      'caminho_arquivo' => $path,
      'status' => 'PENDENTE'
    ]);
  }

  /**
   * Gerar relatório do contrato
   */
  public function gerarRelatorio($contratoId)
  {
    $contrato = ContratoEstagio::with([
      'solicitacao.aluno',
      'solicitacao.empresa',
      'registrosAtividade',
      'avaliacoes'
    ])
      ->findOrFail($contratoId);

    return [
      'contrato' => $contrato,
      'aluno' => $contrato->getAluno(),
      'empresa' => $contrato->getEmpresa(),
      'supervisor' => $contrato->getSupervisor(),
      'coordenador' => $contrato->getCoordenador(),
      'horas_cumpridas' => $contrato->getHorasCumpridas(),
      'percentual_conclusao' => $contrato->getPercentualConclusao(),
      'atividades_por_mes' => $this->getAtividadesPorMes($contrato),
      'media_avaliacoes' => $this->getMediaAvaliacoes($contrato),
      'dias_restantes' => $contrato->getDiasRestantes(),
      'status_contrato' => $contrato->status,
      'documentos' => $contrato->documentos
    ];
  }

  private function getAtividadesPorMes($contrato)
  {
    return $contrato->registrosAtividade()
      ->where('validado_supervisor', true)
      ->select(
        DB::raw('YEAR(data_atividade) as ano'),
        DB::raw('MONTH(data_atividade) as mes'),
        DB::raw('SUM(horas_computadas) as total_horas')
      )
      ->groupBy('ano', 'mes')
      ->orderBy('ano', 'desc')
      ->orderBy('mes', 'desc')
      ->get();
  }

  private function getMediaAvaliacoes($contrato)
  {
    $avaliacoes = $contrato->avaliacoes;

    if ($avaliacoes->isEmpty()) {
      return null;
    }

    return [
      'media_desempenho' => $avaliacoes->avg('nota_desempenho'),
      'media_comportamento' => $avaliacoes->avg('nota_comportamento'),
      'media_pontualidade' => $avaliacoes->avg('nota_pontualidade'),
      'media_geral' => $avaliacoes->avg('media_final'),
      'total_avaliacoes' => $avaliacoes->count()
    ];
  }
}
