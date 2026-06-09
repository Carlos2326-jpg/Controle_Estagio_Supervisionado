<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Coordenador - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Novo Coordenador</h1>
                <a href="{{ route('coordenadores.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="POST" action="{{ route('coordenadores.store') }}">
                @csrf

                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" required>
                </div>

                <div class="form-group">
                    <label>E-mail *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Curso</label>
                    <select name="curso_id" class="form-control">
                        <option value="">Selecione um curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Instituição *</label>
                    <select name="instituicao_id" class="form-control" required>
                        <option value="">Selecione uma instituição</option>
                        @foreach ($instituicoes as $instituicao)
                            <option value="{{ $instituicao->id }}" {{ old('instituicao_id') == $instituicao->id ? 'selected' : '' }}>
                                {{ $instituicao->nome_instituicao }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Senha *</label>
                    <input type="password" name="password" required>
                    <small class="dica">Mínimo 8 caracteres</small>
                </div>

                <div class="form-group">
                    <label>Confirmar Senha *</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar</button>
                    <a href="{{ route('coordenadores.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>