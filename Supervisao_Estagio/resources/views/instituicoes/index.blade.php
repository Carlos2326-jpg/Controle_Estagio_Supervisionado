<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituições - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Instituições de Ensino</h1>
                <a href="{{ route('instituicoes.create') }}" class="btn btn-primario">+ Nova Instituição</a>
            </div>

            @if (session('success'))
                <div class="alerta-sucesso">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
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
                                    @if ($instituicao->ativa)
                                        <span class="badge badge-ativo">Ativa</span>
                                    @else
                                        <span class="badge badge-inativo">Inativa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ route('instituicoes.edit', $instituicao->id) }}"
                                            class="btn btn-primario">Editar</a>
                                        <form action="{{ route('instituicoes.toggleAtiva', $instituicao->id) }}"
                                            method="POST" style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn {{ $instituicao->ativa ? 'btn-perigo' : 'btn-sucesso' }}">
                                                {{ $instituicao->ativa ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhuma instituição cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $instituicoes->links() }}
            </div>
        </div>
    </div>
</body>

</html>
