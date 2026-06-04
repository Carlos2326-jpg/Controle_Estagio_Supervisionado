<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Estágio</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 800px; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 20px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .secao { font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; margin: 20px 0 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.9rem; }
        .campo label { font-weight: 600; font-size: 0.82rem; color: #6b7280; display: block; }
        .campo span  { display: block; margin-top: 2px; }
        .aviso { background: #fef3c7; color: #92400e; padding: 12px 14px; border-radius: 6px; }
    </style>
</head>
<body>

<h1>Contrato de Estágio</h1>
<p class="subtitulo">
    Empresa: {{ $empresa->razao_social }} ·
    Aluno: {{ $solicitacao->aluno->user->name ?? '—' }}
</p>

<a href="{{ route('empresas.solicitacoes', $empresa) }}" class="btn btn-secundario">← Voltar</a>

@if($contrato)

    <p class="secao">Dados do Contrato</p>
    <div class="grid">
        <div class="campo"><label>Número</label><span>{{ $contrato->numero_contrato ?? '—' }}</span></div>
        <div class="campo"><label>Status</label><span>{{ ucfirst($contrato->status ?? '—') }}</span></div>
        <div class="campo"><label>Data de Início</label><span>{{ $contrato->data_inicio?->format('d/m/Y') ?? '—' }}</span></div>
        <div class="campo"><label>Data de Fim</label><span>{{ $contrato->data_fim?->format('d/m/Y') ?? '—' }}</span></div>
    </div>

    <p class="secao">Dados do Estágio</p>
    <div class="grid">
        <div class="campo"><label>Aluno</label><span>{{ $solicitacao->aluno->user->name ?? '—' }}</span></div>
        <div class="campo"><label>Supervisor</label><span>{{ $solicitacao->supervisor->nome ?? '—' }}</span></div>
        <div class="campo"><label>CH Semanal</label><span>{{ $solicitacao->carga_horaria_semanal }}h</span></div>
        <div class="campo"><label>CH Total</label><span>{{ $solicitacao->carga_horaria_total }}h</span></div>
    </div>

    <p class="secao">Descrição das Atividades</p>
    <p style="font-size:0.9rem;">{{ $solicitacao->descricao_atividades }}</p>

@else
    <div class="aviso" style="margin-top:20px;">
        Contrato ainda não gerado. O contrato é formalizado após a aprovação da solicitação pelo coordenador.
    </div>
@endif

</body>
</html>
