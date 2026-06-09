<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Relatórios - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Gerar Relatórios</h1>
                <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">← Voltar</a>
            </div>

            <form method="GET" action="{{ route('coordenadores.relatorios.gerar', $coordenador) }}">
                <div class="form-group">
                    <label>Tipo de Relatório</label>
                    <select name="tipo" class="form-control" required>
                        <option value="">Selecione...</option>
                        <option value="alunos">Alunos</option>
                        <option value="contratos">Contratos</option>
                        <option value="horas">Horas de Estágio</option>
                        <option value="avaliacoes">Avaliações</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Formato</label>
                    <select name="formato" class="form-control">
                        <option value="html">Visualizar</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Período (opcional)</label>
                    <div class="linha">
                        <input type="date" name="data_inicio" placeholder="Data inicial">
                        <input type="date" name="data_fim" placeholder="Data final">
                    </div>
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Gerar Relatório</button>
                    <a href="{{ route('coordenadores.dashboard', $coordenador) }}" class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>