<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrículas do Curso - {{ $curso->nome }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Alunos Matriculados - {{ $curso->nome }}</h1>
                <a href="{{ route('cursos.show', $curso) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="GET" class="filtros">
                <input type="text" name="busca" placeholder="Buscar por Matrícula ou CPF"
                    value="{{ request('busca') }}">
                <select name="situacao_estagio">
                    <option value="">Todas as situações</option>
                    <option value="sem_estagio" {{ request('situacao_estagio') === 'sem_estagio' ? 'selected' : '' }}>
                        Sem Estágio</option>
                    <option value="em_andamento" {{ request('situacao_estagio') === 'em_andamento' ? 'selected' : '' }}>
                        Em Andamento</option>
                    <option value="concluido" {{ request('situacao_estagio') === 'concluido' ? 'selected' : '' }}>
                        Concluído</option>
                </select>
                <button type="submit" class="btn btn-secundario">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Período</th>
                            <th>Situação</th>
                            <th>Horas Cumpridas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->user->name ?? ($aluno->nome ?? '-') }}</td>
                                <td>{{ $aluno->matricula }}</td>
                                <td>{{ $aluno->periodo_atual }}° períod
                                <td>{{ $aluno->periodo_atual }}° período</td>
                                <td>
                                    @php
                                        $situacaoEstagio = $aluno->situacao_estagio ?? 'sem_estagio';
                                        $badgeClass = match ($situacaoEstagio) {
                                            'em_andamento' => 'badge-ativo',
                                            'concluido' => 'badge-sucesso',
                                            default => 'badge-inativo',
                                        };
                                        $situacaoLabel = match ($situacaoEstagio) {
                                            'em_andamento' => 'Em Andamento',
                                            'concluido' => 'Concluído',
                                            default => 'Sem Estágio',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $situacaoLabel }}</span>
                                </td>
                                <td>{{ $aluno->carga_horaria_cumprida ?? 0 }}h / {{ $curso->carga_horaria_estagio }}h
                                </td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ route('matriculas.historico', [$curso, $aluno]) }}"
                                            class="btn btn-secundario">Histórico</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhum aluno encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
            </div>

            <div class="paginacao">
                {{ $alunos->links() }}
            </div>
        </div>
    </div>
</body>

</html>
