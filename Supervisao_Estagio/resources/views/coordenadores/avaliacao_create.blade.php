<h1>Registrar Avaliação</h1>

<form method="POST" action="/coordenadores/{{ $coordenador->id }}/avaliacoes/{{ $solicitacao->id }}">

    @csrf

    <select name="tipo">
        <option value="parcial">Parcial</option>
        <option value="final">Final</option>
    </select>

    <textarea name="parecer"></textarea>

    <input type="number" step="0.1" min="0" max="10" name="nota">

    <button type="submit">
        Salvar
    </button>

</form>
