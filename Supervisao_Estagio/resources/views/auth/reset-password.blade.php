<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Sistema de Estágios</title>
</head>

<body>
    <div class="container">
        <h1>Redefinir Senha</h1>

        @if ($errors->any())
            <div class="alert alert-danger"
                style="background:#fee;color:#c0392b;padding:10px;border-radius:5px;margin-bottom:20px;">
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

            <button type="submit">Redefinir Senha</button>

            <div class="links">
                <a href="{{ route('login') }}">Voltar para o login</a>
            </div>
        </form>
    </div>
</body>

</html>
