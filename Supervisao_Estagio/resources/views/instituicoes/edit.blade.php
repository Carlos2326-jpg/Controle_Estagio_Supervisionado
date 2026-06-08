<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Instituição</title>
</head>

<body>

    <h1>Editar Instituição</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('instituicoes.update', $instituicao->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nome da Instituição *</label>
            <input type="text" name="nome_instituicao"
                value="{{ old('nome_instituicao', $instituicao->nome_instituicao) }}" required>
            @error('nome_instituicao')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Sigla *</label>
            <input type="text" name="sigla" value="{{ old('sigla', $instituicao->sigla) }}" required>
            @error('sigla')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>CNPJ *</label>
            <input type="text" name="cnpj" value="{{ old('cnpj', $instituicao->cnpj) }}" maxlength="14" required>
            @error('cnpj')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Endereço *</label>
            <input type="text" name="endereco" value="{{ old('endereco', $instituicao->endereco) }}" required>
            @error('endereco')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Cidade *</label>
            <input type="text" name="cidade" value="{{ old('cidade', $instituicao->cidade) }}" required>
            @error('cidade')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Estado (UF) *</label>
            <input type="text" name="estado" value="{{ old('estado', $instituicao->estado) }}" maxlength="2"
                required>
            @error('estado')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone', $instituicao->telefone) }}">
            @error('telefone')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>E-mail de Contato</label>
            <input type="email" name="email_contato" value="{{ old('email_contato', $instituicao->email_contato) }}">
            @error('email_contato')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Site</label>
            <input type="text" name="site" value="{{ old('site', $instituicao->site) }}">
            @error('site')
                <span class="erro">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('instituicoes.index') }}" class="btn btn-secondary"
                style="margin-left: 10px;">Cancelar</a>
        </div>

    </form>

</body>

</html>
