<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\ContratoEstagio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Listar avaliações
   */
  public function index(Request $request)
  {
    $usuario = Auth::user();
    $avaliacoes = $usuario->getAvaliacoes();

    // Filtros
    if ($request->filled('tipo')) {
      $avaliacoes = $avaliacoes->where('tipo_avaliador', $request->tipo);
    }

    if ($request->filled('situacao')) {
      if ($request->situacao == 'PENDENTE') {
        $avaliacoes = $avaliacoes->whereNull('parecer');
      } elseif ($request->situacao == 'APROVADO') {
        $avaliacoes = $avaliacoes->where('situacao_final', 'APROVADO');
      } elseif ($request->situacao == 'REPROVADO') {
        $avaliacoes = $avaliacoes->where('situacao_final', 'REPROVADO');
      }
    }

    $estatisticas = $this->getEstatisticasAvaliacoes($avaliacoes);

    return view('avaliacoes.index', compact('avaliacoes', 'estatisticas'));
  }

  /**
   * Formulário de avaliação
   */
  public function create(Request $request, $contratoId = null)
  {
    $usuario = Auth::user();

    if ($contratoId) {
      $contrato = ContratoEstagio::findOrFail($contratoId);

      // Verificar se usuário pode avaliar este contrato
      if (!$this->podeAvaliar($usuario, $contrato)) {
        abort(403);
      }

      return view('avaliacoes.create', compact('contrato'));
    }

    // Listar contratos que podem ser avaliados
    $contratos = $this->getContratosParaAvaliar($usuario);

    return view('avaliacoes.select_contrato', compact('contratos'));
  }

  /**
   * Salvar avaliação
   */
  public function store(Request $request)
  {
    $usuario = Auth::user();
    $contrato = ContratoEstagio::findOrFail($request->contrato_id);

    if (!$this->podeAvaliar($usuario, $contrato)) {
      abort(403);
    }

    $request->validate([
      'contrato_id' => 'required|exists:contrato_estagio,id_contrato',
      'periodo_referencia' => 'required|string|max:20',
      'nota_desempenho' => 'required|numeric|min:0|max:10',
      'nota_comportamento' => 'required|numeric|min:0|max:10',
      'nota_pontualidade' => 'required|numeric|min:0|max:10',
      'parecer' => 'nullable|string'
    ]);

    $avaliacao = new Avaliacao($request->all());
    $avaliacao->id_contrato = $request->contrato_id;
    $avaliacao->tipo_avaliador = $usuario->isCoordenador() ? 'COORDENADOR' : 'SUPERVISOR';
    $avaliacao->id_avaliador = $usuario->id_usuario;
    $avaliacao->calcularMedia();
    $avaliacao->save();

    return redirect()->route('avaliacoes.show', $avaliacao->id_avaliacao)
      ->with('success', 'Avaliação registrada com sucesso!');
  }

  /**
   * Visualizar avaliação
   */
  public function show($id)
  {
    $avaliacao = Avaliacao::with(['contrato', 'avaliador'])->findOrFail($id);
    $usuario = Auth::user();

    // Verificar permissão
    if ($avaliacao->id_avaliador != $usuario->id_usuario && !$usuario->isAluno()) {
      abort(403);
    }

    $aluno = $avaliacao->contrato->getAluno();

    return view('avaliacoes.show', compact('avaliacao', 'aluno'));
  }

  /**
   * Formulário de avaliação final (coordenador)
   */
  public function avaliacaoFinal($contratoId)
  {
    $usuario = Auth::user();

    if (!$usuario->isCoordenador()) {
      abort(403, 'Apenas coordenadores podem realizar avaliação final');
    }

    $contrato = ContratoEstagio::findOrFail($contratoId);
    $avaliacoesSupervisor = $contrato->avaliacoes()
      ->where('tipo_avaliador', 'SUPERVISOR')
      ->get();

    if ($avaliacoesSupervisor->isEmpty()) {
      return redirect()->route('contratos.show', $contratoId)
        ->with('warning', 'É necessário pelo menos uma avaliação do supervisor antes da avaliação final.');
    }

    return view('avaliacoes.final', compact('contrato', 'avaliacoesSupervisor'));
  }

  /**
   * Salvar avaliação final
   */
  public function storeAvaliacaoFinal(Request $request, $contratoId)
  {
    $usuario = Auth::user();

    if (!$usuario->isCoordenador()) {
      abort(403);
    }

    $request->validate([
      'nota_desempenho' => 'required|numeric|min:0|max:10',
      'nota_comportamento' => 'required|numeric|min:0|max:10',
      'nota_pontualidade' => 'required|numeric|min:0|max:10',
      'situacao_final' => 'required|in:APROVADO,REPROVADO',
      'parecer' => 'required|string|min:20'
    ]);

    $contrato = ContratoEstagio::findOrFail($contratoId);

    $avaliacao = new Avaliacao($request->all());
    $avaliacao->id_contrato = $contratoId;
    $avaliacao->tipo_avaliador = 'COORDENADOR';
    $avaliacao->id_avaliador = $usuario->id_usuario;
    $avaliacao->periodo_referencia = date('Y') . '/' . (date('m') <= 6 ? '1' : '2');
    $avaliacao->calcularMedia();
    $avaliacao->save();

    // Atualizar situação do aluno
    $aluno = $contrato->getAluno();
    $aluno->situacao_estagio = $request->situacao_final == 'APROVADO' ? 'CONCLUIDO' : 'REPROVADO';
    $aluno->save();

    return redirect()->route('contratos.show', $contratoId)
      ->with('success', 'Avaliação final registrada com sucesso!');
  }

  /**
   * Verificar se usuário pode avaliar
   */
  private function podeAvaliar($usuario, $contrato)
  {
    if ($usuario->isSupervisor()) {
      return $contrato->getSupervisor()->id_usuario == $usuario->id_usuario;
    }

    if ($usuario->isCoordenador()) {
      return $contrato->getCoordenador()->id_coordenador == $usuario->coordenador->id_coordenador;
    }

    return false;
  }

  /**
   * Obter contratos que podem ser avaliados
   */
  private function getContratosParaAvaliar($usuario)
  {
    if ($usuario->isSupervisor()) {
      return ContratoEstagio::whereHas('solicitacao', function ($q) use ($usuario) {
        $q->where('id_supervisor', $usuario->supervisor->id_supervisor);
      })->where('status', 'ATIVO')->get();
    }

    if ($usuario->isCoordenador()) {
      return ContratoEstagio::whereHas('solicitacao', function ($q) use ($usuario) {
        $q->where('id_coordenador', $usuario->coordenador->id_coordenador);
      })->where('status', 'ATIVO')->get();
    }

    return collect();
  }

  /**
   * Estatísticas das avaliações
   */
  private function getEstatisticasAvaliacoes($avaliacoes)
  {
    return [
      'total' => $avaliacoes->count(),
      'pendentes' => $avaliacoes->whereNull('parecer')->count(),
      'aprovadas' => $avaliacoes->where('situacao_final', 'APROVADO')->count(),
      'reprovadas' => $avaliacoes->where('situacao_final', 'REPROVADO')->count(),
      'media_geral' => $avaliacoes->avg('media_final'),
      'media_desempenho' => $avaliacoes->avg('nota_desempenho'),
      'melhor_avaliacao' => $avaliacoes->max('media_final'),
      'pior_avaliacao' => $avaliacoes->min('media_final')
    ];
  }
}
