<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.dashboard');
        }

        return view('trainer.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $trainer = Trainer::where('email', $validated['email'])->first();
        if (! $trainer || ! $trainer->is_active || ! $trainer->password || ! Hash::check($validated['password'], $trainer->password)) {
            return back()->withErrors([
                'email' => 'These trainer credentials are invalid or the account is inactive.',
            ])->onlyInput('email');
        }

        Auth::guard('web')->logout();
        Auth::guard('trainer')->login($trainer, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('trainer.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('trainer')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('trainer.login');
    }
}
