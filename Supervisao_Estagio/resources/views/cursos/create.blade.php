<!DOCTYPE html>

<html>

<head>
    <title>Cadastrar Curso</title>

    ```
    ```

</head>

<body>

    <h1>Cadastrar Curso</h1>

    <form action="{{ route('cursos.store') }}" method="POST">
        @csrf

        ```
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" value="{{ old('nome') }}">

            @error('nome')
                <div class="erro">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Código</label>
            <input type="text" name="codigo" value="{{ old('codigo') }}">

            @error('codigo')
                <div class="erro">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Carga Horária de Estágio</label>
            <input type="number" name="carga_horaria_estagio" value="{{ old('carga_horaria_estagio') }}">

            @error('carga_horaria_estagio')
                <div class="erro">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Modalidade</label>

            <select name="modalidade">
                <option value="">Selecione</option>
                <option value="Presencial">Presencial</option>
                <option value="EAD">EAD</option>
                <option value="Hibrido">Híbrido</option>
            </select>

            @error('modalidade')
                <div class="erro">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Salvar
        </button>

        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
            Voltar
        </a>
        ```

    </form>

</body>

</html>
