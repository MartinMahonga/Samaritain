<?php

namespace App\Http\Middleware;

use App\Models\Contract;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Vous devez être connecté.');
        }

        // Vérifier si l'utilisateur est locataire via un contrat actif ou en attente de signature
        $isTenant = Contract::where('tenant_email', $user->email)
            ->whereIn('status', ['active', 'pending_owner', 'pending_tenant'])
            ->exists();

        if (! $isTenant) {
            abort(403, 'Accès réservé aux locataires.');
        }

        return $next($request);
    }
}
