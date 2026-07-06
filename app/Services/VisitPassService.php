<?php

namespace App\Services;

use App\Models\Property;
use App\Models\VisitPass;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VisitPassService
{
    /**
     * The price of a visit pass in FCFA.
     */
    const PASS_PRICE = 2500;

    public function getPassPrice(): int
    {
        return self::PASS_PRICE;
    }

    public function createVisitPass(array $data): VisitPass
    {
        return DB::transaction(function () use ($data) {
            $property = Property::findOrFail($data['property_id']);

            $visitPass = VisitPass::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => auth()->id(),
                'property_id' => $property->id,
                'holder_name' => $data['holder_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'comment' => $data['comment'] ?? null,
                'amount' => self::PASS_PRICE,
                'payment_status' => 'pending',
                'status' => 'pending_payment',
            ]);

            return $visitPass;
        });
    }

    public function generateQrCode(VisitPass $visitPass): void
    {
        $url = route('scan.show', $visitPass->uuid);

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

            $fileName = 'visit-passes/qrcodes/'.$visitPass->uuid.'.png';
            Storage::put($fileName, $result->getString());

            $visitPass->qr_code_path = $fileName;
            $visitPass->saveQuietly();

        } catch (\Exception $e) {
            \Log::error('Erreur génération QR Code VisitPass: '.$e->getMessage());
        }
    }

    public function generatePdf(VisitPass $visitPass): string
    {
        $this->generateQrCode($visitPass);

        $pdf = Pdf::loadView('visit-passes.pdf', ['visitPass' => $visitPass]);

        $fileName = 'visit-passes/pdfs/'.$visitPass->uuid.'.pdf';
        Storage::put($fileName, $pdf->output());

        $visitPass->pdf_path = $fileName;
        $visitPass->saveQuietly();

        return $fileName;
    }

    public function getUserVisitPasses()
    {
        return VisitPass::with('property.images', 'property.city')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function handleSuccessfulPayment(VisitPass $visitPass): void
    {
        DB::transaction(function () use ($visitPass) {
            $visitPass->markAsPaid();

            $this->generatePdf($visitPass);
        });
    }

    public function handleFailedPayment(VisitPass $visitPass): void
    {
        $visitPass->markAsPaymentFailed();
    }
}
