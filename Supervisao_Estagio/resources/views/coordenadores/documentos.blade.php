<h1>Documentos Pendentes</h1>

<table>

    <tr>
        <th>Aluno</th>
        <th>Documento</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>

    @foreach ($documentos as $documento)
        <tr>

            <td>{{ $documento->aluno->nome }}</td>

            <td>{{ $documento->tipo }}</td>

            <td>{{ $documento->status }}</td>

            <td>

                <a href="{{ $documento->arquivo }}">Visualizar</a>

                <form method="POST"
                    action="/coordenadores/{{ $coordenador->id }}/documentos/{{ $documento->id }}/aprovar">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Aprovar</button>
                </form>

                <form method="POST"
                    action="/coordenadores/{{ $coordenador->id }}/documentos/{{ $documento->id }}/reprovar">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="justificativa">
                    <button type="submit">Reprovar</button>
                </form>

            </td>

        </tr>
    @endforeach

</table>
