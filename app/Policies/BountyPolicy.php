<?php

namespace App\Policies;

use App\Models\Bounty;
use App\Models\User;

class BountyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Bounty $bounty): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Bounty $bounty): bool
    {
        return $user->id === $bounty->user_id;
    }

    public function delete(User $user, Bounty $bounty): bool
    {
        return $user->id === $bounty->user_id;
    }
}
