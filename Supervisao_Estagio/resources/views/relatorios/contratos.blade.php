@foreach($dados['dados'] as $contrato)

<tr>
    <td>{{ $contrato->aluno->user->name ?? '-' }}</td>
    <td>{{ $contrato->empresa->razao_social ?? '-' }}</td>
    <td>{{ $contrato->data_inicio_prevista }}</td>
    <td>{{ $contrato->data_fim_prevista }}</td>
</tr>

@endforeach