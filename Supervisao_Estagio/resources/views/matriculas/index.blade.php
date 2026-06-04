<!DOCTYPE html>

<html>
<head>
    <title>Matrículas do Curso</title>

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
</style>
```

</head>
<body>

<h1>Alunos Matriculados</h1>

<form method="GET">

```
<div class="form-group">
    <label>Buscar por Matrícula ou CPF</label>
    <input
        type="text"
        name="busca"
        value="{{ request('busca') }}">
</div>

<div class="form-group">
    <label>Situação do Estágio</label>

    <select name="situacao_estagio">
        <option value="">Todas</option>
        <option value="sem_estagio">Sem Estágio</option>
        <option value="em_andamento">Em Andamento</option>
        <option value="concluido">Concluído</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">
    Filtrar
</button>
```

</form>

<table>

```
<tr>
    <th>Nome</th>
    <th>Matrícula</th>
    <th>Período</th>
    <th>Situação</th>
    <th>Horas Cumpridas</th>
    <th>Ações</th>
</tr>

@forelse($alunos as $aluno)

<tr>
    <td>{{ $aluno->user->nome ?? '-' }}</td>
    <td>{{ $aluno->matricula }}</td>
    <td>{{ $aluno->periodo_atual }}</td>
    <td>{{ $aluno->situacao_label }}</td>
    <td>{{ $aluno->carga_horaria_cumprida }}</td>

    <td>
        <a
            class="btn btn-secondary"
            href="/cursos/{{ $curso->id }}/matriculas/{{ $aluno->id }}/historico">
            Histórico
        </a>
    </td>
</tr>

@empty

<tr>
    <td colspan="6">
        Nenhum aluno encontrado.
    </td>
</tr>

@endforelse
```

</table>

@if(method_exists($alunos, 'links'))
{{ $alunos->links() }}
@endif

</body>
</html>
