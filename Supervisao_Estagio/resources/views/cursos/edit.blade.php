<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - {{ $curso->nome }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Editar Curso</h1>
                <a href="{{ route('cursos.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form action="{{ route('cursos.update', $curso) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $curso->nome) }}" required>
                </div>

                <div class="form-group">
                    <label>Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $curso->codigo) }}" required>
                </div>

                <div class="form-group">
                    <label>Carga Horária de Estágio *</label>
                    <input type="number" name="carga_horaria_estagio"
                        value="{{ old('carga_horaria_estagio', $curso->carga_horaria_estagio) }}" required>
                </div>

                <div class="form-group">
                    <label>Modalidade *</label>
                    <select name="modalidade" required>
                        <option value="Presencial"
                            {{ old('modalidade', $curso->modalidade) === 'Presencial' ? 'selected' : '' }}>Presencial
                        </option>
                        <option value="EAD" {{ old('modalidade', $curso->modalidade) === 'EAD' ? 'selected' : '' }}>
                            EAD</option>
                        <option value="Hibrido"
                            {{ old('modalidade', $curso->modalidade) === 'Hibrido' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Atualizar</button>
                    <a href="{{ route('cursos.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
