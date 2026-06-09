<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * O construtor padrão do controller
     */
    public function __construct()
    {
        // Middleware pode ser definido aqui ou nos controllers filhos
    }

    /**
     * Retorna uma resposta de erro padronizada
     */
    protected function errorResponse(string $message, int $code = 400, array $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $code);
    }

    /**
     * Retorna uma resposta de sucesso padronizada
     */
    protected function successResponse($data = null, string $message = 'Operação realizada com sucesso', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Retorna uma resposta de criação padronizada
     */
    protected function createdResponse($data = null, string $message = 'Registro criado com sucesso')
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Retorna uma resposta sem conteúdo (DELETE)
     */
    protected function noContentResponse()
    {
        return response()->json(null, 204);
    }

    /**
     * Valida se o usuário tem permissão para acessar o recurso
     */
    protected function authorizeAction(string $ability, $arguments = [])
    {
        $this->authorize($ability, $arguments);
    }

    /**
     * Verifica se o usuário está autenticado
     */
    protected function checkAuth(): bool
    {
        return Auth::check();
    }

    /**
     * Retorna o usuário autenticado
     */
    protected function getAuthenticatedUser()
    {
        return Auth::user();
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    protected function userHasRole(string $role): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole($role);
        }
        
        return isset($user->role) && $user->role === $role;
    }

    /**
     * Verifica se o usuário tem alguma das roles informadas
     */
    protected function userHasAnyRole(array $roles): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }
        
        return isset($user->role) && in_array($user->role, $roles);
    }

    /**
     * Verifica se o usuário autenticado é o proprietário do recurso Aluno
     */
    protected function isOwnAluno(int $alunoId): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // Se for admin ou coordenador, permite acesso
        if ($this->userHasAnyRole(['admin', 'coordenador'])) {
            return true;
        }
        
        // Verifica se o aluno pertence ao usuário autenticado
        $aluno = \App\Models\Aluno::where('user_id', $user->id)->first();
        return $aluno && $aluno->id === $alunoId;
    }

    /**
     * Verifica se o usuário autenticado é o proprietário do recurso Empresa
     */
    protected function isOwnEmpresa(int $empresaId): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // Se for admin, permite acesso
        if ($user->role === 'admin') {
            return true;
        }
        
        // Verifica se a empresa pertence ao usuário autenticado
        $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
        return $empresa && $empresa->id === $empresaId;
    }

    /**
     * Aplica middleware para proteger rotas específicas
     */
    protected function applyMiddleware($middleware, array $options = [])
    {
        $this->middleware($middleware, $options);
    }

    /**
     * Aplica middleware de autenticação
     */
    protected function requireAuth(array $except = [])
    {
        $this->middleware('auth')->except($except);
    }

    /**
     * Aplica middleware de role (admin)
     */
    protected function requireAdmin(array $except = [])
    {
        $this->middleware('auth')->except($except);
        $this->middleware('role:admin')->except($except);
    }

    /**
     * Aplica middleware de role (coordenador)
     */
    protected function requireCoordenador(array $except = [])
    {
        $this->middleware('auth')->except($except);
        $this->middleware('role:coordenador')->except($except);
    }

    /**
     * Aplica middleware de role (aluno)
     */
    protected function requireAluno(array $except = [])
    {
        $this->middleware('auth')->except($except);
        $this->middleware('role:aluno')->except($except);
    }

    /**
     * Aplica middleware de role (empresa)
     */
    protected function requireEmpresa(array $except = [])
    {
        $this->middleware('auth')->except($except);
        $this->middleware('role:empresa')->except($except);
    }

    /**
     * Valida dados de requisição e retorna erro formatado
     */
    protected function validateRequest(Request $request, array $rules, array $messages = [])
    {
        try {
            return $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Erro de validação', 422, $e->errors());
        }
    }

    /**
     * Log de auditoria para ações importantes
     */
    protected function logAction(string $action, array $data = [])
    {
        $user = Auth::user();
        Log::info('Ação do usuário', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'user_role' => $user?->role,
            'action' => $action,
            'data' => $data,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}