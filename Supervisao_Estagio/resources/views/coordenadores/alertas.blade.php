<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Alertas do Sistema</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            @forelse($alertas as $alerta)
                <div class="alert-{{ $alerta->tipo ?? 'warning' }}" style="margin-bottom: 15px;">
                    <strong>{{ $alerta->title }}</strong>
                    <p>{{ $alerta->message }}</p>
                </div>
            @empty
                <div class="alert-success">
                    ✅ Nenhum alerta no momento. Tudo está em ordem!
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>