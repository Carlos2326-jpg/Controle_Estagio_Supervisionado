<!DOCTYPE html>

<html>
<head>
    <title>Histórico de Estágios</title>

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

    .info {
        margin-bottom: 20px;
    }

    .info p {
        margin: 5px 0;
    }
</style>
```

</head>
<body>

<h1>Histórico de Estágios</h1>

<div class="info">
    <p><strong>Aluno:</strong> {{ $aluno->user->nome ?? '-' }}</p>
    <p><strong>Matrícula:</strong> {{ $aluno->matricula }}</p>
    <p><strong>Período Atual:</strong> {{ $aluno->periodo_atual }}</p>
    <p><strong>Situação:</strong> {{ $aluno->situacao_label }}</p>
</div>

<table>

```
<tr>
    <th>Empresa</th>
    <th>Data Solicitação</th>
    <th>Início Previsto</th>
    <th>Fim Previsto</th>
    <th>Carga Semanal</th>
    <th>Status</th>
</tr>

@forelse($aluno->solicitacoesEstagio as $solicitacao)

<tr>
    <td>{{ $solicitacao->empresa->nome_fantasia ?? '-' }}</td>
    <td>{{ $solicitacao->data_solicitacao }}</td>
    <td>{{ $solicitacao->data_inicio_prevista }}</td>
    <td>{{ $solicitacao->data_fim_prevista }}</td>
    <td>{{ $solicitacao->carga_horaria_semanal }}h</td>
    <td>{{ $solicitacao->status }}</td>
</tr>

@empty

<tr>
    <td colspan="6">
        Nenhum estágio encontrado.
    </td>
</tr>

@endforelse
```

</table>

<br>

<a href="javascript:history.back()" class="btn btn-secondary">
    Voltar
</a>

</body>
</html>
