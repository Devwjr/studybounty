<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function myBounties()
    {
        $bounties = Auth::user()
            ->bounties()
            ->withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.my-bounties', compact('bounties'));
    }

    public function mySubmissions()
    {
        $submissions = Auth::user()
            ->submissions()
            ->with('bounty')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.my-submissions', compact('submissions'));
    }

    public function savedBounties()
    {
        $savedBounties = Auth::user()
            ->savedBounties()
            ->with('bounty.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.saved-bounties', compact('savedBounties'));
    }
}
