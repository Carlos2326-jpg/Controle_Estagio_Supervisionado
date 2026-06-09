<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Pendentes - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Documentos Pendentes</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Documento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentos as $documento)
                            <tr>
                                <td>{{ $documento->aluno->user->name ?? $documento->aluno->nome ?? '-' }}</td>
                                <td>{{ $documento->tipo }}</td>
                                <td>{{ $documento->status }}</td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ $documento->arquivo }}" class="btn btn-secundario" target="_blank">Visualizar</a>
                                        <form method="POST" action="{{ route('coordenadores.documentos.aprovar', [$coordenador, $documento]) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sucesso">Aprovar</button>
                                        </form>
                                        <form method="POST" action="{{ route('coordenadores.documentos.reprovar', [$coordenador, $documento]) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="justificativa" placeholder="Justificativa" style="width: 120px;">
                                            <button type="submit" class="btn btn-perigo">Reprovar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#888;">Nenhum documento pendente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>