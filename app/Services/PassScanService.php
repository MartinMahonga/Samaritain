<?php

namespace App\Services;

use App\Models\Pass;
use App\Models\PassScan;
use App\Models\User;
use App\Models\VisitPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassScanService
{
    private array $validationResult = [];

    public function validatePass(string $uuid): array
    {
        $pass = Pass::where('uuid', $uuid)->first();

        if ($pass) {
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

        $visitPass = VisitPass::where('uuid', $uuid)->first();

        if ($visitPass) {
            if (! $visitPass->isPaid()) {
                return $this->invalid('Pass visite non payé', 'pending_payment', $visitPass);
            }

            if ($visitPass->isExpired()) {
                return $this->invalid('Pass visite expiré', 'expired', $visitPass);
            }

            if (! $visitPass->isActive()) {
                return $this->invalid('Pass visite inactif', 'inactive', $visitPass);
            }

            return $this->valid($visitPass);
        }

        return $this->invalid('Pass introuvable', 'not_found');
    }

    public function scanPass(Pass|VisitPass $pass, User $user, Request $request): Pass|VisitPass
    {
        return DB::transaction(function () use ($pass, $user, $request) {
            if ($pass instanceof Pass) {
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
            } else {
                $pass->updateStatus();
                $pass->save();

                PassScan::create([
                    'visit_pass_id' => $pass->id,
                    'user_id' => $user->id,
                    'scanned_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'device_info' => $this->getDeviceInfo($request),
                ]);
            }

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

    private function valid(Pass|VisitPass $pass): array
    {
        return [
            'valid' => true,
            'message' => 'Pass valide',
            'pass' => $pass,
        ];
    }

    private function invalid(string $message, string $reason, Pass|VisitPass|null $pass = null): array
    {
        return [
            'valid' => false,
            'message' => $message,
            'reason' => $reason,
            'pass' => $pass,
        ];
    }

    public function getPassScansHistory(Pass|VisitPass $pass)
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
        return PassScan::with(['pass', 'visitPass', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
