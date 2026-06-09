<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas Acadêmicos - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .alerta {
            background-color: #fff3cd;
        }
        .alerta-critico {
            background-color: #f8d7da;
        }
        .progress-bar-container {
            background-color: #e9ecef;
            border-radius: 10px;
            height: 8px;
            width: 100%;
            overflow: hidden;
        }
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .progress-bar-warning {
            background: #ffc107;
        }
        .progress-bar-danger {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>⚠️ Alertas Acadêmicos</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador ?? 1) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="alert-info">
                <strong>📌 Alunos com Pendência de Carga Horária</strong><br>
                Alunos que ainda não atingiram a carga horária mínima obrigatória de estágio.
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Período</th>
                            <th>Situação</th>
                            <th>Horas Cumpridas</th>
                            <th>Horas Obrigatórias</th>
                            <th>Horas Faltantes</th>
                            <th>Percentual</th>
                            <th>Progresso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alunos as $aluno)
                            @php
                                $horasCumpridas = $aluno->carga_horaria_cumprida ?? 0;
                                $horasObrigatorias = $curso->carga_horaria_estagio ?? 400;
                                $horasFaltantes = max(0, $horasObrigatorias - $horasCumpridas);
                                $percentual = $horasObrigatorias > 0 ? round($horasCumpridas / $horasObrigatorias * 100, 1) : 0;
                                
                                $classeAlerta = '';
                                if ($percentual < 30 && $horasFaltantes > 0) {
                                    $classeAlerta = 'alerta-critico';
                                } elseif ($horasFaltantes > 0) {
                                    $classeAlerta = 'alerta';
                                }
                                
                                $barClass = '';
                                if ($percentual >= 80) {
                                    $barClass = '';
                                } elseif ($percentual >= 50) {
                                    $barClass = 'progress-bar-warning';
                                } else {
                                    $barClass = 'progress-bar-danger';
                                }
                            @endphp
                            <tr class="{{ $classeAlerta }}">
                                <td>{{ $aluno->user->name ?? $aluno->nome ?? '-' }}</td>
                                <td>{{ $aluno->matricula }}</td>
                                <td>{{ $aluno->periodo_atual }}°</td>
                                <td>
                                    @php
                                        $situacaoLabel = match($aluno->situacao_estagio ?? 'sem_estagio') {
                                            'em_andamento' => 'Em Andamento',
                                            'concluido' => 'Concluído',
                                            default => 'Sem Estágio'
                                        };
                                    @endphp
                                    {{ $situacaoLabel }}
                                </td>
                                <td>{{ number_format($horasCumpridas, 1) }}h</td>
                                <td>{{ $horasObrigatorias }}h</td>
                                <td><strong>{{ number_format($horasFaltantes, 1) }}h</strong></td>
                                <td>{{ $percentual }}%</td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar {{ $barClass }}" style="width: {{ $percentual }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;color:#888;">Nenhum aluno com pendência de carga horária encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($alunos->hasPages())
                <div class="paginacao">
                    {{ $alunos->links() }}
                </div>
            @endif

            <div class="rodape">
                <a href="javascript:history.back()" class="btn btn-secundario">← Voltar</a>
                <button class="btn btn-primario" onclick="window.print()">🖨️ Imprimir Alertas</button>
            </div>
        </div>
    </div>
</body>
</html>