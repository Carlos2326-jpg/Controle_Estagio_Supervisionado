<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Supervisor - {{ $empresa->razao_social }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Novo Supervisor — {{ $empresa->razao_social }}</h1>
                <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('empresas.supervisores.store', $empresa) }}">
                @csrf

                <div class="form-group">
                    <label>Nome Completo *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" required>
                    @error('nome')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Cargo *</label>
                        <input type="text" name="cargo" value="{{ old('cargo') }}" required>
                        @error('cargo')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Formação</label>
                        <input type="text" name="formacao" value="{{ old('formacao') }}" placeholder="Ex: Engenharia de Software">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>E-mail *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14">
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Supervisor</button>
                    <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>