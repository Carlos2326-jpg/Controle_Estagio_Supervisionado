<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Alunos</title>
</head>

<body>

    <h1>{{ $dados['titulo'] }}</h1>

    <p>
        Gerado em:
        {{ \Carbon\Carbon::parse($dados['gerado_em'])->format('d/m/Y H:i') }}
    </p>

    <table>
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Situação</th>
        </tr>

        @foreach ($dados['dados'] as $aluno)
            <tr>
                <td>{{ $aluno->user->name ?? '-' }}</td>
                <td>{{ $aluno->matricula }}</td>
                <td>{{ $aluno->situacao_estagio }}</td>
            </tr>
        @endforeach

    </table>

</body>

</html>
