<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagiários — {{ $empresa->razao_social }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="topo">
                <div>
                    <h1>Estagiários Vinculados</h1>
                    <p class="subtitulo">{{ $empresa->razao_social }}</p>
                </div>
                <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="GET" action="{{ route('empresas.estagiarios', $empresa) }}" class="filtros">
                <select name="supervisor_id">
                    <option value="">Todos os supervisores</option>
                    @foreach ($supervisores as $sup)
                        <option value="{{ $sup->id }}"
                            {{ request('supervisor_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->nome }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secundario">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Supervisor</th>
                            <th>Início</th>
                            <th>Fim Previsto</th>
                            <th>CH Semanal</th>
                            <th>CH Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estagiarios as $estagio)
                            <tr>
                                <td>{{ $estagio->aluno->user->name ?? '—' }}</td>
                                <td>{{ $estagio->supervisor->nome ?? '—' }}</td>
                                <td>{{ $estagio->data_inicio_prevista?->format('d/m/Y') }}</td>
                                <td>{{ $estagio->data_fim_prevista?->format('d/m/Y') }}</td>
                                <td>{{ $estagio->carga_horaria_semanal }}h</td>
                                <td>{{ $estagio->carga_horaria_total }}h</td>
                                <td>
                                    @if ($estagio->supervisor)
                                        <a href="{{ route('empresas.supervisores.avaliacoes.create', [$empresa, $estagio->supervisor, $estagio]) }}"
                                            class="btn btn-primario">Avaliar</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:#888;">Nenhum estagiário vinculado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $estagiarios->links() }}
            </div>
        </div>
    </div>
</body>

</html>
