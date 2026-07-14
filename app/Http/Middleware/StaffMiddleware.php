<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Exclure les routes d'acceptation d'invitation
        if ($request->routeIs('admin.invitations.accept.form') ||
            $request->routeIs('admin.invitations.accept')) {
            return $next($request);
        }

        // Vérifier si l'utilisateur est connecté et est staff
        if (! auth()->check() || ! auth()->user()->is_staff) {
            abort(403, 'Accès réservé aux membres de l\'entreprise.');
        }

        return $next($request);
    }
}
