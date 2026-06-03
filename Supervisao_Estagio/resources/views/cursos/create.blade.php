<!DOCTYPE html>

<html>
<head>
    <title>Cadastrar Curso</title>

```
<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    h1 { margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
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
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
    }
    .erro {
        color: red;
        font-size: 12px;
        margin-top: 4px;
    }
</style>
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
    <input type="number"
           name="carga_horaria_estagio"
           value="{{ old('carga_horaria_estagio') }}">

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
