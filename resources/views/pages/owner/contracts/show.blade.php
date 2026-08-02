@extends('layouts.owner')

@section('title', 'Contrat — ' . $contract->tenant_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.contracts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux contrats
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $contract->tenant_name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $contract->property->title }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if($contract->status === 'pending_owner')
                <button onclick="document.getElementById('signatureModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H2v-4.572L16.732 3.732z"></path></svg>
                    Signer le contrat
                </button>
            @endif
            <a href="{{ route('owner.contracts.pdf', $contract) }}" target="_blank"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Télécharger le contrat
            </a>
            <form action="{{ route('owner.contracts.generate-rents', $contract) }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Régénérer l'échéancier
                </button>
            </form>
            @if($contract->canBeCancelled())
                <form action="{{ route('owner.contracts.cancel', $contract) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce contrat ? Cette action est irréversible.')">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 border border-amber-200 dark:border-amber-700 text-amber-600 dark:text-amber-400 rounded-lg text-sm hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Annuler le contrat
                    </button>
                </form>
            @endif
            @if($contract->canBeDeleted())
                <form action="{{ route('owner.contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('⚠️ ATTENTION : Cette action supprimera définitivement le contrat et toutes les données associées (signatures, paiements, documents). Cette action est IRRÉVERSIBLE. Confirmez-vous ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 border border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 rounded-lg text-sm hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Contract Info --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Informations du bail</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Statut</dt>
                    <dd>
                        @php
                            $sc = [
                                'draft' => 'gray',
                                'pending_owner' => 'amber',
                                'pending_tenant' => 'orange',
                                'active' => 'emerald',
                                'rejected' => 'red',
                                'cancelled' => 'red',
                                'terminated' => 'red',
                            ];
                            $sl = [
                                'draft' => 'Brouillon',
                                'pending_owner' => 'En attente propriétaire',
                                'pending_tenant' => 'En attente locataire',
                                'active' => 'Actif',
                                'rejected' => 'Refusé',
                                'cancelled' => 'Annulé',
                                'terminated' => 'Résilié',
                            ];
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
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Dépôt garantie</dt>
                    <dd class="text-gray-800 dark:text-white">{{ $contract->deposit ? number_format($contract->deposit, 0, ',', ' ') . ' FCFA' : '—' }}</dd>
                </div>
                @if($contract->cancelled_at)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Annulé le</dt>
                    <dd class="text-red-600 dark:text-red-400 font-medium">{{ $contract->cancelled_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif
            </dl>

            <div class="border-t border-gray-100 dark:border-gray-700 mt-4 pt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Contact locataire</p>
                <p class="text-sm text-gray-800 dark:text-white">{{ $contract->tenant_phone ?? '—' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant_email ?? '—' }}</p>
            </div>
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

        @if($contract->status === 'pending_owner' || $contract->status === 'pending_tenant')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Prochaines étapes</h3>
            <div class="space-y-2">
                @if($contract->status === 'pending_owner')
                    <div class="flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Signature du propriétaire requise
                    </div>
                @endif
                @if($contract->status === 'pending_tenant')
                    <div class="flex items-center gap-2 text-sm text-orange-600 dark:text-orange-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Signature du locataire requise
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if($contract->isCancelled())
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-800 p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="font-semibold text-red-800 dark:text-red-300">Contrat annulé</h3>
            </div>
            <p class="text-sm text-red-600 dark:text-red-400">Ce contrat a été annulé{{ $contract->cancelled_at ? ' le ' . $contract->cancelled_at->format('d/m/Y à H:i') : '' }}. Les données sont conservées mais le contrat n'est plus actif.</p>
        </div>
        @endif

        {{-- Stats --}}
        @php
            $paidCount = $contract->rentPayments->where('status', 'paid')->count();
            $totalCount = $contract->rentPayments->count();
            $collectedTotal = $contract->rentPayments->sum('amount_paid');
            $dueTotal = $contract->rentPayments->sum('amount_due');
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Bilan de collecte</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3 text-center">
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $paidCount }}/{{ $totalCount }}</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">Loyers payés</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 text-center">
                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($collectedTotal / 1000, 0) }}k</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400">FCFA perçus</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rent Payments Schedule --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white">Échéancier de loyer</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-5 py-3 font-medium">Période</th>
                        <th class="px-5 py-3 font-medium text-right">Montant dû</th>
                        <th class="px-5 py-3 font-medium text-right">Montant payé</th>
                        <th class="px-5 py-3 font-medium">Échéance</th>
                        <th class="px-5 py-3 font-medium text-center">Statut</th>
                        <th class="px-5 py-3 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($contract->rentPayments->sortBy(['year', 'month']) as $payment)
                        @php
                            $pc = ['unpaid' => 'gray', 'paid' => 'emerald', 'late' => 'red', 'partial' => 'amber'];
                            $pl = ['unpaid' => 'Non payé', 'paid' => 'Payé', 'late' => 'En retard', 'partial' => 'Partiel'];
                            $payColor = $pc[$payment->status] ?? 'gray';
                            $months = ['', 'Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                                {{ $months[$payment->month] ?? $payment->month }} {{ $payment->year }}
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ number_format($payment->amount_due, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">{{ number_format($payment->amount_paid, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $payment->due_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $payColor }}-100 dark:bg-{{ $payColor }}-900/30 text-{{ $payColor }}-600 dark:text-{{ $payColor }}-400">
                                    {{ $pl[$payment->status] ?? $payment->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('owner.rent-payments.toggle-paid', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs px-3 py-1 rounded-lg border {{ $payment->status === 'paid' ? 'border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : 'border-emerald-200 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} transition">
                                        {{ $payment->status === 'paid' ? 'Annuler' : 'Marquer payé' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                Aucun échéancier. <a href="{{ route('owner.contracts.generate-rents', $contract) }}" class="text-primary hover:underline">Générer</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if($contract->status === 'pending_owner')
<div id="signatureModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Signer le contrat</h3>
                <button onclick="document.getElementById('signatureModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('owner.contracts.sign', $contract) }}" method="POST">
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

@if($contract->status === 'pending_owner')
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