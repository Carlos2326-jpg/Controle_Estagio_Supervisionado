<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Estágio - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Solicitações de Estágio</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Empresa</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitacoes as $solicitacao)
                            <tr>
                                <td>{{ $solicitacao->aluno->user->name ?? $solicitacao->aluno->nome ?? '-' }}</td>
                                <td>{{ $solicitacao->empresa->razao_social ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $solicitacao->status }}">
                                        {{ ucfirst($solicitacao->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="acoes">
                                        <form method="POST" action="{{ route('coordenadores.solicitacoes.aprovar', [$coordenador, $solicitacao]) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sucesso">Aprovar</button>
                                        </form>
                                        <form method="POST" action="{{ route('coordenadores.solicitacoes.reprovar', [$coordenador, $solicitacao]) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="justificativa" placeholder="Justificativa" style="width: 150px;">
                                            <button type="submit" class="btn btn-perigo">Reprovar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#888;">Nenhuma solicitação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $solicitacoes->links() }}
            </div>
        </div>
    </div>
</body>
</html>