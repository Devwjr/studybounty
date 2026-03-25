<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'is_admin',
        'provider',
        'provider_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    public function isOAuth(): bool
    {
        return $this->provider !== null;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->is_admin === 1;
    }

    public function bounties(): HasMany
    {
        return $this->hasMany(Bounty::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function savedBounties(): HasMany
    {
        return $this->hasMany(SavedBounty::class);
    }

    public function hasSavedBounty(Bounty $bounty): bool
    {
        return $this->savedBounties()->where('bounty_id', $bounty->id)->exists();
    }
}
