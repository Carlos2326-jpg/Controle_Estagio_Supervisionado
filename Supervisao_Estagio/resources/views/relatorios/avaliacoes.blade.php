@foreach($dados['dados'] as $avaliacao)

<tr>
    <td>{{ $avaliacao->aluno->user->name ?? '-' }}</td>
    <td>{{ $avaliacao->tipo }}</td>
    <td>{{ $avaliacao->conceito }}</td>
    <td>{{ $avaliacao->data_avaliacao }}</td>
</tr>

@endforeach