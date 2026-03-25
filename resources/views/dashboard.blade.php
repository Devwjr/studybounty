<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Saldo</div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format(Auth::user()->balance, 2) }}</div>
                    <a href="{{ route('wallet.index') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Ver carteira →</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Meus Bounties</div>
                    <div class="text-3xl font-bold text-gray-900">{{ Auth::user()->bounties()->count() }}</div>
                    <a href="{{ route('dashboard.my-bounties') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Ver todos →</a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Minhas Submissões</div>
                    <div class="text-3xl font-bold text-gray-900">{{ Auth::user()->submissions()->count() }}</div>
                    <a href="{{ route('dashboard.my-submissions') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Ver todas →</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('bounties.create') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <div>
                            <div class="font-semibold text-gray-900">Criar Bounty</div>
                            <div class="text-sm text-gray-500">Ofereça uma recompensa por ajuda</div>
                        </div>
                    </a>
                    <a href="{{ route('bounties.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <div>
                            <div class="font-semibold text-gray-900">Explorar Bounties</div>
                            <div class="text-sm text-gray-500">Encontre tarefas para ganhar</div>
                        </div>
                    </a>
                    <a href="{{ route('wallet.deposit') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <div>
                            <div class="font-semibold text-gray-900">Depositar Funds</div>
                            <div class="text-sm text-gray-500">Adicione saldo à carteira</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Bounties Recentes</h3>
                </div>
                <div class="p-6">
                    @php
                        $recentBounties = \App\Models\Bounty::with('user')
                            ->where('status', '!=', 'CANCELLED')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($recentBounties->isEmpty())
                        <p class="text-gray-500 text-center py-4">Nenhum bounty disponível no momento.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentBounties as $bounty)
                                <a href="{{ route('bounties.show', $bounty) }}" class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $bounty->title }}</div>
                                        <div class="text-sm text-gray-500">Por {{ $bounty->user->name }} • {{ $bounty->subject }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600">${{ number_format($bounty->price, 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ $bounty->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
