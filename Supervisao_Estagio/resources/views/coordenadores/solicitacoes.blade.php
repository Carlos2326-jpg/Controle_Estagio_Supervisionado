<h1>Solicitações de Estágio</h1>

<table>

<thead>
<tr>
    <th>Aluno</th>
    <th>Empresa</th>
    <th>Status</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>

@foreach($solicitacoes as $solicitacao)

<tr>

<td>{{ $solicitacao->aluno->nome }}</td>

<td>{{ $solicitacao->empresa->razao_social }}</td>

<td>{{ $solicitacao->status }}</td>

<td>

<form method="POST"
      action="/coordenadores/{{ $coordenador->id }}/solicitacoes/{{ $solicitacao->id }}/aprovar">
    @csrf
    @method('PATCH')

    <button type="submit">
        Aprovar
    </button>
</form>

<form method="POST"
      action="/coordenadores/{{ $coordenador->id }}/solicitacoes/{{ $solicitacao->id }}/reprovar">
    @csrf
    @method('PATCH')

    <input type="text" name="justificativa">

    <button type="submit">
        Reprovar
    </button>
</form>

</td>

</tr>

@endforeach

</tbody>

</table>