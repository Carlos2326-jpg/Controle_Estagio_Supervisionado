<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Estágio - {{ $empresa->razao_social }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="topo">
                <div>
                    <h1>Contrato de Estágio</h1>
                    <p class="subtitulo">
                        Empresa: {{ $empresa->razao_social }} ·
                        Aluno: {{ $solicitacao->aluno->user->name ?? '—' }}
                    </p>
                </div>
                <a href="{{ route('empresas.solicitacoes', $empresa) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            @if ($contrato)
                <div class="secao">Dados do Contrato</div>
                <div class="grid">
                    <div class="campo"><label>Número</label><span>{{ $contrato->numero_contrato ?? '—' }}</span></div>
                    <div class="campo"><label>Status</label><span>{{ ucfirst($contrato->status ?? '—') }}</span></div>
                    <div class="campo"><label>Data de
                            Início</label><span>{{ $contrato->data_inicio?->format('d/m/Y') ?? '—' }}</span></div>
                    <div class="campo"><label>Data de
                            Fim</label><span>{{ $contrato->data_fim?->format('d/m/Y') ?? '—' }}</span></div>
                </div>

                <div class="secao">Dados do Estágio</div>
                <div class="grid">
                    <div class="campo"><label>Aluno</label><span>{{ $solicitacao->aluno->user->name ?? '—' }}</span>
                    </div>
                    <div class="campo">
                        <label>Supervisor</label><span>{{ $solicitacao->supervisor->nome ?? '—' }}</span></div>
                    <div class="campo"><label>CH
                            Semanal</label><span>{{ $solicitacao->carga_horaria_semanal }}h</span></div>
                    <div class="campo"><label>CH Total</label><span>{{ $solicitacao->carga_horaria_total }}h</span>
                    </div>
                </div>

                <div class="secao">Descrição das Atividades</div>
                <div class="alert-info">{{ $solicitacao->descricao_atividades }}</div>
            @else
                <div class="alert-warning" style="margin-top:20px;">
                    Contrato ainda não gerado. O contrato é formalizado após a aprovação da solicitação pelo
                    coordenador.
                </div>
            @endif
        </div>
    </div>
</body>

</html>
