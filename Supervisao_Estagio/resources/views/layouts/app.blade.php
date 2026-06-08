<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    <nav class="bg-white shadow px-6 py-4 flex items-center justify-between">
        <span class="font-bold text-lg text-gray-800">Gestão de Estágios</span>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">Sair</button>
                </form>
            @endauth
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if(session('sucesso'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('sucesso') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
