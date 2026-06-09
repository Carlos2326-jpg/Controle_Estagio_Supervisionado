<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades de Estágio - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Atividades de Estágio</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Empresa</th>
                            <th>Horas</th>
                            <th>Última Atualização</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atividades as $atividade)
                            <tr>
                                <td>{{ $atividade->aluno->user->name ?? $atividade->aluno->nome ?? '-' }}</td>
                                <td>{{ $atividade->empresa->razao_social ?? '-' }}</td>
                                <td>{{ $atividade->horas }}h</td>
                                <td>{{ $atividade->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#888;">Nenhuma atividade registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $atividades->links() }}
            </div>
        </div>
    </div>
</body>
</html>