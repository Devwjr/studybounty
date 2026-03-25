<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()
            ->walletTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wallet.index', compact('transactions'));
    }

    public function deposit(StripeService $stripeService)
    {
        return view('wallet.deposit');
    }

    public function createCheckoutSession(Request $request, StripeService $stripeService)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
        ]);

        $session = $stripeService->createCheckoutSession(
            Auth::user(),
            $validated['amount'],
            'Depósito na wallet'
        );

        return redirect()->away(config('services.stripe.url').$session);
    }

    public function success(Request $request, StripeService $stripeService)
    {
        return redirect()->route('wallet.index')
            ->with('success', 'Depósito realizado com sucesso!');
    }

    public function cancel()
    {
        return redirect()->route('wallet.index')
            ->with('error', 'Depósito cancelado.');
    }

    public function webhook(Request $request, StripeService $stripeService)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        $stripeService->handleWebhook($payload, $signature);

        return response()->json(['received' => true]);
    }
}
