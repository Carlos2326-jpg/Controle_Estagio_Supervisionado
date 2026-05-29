<?php

namespace App\Http\Controllers;

use App\Models\AlertaPrazo;
use App\Services\AlertaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertaController extends Controller
{
  protected $alertaService;

  public function __construct(AlertaService $alertaService)
  {
    $this->alertaService = $alertaService;
    $this->middleware('auth');
  }

  /**
   * Listar alertas do usuário
   */
  public function index(Request $request)
  {
    $usuario = Auth::user();
    $query = $usuario->alertas()->orderBy('data_geracao', 'desc');

    // Filtros
    if ($request->filled('status')) {
      if ($request->status == 'nao_lidos') {
        $query->where('lido', false);
      } elseif ($request->status == 'lidos') {
        $query->where('lido', true);
      } elseif ($request->status == 'vencidos') {
        $query->where('data_vencimento', '<', now());
      }
    }

    if ($request->filled('tipo')) {
      $query->where('tipo_alerta', $request->tipo);
    }

    $alertas = $query->paginate(20);

    $estatisticas = [
      'total' => $usuario->alertas()->count(),
      'nao_lidos' => $usuario->alertas()->where('lido', false)->count(),
      'vencidos' => $usuario->alertas()->where('data_vencimento', '<', now())->count(),
      'proximos_vencer' => $usuario->alertas()
        ->where('lido', false)
        ->where('data_vencimento', '>=', now())
        ->where('data_vencimento', '<=', now()->addDays(7))
        ->count()
    ];

    return view('alertas.index', compact('alertas', 'estatisticas'));
  }

  /**
   * API: Obter alertas não lidos (para AJAX/notificações)
   */
  public function getNaoLidos()
  {
    $usuario = Auth::user();
    $alertas = $usuario->getAlertasNaoLidos();

    return response()->json([
      'total' => $alertas->count(),
      'alertas' => $alertas->map(function ($alerta) {
        return [
          'id' => $alerta->id_alerta,
          'tipo' => $alerta->getTipoDisplay(),
          'icone' => $alerta->getIcone(),
          'mensagem' => $alerta->mensagem,
          'data' => $alerta->data_geracao->format('d/m/Y H:i'),
          'cor' => $alerta->getCor(),
          'dias_restantes' => $alerta->getDiasRestantes()
        ];
      })
    ]);
  }

  /**
   * Marcar alerta como lido
   */
  public function marcarLido($id)
  {
    $alerta = AlertaPrazo::findOrFail($id);
    $usuario = Auth::user();

    if ($alerta->id_usuario_destino != $usuario->id_usuario) {
      abort(403);
    }

    $alerta->marcarComoLido();

    if (request()->ajax()) {
      return response()->json(['success' => true]);
    }

    return redirect()->back()->with('success', 'Alerta marcado como lido');
  }

  /**
   * Marcar todos como lidos
   */
  public function marcarTodosLidos()
  {
    $usuario = Auth::user();

    AlertaPrazo::where('id_usuario_destino', $usuario->id_usuario)
      ->where('lido', false)
      ->update([
        'lido' => true,
        'data_leitura' => now()
      ]);

    return redirect()->route('alertas.index')
      ->with('success', 'Todos os alertas foram marcados como lidos');
  }

  /**
   * Gerar alertas manualmente (admin)
   */
  public function gerarAlertas()
  {
    $this->alertaService->gerarAlertasVencimentoContratos();
    $this->alertaService->gerarAlertasAvaliacoesPendentes();

    return redirect()->route('alertas.index')
      ->with('success', 'Alertas gerados com sucesso!');
  }

  /**
   * Excluir alerta
   */
  public function destroy($id)
  {
    $alerta = AlertaPrazo::findOrFail($id);
    $usuario = Auth::user();

    if ($alerta->id_usuario_destino != $usuario->id_usuario) {
      abort(403);
    }

    $alerta->delete();

    return redirect()->route('alertas.index')
      ->with('success', 'Alerta removido com sucesso');
  }

  /**
   * Excluir alertas antigos (mais de 30 dias)
   */
  public function limparAntigos()
  {
    $this->alertaService->limparAlertasAntigos();

    return redirect()->route('alertas.index')
      ->with('success', 'Alertas antigos removidos com sucesso');
  }
}
