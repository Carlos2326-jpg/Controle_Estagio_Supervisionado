<!DOCTYPE html>

<html>

<head>
    <title>Alertas Acadêmicos</title>

    ```
    ```

</head>

<body>

    <h1>Alunos com Carga Horária Pendente</h1>

    <table>

        ```
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Período</th>
            <th>Situação</th>
            <th>Horas Cumpridas</th>
            <th>Horas Obrigatórias</th>
            <th>Horas Faltantes</th>
            <th>Percentual</th>
        </tr>

        @forelse($alunos as $aluno)
            <tr class="alerta">

                <td>{{ $aluno->user->nome ?? '-' }}</td>

                <td>{{ $aluno->matricula }}</td>

                <td>{{ $aluno->periodo_atual }}</td>

                <td>{{ $aluno->situacao_label }}</td>

                <td>{{ $aluno->carga_horaria_cumprida }}</td>

                <td>{{ $curso->carga_horaria_estagio }}</td>

                <td>
                    {{ $curso->carga_horaria_estagio - $aluno->carga_horaria_cumprida }}
                </td>

                <td>
                    {{ $aluno->percentual_horas }}%
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="8">
                    Nenhum aluno com pendência de carga horária encontrado.
                </td>
            </tr>
        @endforelse
        ```

    </table>

    <br>

    <a href="javascript:history.back()" class="btn btn-secondary">
        Voltar
    </a>

    @if (method_exists($alunos, 'links'))
        <br><br>
        {{ $alunos->links() }}
    @endif

</body>

</html>
