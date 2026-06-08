<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Sistema de Estágios</title>
</head>

<body>
    <div class="container">
        <h1>Recuperar Senha</h1>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit">Enviar link de recuperação</button>

            <div class="links">
                <a href="{{ route('login') }}">Voltar para o login</a>
            </div>
        </form>
    </div>
</body>

</html>
