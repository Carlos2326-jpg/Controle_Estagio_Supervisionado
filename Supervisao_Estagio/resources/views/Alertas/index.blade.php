@extends('layouts.app')

@section('title', 'Meus Alertas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell"></i> Sistema de Alertas e Notificações
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('alertas.marcar-todos-lidos') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-check-double"></i> Marcar todos como lidos
                            </a>
                            @if (auth()->user()->isCoordenador())
                                <a href="{{ route('alertas.gerar') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync"></i> Gerar Alertas
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Estatísticas dos Alertas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['total'] }}</h3>
                                        <p>Total de Alertas</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['nao_lidos'] }}</h3>
                                        <p>Não Lidos</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['vencidos'] }}</h3>
                                        <p>Vencidos</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $estatisticas['proximos_vencer'] }}</h3>
                                        <p>Vencem em 7 dias</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="GET" class="form-inline">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">Todos os alertas</option>
                                        <option value="nao_lidos" {{ request('status') == 'nao_lidos' ? 'selected' : '' }}>
                                            Não lidos
                                        </option>
                                        <option value="lidos" {{ request('status') == 'lidos' ? 'selected' : '' }}>
                                            Lidos
                                        </option>
                                        <option value="vencidos" {{ request('status') == 'vencidos' ? 'selected' : '' }}>
                                            Vencidos
                                        </option>
                                    </select>

                                    <select name="tipo" class="form-control ml-2" onchange="this.form.submit()">
                                        <option value="">Todos os tipos</option>
                                        <option value="VENCIMENTO_CONTRATO"
                                            {{ request('tipo') == 'VENCIMENTO_CONTRATO' ? 'selected' : '' }}>
                                            Vencimento Contrato
                                        </option>
                                        <option value="VENCIMENTO_CONVENIO"
                                            {{ request('tipo') == 'VENCIMENTO_CONVENIO' ? 'selected' : '' }}>
                                            Vencimento Convênio
                                        </option>
                                        <option value="DOCUMENTO_PENDENTE"
                                            {{ request('tipo') == 'DOCUMENTO_PENDENTE' ? 'selected' : '' }}>
                                            Documento Pendente
                                        </option>
                                        <option value="AVALIACAO_PENDENTE"
                                            {{ request('tipo') == 'AVALIACAO_PENDENTE' ? 'selected' : '' }}>
                                            Avaliação Pendente
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Lista de Alertas -->
                        <div class="timeline">
                            @forelse($alertas as $alerta)
                                <div class="time-label">
                                    <span class="bg-{{ $alerta->getCor() }}">
                                        {{ $alerta->data_geracao->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div>
                                    <i
                                        class="fas {{ $alerta->getIcone() == '📄'
                                            ? 'fa-file-alt'
                                            : ($alerta->getIcone() == '🏢'
                                                ? 'fa-building'
                                                : ($alerta->getIcone() == '📎'
                                                    ? 'fa-paperclip'
                                                    : 'fa-star')) }} 
                                   bg-{{ $alerta->getCor() }}"></i>
                                    <div class="timeline-item {{ !$alerta->lido ? 'bg-light' : '' }}">
                                        <span class="time">
                                            <i class="fas fa-clock"></i>
                                            {{ $alerta->data_geracao->format('H:i') }}

                                            @if (!$alerta->lido)
                                                <span class="badge badge-danger ml-2">NOVO</span>
                                            @endif
                                        </span>

                                        <h3 class="timeline-header">
                                            <a href="#">{{ $alerta->getTipoDisplay() }}</a>
                                        </h3>

                                        <div class="timeline-body">
                                            <p>{{ $alerta->mensagem }}</p>

                                            @if ($alerta->data_vencimento)
                                                <p class="text-muted small">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    Data limite: {{ date('d/m/Y', strtotime($alerta->data_vencimento)) }}

                                                    @if ($alerta->getDiasRestantes() > 0)
                                                        <span class="text-info ml-2">
                                                            ({{ $alerta->getDiasRestantes() }} dias restantes)
                                                        </span>
                                                    @else
                                                        <span class="text-danger ml-2">
                                                            (Vencido)
                                                        </span>
                                                    @endif
                                                </p>
                                            @endif
                                        </div>

                                        <div class="timeline-footer">
                                            @if (!$alerta->lido)
                                                <a href="{{ route('alertas.marcar-lido', $alerta->id_alerta) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check"></i> Marcar como lido
                                                </a>
                                            @endif

                                            @if ($alerta->getEntidadeReferencia())
                                                <a href="{{ route('contratos.show', $alerta->id_referencia) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Visualizar
                                                </a>
                                            @endif

                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="if(confirm('Excluir este alerta?')) 
                                                        window.location='{{ route('alertas.destroy', $alerta->id_alerta) }}'">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-success text-center">
                                    <i class="fas fa-check-circle"></i>
                                    Nenhum alerta encontrado. Tudo em dia!
                                </div>
                            @endforelse
                        </div>

                        <!-- Paginação -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                {{ $alertas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }

        .timeline>div {
            position: relative;
            margin-bottom: 15px;
        }

        .timeline>div>.timeline-item {
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            border-radius: 3px;
            margin-top: 0;
            background: #fff;
            color: #444;
            margin-left: 60px;
            margin-right: 15px;
            padding: 0;
            position: relative;
        }

        .timeline>div>.timeline-item>.time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }

        .timeline>div>.timeline-item>.timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline>div>.timeline-item>.timeline-body {
            padding: 10px;
        }

        .timeline>div>.timeline-item>.timeline-footer {
            padding: 10px;
        }

        .timeline>div>.fa,
        .timeline>div>.fas,
        .timeline>div>.far {
            position: absolute;
            color: #fff;
            background: #d2d6de;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            text-align: center;
            line-height: 30px;
            font-size: 16px;
            left: 18px;
            top: 0;
        }

        .time-label {
            margin-left: 10px;
            margin-bottom: 15px;
        }

        .time-label>span {
            font-weight: 600;
            padding: 5px;
            display: inline-block;
            border-radius: 4px;
        }
    </style>
@endpush
