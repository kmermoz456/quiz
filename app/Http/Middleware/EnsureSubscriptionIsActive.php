<?php

// app/Http/Middleware/EnsureSubscriptionIsActive.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSubscriptionIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // On ne bloque pas les non-étudiants (admins…)
        if ($user && $user->role !== 'student') {
            return $next($request);
        }

        // Abonnement inactif ? (flag false OU date expirée)
        $inactive = !$user?->subscription_active
                    || ($user?->subscription_ends_at && now()->greaterThan($user->subscription_ends_at));

        if ($inactive) {
            // message + redirection “douce”
            return redirect()->route('dashboard')
                ->with('error', "Vous devez payer votre mensualité.");
        }

        return $next($request);
    }
}
