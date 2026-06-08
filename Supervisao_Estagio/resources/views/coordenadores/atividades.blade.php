<h1>Atividades de Estágio</h1>

<table>

    <tr>
        <th>Aluno</th>
        <th>Empresa</th>
        <th>Horas</th>
        <th>Última Atualização</th>
    </tr>

    @foreach ($atividades as $atividade)
        <tr>
            <td>{{ $atividade->aluno->nome }}</td>
            <td>{{ $atividade->empresa->razao_social }}</td>
            <td>{{ $atividade->horas }}</td>
            <td>{{ $atividade->updated_at }}</td>
        </tr>
    @endforeach

</table>
