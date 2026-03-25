@extends('layouts.app')

@section('title', 'Bounties Salvos - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bounties Salvos</h1>
            <a href="{{ route('bounties.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Ver Todos os Bounties
            </a>
        </div>

        @if($savedBounties->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg mb-4">Você não tem nenhum bounty salvo.</p>
                <a href="{{ route('bounties.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Explorar Bounties
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($savedBounties as $saved)
                    @php $bounty = $saved->bounty; @endphp
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
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="bg-gray-100 px-2 py-1 rounded">{{ $bounty->subject }}</span>
                            <span>Prazo: {{ $bounty->deadline->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Por {{ $bounty->user->name }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('bounties.show', $bounty) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                    Ver Detalhes
                                </a>
                                <form action="{{ route('bounties.unsave', $bounty) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 text-sm">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $savedBounties->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
