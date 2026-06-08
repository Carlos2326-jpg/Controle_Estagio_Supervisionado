<!DOCTYPE html>

<html>

<head>
    <title>Histórico de Estágios</title>

    ```
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
