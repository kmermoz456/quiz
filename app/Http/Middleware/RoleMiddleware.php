<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Autorise l'accès si l'utilisateur connecté a l'un des rôles passés en paramètres.
     * Usage: ->middleware('role:admin') ou ->middleware('role:student,admin')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Doit être authentifié (la route doit aussi avoir 'auth')
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Normalise les rôles (Admin/ADMIN -> admin)
        $userRole = strtolower((string) ($user->role ?? ''));
        $roles    = array_map(fn($r) => strtolower(trim((string) $r)), $roles);

        // Si aucun rôle n'est fourni, on refuse par sécurité
        if (empty($roles)) {
            abort(403);
        }

        if (! in_array($userRole, $roles, true)) {
            // Redirige proprement (ou abort(403) si tu préfères)
            return redirect()->route('home')->with('error', 'Accès refusé (rôle requis).');
        }

        return $next($request);
    }
}
