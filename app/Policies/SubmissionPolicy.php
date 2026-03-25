<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Submission $submission): bool
    {
        return $user->id === $submission->bounty->user_id;
    }

    public function accept(User $user, Submission $submission): bool
    {
        return $user->id === $submission->bounty->user_id;
    }

    public function reject(User $user, Submission $submission): bool
    {
        return $user->id === $submission->bounty->user_id;
    }
}
