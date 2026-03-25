<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalletTransaction;

class WalletTransactionPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, WalletTransaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }
}
