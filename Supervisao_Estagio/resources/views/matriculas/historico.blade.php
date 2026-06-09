<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Estágios - {{ $aluno->user->name ?? $aluno->nome ?? 'Aluno' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Histórico de Estágios</h1>
                <a href="{{ route('matriculas.index', $curso) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="grid-info">
                <div class="campo"><label>Aluno</label><span>{{ $aluno->user->name ?? $aluno->nome ?? '-' }}</span></div>
                <div class="campo"><label>Matrícula</label><span>{{ $aluno->matricula }}</span></div>
                <div class="campo"><label>Período Atual</label><span>{{ $aluno->periodo_atual }}° período</span></div>
                <div class="campo">
                    <label>Situação</label>
                    <span>
                        @php
                            $situacaoEstagio = $aluno->situacao_estagio ?? 'sem_estagio';
                            $badgeClass = match($situacaoEstagio) {
                                'em_andamento' => 'badge-ativo',
                                'concluido' => 'badge-sucesso',
                                default => 'badge-inativo'
                            };
                            $situacaoLabel = match($situacaoEstagio) {
                                'em_andamento' => 'Em Andamento',
                                'concluido' => 'Concluído',
                                default => 'Sem Estágio'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $situacaoLabel }}</span>
                    </span>
                </div>
                <div class="campo"><label>Horas Cumpridas</label><span>{{ $aluno->carga_horaria_cumprida ?? 0 }}h / {{ $curso->carga_horaria_estagio }}h</span></div>
                <div class="campo">
                    <label>Percentual</label>
                    <span>
                        @php
                            $percentual = $curso->carga_horaria_estagio > 0 
                                ? round(($aluno->carga_horaria_cumprida ?? 0) / $curso->carga_horaria_estagio * 100, 1) 
                                : 0;
                        @endphp
                        {{ $percentual }}%
                    </span>
                </div>
            </div>

            <div class="secao">Solicitações de Estágio</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Data Solicitação</th>
                            <th>Início Previsto</th>
                            <th>Fim Previsto</th>
                            <th>Carga Semanal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aluno->solicitacoesEstagio ?? [] as $solicitacao)
                            <tr>
                                <td>{{ $solicitacao->empresa->razao_social ?? $solicitacao->empresa->nome_fantasia ?? '-' }}</td>
                                <td>{{ $solicitacao->created_at?->format('d/m/Y') ?? $solicitacao->data_solicitacao ?? '-' }}</td>
                                <td>{{ $solicitacao->data_inicio_prevista?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $solicitacao->data_fim_prevista?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $solicitacao->carga_horaria_semanal }}h</td>
                                <td>
                                    @php
                                        $statusBadge = match($solicitacao->status) {
                                            'aprovada' => 'badge-ativo',
                                            'pendente' => 'badge-pendente',
                                            'reprovada' => 'badge-inativo',
                                            'cancelada' => 'badge-inativo',
                                            default => 'badge-inativo'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }}">{{ ucfirst($solicitacao->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhum estágio encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </div>
            </div>

            <div class="secao">Atividades Registradas</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Horas</th>
                            <th>Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aluno->atividades ?? [] as $atividade)
                            <tr>
                                <td>{{ $atividade->data_atividade?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $atividade->descricao ?? '-' }}</td>
                                <td>{{ $atividade->horas }}h</td>
                                <td>{{ $atividade->empresa->razao_social ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:#888;">Nenhuma atividade registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="secao">Avaliações Recebidas</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Nota</th>
                            <th>Conceito</th>
                            <th>Avaliador</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aluno->avaliacoes ?? [] as $avaliacao)
                            <tr>
                                <td>{{ $avaliacao->data_avaliacao?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ ucfirst($avaliacao->tipo) }}</td>
                                <td>{{ $avaliacao->nota_geral ?? $avaliacao->nota ?? '-' }}</td>
                                <td>{{ $avaliacao->conceito ?? '-' }}</td>
                                <td>{{ $avaliacao->supervisor->nome ?? $avaliacao->coordenador->nome ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:#888;">Nenhuma avaliação registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rodape">
                <a href="{{ route('matriculas.index', $curso) }}" class="btn btn-secundario">← Voltar</a>
                @if(($aluno->situacao_estagio ?? 'sem_estagio') === 'em_andamento')
                    <button class="btn btn-primario" onclick="window.print()">🖨️ Imprimir Histórico</button>
                @endif
            </div>
        </div>
    </div>
</body>
</html>