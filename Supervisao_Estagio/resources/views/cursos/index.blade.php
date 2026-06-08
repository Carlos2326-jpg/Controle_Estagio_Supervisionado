<!DOCTYPE html>
<html>

<head>
    <title>Cursos</title>
</head>

<body>

    <h1>Gerenciamento de Cursos</h1>

    <a href="{{ route('cursos.create') }}" class="btn btn-primary">
        Novo Curso
    </a>

    <br><br>

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
                    <td>{{ $curso->ativo ? 'Ativo' : 'Inativo' }}</td>

                    <td>
                        <a href="{{ route('cursos.show', $curso) }}">
                            Visualizar
                        </a>

                        |

                        <a href="{{ route('cursos.edit', $curso) }}">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        Nenhum curso cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    {{ $cursos->links() }}

</body>

</html>
