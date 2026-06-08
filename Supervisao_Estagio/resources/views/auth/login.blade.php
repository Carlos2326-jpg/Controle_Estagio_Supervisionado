<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estágios</title>
</head>

<body>
    <div class="login-container">
        <h1>Sistema de Estágios</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="remember" style="width: auto; margin-right: 10px;">
                    Lembrar-me
                </label>
            </div>

            <button type="submit">Entrar</button>

            <div class="links">
                <a href="{{ route('password.request') }}">Esqueceu sua senha?</a>
                <br>
                <a href="{{ route('register') }}">Criar nova conta</a>
            </div>
        </form>
    </div>
</body>

</html>
