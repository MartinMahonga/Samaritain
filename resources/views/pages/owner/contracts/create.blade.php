@extends('layouts.owner')

@section('title', 'Nouveau contrat')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signatureCanvas');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let hasSignature = false;

        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);
            ctx.strokeStyle = '#1f2937';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: clientX - rect.left,
                y: clientY - rect.top,
            };
        }

        function startDrawing(e) {
            if (e.type.startsWith('touch')) {
                e.preventDefault();
            }
            drawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        }

        function draw(e) {
            if (!drawing) return;
            if (e.type.startsWith('touch')) {
                e.preventDefault();
            }
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            hasSignature = true;
        }

        function stopDrawing() {
            drawing = false;
        }

        function debugSignature() {
            console.log('hasSignature', hasSignature);
            console.log('canvas dataURL length', canvas.toDataURL('image/png').length);
        }

        window.clearSignature = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSignature = false;
            document.getElementById('signatureInput').value = '';
            const hint = document.getElementById('signatureHint');
            if (hint) hint.textContent = 'Dessinez votre signature ci-dessus.';
        };

        const form = canvas.closest('form');
        if (form) {
            const signatureInput = document.getElementById('signatureInput');
            const signatureHint = document.getElementById('signatureHint');
            form.addEventListener('submit', function (e) {
                debugSignature();
                if (!hasSignature) {
                    e.preventDefault();
                    alert('Veuillez dessiner votre signature avant de créer le contrat.');
                    return;
                }
                const dataUrl = canvas.toDataURL('image/png');
                signatureInput.value = dataUrl;
                if (signatureHint) signatureHint.textContent = 'Signature capturée.';
            });
        }
    });
</script>
@endpush

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.contracts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux contrats
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Nouveau contrat de bail</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Remplissez les informations du locataire et du bien.</p>
</div>

<form action="{{ route('owner.contracts.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    <div class="lg:col-span-2 space-y-6">
        {{-- Tenant Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-primary"></i> Informations du locataire
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Nom complet du locataire" name="tenant_name" icon="user"
                    placeholder="Jean Dupont" :value="old('tenant_name')" />
                <x-form.input label="Téléphone" name="tenant_phone" icon="phone"
                    placeholder="06 800 00 00" :value="old('tenant_phone')" />
                <x-form.input label="Email" name="tenant_email" icon="mail" type="email"
                    placeholder="locataire@email.com" :value="old('tenant_email')" />
            </div>
        </div>

        {{-- Contract Terms --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Conditions du bail
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Date de début" name="start_date" icon="calendar"
                    type="date" :value="old('start_date')" />
                <x-form.input label="Date de fin (optionnel)" name="end_date" icon="calendar"
                    type="date" :value="old('end_date')" />
                <x-form.input label="Loyer mensuel (FCFA)" name="monthly_rent" icon="banknote"
                    type="number" placeholder="150000" :value="old('monthly_rent')" />
                <x-form.input label="Dépôt de garantie (FCFA)" name="deposit" icon="shield"
                    type="number" placeholder="300000" :value="old('deposit')" />
            </div>
        </div>

        {{-- Owner Signature --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="pen-tool" class="w-4 h-4 text-primary"></i> Signature du propriétaire
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Dessinez votre signature pour signer le contrat dès sa création.</p>
            <input type="hidden" name="signature" id="signatureInput">
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white">
                <canvas id="signatureCanvas" width="800" height="200" class="w-full h-48 cursor-crosshair"></canvas>
            </div>
            <button type="button" onclick="clearSignature()" class="mt-2 text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                Effacer la signature
            </button>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-primary"></i> Bien concerné
            </h3>
            <x-form.select label="Propriété" name="property_id" icon="home"
                placeholder="Sélectionner un bien"
                :options="$properties->pluck('title', 'id')->toArray()" />

            <div class="mt-4">
                <x-form.select label="Statut du contrat" name="status" icon="badge-check"
                    placeholder="Statut"
                    :options="['active' => 'Actif', 'pending_owner' => 'En attente propriétaire', 'terminated' => 'Résilié']" />
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 text-sm text-blue-700 dark:text-blue-300">
            <div class="flex gap-2">
                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <p>Un échéancier de 12 mois de loyer sera automatiquement généré lors de la création du contrat.</p>
            </div>
        </div>

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
            <i data-lucide="save" class="w-4 h-4"></i>
            Créer le contrat
        </button>
    </div>
</form>
@endsection
