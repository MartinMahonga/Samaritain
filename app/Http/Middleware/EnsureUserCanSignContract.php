<?php

namespace App\Http\Middleware;

use App\Services\ContractSignatureService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanSignContract
{
    public function __construct(protected ContractSignatureService $signatureService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $contract = $request->route('contract');
        $user = auth()->user();

        if (! $contract) {
            abort(404);
        }

        if (! $this->signatureService->canBeSignedBy($user, $contract)) {
            abort(403, 'Vous ne pouvez pas signer ce contrat.');
        }

        return $next($request);
    }
}
