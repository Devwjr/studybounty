<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedBounty extends Model
{
    protected $table = 'saved_bounties';

    protected $fillable = [
        'user_id',
        'bounty_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bounty(): BelongsTo
    {
        return $this->belongsTo(Bounty::class);
    }
}
