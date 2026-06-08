<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
     * Executa antes de qualquer ação do controller (opcional)
     */
    protected function callAction($method, $parameters)
    {
        return parent::callAction($method, $parameters);
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
     * Este método é um wrapper para o authorize() do Laravel
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
        return auth()->check();
    }

    /**
     * Retorna o usuário autenticado
     */
    protected function getAuthenticatedUser()
    {
        return auth()->user();
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    protected function userHasRole(string $role): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Verifica se o método hasRole existe no model User
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole($role);
        }
        
        // Fallback: verifica o campo role diretamente
        return isset($user->role) && $user->role === $role;
    }

    /**
     * Verifica se o usuário tem alguma das roles informadas
     */
    protected function userHasAnyRole(array $roles): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        // Verifica se o método hasAnyRole existe no model User
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }
        
        // Fallback: verifica o campo role diretamente
        return isset($user->role) && in_array($user->role, $roles);
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
        $this->middleware('auth:sanctum')->except($except);
    }

    /**
     * Aplica middleware de role (admin)
     */
    protected function requireAdmin(array $except = [])
    {
        $this->middleware('auth:sanctum')->except($except);
        $this->middleware('role:admin')->except($except);
    }

    /**
     * Aplica middleware de role (coordenador)
     */
    protected function requireCoordenador(array $except = [])
    {
        $this->middleware('auth:sanctum')->except($except);
        $this->middleware('role:coordenador')->except($except);
    }

    /**
     * Aplica middleware de role (aluno)
     */
    protected function requireAluno(array $except = [])
    {
        $this->middleware('auth:sanctum')->except($except);
        $this->middleware('role:aluno')->except($except);
    }

    /**
     * Aplica middleware de role (empresa)
     */
    protected function requireEmpresa(array $except = [])
    {
        $this->middleware('auth:sanctum')->except($except);
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
        $user = auth()->user();
        \Illuminate\Support\Facades\Log::info('Ação do usuário', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'action' => $action,
            'data' => $data,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}