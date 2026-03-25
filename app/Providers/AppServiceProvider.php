<?php

namespace App\Providers;

use App\Models\Bounty;
use App\Models\Submission;
use App\Models\WalletTransaction;
use App\Policies\BountyPolicy;
use App\Policies\SubmissionPolicy;
use App\Policies\WalletTransactionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Bounty::class, BountyPolicy::class);
        Gate::policy(Submission::class, SubmissionPolicy::class);
        Gate::policy(WalletTransaction::class, WalletTransactionPolicy::class);
    }
}
