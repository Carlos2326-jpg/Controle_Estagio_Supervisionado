<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .register-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .register-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h1>Cadastro de Empresa</h1>
        <div class="subtitle">Sistema de Gestão de Estágios</div>

        <div class="info-box">
            <p>📌 Este cadastro é <strong>exclusivo para EMPRESAS</strong>.</p>
            <p>👨‍🎓 Alunos devem ser cadastrados pelo coordenador do curso.</p>
            <p>👨‍🏫 Coordenadores são cadastrados pelo administrador.</p>
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Nome do Responsável *</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label>E-mail Corporativo *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Senha *</label>
                <input type="password" name="password" required>
                <small class="dica">Mínimo 8 caracteres</small>
            </div>

            <div class="form-group">
                <label>Confirmar Senha *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label>Razão Social *</label>
                <input type="text" name="razao_social" value="{{ old('razao_social') }}" required>
            </div>

            <div class="form-group">
                <label>CNPJ *</label>
                <input type="text" name="cnpj" value="{{ old('cnpj') }}" placeholder="00000000000000"
                    maxlength="14" required>
                <small class="dica">Digite apenas números (14 dígitos)</small>
            </div>

            <button type="submit" class="btn btn-primario" style="width: 100%;">Cadastrar Empresa</button>

            <div class="links">
                <a href="{{ route('login') }}">Já tem conta? Faça login</a>
            </div>
        </form>
    </div>
</body>

</html>
