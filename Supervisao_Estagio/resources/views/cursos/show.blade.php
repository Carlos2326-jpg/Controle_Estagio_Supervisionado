<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Curso</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 6px;
        }

        .item {
            margin-bottom: 10px;
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

<h1>Detalhes do Curso</h1>

<div class="card">

    <div class="item">
        <strong>Nome:</strong>
        {{ $curso->nome }}
    </div>

    <div class="item">
        <strong>Código:</strong>
        {{ $curso->codigo }}
    </div>

    <div class="item">
        <strong>Carga Horária:</strong>
        {{ $curso->carga_horaria_estagio }}
    </div>

    <div class="item">
        <strong>Modalidade:</strong>
        {{ $curso->modalidade }}
    </div>

    <div class="item">
        <strong>Status:</strong>
        {{ $curso->ativo ? 'Ativo' : 'Inativo' }}
    </div>

    <div class="item">
        <strong>Total de Alunos:</strong>
        {{ $curso->alunos->count() }}
    </div>

    <div class="item">
        <strong>Total de Coordenadores:</strong>
        {{ $curso->coordenadores->count() }}
    </div>

</div>

<br>

<a href="{{ route('cursos.edit', $curso) }}" class="btn btn-primary">
    Editar
</a>

<a href="{{ route('cursos.index') }}" class="btn btn-secondary">
    Voltar
</a>

</body>
</html>