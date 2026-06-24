<?php

namespace App\Services;

use App\Models\Pass;
use App\Models\PassScan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassScanService
{
    private array $validationResult = [];

    public function validatePass(string $uuid): array
    {
        $pass = Pass::where('uuid', $uuid)->first();

        if (! $pass) {
            return $this->invalid('Pass introuvable', 'not_found');
        }

        if ($pass->isSuspended()) {
            return $this->invalid('Pass suspendu', 'suspended', $pass);
        }

        if ($pass->isExpired()) {
            return $this->invalid('Pass expiré', 'expired', $pass);
        }

        if ($pass->isUsed()) {
            return $this->invalid('Plus aucune visite disponible', 'used', $pass);
        }

        return $this->valid($pass);
    }

    public function scanPass(Pass $pass, User $user, Request $request): Pass
    {
        return DB::transaction(function () use ($pass, $user, $request) {
            $pass->remaining_visits--;
            $pass->updateStatus();
            $pass->save();

            PassScan::create([
                'pass_id' => $pass->id,
                'user_id' => $user->id,
                'scanned_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_info' => $this->getDeviceInfo($request),
            ]);

            return $pass->fresh();
        });
    }

    private function getDeviceInfo(Request $request): string
    {
        $userAgent = $request->userAgent();

        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            return 'Tablet';
        }

        return 'Desktop';
    }

    private function valid(Pass $pass): array
    {
        return [
            'valid' => true,
            'message' => 'Pass valide',
            'pass' => $pass,
        ];
    }

    private function invalid(string $message, string $reason, ?Pass $pass = null): array
    {
        return [
            'valid' => false,
            'message' => $message,
            'reason' => $reason,
            'pass' => $pass,
        ];
    }

    public function getPassScansHistory(Pass $pass)
    {
        return $pass->scans()
            ->with('user')
            ->orderBy('scanned_at', 'desc')
            ->paginate(20);
    }

    /**
     * Récupère les scans récents
     */
    public function getRecentScans(int $limit = 10)
    {
        return PassScan::with(['pass', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
