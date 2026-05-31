<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Avaliação</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 620px; }
        h1 { font-size: 1.4rem; margin-bottom: 6px; }
        .info { background: #f3f4f6; border-radius: 6px; padding: 12px 14px; font-size: 0.9rem; margin-bottom: 20px; }
        .info strong { display: block; margin-bottom: 4px; }
        .grupo { margin-bottom: 14px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; }
        input, textarea { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        textarea { resize: vertical; min-height: 80px; }
        .linha { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .dica { color: #6b7280; font-size: 0.8rem; margin-top: 2px; }
        .btn { padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .rodape { display: flex; gap: 10px; margin-top: 20px; }
        .erro { color: #dc2626; font-size: 0.8rem; margin-top: 3px; }
        .secao { font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; margin: 20px 0 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
    </style>
</head>
<body>

<h1>Nova Avaliação de Estagiário</h1>

<div class="info">
    <strong>Supervisor:</strong> {{ $supervisor->nome }} — {{ $supervisor->cargo }}
    <strong style="margin-top:6px;">Estagiário:</strong> {{ $solicitacao->aluno->user->name ?? 'Não identificado' }}
    <span>Estágio: {{ $solicitacao->data_inicio_prevista?->format('d/m/Y') }} até {{ $solicitacao->data_fim_prevista?->format('d/m/Y') }}</span>
</div>

@if($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:4px;margin-bottom:14px;">
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('empresas.supervisores.avaliacoes.store', [$empresa, $supervisor, $solicitacao]) }}">
    @csrf

    <p class="secao">Critérios (nota de 0 a 10)</p>

    <div class="linha">
        <div class="grupo">
            <label>Pontualidade</label>
            <input type="number" name="pontualidade" value="{{ old('pontualidade') }}" min="0" max="10" step="0.1">
        </div>
        <div class="grupo">
            <label>Proatividade</label>
            <input type="number" name="proatividade" value="{{ old('proatividade') }}" min="0" max="10" step="0.1">
        </div>
    </div>

    <div class="linha">
        <div class="grupo">
            <label>Qualidade do Trabalho</label>
            <input type="number" name="qualidade_trabalho" value="{{ old('qualidade_trabalho') }}" min="0" max="10" step="0.1">
        </div>
        <div class="grupo">
            <label>Relacionamento Interpessoal</label>
            <input type="number" name="relacionamento" value="{{ old('relacionamento') }}" min="0" max="10" step="0.1">
        </div>
    </div>

    <p class="dica">A nota geral será calculada automaticamente como média dos critérios preenchidos.</p>

    <p class="secao">Informações Adicionais</p>

    <div class="grupo">
        <label>Observações</label>
        <textarea name="observacoes">{{ old('observacoes') }}</textarea>
    </div>

    <div class="grupo">
        <label>Data da Avaliação *</label>
        <input type="date" name="data_avaliacao" value="{{ old('data_avaliacao', now()->format('Y-m-d')) }}" required>
        @error('data_avaliacao')<span class="erro">{{ $message }}</span>@enderror
    </div>

    <div class="rodape">
        <button type="submit" class="btn btn-primario">Registrar Avaliação</button>
        <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}" class="btn btn-secundario">Cancelar</a>
    </div>
</form>

</body>
</html>
