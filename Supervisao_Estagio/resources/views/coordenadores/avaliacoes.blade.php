<h1>Avaliações</h1>

<a href="/coordenadores/{{ $coordenador->id }}/avaliacoes/nova">Nova Avaliação</a>

<table>

<tr>
    <th>Aluno</th>
    <th>Tipo</th>
    <th>Conceito</th>
    <th>Data</th>
</tr>

@foreach($avaliacoes as $avaliacao)

<tr>
    <td>{{ $avaliacao->aluno->nome }}</td>
    <td>{{ $avaliacao->tipo }}</td>
    <td>{{ $avaliacao->conceito }}</td>
    <td>{{ $avaliacao->data_avaliacao }}</td>
</tr>

@endforeach

</table>