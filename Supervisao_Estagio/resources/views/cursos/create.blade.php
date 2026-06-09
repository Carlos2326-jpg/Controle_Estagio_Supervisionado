<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Curso - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Cadastrar Curso</h1>
                <a href="{{ route('cursos.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form action="{{ route('cursos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" required>
                    @error('nome')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" required>
                    @error('codigo')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Carga Horária de Estágio *</label>
                    <input type="number" name="carga_horaria_estagio" value="{{ old('carga_horaria_estagio') }}"
                        required>
                    @error('carga_horaria_estagio')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Modalidade *</label>
                    <select name="modalidade" required>
                        <option value="">Selecione</option>
                        <option value="Presencial" {{ old('modalidade') === 'Presencial' ? 'selected' : '' }}>Presencial
                        </option>
                        <option value="EAD" {{ old('modalidade') === 'EAD' ? 'selected' : '' }}>EAD</option>
                        <option value="Hibrido" {{ old('modalidade') === 'Hibrido' ? 'selected' : '' }}>Híbrido
                        </option>
                    </select>
                    @error('modalidade')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar</button>
                    <a href="{{ route('cursos.index') }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
