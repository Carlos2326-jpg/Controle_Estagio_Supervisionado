<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Solicitações — {{ $empresa->razao_social }}</title>
</head>

<body>

    <div class="topo">
        <div>
            <h1>Solicitações de Estágio Recebidas</h1>
            <p class="subtitulo">{{ $empresa->razao_social }}</p>
        </div>
        <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
    </div>

    <form method="GET" action="{{ route('empresas.solicitacoes', $empresa) }}" class="filtros">
        <select name="status">
            <option value="">Todos os status</option>
            <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
            <option value="aprovada" {{ request('status') === 'aprovada' ? 'selected' : '' }}>Aprovada</option>
            <option value="reprovada" {{ request('status') === 'reprovada' ? 'selected' : '' }}>Reprovada</option>
            <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
        <button type="submit" class="btn btn-secundario">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Supervisor</th>
                <th>Início Previsto</th>
                <th>Fim Previsto</th>
                <th>CH Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($solicitacoes as $solicitacao)
                <tr>
                    <td>{{ $solicitacao->aluno->user->name ?? '—' }}</td>
                    <td>{{ $solicitacao->supervisor->nome ?? '—' }}</td>
                    <td>{{ $solicitacao->data_inicio_prevista?->format('d/m/Y') }}</td>
                    <td>{{ $solicitacao->data_fim_prevista?->format('d/m/Y') }}</td>
                    <td>{{ $solicitacao->carga_horaria_total }}h</td>
                    <td>
                        @php $s = $solicitacao->status @endphp
                        <span class="badge badge-{{ $s }}">{{ ucfirst($s) }}</span>
                    </td>
                    <td>
                        @if ($solicitacao->status === 'aprovada')
                            <a href="{{ route('empresas.contrato', [$empresa, $solicitacao]) }}"
                                class="btn btn-primario">Ver Contrato</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#888;">Nenhuma solicitação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">{{ $solicitacoes->links() }}</div>

</body>

</html>
