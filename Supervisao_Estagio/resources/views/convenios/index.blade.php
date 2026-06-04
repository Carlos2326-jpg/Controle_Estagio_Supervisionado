<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convênios — {{ $empresa->razao_social }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-ativo  { background: #dcfce7; color: #16a34a; }
        .badge-inativo { background: #fee2e2; color: #dc2626; }
        .badge-vencido { background: #fef3c7; color: #92400e; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 14px; }
        .aviso { background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; margin-left: 6px; }
    </style>
</head>
<body>

@if(session('sucesso'))
    <div class="alerta-sucesso">{{ session('sucesso') }}</div>
@endif

<div class="topo">
    <div>
        <h1>Convênios</h1>
        <p class="subtitulo">{{ $empresa->razao_social }}</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('empresas.convenios.create', $empresa) }}" class="btn btn-primario">+ Novo Convênio</a>
        <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
    </div>
</div>

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
                @if($convenio->estaVencendo())
                    <span class="aviso">Vence em {{ $convenio->dias_para_vencimento }}d</span>
                @endif
            </td>
            <td>
                @php $s = $convenio->status @endphp
                <span class="badge {{ $s === 'ativo' ? 'badge-ativo' : ($s === 'vencido' ? 'badge-vencido' : 'badge-inativo') }}">
                    {{ ucfirst($s) }}
                </span>
            </td>
            <td>{{ Str::limit($convenio->observacoes, 40) ?? '—' }}</td>
            <td>
                <a href="{{ route('empresas.convenios.edit', [$empresa, $convenio]) }}" class="btn btn-secundario">Editar</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#888;">Nenhum convênio cadastrado.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">{{ $convenios->links() }}</div>

</body>
</html>
