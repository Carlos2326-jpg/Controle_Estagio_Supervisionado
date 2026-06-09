<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Gerenciamento de Cursos</h1>
                <a href="{{ route('cursos.create') }}" class="btn btn-primario">+ Novo Curso</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Código</th>
                            <th>Modalidade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cursos as $curso)
                            <tr>
                                <td>{{ $curso->id }}</td>
                                <td>{{ $curso->nome }}</td>
                                <td>{{ $curso->codigo }}</td>
                                <td>{{ $curso->modalidade }}</td>
                                <td>{{ $curso->ativo ? '✅ Ativo' : '❌ Inativo' }}</td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-secundario">Ver</a>
                                        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-primario">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhum curso cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $cursos->links() }}
            </div>
        </div>
    </div>
</body>

</html>
