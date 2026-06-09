<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Supervisor - {{ $supervisor->nome }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Editar Supervisor — {{ $supervisor->nome }}</h1>
                <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="POST" action="{{ route('empresas.supervisores.update', [$empresa, $supervisor]) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Nome Completo *</label>
                    <input type="text" name="nome" value="{{ old('nome', $supervisor->nome) }}" required>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Cargo *</label>
                        <input type="text" name="cargo" value="{{ old('cargo', $supervisor->cargo) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Formação</label>
                        <input type="text" name="formacao" value="{{ old('formacao', $supervisor->formacao) }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>E-mail *</label>
                        <input type="email" name="email" value="{{ old('email', $supervisor->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $supervisor->telefone) }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $supervisor->cpf) }}" maxlength="14">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="ativo"
                                {{ old('status', $supervisor->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo"
                                {{ old('status', $supervisor->status) === 'inativo' ? 'selected' : '' }}>Inativo
                            </option>
                        </select>
                    </div>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Alterações</button>
                    <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
