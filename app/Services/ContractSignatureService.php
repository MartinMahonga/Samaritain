<?php

namespace App\Services;

use App\Enums\Owner\ContractStatus;
use App\Models\Contract;
use App\Models\ContractSignature;
use App\Models\Document;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractSignatureService
{
    public function calculateContractHash(Contract $contract): string
    {
        $content = $this->getContractContentForHash($contract);

        return hash('sha256', $content);
    }

    public function getContractContentForHash(Contract $contract): string
    {
        $contract->load('property', 'rentPayments');

        $data = [
            'contract_id' => $contract->id,
            'property_id' => $contract->property_id,
            'tenant_name' => $contract->tenant_name,
            'tenant_email' => $contract->tenant_email,
            'tenant_phone' => $contract->tenant_phone,
            'start_date' => $contract->start_date?->toDateString(),
            'end_date' => $contract->end_date?->toDateString(),
            'monthly_rent' => $contract->monthly_rent,
            'deposit' => $contract->deposit,
            'contract_version' => $contract->contract_version,
            'rent_payments' => $contract->rentPayments->map(function ($rp) {
                return [
                    'month' => $rp->month,
                    'year' => $rp->year,
                    'amount_due' => $rp->amount_due,
                    'due_date' => $rp->due_date?->toDateString(),
                    'status' => $rp->status,
                ];
            })->toArray(),
            'updated_at' => $contract->updated_at?->toDateTimeString(),
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function isContractModifiedSinceSignature(Contract $contract, ContractSignature $signature): bool
    {
        $currentHash = $this->calculateContractHash($contract);

        return $currentHash !== $signature->contract_hash;
    }

    public function createSignature(
        Contract $contract,
        User $user,
        string $role,
        string $signatureDataUrl,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): ContractSignature {
        if ($contract->hasSignatureFrom($user)) {
            throw new \RuntimeException('Cet utilisateur a déjà signé ce contrat.');
        }

        $currentHash = $this->calculateContractHash($contract);
        $version = (string) $contract->contract_version;

        $signatureImagePath = $this->storeSignatureImage($signatureDataUrl, $contract->id, $user->id);

        $signature = ContractSignature::create([
            'contract_id' => $contract->id,
            'user_id' => $user->id,
            'role' => $role,
            'signed_at' => now(),
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'signature_image' => $signatureImagePath,
            'signature_hash' => hash('sha256', $signatureDataUrl),
            'contract_hash' => $currentHash,
            'contract_version' => $version,
        ]);

        $this->updateContractAfterSignature($contract, $role);

        return $signature;
    }

    private function storeSignatureImage(string $dataUrl, int $contractId, int $userId): string
    {
        $folder = 'contracts/signatures';
        $fileName = 'sig_'.$contractId.'_'.$userId.'_'.time().'.png';

        if (str_starts_with($dataUrl, 'data:image/png;base64,')) {
            $base64 = substr($dataUrl, strlen('data:image/png;base64,'));
            $contents = base64_decode($base64);
        } else {
            throw new \InvalidArgumentException('Format de signature invalide.');
        }

        Storage::put($folder.'/'.$fileName, $contents);

        return $folder.'/'.$fileName;
    }

    private function updateContractAfterSignature(Contract $contract, string $role): void
    {
        $now = now();

        if ($role === 'owner') {
            $contract->update([
                'owner_signed_at' => $now,
                'status' => match ($contract->status) {
                    ContractStatus::PENDING_OWNER_SIGNATURE->value => ContractStatus::PENDING_TENANT_SIGNATURE->value,
                    default => $contract->status,
                },
            ]);
        } elseif ($role === 'tenant') {
            $contract->update([
                'tenant_signed_at' => $now,
                'status' => ContractStatus::ACTIVE->value,
                'activated_at' => $now,
            ]);
        }
    }

    public function invalidateSignatures(Contract $contract): void
    {
        $contract->signatures()->delete();

        $contract->update([
            'owner_signed_at' => null,
            'tenant_signed_at' => null,
            'activated_at' => null,
            'status' => ContractStatus::DRAFT->value,
            'contract_version' => ($contract->contract_version ?? 1) + 1,
            'content_hash' => null,
        ]);
    }

    public function canBeSignedBy(User $user, Contract $contract): bool
    {
        if ($contract->isFullySigned()) {
            return false;
        }

        $status = $contract->status;

        if ($contract->created_by === $user->id) {
            return $status === ContractStatus::PENDING_OWNER_SIGNATURE->value;
        }

        if ($this->isTenantForContract($user, $contract)) {
            return $status === ContractStatus::PENDING_TENANT_SIGNATURE->value;
        }

        return false;
    }

    private function isTenantForContract(User $user, Contract $contract): bool
    {
        return $user->email === $contract->tenant_email;
    }

    public function getSignerRoleForUser(User $user, Contract $contract): ?string
    {
        if ($contract->created_by === $user->id) {
            return 'owner';
        }

        if ($this->isTenantForContract($user, $contract)) {
            return 'tenant';
        }

        return null;
    }

    public function generateSignedPdfDocument(Contract $contract): void
    {
        $contract->load('property.city', 'signatures.user');
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.lease-contract', compact('contract', 'property'));

        $folder = 'documents/contracts';
        $fileName = 'contrat_bail_'.$contract->id.'_v'.$contract->contract_version.'_'.time().'.pdf';
        $fullPath = $folder.'/'.$fileName;

        Storage::put($fullPath, $pdf->output());

        if (Document::where('documentable_id', $contract->id)
            ->where('documentable_type', Contract::class)
            ->where('category', 'lease_contract')
            ->exists()) {
            Document::where('documentable_id', $contract->id)
                ->where('documentable_type', Contract::class)
                ->where('category', 'lease_contract')
                ->update([
                    'file_path' => $fullPath,
                    'file_size' => Storage::size($fullPath),
                ]);
        } else {
            Document::create([
                'property_id' => $contract->property_id,
                'name' => 'Contrat de bail - '.$contract->tenant_name.' (signé)',
                'category' => 'lease_contract',
                'file_path' => $fullPath,
                'file_size' => Storage::size($fullPath),
                'documentable_id' => $contract->id,
                'documentable_type' => Contract::class,
                'created_by' => $contract->created_by,
            ]);
        }
    }
}
