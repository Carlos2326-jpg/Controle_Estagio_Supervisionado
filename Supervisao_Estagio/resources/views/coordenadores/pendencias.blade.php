<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendências - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Pendências do Sistema</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador ?? 1) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            @forelse($pendencias as $pendencia)
                <div class="alert-warning" style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <span>⚠️ {{ $pendencia }}</span>
                    <button class="btn btn-primario btn-sm" onclick="marcarResolvido(this)">Marcar como resolvido</button>
                </div>
            @empty
                <div class="alert-success">
                    ✅ Nenhuma pendência no momento. Tudo está em ordem!
                </div>
            @endforelse

            <div class="rodape">
                <button class="btn btn-secundario" onclick="window.location.reload()">🔄 Atualizar</button>
            </div>
        </div>
    </div>

    <script>
        function marcarResolvido(button) {
            button.parentElement.remove();
            if (document.querySelectorAll('.alert-warning').length === 0) {
                const container = document.querySelector('.card');
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert-success';
                alertDiv.innerHTML = '✅ Nenhuma pendência no momento. Tudo está em ordem!';
                container.insertBefore(alertDiv, document.querySelector('.rodape'));
            }
        }
    </script>
</body>
</html>