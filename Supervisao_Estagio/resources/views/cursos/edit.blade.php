<!DOCTYPE html>
<html>
<head>
    <title>Editar Curso</title>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>

<h1>Editar Curso</h1>

<form action="{{ route('cursos.update', $curso) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nome</label>

        <input
            type="text"
            name="nome"
            value="{{ old('nome', $curso->nome) }}"
        >
    </div>

    <div class="form-group">
        <label>Código</label>

        <input
            type="text"
            name="codigo"
            value="{{ old('codigo', $curso->codigo) }}"
        >
    </div>

    <div class="form-group">
        <label>Carga Horária de Estágio</label>

        <input
            type="number"
            name="carga_horaria_estagio"
            value="{{ old('carga_horaria_estagio', $curso->carga_horaria_estagio) }}"
        >
    </div>

    <div class="form-group">
        <label>Modalidade</label>

        <select name="modalidade">

            <option value="Presencial"
                {{ $curso->modalidade == 'Presencial' ? 'selected' : '' }}>
                Presencial
            </option>

            <option value="EAD"
                {{ $curso->modalidade == 'EAD' ? 'selected' : '' }}>
                EAD
            </option>

            <option value="Hibrido"
                {{ $curso->modalidade == 'Hibrido' ? 'selected' : '' }}>
                Híbrido
            </option>

        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Atualizar
    </button>

    <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
        Voltar
    </a>

</form>

</body>
</html>