<!DOCTYPE html>
<html>

<head>
    <title>Detalhes do Curso</title>
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
