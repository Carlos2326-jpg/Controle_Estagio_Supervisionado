<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Supervisor</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 600px; }
        h1 { font-size: 1.4rem; margin-bottom: 20px; }
        .grupo { margin-bottom: 14px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; }
        input, select { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        .linha { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn { padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .rodape { display: flex; gap: 10px; margin-top: 20px; }
    </style>
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
                <option value="ativo"   {{ old('status', $supervisor->status) === 'ativo'   ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ old('status', $supervisor->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
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
