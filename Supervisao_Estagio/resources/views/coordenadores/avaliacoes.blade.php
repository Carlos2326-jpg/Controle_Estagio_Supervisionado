<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Avaliações</h1>
                <div class="acoes">
                    <a href="{{ route('coordenadores.avaliacoes.create', $coordenador) }}" class="btn btn-primario">+ Nova Avaliação</a>
                    <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Tipo</th>
                            <th>Conceito</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($avaliacoes as $avaliacao)
                            <tr>
                                <td>{{ $avaliacao->aluno->user->name ?? $avaliacao->aluno->nome ?? '-' }}</td>
                                <td>{{ ucfirst($avaliacao->tipo) }}</td>
                                <td>{{ $avaliacao->conceito ?? $avaliacao->nota ?? '-' }}</td>
                                <td>{{ $avaliacao->data_avaliacao->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#888;">Nenhuma avaliação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $avaliacoes->links() }}
            </div>
        </div>
    </div>
</body>
</html>