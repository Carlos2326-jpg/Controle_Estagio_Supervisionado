<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Alunos</title>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            background: #6c757d;
            color: white;
            text-decoration: none;
        }
    </style>
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

    @foreach($dados['dados'] as $aluno)
    <tr>
        <td>{{ $aluno->user->name ?? '-' }}</td>
        <td>{{ $aluno->matricula }}</td>
        <td>{{ $aluno->situacao_estagio }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>