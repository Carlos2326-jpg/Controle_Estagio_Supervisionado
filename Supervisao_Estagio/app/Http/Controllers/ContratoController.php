<?php

namespace App\Http\Controllers;

use App\Models\ContratoEstagio;
use App\Services\ContratoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratoController extends Controller
{
    protected $contratoService;
    
    public function __construct(ContratoService $contratoService)
    {
        $this->contratoService = $contratoService;
        $this->middleware('auth');
    }
    
    /**
     * Listar contratos do usuário
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $query = $usuario->getContratos();
        
        // Filtros
        if ($request->filled('status')) {
            $query = $query->where('status', $request->status);
        }
        
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query = $query->filter(function($contrato) use ($busca) {
                return stripos($contrato->getAluno()->user->nome, $busca) !== false ||
                       stripos($contrato->numero_contrato, $busca) !== false;
            });
        }
        
        $contratos = $query->values();
        $estatisticas = $this->contratoService->getEstatisticas($usuario);
        
        return view('contratos.index', compact('contratos', 'estatisticas', 'request'));
    }
    
    /**
     * Visualizar detalhes do contrato
     */
    public function show($id)
    {
        $contrato = ContratoEstagio::findOrFail($id);
        $usuario = Auth::user();
        
        // Verificar permissão
        if (!$this->temPermissao($usuario, $contrato)) {
            abort(403, 'Acesso não autorizado');
        }
        
        $relatorio = $this->contratoService->gerarRelatorio($id);
        
        return view('contratos.show', compact('contrato', 'relatorio'));
    }
    
    /**
     * Registrar atividade no estágio
     */
    public function registrarAtividade(Request $request, $id)
    {
        $contrato = ContratoEstagio::findOrFail($id);
        $usuario = Auth::user();
        
        if (!$usuario->isAluno() || $contrato->getAluno()->id_usuario != $usuario->id_usuario) {
            abort(403, 'Apenas o aluno pode registrar atividades');
        }
        
        $request->validate([
            'data_atividade' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required',
            'descricao' => 'required|string|min:10'
        ]);
        
        // Calcular horas
        $horaInicio = strtotime($request->hora_inicio);
        $horaFim = strtotime($request->hora_fim);
        $horasComputadas = round(($horaFim - $horaInicio) / 3600, 2);
        
        $dados = $request->all();
        $dados['horas_computadas'] = $horasComputadas;
        
        $registro = $this->contratoService->registrarAtividade($id, $dados);
        
        return redirect()->route('contratos.show', $id)
            ->with('success', 'Atividade registrada com sucesso! Aguardando validação do supervisor.');
    }
    
    /**
     * Validar atividade (supervisor)
     */
    public function validarAtividade(Request $request, $id, $registroId)
    {
        $contrato = ContratoEstagio::findOrFail($id);
        $usuario = Auth::user();
        
        if (!$usuario->isSupervisor() || 
            $contrato->getSupervisor()->id_usuario != $usuario->id_usuario) {
            abort(403, 'Apenas o supervisor pode validar atividades');
        }
        
        $request->validate([
            'validado' => 'required|boolean',
            'observacao' => 'nullable|string'
        ]);
        
        $this->contratoService->validarAtividade(
            $registroId, 
            $request->validado, 
            $request->observacao
        );
        
        return redirect()->route('contratos.show', $id)
            ->with('success', 'Atividade validada com sucesso!');
    }
    
    /**
     * Encerrar contrato (coordenador)
     */
    public function encerrar(Request $request, $id)
    {
        $contrato = ContratoEstagio::findOrFail($id);
        $usuario = Auth::user();
        
        if (!$usuario->isCoordenador()) {
            abort(403, 'Apenas coordenadores podem encerrar contratos');
        }
        
        $this->contratoService->encerrarContrato($id);
        
        return redirect()->route('contratos.show', $id)
            ->with('success', 'Contrato encerrado com sucesso!');
    }
    
    /**
     * Verificar permissão de acesso ao contrato
     */
    private function temPermissao($usuario, $contrato)
    {
        if ($usuario->isAluno()) {
            return $contrato->getAluno()->id_usuario == $usuario->id_usuario;
        }
        
        if ($usuario->isCoordenador()) {
            return $contrato->getCoordenador()->id_coordenador == $usuario->coordenador->id_coordenador;
        }
        
        if ($usuario->isSupervisor()) {
            return $contrato->getSupervisor()->id_supervisor == $usuario->supervisor->id_supervisor;
        }
        
        return false;
    }
}