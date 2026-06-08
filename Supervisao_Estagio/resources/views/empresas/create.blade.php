<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Nova Empresa</title>
</head>

<body>

    <h1>Nova Empresa Concedente</h1>

    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:4px;margin-bottom:14px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('empresas.store') }}">
        @csrf

        <p class="secao">Dados da Empresa</p>

        <div class="grupo">
            <label>Razão Social *</label>
            <input type="text" name="razao_social" value="{{ old('razao_social') }}" required>
            @error('razao_social')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="linha">
            <div class="grupo">
                <label>Nome </label>
                <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
            </div>
            <div class="grupo">
                <label>CNPJ *</label>
                <input type="text" name="cnpj" value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00"
                    maxlength="18" required>
                @error('cnpj')
                    <span class="erro">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>E-mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="erro">{{ $message }}</span>
                @enderror
            </div>
            <div class="grupo">
                <label>Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
            </div>
        </div>

        <div class="grupo">
            <label>Ramo de Atividade</label>
            <input type="text" name="ramo_atividade" value="{{ old('ramo_atividade') }}">
        </div>

        <p class="secao">Endereço</p>

        <div class="linha-3">
            <div class="grupo">
                <label>CEP</label>
                <input type="text" name="cep" value="{{ old('cep') }}" placeholder="00000-000"
                    maxlength="9">
            </div>
            <div class="grupo">
                <label>Número</label>
                <input type="text" name="numero" value="{{ old('numero') }}">
            </div>
            <div class="grupo">
                <label>Complemento</label>
                <input type="text" name="complemento" value="{{ old('complemento') }}">
            </div>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>Logradouro</label>
                <input type="text" name="logradouro" value="{{ old('logradouro') }}">
            </div>
            <div class="grupo">
                <label>Bairro</label>
                <input type="text" name="bairro" value="{{ old('bairro') }}">
            </div>
        </div>

        <div class="linha">
            <div class="grupo">
                <label>Cidade</label>
                <input type="text" name="cidade" value="{{ old('cidade') }}">
            </div>
            <div class="grupo">
                <label>Estado (UF)</label>
                <input type="text" name="estado" value="{{ old('estado') }}" maxlength="2" placeholder="SP">
            </div>
        </div>

        <div class="rodape">
            <button type="submit" class="btn btn-primario">Salvar Empresa</button>
            <a href="{{ route('empresas.index') }}" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>

</body>

</html>
