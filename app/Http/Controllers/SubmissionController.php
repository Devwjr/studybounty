<?php

namespace App\Http\Controllers;

use App\Models\Bounty;
use App\Models\Submission;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SubmissionController extends Controller
{
    public function store(Request $request, Bounty $bounty)
    {
        Gate::authorize('create', Submission::class);

        if ($bounty->status !== 'OPEN') {
            return back()->with('error', 'Este bounty não está mais aberto para submissões.');
        }

        if ($bounty->user_id === Auth::id()) {
            return back()->with('error', 'Você não pode fazer uma submissão no seu próprio bounty.');
        }

        $existingSubmission = Submission::where('bounty_id', $bounty->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingSubmission) {
            return back()->with('error', 'Você já fez uma submissão para este bounty.');
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'attachment_url' => 'nullable|url',
        ]);

        Submission::create([
            'content' => $validated['content'],
            'attachment_url' => $validated['attachment_url'] ?? null,
            'bounty_id' => $bounty->id,
            'user_id' => Auth::id(),
            'status' => 'PENDING',
            'price' => $bounty->price,
        ]);

        $bounty->update(['status' => 'IN_PROGRESS']);

        return redirect()->route('bounties.show', $bounty)
            ->with('success', 'Submissão enviada com sucesso!');
    }

    public function accept(Submission $submission)
    {
        Gate::authorize('accept', $submission);

        if ($submission->status !== 'PENDING') {
            return back()->with('error', 'Esta submissão já foi processada.');
        }

        DB::transaction(function () use ($submission) {
            $bountyOwner = $submission->bounty->user;

            if ($bountyOwner->balance < $submission->price) {
                throw new \Exception('Saldo insuficiente para completar a transação.');
            }

            $bountyOwner->decrement('balance', $submission->price);

            WalletTransaction::create([
                'user_id' => $bountyOwner->id,
                'type' => 'PAYMENT',
                'amount' => -$submission->price,
                'description' => 'Pagamento pela submissão no bounty: '.$submission->bounty->title,
            ]);

            $submission->user->increment('balance', $submission->price);

            WalletTransaction::create([
                'user_id' => $submission->user_id,
                'type' => 'EARNING',
                'amount' => $submission->price,
                'description' => 'Ganho pela submissão aceita no bounty: '.$submission->bounty->title,
            ]);

            $submission->update(['status' => 'ACCEPTED']);
            $submission->bounty->update(['status' => 'COMPLETED']);
        });

        return redirect()->route('bounties.show', $submission->bounty)
            ->with('success', 'Submissão aceita! O pagamento foi processado.');
    }

    public function reject(Submission $submission)
    {
        Gate::authorize('reject', $submission);

        if ($submission->status !== 'PENDING') {
            return back()->with('error', 'Esta submissão já foi processada.');
        }

        $submission->update(['status' => 'REJECTED']);

        $pendingSubmissions = Submission::where('bounty_id', $submission->bounty_id)
            ->where('status', 'PENDING')
            ->count();

        if ($pendingSubmissions === 0) {
            $submission->bounty->update(['status' => 'OPEN']);
        }

        return redirect()->route('bounties.show', $submission->bounty)
            ->with('success', 'Submissão rejeitada.');
    }

    public function createReview(Request $request, Submission $submission)
    {
        Gate::authorize('accept', $submission);

        if ($submission->status !== 'ACCEPTED') {
            return back()->with('error', 'Você só pode avaliar submissões aceitas.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $submission->review()->create([
            'user_id' => $submission->user_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('bounties.show', $submission->bounty)
            ->with('success', 'Avaliação enviada com sucesso!');
    }
}
