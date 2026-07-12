<?php

namespace App\Services;

use App\Models\Pass;
use App\Models\PassScan;
use App\Models\VisitPass;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PassService
{
    public function createPass(array $data): Pass
    {
        return DB::transaction(function () use ($data) {
            $pass = Pass::create([
                'uuid' => (string) Str::uuid(),
                'holder_name' => $data['holder_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'allowed_visits' => $data['allowed_visits'],
                'remaining_visits' => $data['allowed_visits'],
                'start_date' => $data['start_date'],
                'expiration_date' => $data['expiration_date'],
                'status' => 'actif',
            ]);

            $this->generateQrCode($pass);

            return $pass;
        });
    }

    public function updatePass(Pass $pass, array $data): Pass
    {
        return DB::transaction(function () use ($pass, $data) {
            $pass->update($data);
            $pass->updateStatus();

            if (isset($data['regenerate_qr']) && $data['regenerate_qr']) {
                $this->generateQrCode($pass);
            }

            return $pass->fresh();
        });
    }

    public function deletePass(Pass $pass): bool
    {
        return DB::transaction(function () use ($pass) {
            if ($pass->qr_code_path) {
                Storage::delete($pass->qr_code_path);
            }

            return $pass->delete();
        });
    }

    public function generateQrCode(Pass $pass): void
    {
        $url = route('scan.show', $pass->uuid);

        try {
            // Endroid 6.x : Builder n'est plus fluent, on passe tout au constructeur
            $builder = new Builder(
                writer: new PngWriter,
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255),
            );

            $result = $builder->build();

            // Sauvegarde du QR Code
            $fileName = 'qrcodes/'.$pass->uuid.'.png';
            Storage::put($fileName, $result->getString());

            $pass->qr_code_path = $fileName;
            $pass->saveQuietly();

        } catch (\Exception $e) {
            \Log::error('Erreur génération QR Code: '.$e->getMessage());

            // Créer un QR Code minimaliste
            $this->generateMinimalQrCode($pass, $url);
        }
    }

    private function generateMinimalQrCode(Pass $pass, string $url): void
    {
        try {
            $builder = new Builder(
                writer: new PngWriter,
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
            );

            $result = $builder->build();

            $fileName = 'qrcodes/'.$pass->uuid.'.png';
            Storage::put($fileName, $result->getString());

            $pass->qr_code_path = $fileName;
            $pass->saveQuietly();

        } catch (\Exception $e) {
            \Log::error('Erreur QR Code minimal: '.$e->getMessage());
            $pass->qr_code_path = null;
            $pass->saveQuietly();
        }
    }

    public function generateQrCodeBase64(Pass $pass): string
    {
        if ($pass->qr_code_path && Storage::exists($pass->qr_code_path)) {
            $qrContent = Storage::get($pass->qr_code_path);

            return 'data:image/png;base64,'.base64_encode($qrContent);
        }

        $url = route('scan.show', $pass->uuid);

        try {
            $builder = new Builder(
                writer: new PngWriter,
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255),
            );

            $result = $builder->build();

            return $result->getDataUri();

        } catch (\Exception $e) {
            \Log::error('Erreur génération QR Code Base64: '.$e->getMessage());

            return '';
        }
    }

    public function getStatistics(): array
    {
        $totalManual = Pass::count();
        $totalVisit = VisitPass::count();

        $activeManual = Pass::where('status', 'actif')->where('expiration_date', '>', now())->count();
        $activeVisit = VisitPass::where('status', 'active')
            ->where('expires_at', '>', now())
            ->where('remaining_visits', '>', 0)
            ->count();

        $expiredManual = Pass::where('status', 'expiré')->orWhere('expiration_date', '<=', now())->count();
        $expiredVisit = VisitPass::where('status', 'expired')->orWhere('expires_at', '<=', now())->count();

        return [
            'total' => $totalManual + $totalVisit,
            'active' => $activeManual + $activeVisit,
            'expired' => $expiredManual + $expiredVisit,
            'used' => Pass::where('status', 'utilisé')->count() + VisitPass::where('status', 'used')->count(),
            'suspended' => Pass::where('status', 'suspendu')->count(),
            'total_visits' => PassScan::count(),
        ];
    }

    public function searchPasses(array $filters)
    {
        $filter = $filters['filter'] ?? 'all';
        $search = $filters['search'] ?? null;

        $passesQuery = DB::table('passes')
            ->select([
                'id',
                'uuid',
                'holder_name',
                'phone',
                'email',
                'status',
                'created_at',
                'expiration_date as expires_at',
                DB::raw('NULL as user_id'),
                DB::raw('NULL as property_id'),
                DB::raw('NULL as payment_status'),
                DB::raw('NULL as amount'),
                DB::raw('NULL as transaction_id'),
                DB::raw('NULL as reference'),
                DB::raw("'manual' as pass_type"),
            ]);

        $visitPassesQuery = DB::table('visit_passes')
            ->select([
                'id',
                'uuid',
                'holder_name',
                'phone',
                'email',
                'status',
                'created_at',
                'expires_at',
                'user_id',
                'property_id',
                'payment_status',
                'amount',
                'transaction_id',
                'reference',
                DB::raw("'visit_pass' as pass_type"),
            ]);

        if (! empty($search)) {
            $searchPattern = '%'.$search.'%';
            $passesQuery->where(function ($q) use ($searchPattern) {
                $q->where('holder_name', 'ilike', $searchPattern)
                    ->orWhere('phone', 'ilike', $searchPattern)
                    ->orWhere('uuid', 'ilike', $searchPattern);
            });

            $visitPassesQuery->where(function ($q) use ($searchPattern) {
                $q->where('holder_name', 'ilike', $searchPattern)
                    ->orWhere('phone', 'ilike', $searchPattern)
                    ->orWhere('uuid', 'ilike', $searchPattern)
                    ->orWhere('reference', 'ilike', $searchPattern);
            });
        }

        $includeManual = true;
        $includeVisit = true;

        switch ($filter) {
            case 'active':
                $passesQuery->where('status', 'actif')
                    ->where('expiration_date', '>', now());
                $visitPassesQuery->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->where('remaining_visits', '>', 0);
                break;
            case 'expired':
                $passesQuery->where(function ($q) {
                    $q->where('status', 'expiré')
                        ->orWhere('expiration_date', '<=', now());
                });
                $visitPassesQuery->where(function ($q) {
                    $q->where('status', 'expired')
                        ->orWhere('expires_at', '<=', now());
                });
                break;
            case 'manual':
                $includeVisit = false;
                break;
            case 'generated':
                $includeManual = false;
                break;
            case 'paid':
                $includeManual = false;
                $visitPassesQuery->where('payment_status', 'paid');
                break;
            case 'payment_pending':
                $includeManual = false;
                $visitPassesQuery->where('payment_status', 'pending');
                break;
            case 'payment_failed':
                $includeManual = false;
                $visitPassesQuery->where('payment_status', 'failed');
                break;
        }

        if ($includeManual && $includeVisit) {
            $query = DB::table(function ($q) use ($passesQuery, $visitPassesQuery) {
                $q->from($passesQuery->unionAll($visitPassesQuery), 'unified_passes');
            });
        } elseif ($includeManual) {
            $query = DB::table(function ($q) use ($passesQuery) {
                $q->from($passesQuery, 'unified_passes');
            });
        } else {
            $query = DB::table(function ($q) use ($visitPassesQuery) {
                $q->from($visitPassesQuery, 'unified_passes');
            });
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate(15);

        $visitPassIds = collect($paginator->items())->where('pass_type', 'visit_pass')->pluck('id');
        $visitPasses = VisitPass::with(['user', 'property', 'transaction'])
            ->whereIn('id', $visitPassIds)
            ->get()
            ->keyBy('id');

        $manualPassIds = collect($paginator->items())->where('pass_type', 'manual')->pluck('id');
        $manualPasses = Pass::whereIn('id', $manualPassIds)
            ->get()
            ->keyBy('id');

        $paginator->getCollection()->transform(function ($item) use ($visitPasses, $manualPasses) {
            if ($item->pass_type === 'visit_pass') {
                return $visitPasses->get($item->id);
            }

            return $manualPasses->get($item->id);
        });

        return $paginator;
    }
}
