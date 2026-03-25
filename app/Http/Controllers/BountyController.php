<?php

namespace App\Http\Controllers;

use App\Models\Bounty;
use App\Models\SavedBounty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BountyController extends Controller
{
    public function index(Request $request)
    {
        $query = Bounty::with('user')->where('status', '!=', 'CANCELLED');

        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        $bounties = $query->orderBy('created_at', 'desc')->paginate(12);
        $subjects = Bounty::distinct()->pluck('subject')->filter()->sort()->values();

        $savedBountyIds = [];
        if (Auth::check()) {
            $savedBountyIds = Auth::user()->savedBounties()->pluck('bounty_id')->toArray();
        }

        return view('bounties.index', compact('bounties', 'subjects', 'savedBountyIds'));
    }

    public function show(Bounty $bounty)
    {
        $bounty->load(['user', 'submissions.user']);

        $userSubmission = Auth::check()
            ? $bounty->submissions()->where('user_id', Auth::id())->first()
            : null;

        return view('bounties.show', compact('bounty', 'userSubmission'));
    }

    public function create()
    {
        return view('bounties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:now',
            'attachment_url' => 'nullable|url',
        ]);

        $bounty = Bounty::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'OPEN',
        ]);

        return redirect()->route('bounties.show', $bounty)
            ->with('success', 'Bounty criado com sucesso!');
    }

    public function edit(Bounty $bounty)
    {
        Gate::authorize('update', $bounty);

        return view('bounties.edit', compact('bounty'));
    }

    public function update(Request $request, Bounty $bounty)
    {
        Gate::authorize('update', $bounty);

        if ($bounty->status !== 'OPEN') {
            return back()->with('error', 'Não é possível editar um bounty que não está aberto.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:now',
            'attachment_url' => 'nullable|url',
        ]);

        $bounty->update($validated);

        return redirect()->route('bounties.show', $bounty)
            ->with('success', 'Bounty atualizado com sucesso!');
    }

    public function destroy(Bounty $bounty)
    {
        Gate::authorize('delete', $bounty);

        if ($bounty->status !== 'OPEN') {
            return back()->with('error', 'Não é possível excluir um bounty que não está aberto.');
        }

        $bounty->update(['status' => 'CANCELLED']);

        return redirect()->route('dashboard')
            ->with('success', 'Bounty cancelado com sucesso!');
    }

    public function save(Request $request, Bounty $bounty)
    {
        $existingSave = SavedBounty::where('user_id', Auth::id())
            ->where('bounty_id', $bounty->id)
            ->first();

        if ($existingSave) {
            return back()->with('info', 'Bounty já está salvo.');
        }

        SavedBounty::create([
            'user_id' => Auth::id(),
            'bounty_id' => $bounty->id,
        ]);

        return back()->with('success', 'Bounty salvo para fazer depois!');
    }

    public function unsave(Request $request, Bounty $bounty)
    {
        SavedBounty::where('user_id', Auth::id())
            ->where('bounty_id', $bounty->id)
            ->delete();

        return back()->with('success', 'Bounty removido dos salvos.');
    }
}
