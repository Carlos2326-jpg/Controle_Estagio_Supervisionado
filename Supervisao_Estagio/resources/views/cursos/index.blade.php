<!DOCTYPE html>
<html>
<head>
    <title>Cursos</title>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
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