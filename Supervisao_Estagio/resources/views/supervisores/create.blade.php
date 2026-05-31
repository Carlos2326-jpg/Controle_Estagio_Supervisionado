<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Supervisor</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 600px; }
        h1 { font-size: 1.4rem; margin-bottom: 20px; }
        .grupo { margin-bottom: 14px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; }
        input { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        .linha { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn { padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .erro { color: #dc2626; font-size: 0.8rem; margin-top: 3px; }
        .rodape { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<h1>Novo Supervisor — {{ $empresa->razao_social }}</h1>

@if($errors->any())
    <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:4px;margin-bottom:14px;">
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('empresas.supervisores.store', $empresa) }}">
    @csrf

    <div class="grupo">
        <label>Nome Completo *</label>
        <input type="text" name="nome" value="{{ old('nome') }}" required>
        @error('nome')<span class="erro">{{ $message }}</span>@enderror
    </div>

    <div class="linha">
        <div class="grupo">
            <label>Cargo *</label>
            <input type="text" name="cargo" value="{{ old('cargo') }}" required>
            @error('cargo')<span class="erro">{{ $message }}</span>@enderror
        </div>
        <div class="grupo">
            <label>Formação</label>
            <input type="text" name="formacao" value="{{ old('formacao') }}" placeholder="Ex: Engenharia de Software">
        </div>
    </div>

    <div class="linha">
        <div class="grupo">
            <label>E-mail *</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')<span class="erro">{{ $message }}</span>@enderror
        </div>
        <div class="grupo">
            <label>Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
        </div>
    </div>

    <div class="grupo">
        <label>CPF</label>
        <input type="text" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14">
    </div>

    <div class="rodape">
        <button type="submit" class="btn btn-primario">Salvar Supervisor</button>
        <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">Cancelar</a>
    </div>
</form>

</body>
</html>
