<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instituições</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .ativo { color: green; font-weight: bold; }
        .inativo { color: red; font-weight: bold; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="topo">
        <h1>Instituições</h1>
        <a href="{{ route('instituicoes.create') }}" class="btn btn-primary">+ Nova Instituição</a>
    </div>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sigla</th>
                <th>CNPJ</th>
                <th>Cidade/UF</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($instituicoes as $instituicao)
            <tr>
                <td>{{ $instituicao->nome_instituicao }}</td>
                <td>{{ $instituicao->sigla }}</td>
                <td>{{ $instituicao->cnpj }}</td>
                <td>{{ $instituicao->cidade }}/{{ $instituicao->estado }}</td>
                <td>
                    @if($instituicao->ativa)
                        <span class="ativo">Ativa</span>
                    @else
                        <span class="inativo">Inativa</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('instituicoes.edit', $instituicao->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('instituicoes.toggleAtiva', $instituicao->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            {{ $instituicao->ativa ? 'Desativar' : 'Ativar' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhuma instituição cadastrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $instituicoes->links() }}
    </div>

</body>
</html>