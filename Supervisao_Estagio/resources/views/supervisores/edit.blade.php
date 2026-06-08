<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Supervisor</title>
</head>

<body>

    <h1>Editar Supervisor — {{ $supervisor->nome }}</h1>

    <form method="POST" action="{{ route('empresas.supervisores.update', [$empresa, $supervisor]) }}">
        @csrf @method('PUT')

        <div class="grupo">
            <label>Nome Completo *</label>
            <input type="text" name="nome" value="{{ old('nome', $supervisor->nome) }}" required>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>Cargo *</label>
                <input type="text" name="cargo" value="{{ old('cargo', $supervisor->cargo) }}" required>
            </div>
            <div class="grupo">
                <label>Formação</label>
                <input type="text" name="formacao" value="{{ old('formacao', $supervisor->formacao) }}">
            </div>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>E-mail *</label>
                <input type="email" name="email" value="{{ old('email', $supervisor->email) }}" required>
            </div>
            <div class="grupo">
                <label>Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone', $supervisor->telefone) }}">
            </div>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>CPF</label>
                <input type="text" name="cpf" value="{{ old('cpf', $supervisor->cpf) }}" maxlength="14">
            </div>
            <div class="grupo">
                <label>Status</label>
                <select name="status">
                    <option value="ativo" {{ old('status', $supervisor->status) === 'ativo' ? 'selected' : '' }}>
                        Ativo</option>
                    <option value="inativo" {{ old('status', $supervisor->status) === 'inativo' ? 'selected' : '' }}>
                        Inativo</option>
                </select>
            </div>
        </div>

        <div class="rodape">
            <button type="submit" class="btn btn-primario">Salvar Alterações</button>
            <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>

</body>

</html>
