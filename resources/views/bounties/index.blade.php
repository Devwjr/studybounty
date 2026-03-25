@extends('layouts.app')

@section('title', 'Bounties - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bounties Disponíveis</h1>
            @auth
                <a href="{{ route('bounties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Criar Bounty
                </a>
            @endauth
        </div>

        <form method="GET" action="{{ route('bounties.index') }}" class="mb-6 bg-white p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Buscar por título ou descrição..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Matéria</label>
                    <select name="subject" id="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas as matérias</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                {{ $subject }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        @if($bounties->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg">Nenhum bounty encontrado.</p>
                @auth
                    <a href="{{ route('bounties.create') }}" class="mt-4 inline-block text-blue-600 hover:underline">
                        Seja o primeiro a criar um bounty!
                    </a>
                @endauth
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bounties as $bounty)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                @if($bounty->status === 'OPEN') bg-green-100 text-green-800
                                @elseif($bounty->status === 'IN_PROGRESS') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $bounty->status === 'OPEN' ? 'Aberto' : ($bounty->status === 'IN_PROGRESS' ? 'Em Progresso' : $bounty->status) }}
                            </span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($bounty->price, 2) }}</span>
                        </div>
                        <a href="{{ route('bounties.show', $bounty) }}" class="block">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $bounty->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $bounty->description }}</p>
                        </a>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span class="bg-gray-100 px-2 py-1 rounded">{{ $bounty->subject }}</span>
                            <span>Prazo: {{ $bounty->deadline->format('d/m/Y') }}</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-gray-500">Por {{ $bounty->user->name }}</span>
                            @auth
                                @if(in_array($bounty->id, $savedBountyIds))
                                    <form action="{{ route('bounties.unsave', $bounty) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-yellow-500 hover:text-yellow-600" title="Remover dos salvos">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('bounties.save', $bounty) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-gray-300 hover:text-yellow-500" title="Salvar para depois">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $bounties->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
