@extends('layouts.app')

@section('title', 'Meus Contratos')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gerenciamento de Contratos de Estágio</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Estatísticas -->
                        <div class="row mb-4">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['total'] }}</h3>
                                        <p>Total de Contratos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['ativos'] }}</h3>
                                        <p>Contratos Ativos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['vencendo_30dias'] }}</h3>
                                        <p>Vencem em 30 dias</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <h3>{{ number_format($estatisticas['percentual_medio'], 1) }}%</h3>
                                        <p>Progresso Médio</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text" name="busca" class="form-control"
                                            placeholder="Buscar por aluno ou contrato..." value="{{ request('busca') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>

                                    <select name="status" class="form-control ml-2" onchange="this.form.submit()">
                                        <option value="">Todos os status</option>
                                        <option value="ATIVO" {{ request('status') == 'ATIVO' ? 'selected' : '' }}>
                                            Ativos
                                        </option>
                                        <option value="ENCERRADO" {{ request('status') == 'ENCERRADO' ? 'selected' : '' }}>
                                            Encerrados
                                        </option>
                                        <option value="CANCELADO" {{ request('status') == 'CANCELADO' ? 'selected' : '' }}>
                                            Cancelados
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Lista de Contratos -->
                        <div class="row">
                            @forelse($contratos as $contrato)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div
                                        class="card h-100 {{ $contrato->isAtivo() ? 'border-success' : 'border-secondary' }}">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                {{ $contrato->numero_contrato }}
                                            </h5>
                                            <span class="float-right">
                                                @if ($contrato->isAtivo())
                                                    <span class="badge badge-success">Ativo</span>
                                                @elseif($contrato->isEncerrado())
                                                    <span class="badge badge-secondary">Encerrado</span>
                                                @else
                                                    <span class="badge badge-danger">Cancelado</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <p>
                                                <strong>Aluno:</strong>
                                                {{ $contrato->getAluno()->user->nome }}
                                            </p>
                                            <p>
                                                <strong>Empresa:</strong>
                                                {{ $contrato->getEmpresa()->razao_social }}
                                            </p>
                                            <p>
                                                <strong>Vigência:</strong><br>
                                                <small>
                                                    Início: {{ date('d/m/Y', strtotime($contrato->data_inicio)) }}<br>
                                                    Término: {{ date('d/m/Y', strtotime($contrato->data_fim)) }}
                                                </small>
                                            </p>

                                            @if ($contrato->isAtivo())
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $contrato->getPercentualConclusao() }}%"
                                                        aria-valuenow="{{ $contrato->getPercentualConclusao() }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        {{ number_format($contrato->getPercentualConclusao(), 1) }}%
                                                    </div>
                                                </div>

                                                <p class="text-muted small">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    {{ $contrato->getDiasRestantes() }} dias restantes
                                                </p>
                                            @endif

                                            <p class="mb-0">
                                                <strong>Horas cumpridas:</strong>
                                                {{ number_format($contrato->getHorasCumpridas(), 1) }}h
                                            </p>
                                        </div>

                                        <div class="card-footer">
                                            <a href="{{ route('contratos.show', $contrato->id_contrato) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Visualizar
                                            </a>

                                            @if ($contrato->isAtivo())
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#modalAtividade{{ $contrato->id_contrato }}">
                                                    <i class="fas fa-plus-circle"></i> Registrar Atividade
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Registrar Atividade -->
                                @if ($contrato->isAtivo())
                                    <div class="modal fade" id="modalAtividade{{ $contrato->id_contrato }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form
                                                    action="{{ route('contratos.registrar-atividade', $contrato->id_contrato) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Registrar Atividade</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Data da Atividade *</label>
                                                            <input type="date" name="data_atividade" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Hora Início *</label>
                                                                <input type="time" name="hora_inicio"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Hora Fim *</label>
                                                                <input type="time" name="hora_fim"
                                                                    class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label>Descrição das Atividades *</label>
                                                            <textarea name="descricao" rows="4" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Salvar Atividade
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle"></i>
                                        Nenhum contrato encontrado.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
