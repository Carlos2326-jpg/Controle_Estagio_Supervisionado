<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convênios — {{ $empresa->razao_social }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            @if (session('sucesso'))
                <div class="alerta-sucesso">{{ session('sucesso') }}</div>
            @endif

            <div class="topo">
                <div>
                    <h1>Convênios</h1>
                    <p class="subtitulo">{{ $empresa->razao_social }}</p>
                </div>
                <div class="acoes">
                    <a href="{{ route('empresas.convenios.create', $empresa) }}" class="btn btn-primario">+ Novo
                        Convênio</a>
                    <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Início</th>
                            <th>Fim</th>
                            <th>Status</th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($convenios as $convenio)
                            <tr>
                                <td>{{ $convenio->numero_convenio }}</td>
                                <td>{{ $convenio->data_inicio->format('d/m/Y') }}</td>
                                <td>
                                    {{ $convenio->data_fim->format('d/m/Y') }}
                                    @if ($convenio->estaVencendo())
                                        <span class="aviso">Vence em {{ $convenio->dias_para_vencimento }}d</span>
                                    @endif
                                </td>
                                <td>
                                    @php $s = $convenio->status @endphp
                                    <span
                                        class="badge {{ $s === 'ativo' ? 'badge-ativo' : ($s === 'vencido' ? 'badge-vencido' : 'badge-inativo') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($convenio->observacoes, 40) ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('empresas.convenios.edit', [$empresa, $convenio]) }}"
                                        class="btn btn-secundario">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhum convênio cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $convenios->links() }}
            </div>
        </div>
    </div>
</body>

</html>
