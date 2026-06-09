<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\Curso;
use App\Models\Coordenador;
use App\Models\Aluno;
use App\Models\SolicitacaoEstagio;
use App\Models\Documento;
use App\Models\AtividadeEstagio;
use App\Models\Supervisor;
use App\Models\Convenio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard do Administrador
     */
    public function admin()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }

        $data = [
            'total_instituicoes' => Instituicao::count(),
            'total_cursos' => Curso::where('ativo', true)->count(),
            'total_coordenadores' => Coordenador::where('status', 'ativo')->count(),
            'total_alunos' => Aluno::where('ativo', true)->count(),
        ];

        return view('dashboards.admin', $data);
    }

    /**
     * Dashboard do Coordenador
     */
    public function coordenador()
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'coordenador' && $user->role !== 'admin')) {
            abort(403, 'Acesso não autorizado.');
        }

        $coordenador = Coordenador::where('user_id', $user->id)->first();

        if (!$coordenador && $user->role !== 'admin') {
            abort(403, 'Coordenador não encontrado.');
        }

        $cursoId = $coordenador?->curso_id;

        $data = [
            'total_alunos' => Aluno::where('curso_id', $cursoId)->where('ativo', true)->count(),
            'total_solicitacoes_pendentes' => SolicitacaoEstagio::where('curso_id', $cursoId)
                ->where('status', 'pendente')
                ->count(),
            'total_documentos_pendentes' => Documento::whereHas('aluno', function ($query) use ($cursoId) {
                $query->where('curso_id', $cursoId);
            })->where('status', 'pendente')->count(),
            'coordenador_nome' => $coordenador?->user?->name ?? $user->name,
            'curso_nome' => $cursoId ? Curso::find($cursoId)?->nome : 'Não vinculado',
        ];

        return view('dashboards.coordenador', $data);
    }

    /**
     * Dashboard do Aluno
     */
    public function aluno()
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'aluno' && $user->role !== 'admin')) {
            abort(403, 'Acesso não autorizado.');
        }

        $aluno = Aluno::where('user_id', $user->id)->first();

        if (!$aluno && $user->role !== 'admin') {
            abort(403, 'Aluno não encontrado.');
        }

        $data = [
            'situacao_estagio' => $aluno?->situacao_estagio ?? 'sem_estagio',
            'horas_cumpridas' => $aluno?->carga_horaria_cumprida ?? 0,
            'total_atividades' => AtividadeEstagio::where('aluno_id', $aluno?->id)->count(),
            'total_documentos' => Documento::where('aluno_id', $aluno?->id)->count(),
            'aluno_nome' => $aluno?->user?->name ?? $user->name,
            'curso_nome' => $aluno?->curso?->nome ?? 'Não vinculado',
        ];

        return view('dashboards.aluno', $data);
    }

    /**
     * Dashboard da Empresa
     */
    public function empresa()
    {
        $user = Auth::user();

        // Verifica se o usuário está autenticado
        if (!$user) {
            return redirect('/login');
        }

        // Busca a empresa vinculada ao usuário
        $empresa = Empresa::where('user_id', $user->id)->first();

        // Se não for empresa e não for admin, redireciona
        if (!$empresa && $user->role !== 'admin') {
            // Se o usuário tem role empresa mas não tem registro na tabela empresas
            if ($user->role === 'empresa') {
                // Cria um registro básico para a empresa
                $empresa = Empresa::create([
                    'user_id' => $user->id,
                    'razao_social' => $user->name,
                    'email' => $user->email,
                    'status' => 'ativa',
                ]);
            } else {
                abort(403, 'Acesso não autorizado.');
            }
        }

        $empresaId = $empresa?->id;

        $data = [
            'razao_social' => $empresa?->razao_social ?? 'Empresa não cadastrada',
            'total_supervisores' => Supervisor::where('empresa_id', $empresaId)->where('status', 'ativo')->count(),
            'total_estagiarios' => SolicitacaoEstagio::where('empresa_id', $empresaId)
                ->where('status', 'aprovada')
                ->count(),
            'total_convenios' => Convenio::where('empresa_id', $empresaId)->where('status', 'ativo')->count(),
            'empresa_nome' => $empresa?->razao_social ?? $user->name,
        ];

        return view('dashboards.empresa', $data);
    }
}
