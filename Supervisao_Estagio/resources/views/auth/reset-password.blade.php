<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Redefinir Senha</h1>

        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('POST')

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label>Nova Senha</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primario" style="width: 100%;">Redefinir Senha</button>

            <div class="links">
                <a href="{{ route('login') }}">Voltar para o login</a>
            </div>
        </form>
    </div>
</body>

</html>
