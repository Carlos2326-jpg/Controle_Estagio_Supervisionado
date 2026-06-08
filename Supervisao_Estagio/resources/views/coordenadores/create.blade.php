<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Novo Coordenador</title>
</head>

<body>

    <h1>Novo Coordenador</h1>

    <form method="POST" action="/coordenadores">
        @csrf

        <div class="grupo">
            <label>Nome</label>
            <input type="text" name="nome" required>
        </div>

        <div class="grupo">
            <label>E-mail</label>
            <input type="email" name="email" required>
        </div>

        <div class="grupo">
            <label>Curso ID</label>
            <input type="number" name="curso_id">
        </div>
        <label>Instituição</label>

        <select name="instituicao_id" class="form-control">
            @foreach ($instituicoes as $instituicao)
                <option value="{{ $instituicao->id }}">
                    {{ $instituicao->nome }}
                </option>
            @endforeach
        </select>

        <div class="rodape">
            <button type="submit" class="btn btn-primario">
                Salvar
            </button>

            <a href="/coordenadores" class="btn btn-secundario">
                Cancelar
            </a>
        </div>
    </form>

</body>

</html>
