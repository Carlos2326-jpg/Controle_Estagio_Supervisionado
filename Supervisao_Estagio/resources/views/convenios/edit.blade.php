<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Convênio - {{ $convenio->numero_convenio }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Editar Convênio — {{ $convenio->numero_convenio }}</h1>
                <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="POST" action="{{ route('empresas.convenios.update', [$empresa, $convenio]) }}">
                @csrf @method('PUT')

                <div class="linha">
                    <div class="form-group">
                        <label>Data de Início</label>
                        <input type="date" name="data_inicio"
                            value="{{ old('data_inicio', $convenio->data_inicio->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Data de Fim *</label>
                        <input type="date" name="data_fim"
                            value="{{ old('data_fim', $convenio->data_fim->format('Y-m-d')) }}" required>
                        @error('data_fim')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select name="status">
                        <option value="ativo" {{ old('status', $convenio->status) === 'ativo' ? 'selected' : '' }}>
                            Ativo</option>
                        <option value="inativo" {{ old('status', $convenio->status) === 'inativo' ? 'selected' : '' }}>
                            Inativo</option>
                        <option value="vencido" {{ old('status', $convenio->status) === 'vencido' ? 'selected' : '' }}>
                            Vencido</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="observacoes">{{ old('observacoes', $convenio->observacoes) }}</textarea>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Alterações</button>
                    <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
