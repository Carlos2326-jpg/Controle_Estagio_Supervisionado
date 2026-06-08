<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\SolicitacaoEstagio;
use App\Services\DocumentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function __construct(protected DocumentoService $service) {}

    public function index()
    {
        $aluno = Auth::user()->aluno;
        $documentos = $this->service->listarPorAluno($aluno);

        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        $aluno = Auth::user()->aluno;
        $solicitacoes = SolicitacaoEstagio::where('aluno_id', $aluno->id)->get();

        return view('documentos.create', compact('solicitacoes'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'solicitacao_estagio_id' => 'required|integer|exists:solicitacoes_estagio,id',
            'arquivo' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $aluno = Auth::user()->aluno;
        $this->service->upload($aluno, $dados, $request->file('arquivo'));

        return redirect()->route('documentos.index')->with('sucesso', 'Documento enviado com sucesso.');
    }

    public function show(Documento $documento)
    {
        return view('documentos.show', compact('documento'));
    }
}
