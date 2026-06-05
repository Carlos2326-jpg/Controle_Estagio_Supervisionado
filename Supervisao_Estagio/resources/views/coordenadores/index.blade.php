<h1>Coordenadores</h1>

<a href="/coordenadores/criar">
    Novo Coordenador
</a>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Curso</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>

    @foreach($coordenadores as $coordenador)
        <tr>
            <td>{{ $coordenador->nome }}</td>
            <td>{{ $coordenador->email }}</td>
            <td>{{ $coordenador->curso->nome ?? '-' }}</td>
            <td>{{ $coordenador->status }}</td>

            <td>
                <a href="/coordenadores/{{ $coordenador->id }}/editar">Editar</a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>