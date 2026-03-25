@extends('layouts.app')

@section('title', $bounty->title . ' - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($bounty->status === 'OPEN') bg-green-100 text-green-800
                            @elseif($bounty->status === 'IN_PROGRESS') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $bounty->status === 'OPEN' ? 'Aberto' : ($bounty->status === 'IN_PROGRESS' ? 'Em Progresso' : $bounty->status) }}
                        </span>
                    </div>
                    <span class="text-3xl font-bold text-green-600">${{ number_format($bounty->price, 2) }}</span>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $bounty->title }}</h1>
                
                <div class="flex items-center text-gray-500 text-sm mb-4">
                    <span>Por {{ $bounty->user->name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $bounty->created_at->diffForHumans() }}</span>
                </div>

                <div class="flex gap-4 text-sm">
                    <span class="bg-gray-100 px-3 py-1 rounded">{{ $bounty->subject }}</span>
                    <span class="text-gray-600">Prazo: {{ $bounty->deadline->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="p-6">
                <h2 class="text-lg font-semibold mb-3">Descrição</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $bounty->description }}</p>

                @if($bounty->attachment_url)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-3">Anexo</h2>
                        <a href="{{ $bounty->attachment_url }}" target="_blank" class="text-blue-600 hover:underline">
                            Ver arquivo anexo →
                        </a>
                    </div>
                @endif

                @auth
                    @if($bounty->user_id === auth()->id())
                        <div class="mt-6 flex gap-4">
                            @if($bounty->status === 'OPEN')
                                <a href="{{ route('bounties.edit', $bounty) }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                                    Editar
                                </a>
                            @endif
                            <form action="{{ route('bounties.destroy', $bounty) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar este bounty?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                    Cancelar Bounty
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="p-6 bg-gray-50 border-t">
                @auth
                    @if($bounty->status === 'OPEN' && $bounty->user_id !== auth()->id() && !$userSubmission)
                        <h2 class="text-lg font-semibold mb-4">Fazer Submissão</h2>
                        <form action="{{ route('submissions.store', $bounty) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Sua resposta</label>
                                <textarea name="content" id="content" rows="6" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Descreva sua solução..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="attachment_url" class="block text-sm font-medium text-gray-700 mb-1">Link para arquivo (opcional)</label>
                                <input type="url" name="attachment_url" id="attachment_url"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="https://...">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                Enviar Submissão
                            </button>
                        </form>
                    @elseif($userSubmission)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-2">Sua Submissão</h3>
                            <p class="text-sm text-gray-600 mb-2">Status: 
                                <span class="font-semibold @if($userSubmission->status === 'ACCEPTED') text-green-600 @elseif($userSubmission->status === 'REJECTED') text-red-600 @else text-yellow-600 @endif">
                                    {{ $userSubmission->status === 'PENDING' ? 'Pendente' : ($userSubmission->status === 'ACCEPTED' ? 'Aceita' : 'Rejeitada') }}
                                </span>
                            </p>
                        </div>
                    @elseif($bounty->status !== 'OPEN')
                        <p class="text-gray-500 text-center">Este bounty não está mais aberto para submissões.</p>
                    @else
                        <p class="text-gray-500 text-center">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Faça login</a> para fazer uma submissão.
                        </p>
                    @endif
                @else
                    <p class="text-gray-500 text-center">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Faça login</a> para fazer uma submissão.
                    </p>
                @endauth
            </div>

            @if($bounty->submissions->count() > 0 && ($bounty->user_id === auth()->id() || auth()->check()))
                <div class="p-6 border-t">
                    <h2 class="text-lg font-semibold mb-4">
                        Submissões ({{ $bounty->submissions->count() }})
                    </h2>
                    
                    @if(auth()->check() && $bounty->user_id === auth()->id())
                        @foreach($bounty->submissions as $submission)
                            <div class="border rounded-lg p-4 mb-4 @if($submission->status === 'ACCEPTED') border-green-300 bg-green-50 @elseif($submission->status === 'REJECTED') border-red-300 bg-red-50 @else bg-white @endif">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-semibold">{{ $submission->user->name }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $submission->created_at->diffForHumans() }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        @if($submission->status === 'ACCEPTED') bg-green-100 text-green-800
                                        @elseif($submission->status === 'REJECTED') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $submission->status === 'PENDING' ? 'Pendente' : ($submission->status === 'ACCEPTED' ? 'Aceita' : 'Rejeitada') }}
                                    </span>
                                </div>
                                <p class="text-gray-700 mb-3 whitespace-pre-wrap">{{ $submission->content }}</p>
                                
                                @if($submission->attachment_url)
                                    <p class="text-sm mb-3">
                                        <a href="{{ $submission->attachment_url }}" target="_blank" class="text-blue-600 hover:underline">
                                            Ver arquivo anexo →
                                        </a>
                                    </p>
                                @endif

                                @if($bounty->user_id === auth()->id() && $submission->status === 'PENDING')
                                    <div class="flex gap-2 mt-4">
                                        <form action="{{ route('submissions.accept', $submission) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded text-sm hover:bg-green-700">
                                                Aceitar
                                            </button>
                                        </form>
                                        <form action="{{ route('submissions.reject', $submission) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded text-sm hover:bg-red-700">
                                                Rejeitar
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Submissões são visíveis apenas para o criador do bounty.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
