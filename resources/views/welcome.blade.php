<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StudyBounty - Ganhe recompensas ajudando outros nos estudos</title>
        <meta name="description" content="Plataforma de recompensas por estudos. Crie bounties para receber ajuda ou ganhe dinheiro resolvendo dúvidas de outros estudantes.">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
        <div class="min-h-screen flex flex-col">
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center">
                            <a href="{{ route('bounties.index') }}" class="text-2xl font-bold text-blue-600">
                                StudyBounty
                            </a>
                        </div>
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                                <a href="{{ route('wallet.index') }}" class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                                    ${{ number_format(Auth::user()->balance, 2) }}
                                </a>
                                <a href="{{ route('bounties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Criar Bounty
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Registrar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="text-center mb-16">
                        <h1 class="text-5xl font-bold text-gray-900 mb-6">
                            Ganhe recompensas<br>ajudando nos estudos
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                            StudyBounty é uma plataforma onde você pode criar bounties para receber ajuda 
                            com seus estudos ou ganhar dinheiro resolvendo dúvidas de outros estudantes.
                        </p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('bounties.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                                Ver Bounties
                            </a>
                            @guest
                                <a href="{{ route('register') }}" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-50 transition">
                                    Começar Agora
                                </a>
                            @endguest
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Ganhe Dinheiro</h3>
                            <p class="text-gray-600">Resolva dúvidas de outros estudantes e receba recompensas pelos seus conhecimentos.</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Receba Ajuda</h3>
                            <p class="text-gray-600">Crie bounties para suas dúvidas e receba ajuda de outros estudantes.</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Comunidade</h3>
                            <p class="text-gray-600">Faça parte de uma comunidade colaborativa de estudantes e educadores.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Bounties Recentes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $recentBounties = \App\Models\Bounty::with('user')
                                    ->where('status', '!=', 'CANCELLED')
                                    ->orderBy('created_at', 'desc')
                                    ->take(6)
                                    ->get();
                            @endphp
                            
                            @forelse($recentBounties as $bounty)
                                <a href="{{ route('bounties.show', $bounty) }}" class="border rounded-lg p-4 hover:shadow-md transition block">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $bounty->status === 'OPEN' ? 'Aberto' : 'Em Progresso' }}
                                        </span>
                                        <span class="text-xl font-bold text-green-600">${{ number_format($bounty->price, 2) }}</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $bounty->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2 line-clamp-2">{{ $bounty->description }}</p>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $bounty->subject }}</span>
                                        <span>Por {{ $bounty->user->name }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-gray-500">
                                    Nenhum bounty disponível ainda. <a href="{{ route('bounties.create') }}" class="text-blue-600 hover:underline">Seja o primeiro a criar!</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>

            <footer class="bg-white border-t mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="flex justify-between items-center">
                        <div class="text-gray-500">
                            &copy; {{ date('Y') }} StudyBounty. Todos os direitos reservados.
                        </div>
                        <div class="flex gap-4 text-gray-500">
                            <a href="#" class="hover:text-gray-900">Termos</a>
                            <a href="#" class="hover:text-gray-900">Privacidade</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
