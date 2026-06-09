<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Curso - {{ $curso->nome }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Detalhes do Curso</h1>
                <a href="{{ route('cursos.index') }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="grid-info">
                <div class="campo"><label>Nome</label><span>{{ $curso->nome }}</span></div>
                <div class="campo"><label>Código</label><span>{{ $curso->codigo }}</span></div>
                <div class="campo"><label>Carga Horária</label><span>{{ $curso->carga_horaria_estagio }}h</span></div>
                <div class="campo"><label>Modalidade</label><span>{{ $curso->modalidade }}</span></div>
                <div class="campo"><label>Status</label><span>{{ $curso->ativo ? 'Ativo' : 'Inativo' }}</span></div>
                <div class="campo"><label>Total de Alunos</label><span>{{ $curso->alunos->count() }}</span></div>
                <div class="campo"><label>Total de
                        Coordenadores</label><span>{{ $curso->coordenadores->count() }}</span></div>
            </div>

            <div class="rodape">
                <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-primario">Editar</a>
                <a href="{{ route('cursos.index') }}" class="btn btn-secundario">Voltar</a>
            </div>
        </div>
    </div>
</body>

</html>
