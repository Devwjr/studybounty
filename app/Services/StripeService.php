<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    protected string $secretKey;

    protected string $webhookSecret;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->webhookSecret = config('services.stripe.webhook_secret');
    }

    public function createCheckoutSession(User $user, float $amount, string $description): string
    {
        Stripe::setApiKey($this->secretKey);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Depósito na Wallet',
                            'description' => $description,
                        ],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('wallet.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('wallet.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'type' => 'deposit',
            ],
        ]);

        return $session->id;
    }

    public function handleWebhook(array $payload, string $signature): ?array
    {
        Stripe::setApiKey($this->secretKey);

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $this->webhookSecret
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                if ($session->metadata->type === 'deposit') {
                    $this->processDeposit($session);
                }

                return [
                    'type' => $event->type,
                    'data' => $session,
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function processDeposit($session): void
    {
        $userId = $session->metadata->user_id;
        $amount = $session->amount_total / 100;

        DB::transaction(function () use ($userId, $amount, $session) {
            $user = User::findOrFail($userId);
            $user->increment('balance', $amount);

            WalletTransaction::create([
                'user_id' => $userId,
                'type' => 'DEPOSIT',
                'amount' => $amount,
                'description' => 'Depósito via Stripe',
                'stripe_payment_id' => $session->payment_intent,
            ]);
        });
    }
}
