<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Empresa - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Nova Empresa Concedente</h1>
                <a href="{{ route('empresas.index') }}" class="btn btn-secundario">← Voltar</a>
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

            <form method="POST" action="{{ route('empresas.store') }}">
                @csrf

                <div class="secao">Dados da Empresa</div>

                <div class="form-group">
                    <label>Razão Social *</label>
                    <input type="text" name="razao_social" value="{{ old('razao_social') }}" required>
                    @error('razao_social')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
                    </div>
                    <div class="form-group">
                        <label>CNPJ *</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00" maxlength="18" required>
                        @error('cnpj')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>E-mail *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-group">
                    <label>Ramo de Atividade</label>
                    <input type="text" name="ramo_atividade" value="{{ old('ramo_atividade') }}">
                </div>

                <div class="secao">Endereço</div>

                <div class="linha-3">
                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" value="{{ old('cep') }}" placeholder="00000-000" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="numero" value="{{ old('numero') }}">
                    </div>
                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" name="complemento" value="{{ old('complemento') }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Logradouro</label>
                        <input type="text" name="logradouro" value="{{ old('logradouro') }}">
                    </div>
                    <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" name="bairro" value="{{ old('bairro') }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade') }}">
                    </div>
                    <div class="form-group">
                        <label>Estado (UF)</label>
                        <input type="text" name="estado" value="{{ old('estado') }}" maxlength="2" placeholder="SP">
                    </div>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Empresa</button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>