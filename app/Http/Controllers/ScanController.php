<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanRequest;
use App\Models\Pass;
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

        return response()->json([
            'valid' => true,
            'message' => 'Scan validé avec succès',
            'pass' => [
                'holder_name' => $pass->holder_name,
                'expiration_date' => $pass->expiration_date->format('d/m/Y'),
                'remaining_visits' => $pass->remaining_visits,
                'total_visits' => $pass->allowed_visits,
            ],
        ]);
    }
}
