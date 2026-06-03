@foreach($dados['dados'] as $item)

<tr>
    <td>{{ $item['aluno'] }}</td>
    <td>{{ $item['matricula'] }}</td>
    <td>{{ $item['empresa'] }}</td>
    <td>{{ $item['horas_previstas'] }}</td>
    <td>{{ $item['horas_cumpridas'] }}</td>
    <td>{{ $item['percentual'] }}</td>
</tr>

@endforeach