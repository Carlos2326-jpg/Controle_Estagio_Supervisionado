<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $empresa->razao_social }} - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            @if (session('sucesso'))
                <div class="alerta-sucesso">{{ session('sucesso') }}</div>
            @endif

            <div class="topo">
                <div>
                    <h1>{{ $empresa->razao_social }}</h1>
                    <p class="subtitulo">
                        {{ $empresa->nome_fantasia ? $empresa->nome_fantasia . ' · ' : '' }}
                        CNPJ: {{ $empresa->cnpj }} ·
                        <span class="badge {{ $empresa->status === 'ativa' ? 'badge-ativo' : 'badge-inativo' }}">
                            {{ ucfirst($empresa->status) }}
                        </span>
                    </p>
                </div>
                <div class="acoes">
                    <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-primario">Editar</a>
                    @if ($empresa->isAtiva())
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
            </div>

            <div class="secao">Informações da Empresa</div>
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

            <div class="secao">Módulos</div>
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
        </div>
    </div>
</body>
</html>