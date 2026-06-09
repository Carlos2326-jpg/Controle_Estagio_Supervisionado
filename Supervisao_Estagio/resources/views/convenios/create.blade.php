<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Convênio - {{ $empresa->razao_social }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Novo Convênio — {{ $empresa->razao_social }}</h1>
                <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">← Voltar</a>
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

            <form method="POST" action="{{ route('empresas.convenios.store', $empresa) }}">
                @csrf

                <div class="form-group">
                    <label>Número do Convênio *</label>
                    <input type="text" name="numero_convenio" value="{{ old('numero_convenio') }}" required>
                    @error('numero_convenio')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Data de Início *</label>
                        <input type="date" name="data_inicio" value="{{ old('data_inicio') }}" required>
                        @error('data_inicio')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Data de Fim *</label>
                        <input type="date" name="data_fim" value="{{ old('data_fim') }}" required>
                        @error('data_fim')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="observacoes">{{ old('observacoes') }}</textarea>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Convênio</button>
                    <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
