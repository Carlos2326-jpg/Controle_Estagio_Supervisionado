<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Avaliação - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Registrar Avaliação</h1>
                <a href="{{ route('coordenadores.avaliacoes', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="POST" action="{{ route('coordenadores.avaliacoes.store', [$coordenador, $solicitacao]) }}">
                @csrf

                <div class="alert-info">
                    <strong>Aluno:</strong> {{ $solicitacao->aluno->user->name ?? $solicitacao->aluno->nome ?? '-' }}<br>
                    <strong>Empresa:</strong> {{ $solicitacao->empresa->razao_social ?? '-' }}
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Tipo de Avaliação</label>
                        <select name="tipo" class="form-control" required>
                            <option value="parcial">Parcial</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nota (0 a 10)</label>
                        <input type="number" name="nota" step="0.1" min="0" max="10" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Parecer</label>
                    <textarea name="parecer" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label>Data da Avaliação</label>
                    <input type="date" name="data_avaliacao" value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Salvar Avaliação</button>
                    <a href="{{ route('coordenadores.avaliacoes', $coordenador) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>