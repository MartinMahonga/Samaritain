<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanRequest;
use App\Models\Pass;
use App\Models\VisitPass;
use App\Services\PassScanService;
use Illuminate\Support\Facades\Gate;

class ScanController extends Controller
{
    public function __construct(private PassScanService $scanService) {}

    public function index()
    {
        Gate::authorize('scan', Pass::class);

        return view('scan.index');
    }

    public function show(string $uuid)
    {
        $result = $this->scanService->validatePass($uuid);

        if (! $result['valid']) {
            return view('scan.invalid', ['result' => $result]);
        }

        return view('scan.show', ['result' => $result]);
    }

    public function process(ScanRequest $request)
    {
        Gate::authorize('scan', Pass::class);

        $result = $this->scanService->validatePass($request->uuid);

        if (! $result['valid']) {
            return response()->json($result, 400);
        }

        $pass = $this->scanService->scanPass($result['pass'], auth()->user(), $request);

        $isVisitPass = $pass instanceof VisitPass;
        $expDate = $isVisitPass ? $pass->expires_at : $pass->expiration_date;

        return response()->json([
            'valid' => true,
            'message' => $isVisitPass ? 'Pass visite validé avec succès' : 'Scan validé avec succès',
            'pass' => [
                'holder_name' => $pass->holder_name,
                'phone' => $pass->phone,
                'expiration_date' => $expDate ? $expDate->toIso8601String() : null,
                'remaining_visits' => $isVisitPass ? 1 : $pass->remaining_visits,
                'allowed_visits' => $isVisitPass ? 1 : $pass->allowed_visits,
                'status' => $pass->status === 'active' ? 'actif' : ($pass->status === 'expired' ? 'expiré' : $pass->status),
            ],
        ]);
    }
}
