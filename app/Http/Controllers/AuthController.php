<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected array $allowedProviders = ['google', 'microsoft', 'github'];

    public function redirect($provider)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            return redirect('/login')->with('error', 'Provedor inválido.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (! in_array($provider, $this->allowedProviders)) {
            return redirect('/login')->with('error', 'Provedor inválido.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Falha ao autenticar com '.ucfirst($provider).'. Tente novamente.');
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            return redirect('/login')->with('error', 'Não foi possível obter seu email. Configure as permissões do OAuth.');
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->provider && $user->provider !== $provider) {
                return redirect('/login')->with('error', 'Este email já está vinculado a outra conta.');
            }

            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuário',
                'email' => $email,
                'password' => Hash::make(\Str::random(32)),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        $request = request();
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
