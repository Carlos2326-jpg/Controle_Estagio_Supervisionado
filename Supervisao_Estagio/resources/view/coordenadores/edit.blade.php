<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Coordenador</title>

    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 700px; }
        h1 { font-size: 1.4rem; margin-bottom: 20px; }
        .grupo { margin-bottom: 14px; }
        label { display:block; margin-bottom:4px; font-weight:600; }
        input { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }
        .btn { padding:9px 18px; border:none; border-radius:4px; cursor:pointer; text-decoration:none; }
        .btn-primario { background:#2563eb; color:white; }
        .btn-secundario { background:#e5e7eb; color:#333; }
        .rodape { display:flex; gap:10px; margin-top:20px; }
    </style>
</head>
<body>

<h1>Editar Coordenador</h1>

<form method="POST" action="/coordenadores/{{ $coordenador->id }}">
    @csrf
    @method('PUT')

    <div class="grupo">
        <label>Nome</label>
        <input
            type="text"
            name="nome"
            value="{{ old('nome', $coordenador->nome) }}"
            required
        >
    </div>

    <div class="grupo">
        <label>E-mail</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $coordenador->email) }}"
            required
        >
    </div>

    <div class="grupo">
        <label>Curso ID</label>
        <input
            type="number"
            name="curso_id"
            value="{{ old('curso_id', $coordenador->curso_id) }}"
        >
    </div>

    <div class="rodape">
        <button type="submit" class="btn btn-primario">
            Atualizar
        </button>

        <a href="/coordenadores" class="btn btn-secundario">
            Cancelar
        </a>
    </div>
</form>

</body>
</html>