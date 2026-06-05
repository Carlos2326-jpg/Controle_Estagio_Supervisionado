<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Coordenador</title>
</head>
<body>

<h1>Painel do Coordenador</h1>

<div style="display:flex; gap:20px;">

    <div>
        <h3>Solicitações Pendentes</h3>
        <p>{{ $solicitacoesPendentes }}</p>
    </div>

    <div>
        <h3>Documentos Pendentes</h3>
        <p>{{ $documentosPendentes }}</p>
    </div>

    <div>
        <h3>Avaliações</h3>
        <p>{{ $avaliacoes }}</p>
    </div>

</div>

<hr>

<ul>
    <li><a href="/coordenadores/{{ $coordenador->id }}/solicitacoes">Solicitações</a></li>
    <li><a href="/coordenadores/{{ $coordenador->id }}/documentos">Documentos</a></li>
    <li><a href="/coordenadores/{{ $coordenador->id }}/atividades">Atividades</a></li>
    <li><a href="/coordenadores/{{ $coordenador->id }}/relatorios">Relatórios</a></li>
</ul>

</body>
</html>