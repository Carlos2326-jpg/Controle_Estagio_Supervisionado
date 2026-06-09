<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Avaliação - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Nova Avaliação de Estagiário</h1>
                <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}"
                    class="btn btn-secundario">← Voltar</a>
            </div>

            <div class="alert-info">
                <strong>Supervisor:</strong> {{ $supervisor->nome }} — {{ $supervisor->cargo }}<br>
                <strong>Estagiário:</strong> {{ $solicitacao->aluno->user->name ?? 'Não identificado' }}<br>
                <span>Estágio: {{ $solicitacao->data_inicio_prevista?->format('d/m/Y') }} até
                    {{ $solicitacao->data_fim_prevista?->format('d/m/Y') }}</span>
            </div>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                action="{{ route('empresas.supervisores.avaliacoes.store', [$empresa, $supervisor, $solicitacao]) }}">
                @csrf

                <div class="secao">Critérios (nota de 0 a 10)</div>

                <div class="linha">
                    <div class="form-group">
                        <label>Pontualidade</label>
                        <input type="number" name="pontualidade" value="{{ old('pontualidade') }}" min="0"
                            max="10" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>Proatividade</label>
                        <input type="number" name="proatividade" value="{{ old('proatividade') }}" min="0"
                            max="10" step="0.1">
                    </div>
                </div>

                <div class="linha">
                    <div class="form-group">
                        <label>Qualidade do Trabalho</label>
                        <input type="number" name="qualidade_trabalho" value="{{ old('qualidade_trabalho') }}"
                            min="0" max="10" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>Relacionamento Interpessoal</label>
                        <input type="number" name="relacionamento" value="{{ old('relacionamento') }}" min="0"
                            max="10" step="0.1">
                    </div>
                </div>

                <p class="dica">A nota geral será calculada automaticamente como média dos critérios preenchidos.</p>

                <div class="secao">Informações Adicionais</div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="observacoes">{{ old('observacoes') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Data da Avaliação *</label>
                    <input type="date" name="data_avaliacao"
                        value="{{ old('data_avaliacao', now()->format('Y-m-d')) }}" required>
                    @error('data_avaliacao')
                        <span class="erro">{{ $message }}</span>
                    @enderror
                </div>

                <div class="rodape">
                    <button type="submit" class="btn btn-primario">Registrar Avaliação</button>
                    <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}"
                        class="btn btn-secundario">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
