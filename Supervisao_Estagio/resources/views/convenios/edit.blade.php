<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Convênio</title>
</head>

<body>

    <h1>Editar Convênio — {{ $convenio->numero_convenio }}</h1>

    <form method="POST" action="{{ route('empresas.convenios.update', [$empresa, $convenio]) }}">
        @csrf @method('PUT')

        <div class="linha">
            <div class="grupo">
                <label>Data de Início</label>
                <input type="date" name="data_inicio"
                    value="{{ old('data_inicio', $convenio->data_inicio->format('Y-m-d')) }}" required>
            </div>
            <div class="grupo">
                <label>Data de Fim *</label>
                <input type="date" name="data_fim"
                    value="{{ old('data_fim', $convenio->data_fim->format('Y-m-d')) }}" required>
                @error('data_fim')
                    <span class="erro">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grupo">
            <label>Status *</label>
            <select name="status">
                <option value="ativo" {{ old('status', $convenio->status) === 'ativo' ? 'selected' : '' }}>Ativo
                </option>
                <option value="inativo" {{ old('status', $convenio->status) === 'inativo' ? 'selected' : '' }}>Inativo
                </option>
                <option value="vencido" {{ old('status', $convenio->status) === 'vencido' ? 'selected' : '' }}>Vencido
                </option>
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
