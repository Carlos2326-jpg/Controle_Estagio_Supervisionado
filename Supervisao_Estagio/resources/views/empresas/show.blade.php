<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $empresa->razao_social }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 900px; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 20px; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-ativo  { background: #dcfce7; color: #16a34a; }
        .badge-inativo { background: #fee2e2; color: #dc2626; }
        .secao { font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; margin: 24px 0 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .grid-info { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.9rem; }
        .campo label { font-weight: 600; font-size: 0.82rem; color: #6b7280; display: block; }
        .campo span  { display: block; margin-top: 2px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .btn-perigo { background: #dc2626; color: #fff; }
        .btn-sucesso { background: #16a34a; color: #fff; }
        .acoes { display: flex; gap: 8px; margin-bottom: 20px; }
        .card-links { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; margin-top: 10px; }
        .card-link { border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px; text-decoration: none; color: #333; font-size: 0.9rem; }
        .card-link:hover { background: #f9fafb; }
        .card-link strong { display: block; margin-bottom: 4px; }
        .card-link span { color: #6b7280; font-size: 0.82rem; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 14px; }
    </style>
</head>
<body>

@if(session('sucesso'))
    <div class="alerta-sucesso">{{ session('sucesso') }}</div>
@endif

<h1>{{ $empresa->razao_social }}</h1>
<p class="subtitulo">
    {{ $empresa->nome_fantasia ? $empresa->nome_fantasia . ' · ' : '' }}
    CNPJ: {{ $empresa->cnpj }} ·
    <span class="badge {{ $empresa->status === 'ativa' ? 'badge-ativo' : 'badge-inativo' }}">
        {{ ucfirst($empresa->status) }}
    </span>
</p>

<div class="acoes">
    <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-primario">Editar</a>
    @if($empresa->isAtiva())
        <form method="POST" action="{{ route('empresas.desativar', $empresa) }}" style="display:inline;">
            @csrf @method('PATCH')
            <button class="btn btn-perigo" onclick="return confirm('Desativar esta empresa?')">Desativar</button>
        </form>
    @else
        <form method="POST" action="{{ route('empresas.reativar', $empresa) }}" style="display:inline;">
            @csrf @method('PATCH')
            <button class="btn btn-sucesso">Reativar</button>
        </form>
    @endif
    <a href="{{ route('empresas.index') }}" class="btn btn-secundario">← Voltar</a>
</div>

<p class="secao">Informações da Empresa</p>
<div class="grid-info">
    <div class="campo"><label>E-mail</label><span>{{ $empresa->email }}</span></div>
    <div class="campo"><label>Telefone</label><span>{{ $empresa->telefone ?? '—' }}</span></div>
    <div class="campo"><label>Ramo de Atividade</label><span>{{ $empresa->ramo_atividade ?? '—' }}</span></div>
    <div class="campo">
        <label>Endereço</label>
        <span>
            {{ $empresa->logradouro ? $empresa->logradouro . ', ' . $empresa->numero : '—' }}
            {{ $empresa->bairro ? ' · ' . $empresa->bairro : '' }}
            {{ $empresa->cidade ? ' · ' . $empresa->cidade . '/' . $empresa->estado : '' }}
        </span>
    </div>
</div>

<p class="secao">Módulos</p>
<div class="card-links">
    <a href="{{ route('empresas.convenios', $empresa) }}" class="card-link">
        <strong>Convênios</strong>
        <span>{{ $empresa->convenios->count() }} registrado(s)</span>
    </a>
    <a href="{{ route('empresas.supervisores', $empresa) }}" class="card-link">
        <strong>Supervisores</strong>
        <span>{{ $empresa->supervisores->count() }} cadastrado(s)</span>
    </a>
    <a href="{{ route('empresas.solicitacoes', $empresa) }}" class="card-link">
        <strong>Solicitações</strong>
        <span>Solicitações recebidas</span>
    </a>
    <a href="{{ route('empresas.estagiarios', $empresa) }}" class="card-link">
        <strong>Estagiários</strong>
        <span>Estagiários vinculados</span>
    </a>
</div>

</body>
</html>
