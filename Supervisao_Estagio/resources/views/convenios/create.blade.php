<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Novo Convênio</title>
</head>

<body>

    <h1>Novo Convênio — {{ $empresa->razao_social }}</h1>

    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:4px;margin-bottom:14px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('empresas.convenios.store', $empresa) }}">
        @csrf

        <div class="grupo">
            <label>Número do Convênio *</label>
            <input type="text" name="numero_convenio" value="{{ old('numero_convenio') }}" required>
            @error('numero_convenio')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="linha">
            <div class="grupo">
                <label>Data de Início *</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio') }}" required>
                @error('data_inicio')
                    <span class="erro">{{ $message }}</span>
                @enderror
            </div>
            <div class="grupo">
                <label>Data de Fim *</label>
                <input type="date" name="data_fim" value="{{ old('data_fim') }}" required>
                @error('data_fim')
                    <span class="erro">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grupo">
            <label>Observações</label>
            <textarea name="observacoes">{{ old('observacoes') }}</textarea>
        </div>

        <div class="rodape">
            <button type="submit" class="btn btn-primario">Salvar Convênio</button>
            <a href="{{ route('empresas.convenios', $empresa) }}" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>

</body>

</html>
