<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 20px;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar nav a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .main {
            margin-left: 280px;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 1.5rem;
            color: #333;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stats-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #667eea;
            font-size: 28px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🎓 Sistema de Estágios</h2>
        <nav>
            <a href="#">🏠 Dashboard</a>
            <a href="#">📝 Solicitar Estágio</a>
            <a href="#">📋 Minhas Solicitações</a>
            <a href="#">📄 Meus Documentos</a>
            <a href="#">⏱️ Atividades</a>
            <a href="#">⭐ Minhas Avaliações</a>
        </nav>
    </div>
    <div class="main">
        <div class="header">
            <h1>Painel do Aluno</h1>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </div>

        <div class="card welcome-card">
            <h2>Bem-vindo, {{ auth()->user()->name }}!</h2>
            <p>Você está logado como Aluno. Utilize o menu lateral para solicitar estágio e acompanhar suas atividades.</p>
        </div>

        <div class="stats-mini">
            <div class="stat-card">
                <h3>0</h3>
                <p>Solicitações Ativas</p>
            </div>
            <div class="stat-card">
                <h3>0</h3>
                <p>Horas Cumpridas</p>
            </div>
            <div class="stat-card">
                <h3>0</h3>
                <p>Avaliações</p>
            </div>
        </div>
    </div>
</body>
</html>