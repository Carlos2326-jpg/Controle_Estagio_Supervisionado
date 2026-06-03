<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Instituição</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 8px 16px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .erro { color: red; font-size: 12px; margin-top: 4px; }
    </style>
</head>
<body>

    <h1>Nova Instituição</h1>

    @if($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach($errors->all() as $erro)
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
            @error('nome_instituicao') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Sigla *</label>
            <input type="text" name="sigla" value="{{ old('sigla') }}" required>
            @error('sigla') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>CNPJ *</label>
            <input type="text" name="cnpj" value="{{ old('cnpj') }}" maxlength="14" required>
            @error('cnpj') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Endereço *</label>
            <input type="text" name="endereco" value="{{ old('endereco') }}" required>
            @error('endereco') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Cidade *</label>
            <input type="text" name="cidade" value="{{ old('cidade') }}" required>
            @error('cidade') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Estado (UF) *</label>
            <input type="text" name="estado" value="{{ old('estado') }}" maxlength="2" required>
            @error('estado') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone') }}">
            @error('telefone') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>E-mail de Contato</label>
            <input type="email" name="email_contato" value="{{ old('email_contato') }}">
            @error('email_contato') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Site</label>
            <input type="text" name="site" value="{{ old('site') }}">
            @error('site') <span class="erro">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('instituicoes.index') }}" class="btn btn-secondary" style="margin-left: 10px;">Cancelar</a>
        </div>

    </form>

</body>
</html>