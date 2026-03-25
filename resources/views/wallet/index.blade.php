@extends('layouts.app')

@section('title', 'Minha Carteira - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Minha Carteira</h1>
            <a href="{{ route('wallet.deposit') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Depositar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Saldo Atual</p>
                    <p class="text-4xl font-bold text-gray-900">${{ number_format(Auth::user()->balance, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-sm">Total Ganho</p>
                    <p class="text-2xl font-semibold text-green-600">
                        ${{ number_format(Auth::user()->walletTransactions()->where('type', 'EARNING')->sum('amount'), 2) }}
                    </p>
                </div>
            </div>
        </div>

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
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">Histórico de Transações</h2>
            </div>

            @if($transactions->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Nenhuma transação ainda.
                </div>
            @else
                <div class="divide-y">
                    @foreach($transactions as $transaction)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($transaction->type === 'DEPOSIT') bg-green-100 text-green-600
                                    @elseif($transaction->type === 'WITHDRAWAL') bg-red-100 text-red-600
                                    @elseif($transaction->type === 'PAYMENT') bg-red-100 text-red-600
                                    @else bg-blue-100 text-blue-600 @endif">
                                    @if($transaction->type === 'DEPOSIT')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    @elseif($transaction->type === 'WITHDRAWAL')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    @elseif($transaction->type === 'PAYMENT')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l-5 5m0-5l5 5"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $transaction->type === 'DEPOSIT' ? 'Depósito' : ($transaction->type === 'WITHDRAWAL' ? 'Saque' : ($transaction->type === 'PAYMENT' ? 'Pagamento' : 'Ganho')) }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $transaction->description ?? 'Transação' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->amount >= 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
