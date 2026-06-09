<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Coordenador - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .stats-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card-mini {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card-mini h3 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .menu-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .menu-link {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        .menu-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Painel do Coordenador</h1>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-perigo">Sair</button>
                </form>
            </div>

            <div class="stats-mini">
                <div class="stat-card-mini">
                    <h3>{{ $solicitacoesPendentes ?? 0 }}</h3>
                    <p>Solicitações Pendentes</p>
                </div>
                <div class="stat-card-mini">
                    <h3>{{ $documentosPendentes ?? 0 }}</h3>
                    <p>Documentos Pendentes</p>
                </div>
                <div class="stat-card-mini">
                    <h3>{{ $avaliacoes ?? 0 }}</h3>
                    <p>Avaliações</p>
                </div>
            </div>

            <div class="secao">Menu Rápido</div>
            <div class="menu-links">
                <a href="{{ route('coordenadores.solicitacoes', $coordenador) }}" class="menu-link">📋 Solicitações</a>
                <a href="{{ route('coordenadores.documentos', $coordenador) }}" class="menu-link">📄 Documentos</a>
                <a href="{{ route('coordenadores.atividades', $coordenador) }}" class="menu-link">⏱️ Atividades</a>
                <a href="{{ route('coordenadores.relatorios', $coordenador) }}" class="menu-link">📊 Relatórios</a>
                <a href="{{ route('coordenadores.alertas', $coordenador) }}" class="menu-link">⚠️ Alertas</a>
            </div>
        </div>
    </div>
</body>
</html>