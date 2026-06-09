<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Instituição - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Nova Instituição</h1>
                <a href="{{ route('instituicoes.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('instituicoes.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nome da Instituição *</label>
                    <input type="text" name="nome_instituicao" value="{{ old('nome_instituicao') }}" required>
                    @error('nome_instituicao')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Sigla *</label>
                    <input type="text" name="sigla" value="{{ old('sigla') }}" required>
                    @error('sigla')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>CNPJ *</label>
                    <input type="text" name="cnpj" value="{{ old('cnpj') }}" maxlength="14" required>
                    @error('cnpj')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Endereço *</label>
                    <input type="text" name="endereco" value="{{ old('endereco') }}" required>
                    @error('endereco')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Cidade *</label>
                        <input type="text" name="cidade" value="{{ old('cidade') }}" required>
                        @error('cidade')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Estado (UF) *</label>
                        <input type="text" name="estado" value="{{ old('estado') }}" maxlength="2" required>
                        @error('estado')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone') }}">
                        @error('telefone')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>E-mail de Contato</label>
                        <input type="email" name="email_contato" value="{{ old('email_contato') }}">
                        @error('email_contato')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Site</label>
                    <input type="text" name="site" value="{{ old('site') }}">
                    @error('site')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar</button>
                    <a href="{{ route('instituicoes.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>