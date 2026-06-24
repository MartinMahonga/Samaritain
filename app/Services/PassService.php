<?php

namespace App\Services;

use App\Models\Pass;
use App\Models\PassScan;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
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
                Storage::disk('public')->delete($pass->qr_code_path);
            }

            return $pass->delete();
        });
    }

    public function generateQrCode(Pass $pass): void
    {
        $url = route('scan.show', $pass->uuid);

        try {
            // Création du QR Code pour Endroid 5.x
            $qrCode = new QrCode(
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter;
            $result = $writer->write($qrCode);

            // Sauvegarde du QR Code
            $fileName = 'qrcodes/'.$pass->uuid.'.png';
            Storage::disk('public')->put($fileName, $result->getString());

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
            $qrCode = new QrCode($url);
            $qrCode->setSize(300);

            $writer = new PngWriter;
            $result = $writer->write($qrCode);

            $fileName = 'qrcodes/'.$pass->uuid.'.png';
            Storage::disk('public')->put($fileName, $result->getString());

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
        if ($pass->qr_code_path && Storage::disk('public')->exists($pass->qr_code_path)) {
            $qrContent = Storage::disk('public')->get($pass->qr_code_path);

            return 'data:image/png;base64,'.base64_encode($qrContent);
        }

        $url = route('scan.show', $pass->uuid);

        try {
            $qrCode = new QrCode(
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10
            );

            $writer = new PngWriter;
            $result = $writer->write($qrCode);

            return $result->getDataUri();

        } catch (\Exception $e) {
            return '';
        }
    }

    public function getStatistics(): array
    {
        return [
            'total' => Pass::count(),
            'active' => Pass::where('status', 'actif')->count(),
            'expired' => Pass::where('status', 'expiré')->count(),
            'used' => Pass::where('status', 'utilisé')->count(),
            'suspended' => Pass::where('status', 'suspendu')->count(),
            'total_visits' => PassScan::count(),
        ];
    }

    public function searchPasses(array $filters)
    {
        $query = Pass::query();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($search) {
                $q->where('holder_name', 'ilike', $search)
                    ->orWhere('phone', 'ilike', $search)
                    ->orWhere('uuid', 'ilike', $search);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
}
