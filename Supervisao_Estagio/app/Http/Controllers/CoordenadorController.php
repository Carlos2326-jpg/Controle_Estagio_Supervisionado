<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoordenadorRequest;
use App\Services\CoordenadorService;
use Illuminate\Http\Request;

class CoordenadorController extends Controller
{
    protected $service;

    public function __construct(CoordenadorService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | GERENCIAR COORDENADORES
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return response()->json(
            $this->service->listar()
        );
    }

    public function store(StoreCoordenadorRequest $request)
    {
        return response()->json(
            $this->service->criar(
                $request->validated()
            ),
            201
        );
    }

    public function update(Request $request, $id)
    {
        return response()->json(
            $this->service->atualizar(
                $id,
                $request->all()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CURSOS
    |--------------------------------------------------------------------------
    */

    public function vincularCurso(Request $request, $id)
    {
        $this->service->vincularCurso(
            $id,
            $request->curso_id
        );

        return response()->json([
            'message' => 'Curso vinculado'
        ]);
    }
}