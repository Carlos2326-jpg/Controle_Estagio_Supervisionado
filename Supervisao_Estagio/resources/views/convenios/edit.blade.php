<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Convênio</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; max-width: 600px; }
        h1 { font-size: 1.4rem; margin-bottom: 20px; }
        .grupo { margin-bottom: 14px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; }
        input, select, textarea { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 0.9rem; }
        textarea { resize: vertical; min-height: 80px; }
        .linha { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn { padding: 9px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .erro { color: #dc2626; font-size: 0.8rem; margin-top: 3px; }
        .rodape { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<h1>Editar Convênio — {{ $convenio->numero_convenio }}</h1>

<form method="POST" action="{{ route('empresas.convenios.update', [$empresa, $convenio]) }}">
    @csrf @method('PUT')

    <div class="linha">
        <div class="grupo">
            <label>Data de Início</label>
            <input type="date" name="data_inicio" value="{{ old('data_inicio', $convenio->data_inicio->format('Y-m-d')) }}" required>
        </div>
        <div class="grupo">
            <label>Data de Fim *</label>
            <input type="date" name="data_fim" value="{{ old('data_fim', $convenio->data_fim->format('Y-m-d')) }}" required>
            @error('data_fim')<span class="erro">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="grupo">
        <label>Status *</label>
        <select name="status">
            <option value="ativo"   {{ old('status', $convenio->status) === 'ativo'   ? 'selected' : '' }}>Ativo</option>
            <option value="inativo" {{ old('status', $convenio->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
            <option value="vencido" {{ old('status', $convenio->status) === 'vencido' ? 'selected' : '' }}>Vencido</option>
        </select>
    </div>

    <div class="grupo">
        <label>Observações</label>
        <textarea name="observacoes">{{ old('observacoes', $convenio->observacoes) }}</textarea>
    </div>

    <div class="rodape">
        <button type="submit" class="btn btn-primario">Salvar Alterações</button>
        <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">Cancelar</a>
    </div>
</form>

</body>
</html>
