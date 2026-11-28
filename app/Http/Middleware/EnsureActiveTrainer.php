<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveTrainer
{
    public function handle(Request $request, Closure $next): Response
    {
        $trainer = Auth::guard('trainer')->user();

        if (! $trainer->is_active) {
            Auth::guard('trainer')->logout();

            return redirect()->route('trainer.login')
                ->withErrors(['email' => 'This trainer account has been deactivated.']);
        }

        return $next($request);
    }
}
