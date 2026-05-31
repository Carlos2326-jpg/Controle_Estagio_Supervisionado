<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\Documento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentoService
{
    public function listarPorAluno(Aluno $aluno)
    {
        return Documento::where('aluno_id', $aluno->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function upload(Aluno $aluno, array $dados, UploadedFile $arquivo)
    {
        $caminho = $arquivo->store("documentos/{$aluno->id}", 'public');

        return Documento::create([
            'aluno_id' => $aluno->id,
            'solicitacao_id' => $dados['solicitacao_id'] ?? null,
            'nome' => $dados['nome'],
            'tipo' => $dados['tipo'],
            'caminho_arquivo' => $caminho,
        ]);
    }

    public function consultarStatus(Aluno $aluno)
    {
        return Documento::where('aluno_id', $aluno->id)
            ->select('id', 'nome', 'tipo', 'status', 'observacao', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
