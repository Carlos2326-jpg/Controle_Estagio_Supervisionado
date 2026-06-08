<h1>Alertas</h1>

@foreach($alertas as $alerta)

<div>
    <strong>{{ $alerta->title }}</strong>
    <p>{{ $alerta->message }}</p>
</div>

@endforeach