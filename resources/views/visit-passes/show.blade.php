@extends('layouts.base')

@section('title', 'Pass visite #'.$visitPass->reference)

@section('content')
<div class="font-body bg-background dark:bg-gray-950 text-[#0F0E0C] dark:text-white antialiased min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-10 pb-20">

        {{-- Breadcrumb --}}
        <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-[#6B6660] dark:text-gray-400 mb-10 font-body">
            <a href="{{ route('index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Accueil</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('my-visit-passes.index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Mes pass visite</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="dark:text-gray-300">{{ $visitPass->reference }}</span>
        </nav>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="font-display font-semibold text-3xl">Pass visite</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Réf. {{ $visitPass->reference }}</p>
            </div>

            @php
                $statusConfig = [
                    'pending_payment' => ['color' => 'amber', 'icon' => 'clock', 'label' => 'En attente de paiement'],
                    'active' => ['color' => 'emerald', 'icon' => 'check-circle', 'label' => 'Actif'],
                    'expired' => ['color' => 'red', 'icon' => 'calendar-x', 'label' => 'Expiré'],
                    'cancelled' => ['color' => 'gray', 'icon' => 'ban', 'label' => 'Annulé'],
                    'payment_failed' => ['color' => 'red', 'icon' => 'alert-triangle', 'label' => 'Paiement échoué'],
                ];
                $config = $statusConfig[$visitPass->status] ?? ['color' => 'gray', 'icon' => 'help-circle', 'label' => $visitPass->status];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400">
                <i data-lucide="{{ $config['icon'] }}" class="w-3.5 h-3.5"></i>
                {{ $config['label'] }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">

            {{-- Left column --}}
            <div class="space-y-6">

                {{-- Property info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-lg font-semibold mb-4 dark:text-white">Propriété</h2>
                    <div class="flex gap-4">
                        <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                            @if($visitPass->property->cover_image_url)
                                <img src="{{ $visitPass->property->cover_image_url }}" alt="{{ $visitPass->property->title }}"
                                    class="w-full h-full object-cover">
                            @elseif($visitPass->property->images->isNotEmpty())
                                <img src="{{ $visitPass->property->images->first()->image_url }}" alt="{{ $visitPass->property->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <i data-lucide="home" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-display font-semibold dark:text-white">{{ $visitPass->property->title }}</h3>
                            <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                {{ $visitPass->property->city->name ?? 'Brazzaville' }}
                                @if($visitPass->property->address)
                                    , {{ $visitPass->property->address }}
                                @endif
                            </div>
                            @if($visitPass->property->category)
                                <span class="inline-block text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $visitPass->property->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Visitor info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-lg font-semibold mb-4 dark:text-white">Visiteur</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Nom complet</span>
                            <span class="dark:text-white font-medium">{{ $visitPass->holder_name }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Téléphone</span>
                            <span class="dark:text-white font-medium">{{ $visitPass->phone }}</span>
                        </div>
                        @if($visitPass->email)
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400 text-xs">Email</span>
                                <span class="dark:text-white font-medium">{{ $visitPass->email }}</span>
                            </div>
                        @endif
                        @if($visitPass->comment)
                            <div class="col-span-2">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs">Commentaire</span>
                                <span class="dark:text-white font-medium">{{ $visitPass->comment }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-lg font-semibold mb-4 dark:text-white">Paiement</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Montant</span>
                            <span class="dark:text-white font-semibold text-lg">{{ number_format($visitPass->amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Statut paiement</span>
                            @if($visitPass->isPaid())
                                <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    Payé
                                </span>
                            @elseif($visitPass->isPendingPayment())
                                <span class="text-amber-600 dark:text-amber-400 font-medium flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    En attente
                                </span>
                            @elseif($visitPass->isPaymentFailed())
                                <span class="text-red-600 dark:text-red-400 font-medium flex items-center gap-1">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                    Échoué
                                </span>
                            @endif
                        </div>
                        @if($visitPass->paid_at)
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400 text-xs">Payé le</span>
                                <span class="dark:text-white font-medium">{{ $visitPass->paid_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="space-y-6 lg:sticky lg:top-8">

                {{-- QR Code --}}
                @if($visitPass->isPaid())
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <h2 class="font-display text-sm font-semibold mb-4 dark:text-white">QR Code</h2>
                        <div class="inline-flex p-3 bg-white rounded-lg">
                            <img src="{{ $visitPass->getQrCodeBase64() }}" alt="QR Code du pass"
                                class="w-40 h-40">
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                            Présentez ce QR Code à l'accueil
                        </p>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-sm font-semibold mb-4 dark:text-white">Actions</h2>
                    <div class="space-y-3">
                        @if($visitPass->isDownloadable())
                            <a href="{{ route('my-visit-passes.download', $visitPass) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary text-white px-4 py-2.5 text-sm font-semibold hover:bg-primary/90 transition-colors">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Télécharger le pass
                            </a>
                        @endif

                        @if($visitPass->isPaymentFailed())
                            <form action="{{ route('my-visit-passes.retry-payment', $visitPass) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 text-white px-4 py-2.5 text-sm font-semibold hover:bg-amber-600 transition-colors">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    Réessayer le paiement
                                </button>
                            </form>
                        @endif

                        @if($visitPass->isPendingPayment())
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-xs text-amber-700 dark:text-amber-400">
                                <div class="flex items-start gap-2">
                                    <i data-lucide="info" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                                    <span>Votre paiement est en cours de traitement. La génération du pass se fera automatiquement dès confirmation.</span>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('my-visit-passes.index') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2.5 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>

                {{-- Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-sm font-semibold mb-3 dark:text-white">Informations</h2>
                    <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>Référence</span>
                            <span class="font-mono font-medium dark:text-gray-200">{{ $visitPass->reference }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>UUID</span>
                            <span class="font-mono text-[0.6rem] dark:text-gray-200">{{ $visitPass->uuid }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Créé le</span>
                            <span class="dark:text-gray-200">{{ $visitPass->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection