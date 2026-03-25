<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BountyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('bounties.index');
})->name('home');

Route::get('/bounties', [BountyController::class, 'index'])->name('bounties.index');
Route::get('/bounties/{bounty}', [BountyController::class, 'show'])->name('bounties.show');
Route::post('/bounties/{bounty}/save', [BountyController::class, 'save'])->name('bounties.save')->middleware('auth');
Route::delete('/bounties/{bounty}/unsave', [BountyController::class, 'unsave'])->name('bounties.unsave')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}', [AuthController::class, 'redirect'])->name('auth.redirect')->middleware('throttle:5,1');
    Route::get('/auth/{provider}/callback', [AuthController::class, 'callback'])->name('auth.callback')->middleware('throttle:5,1');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/my-bounties', [UserDashboardController::class, 'myBounties'])->name('dashboard.my-bounties');
    Route::get('/dashboard/my-submissions', [UserDashboardController::class, 'mySubmissions'])->name('dashboard.my-submissions');
    Route::get('/dashboard/saved-bounties', [UserDashboardController::class, 'savedBounties'])->name('dashboard.saved-bounties');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth'])->group(function () {
        Route::get('/bounties/create', [BountyController::class, 'create'])->name('bounties.create');
        Route::post('/bounties', [BountyController::class, 'store'])->name('bounties.store');
        Route::get('/bounties/{bounty}/edit', [BountyController::class, 'edit'])->name('bounties.edit');
        Route::put('/bounties/{bounty}', [BountyController::class, 'update'])->name('bounties.update');
        Route::delete('/bounties/{bounty}', [BountyController::class, 'destroy'])->name('bounties.destroy');

        Route::post('/bounties/{bounty}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::post('/submissions/{submission}/accept', [SubmissionController::class, 'accept'])->name('submissions.accept');
        Route::post('/submissions/{submission}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');
        Route::post('/submissions/{submission}/review', [SubmissionController::class, 'createReview'])->name('submissions.review');

        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
        Route::post('/wallet/checkout', [WalletController::class, 'createCheckoutSession'])->name('wallet.checkout');
        Route::get('/wallet/success', [WalletController::class, 'success'])->name('wallet.success');
        Route::get('/wallet/cancel', [WalletController::class, 'cancel'])->name('wallet.cancel');
    });
});

Route::post('/webhook/stripe', [WalletController::class, 'webhook'])->name('webhook.stripe')->withoutMiddleware(['csrf']);

require __DIR__.'/auth.php';
