@extends('layouts.app')

@section('title', 'Minhas Avaliações')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Acompanhamento de Avaliações</h3>
                        <div class="card-tools">
                            <a href="{{ route('avaliacoes.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nova Avaliação
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Cards de Estatísticas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-star"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Avaliações</span>
                                        <span class="info-box-number">{{ $estatisticas['total'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pendentes</span>
                                        <span class="info-box-number">{{ $estatisticas['pendentes'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Aprovadas</span>
                                        <span class="info-box-number">{{ $estatisticas['aprovadas'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Média Geral</span>
                                        <span
                                            class="info-box-number">{{ number_format($estatisticas['media_geral'], 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="GET" class="form-inline">
                                    <select name="tipo" class="form-control" onchange="this.form.submit()">
                                        <option value="">Todos os avaliadores</option>
                                        <option value="SUPERVISOR" {{ request('tipo') == 'SUPERVISOR' ? 'selected' : '' }}>
                                            Supervisor
                                        </option>
                                        <option value="COORDENADOR"
                                            {{ request('tipo') == 'COORDENADOR' ? 'selected' : '' }}>
                                            Coordenador
                                        </option>
                                    </select>

                                    <select name="situacao" class="form-control ml-2" onchange="this.form.submit()">
                                        <option value="">Todas as situações</option>
                                        <option value="PENDENTE" {{ request('situacao') == 'PENDENTE' ? 'selected' : '' }}>
                                            Pendentes
                                        </option>
                                        <option value="APROVADO" {{ request('situacao') == 'APROVADO' ? 'selected' : '' }}>
                                            Aprovadas
                                        </option>
                                        <option value="REPROVADO"
                                            {{ request('situacao') == 'REPROVADO' ? 'selected' : '' }}>
                                            Reprovadas
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Lista de Avaliações -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th>Avaliador</th>
                                        <th>Aluno</th>
                                        <th>Notas</th>
                                        <th>Média</th>
                                        <th>Situação</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($avaliacoes as $avaliacao)
                                        <tr>
                                            <td>{{ $avaliacao->periodo_referencia }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $avaliacao->tipo_avaliador }}
                                                </span>
                                                <br>
                                                <small>{{ $avaliacao->avaliador->nome }}</small>
                                            </td>
                                            <td>{{ $avaliacao->contrato->getAluno()->user->nome }}</td>
                                            <td>
                                                <small>
                                                    D: {{ number_format($avaliacao->nota_desempenho, 1) }}<br>
                                                    C: {{ number_format($avaliacao->nota_comportamento, 1) }}<br>
                                                    P: {{ number_format($avaliacao->nota_pontualidade, 1) }}
                                                </small>
                                            </td>
                                            <td>
                                                <h5>{{ number_format($avaliacao->media_final, 1) }}</h5>
                                            </td>
                                            <td>
                                                {!! $avaliacao->getStatusBadge() !!}
                                            </td>
                                            <td>
                                                <small>{{ $avaliacao->data_avaliacao->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('avaliacoes.show', $avaliacao->id_avaliacao) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>

                                                @if (!$avaliacao->parecer && auth()->user()->id_usuario == $avaliacao->id_avaliador)
                                                    <a href="{{ route('avaliacoes.edit', $avaliacao->id_avaliacao) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="alert alert-info mb-0">
                                                    <i class="fas fa-info-circle"></i>
                                                    Nenhuma avaliação encontrada.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Gráfico de Desempenho (se houver avaliações) -->
                        @if ($avaliacoes->count() > 0)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Evolução das Avaliações</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartAvaliacoes" data-avaliacoes='@json(
                                                $avaliacoes->map(function ($a) {
                                                    return [
                                                        'periodo' => $a->periodo_referencia,
                                                        'media' => $a->media_final,
                                                    ];
                                                }))'>
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            const ctx = document.getElementById('chartAvaliacoes').getContext('2d');
            const dados = JSON.parse($('#chartAvaliacoes').attr('data-avaliacoes'));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dados.map(d => d.periodo),
                    datasets: [{
                        label: 'Média das Avaliações',
                        data: dados.map(d => d.media),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });
        });
    </script>
@endpush
