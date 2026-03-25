@extends('layouts.app')

@section('title', 'Depositar - StudyBounty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Depositar Funds</h1>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('wallet.checkout') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Valor do Depósito ($)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', 10) }}" required min="1" max="10000" step="0.01"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('amount') border-red-500 @enderror">
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Selecione o valor desejado:</p>
                    <div class="flex gap-2 mt-2">
                        @foreach([10, 25, 50, 100] as $preset)
                            <button type="button" onclick="document.getElementById('amount').value = {{ $preset }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                ${{ $preset }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Pagamento Seguro via Stripe</p>
                            <p class="text-sm text-gray-500">Seus dados estão protegidos</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Continuar para Pagamento
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('wallet.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                    ← Voltar para Carteira
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
