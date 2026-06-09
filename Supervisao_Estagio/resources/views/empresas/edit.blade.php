<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empresa - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Editar Empresa — {{ $empresa->razao_social }}</h1>
                <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
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

            <form method="POST" action="{{ route('empresas.update', $empresa) }}">
                @csrf @method('PUT')

                <div class="secao">Dados da Empresa</div>

                <div class="form-group">
                    <label>Razão Social *</label>
                    <input type="text" name="razao_social" value="{{ old('razao_social', $empresa->razao_social) }}"
                        required>
                    @error('razao_social')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Nome Fantasia</label>
                        <input type="text" name="nome_fantasia"
                            value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}">
                    </div>
                    <div class="form-group">
                        <label>CNPJ *</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $empresa->cnpj) }}" maxlength="18"
                            required>
                        @error('cnpj')
                            <span class="erro">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>E-mail *</label>
                        <input type="email" name="email" value="{{ old('email', $empresa->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $empresa->telefone) }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Ramo de Atividade</label>
                        <input type="text" name="ramo_atividade"
                            value="{{ old('ramo_atividade', $empresa->ramo_atividade) }}">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="ativa" {{ old('status', $empresa->status) === 'ativa' ? 'selected' : '' }}>
                                Ativa</option>
                            <option value="inativa"
                                {{ old('status', $empresa->status) === 'inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                    </div>
                </div>

                <div class="secao">Endereço</div>

                <div class="linha-3">
                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" value="{{ old('cep', $empresa->cep) }}" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="numero" value="{{ old('numero', $empresa->numero) }}">
                    </div>
                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" name="complemento"
                            value="{{ old('complemento', $empresa->complemento) }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Logradouro</label>
                        <input type="text" name="logradouro" value="{{ old('logradouro', $empresa->logradouro) }}">
                    </div>
                    <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" name="bairro" value="{{ old('bairro', $empresa->bairro) }}">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $empresa->cidade) }}">
                    </div>
                    <div class="form-group">
                        <label>Estado (UF)</label>
                        <input type="text" name="estado" value="{{ old('estado', $empresa->estado) }}"
                            maxlength="2">
                    </div>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Alterações</button>
                    <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
