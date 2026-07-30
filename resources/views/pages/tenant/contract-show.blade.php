@extends('layouts.tenant')

@section('title', 'Contrat — ' . $contract->tenant_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('tenant.contracts') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Retour aux contrats
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $contract->property->title }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Contrat de bail</p>
        </div>
        <div class="flex items-center gap-2">
            @if($contract->status === 'pending_tenant')
                <button onclick="document.getElementById('signatureModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H2v-4.572L16.732 3.732z"></path></svg>
                    Signer le contrat
                </button>
            @endif
            <a href="{{ route('tenant.contracts.pdf', $contract) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Télécharger PDF
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Informations du bail</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Statut</dt>
                    <dd>
                        @php
                            $sc = ['draft' => 'gray', 'pending_owner' => 'amber', 'pending_tenant' => 'orange', 'active' => 'emerald', 'rejected' => 'red', 'cancelled' => 'red', 'terminated' => 'red'];
                            $sl = ['draft' => 'Brouillon', 'pending_owner' => 'En attente propriétaire', 'pending_tenant' => 'En attente locataire', 'active' => 'Actif', 'rejected' => 'Refusé', 'cancelled' => 'Annulé', 'terminated' => 'Résilié'];
                            $c = $sc[$contract->status] ?? 'gray';
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30 text-{{ $c }}-600 dark:text-{{ $c }}-400">
                            {{ $sl[$contract->status] ?? $contract->status }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Début</dt>
                    <dd class="text-gray-800 dark:text-white font-medium">{{ $contract->start_date->format('d/m/Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Fin</dt>
                    <dd class="text-gray-800 dark:text-white font-medium">{{ $contract->end_date?->format('d/m/Y') ?? 'Illimité' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Loyer mensuel</dt>
                    <dd class="text-gray-800 dark:text-white font-bold">{{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA</dd>
                </div>
            </dl>
        </div>

        @if($contract->ownerSignature || $contract->tenantSignature)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Signatures</h3>
            <div class="space-y-3">
                @if($contract->ownerSignature)
                    <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <img src="{{ Storage::url($contract->ownerSignature->signature_image) }}" alt="Signature propriétaire" class="h-10 w-auto bg-white rounded">
                        <div>
                            <p class="text-xs font-medium text-gray-800 dark:text-white">Propriétaire</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contract->ownerSignature->signed_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($contract->tenantSignature)
                    <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <img src="{{ Storage::url($contract->tenantSignature->signature_image) }}" alt="Signature locataire" class="h-10 w-auto bg-white rounded">
                        <div>
                            <p class="text-xs font-medium text-gray-800 dark:text-white">Locataire</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contract->tenantSignature->signed_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Détails du contrat</h3>
        <div class="prose dark:prose-invert max-w-none">
            <p><strong>Propriété :</strong> {{ $contract->property->title }}</p>
            <p><strong>Adresse :</strong> {{ $contract->property->address }}</p>
            <p><strong>Locataire :</strong> {{ $contract->tenant_name }}</p>
            <p><strong>Email :</strong> {{ $contract->tenant_email }}</p>
            <p><strong>Téléphone :</strong> {{ $contract->tenant_phone ?? '—' }}</p>
            <p><strong>Loyer :</strong> {{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA/mois</p>
            @if($contract->deposit)
                <p><strong>Dépôt :</strong> {{ number_format($contract->deposit, 0, ',', ' ') }} FCFA</p>
            @endif
        </div>
    </div>
</div>

@if($contract->status === 'pending_tenant')
<div id="signatureModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Signer le contrat</h3>
                <button onclick="document.getElementById('signatureModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('tenant.contracts.sign', $contract) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dessinez votre signature</label>
                    <input type="hidden" name="signature" id="signatureInput">
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white">
                        <canvas id="signatureCanvas" width="800" height="200" class="w-full h-48 touch-none cursor-crosshair"></canvas>
                    </div>
                    <button type="button" onclick="clearSignature()" class="mt-2 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        Effacer la signature
                    </button>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('signatureModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Annuler
                    </button>
                    <button type="submit" onclick="return validateSignature()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                        Confirmer la signature
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($contract->status === 'pending_tenant')
<script>
let canvas = document.getElementById('signatureCanvas');
let ctx = canvas.getContext('2d');
let drawing = false;
let hasSignature = false;

ctx.strokeStyle = '#1f2937';
ctx.lineWidth = 2;
ctx.lineCap = 'round';
ctx.lineJoin = 'round';

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mouseleave', stopDrawing);
canvas.addEventListener('touchstart', handleTouchStart);
canvas.addEventListener('touchmove', handleTouchMove);
canvas.addEventListener('touchend', stopDrawing);

function startDrawing(e) {
    drawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
}

function draw(e) {
    if (!drawing) return;
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
    hasSignature = true;
}

function handleTouchStart(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent('mousedown', { clientX: touch.clientX, clientY: touch.clientY });
    canvas.dispatchEvent(mouseEvent);
}

function handleTouchMove(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent('mousemove', { clientX: touch.clientX, clientY: touch.clientY });
    canvas.dispatchEvent(mouseEvent);
}

function stopDrawing() {
    drawing = false;
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasSignature = false;
    document.getElementById('signatureInput').value = '';
}

function validateSignature() {
    if (!hasSignature) {
        alert('Veuillez dessiner votre signature avant de confirmer.');
        return false;
    }
    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('signatureInput').value = dataUrl;
    return true;
}
</script>
@endif
@endsection
</parameter>
</write_to_file>