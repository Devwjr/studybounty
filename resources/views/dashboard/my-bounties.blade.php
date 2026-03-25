@extends('layouts.app')

@section('title', 'Meus Bounties - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meus Bounties</h1>
            <a href="{{ route('bounties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Criar Novo Bounty
            </a>
        </div>

        @if($bounties->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg mb-4">Você ainda não criou nenhum bounty.</p>
                <a href="{{ route('bounties.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Criar Primeiro Bounty
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissões</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prazo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bounties as $bounty)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('bounties.show', $bounty) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $bounty->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($bounty->status === 'OPEN') bg-green-100 text-green-800
                                        @elseif($bounty->status === 'IN_PROGRESS') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $bounty->status === 'OPEN' ? 'Aberto' : ($bounty->status === 'IN_PROGRESS' ? 'Em Progresso' : ($bounty->status === 'COMPLETED' ? 'Concluído' : 'Cancelado')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    ${{ number_format($bounty->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $bounty->submissions_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $bounty->deadline->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('bounties.show', $bounty) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Ver
                                    </a>
                                    @if($bounty->status === 'OPEN')
                                        <a href="{{ route('bounties.edit', $bounty) }}" class="text-gray-600 hover:text-gray-900 mr-3">
                                            Editar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $bounties->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
