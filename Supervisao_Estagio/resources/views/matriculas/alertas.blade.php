<!DOCTYPE html>

<html>
<head>
    <title>Alertas Acadêmicos</title>

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

    .alerta {
        background-color: #fff3cd;
    }
</style>
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

@if(method_exists($alunos, 'links')) <br><br>
{{ $alunos->links() }}
@endif

</body>
</html>
