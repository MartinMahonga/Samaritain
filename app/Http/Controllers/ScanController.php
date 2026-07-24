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
        Gate::authorize('scan', Pass::class);

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
            return redirect()->back()->with('error', $result['message'] ?? 'Pass invalide');
        }

        $pass = $this->scanService->scanPass($result['pass'], auth()->user(), $request);

        $isVisitPass = $pass instanceof VisitPass;

        $message = $isVisitPass ? 'Pass visite validé avec succès' : 'Scan validé avec succès';

        return redirect()->back()->with('success', $message);
    }
}
