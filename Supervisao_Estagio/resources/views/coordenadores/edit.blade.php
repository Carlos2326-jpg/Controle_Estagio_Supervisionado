<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Coordenador - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Editar Coordenador</h1>
                <a href="{{ route('coordenadores.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="POST" action="{{ route('coordenadores.update', $coordenador) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $coordenador->nome) }}" required>
                </div>

                <div class="form-group">
                    <label>E-mail *</label>
                    <input type="email" name="email" value="{{ old('email', $coordenador->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Curso</label>
                    <select name="curso_id" class="form-control">
                        <option value="">Selecione um curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $coordenador->curso_id) == $curso->id ? 'selected' : '' }}>
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
                            <option value="{{ $instituicao->id }}" {{ old('instituicao_id', $coordenador->instituicao_id) == $instituicao->id ? 'selected' : '' }}>
                                {{ $instituicao->nome_instituicao }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="ativo" {{ old('status', $coordenador->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ old('status', $coordenador->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nova Senha (deixe em branco para manter a atual)</label>
                    <input type="password" name="password">
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Atualizar</button>
                    <a href="{{ route('coordenadores.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>