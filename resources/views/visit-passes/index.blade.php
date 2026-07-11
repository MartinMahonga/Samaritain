@extends('layouts.base')

@section('title', 'Mes pass visite')

@section('content')
<x-ui.user-dashboard-nav />
<div class="font-body bg-background dark:bg-gray-950 text-[#0F0E0C] dark:text-white antialiased min-h-screen">
    <div class="max-w-5xl mx-auto px-6 py-10 pb-20">

        {{-- Breadcrumb --}}
        <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-[#6B6660] dark:text-gray-400 mb-10 font-body">
            <a href="{{ route('index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Accueil</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="dark:text-gray-300">Mes pass visite</span>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display font-semibold text-3xl">Mes pass visite</h1>
        </div>

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

        @if($visitPasses->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                    <i data-lucide="ticket" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucun pass visite</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Vous n'avez pas encore de pass visite.</p>
                <a href="{{ route('property.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary text-white px-6 py-3 text-sm font-semibold hover:bg-primary/90 transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Parcourir les propriétés
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($visitPasses as $visitPass)
                    <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 transition hover:shadow-md">
                        <div class="flex h-full">
                            {{-- Property image --}}
                            <div class="w-32 shrink-0">
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

                            {{-- Content --}}
                            <div class="flex-1 p-4 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-display font-semibold text-sm dark:text-white line-clamp-1">
                                            {{ $visitPass->property->title }}
                                        </h3>
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
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[0.65rem] font-medium rounded-full shrink-0 bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                            {{ $config['label'] }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        {{ $visitPass->property->city->name ?? 'Brazzaville' }}
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                                            {{ $visitPass->reference }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                            Achat: {{ $visitPass->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($visitPass->expires_at)
                                            <span class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                                Expire: {{ $visitPass->expires_at->format('d/m/Y H:i') }}
                                                @if($visitPass->isActive())
                                                    @php
                                                        $daysLeft = now()->diffInDays($visitPass->expires_at, false);
                                                        $hoursLeft = now()->diffInHours($visitPass->expires_at, false);
                                                    @endphp
                                                    @if($daysLeft > 0)
                                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">({{ $daysLeft }} j)</span>
                                                    @elseif($hoursLeft > 0)
                                                        <span class="text-amber-600 dark:text-amber-400 font-semibold">({{ $hoursLeft }} h)</span>
                                                    @endif
                                                @endif
                                            </span>
                                        @endif
                                        @if($visitPass->isPaid())
                                            <span class="font-semibold text-primary dark:text-primary-400">
                                                {{ number_format($visitPass->amount, 0, ',', ' ') }} FCFA
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('my-visit-passes.show', $visitPass) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        Voir
                                    </a>

                                    @if($visitPass->isDownloadable())
                                        <a href="{{ route('my-visit-passes.download', $visitPass) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                            Télécharger
                                        </a>
                                    @endif

                                    @if($visitPass->isPaymentFailed())
                                        <form action="{{ route('my-visit-passes.retry-payment', $visitPass) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition-colors">
                                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                                Payer
                                            </button>
                                        </form>
                                    @endif

                                    @can('delete', $visitPass)
                                        <div x-data="{ showConfirm: false }" class="relative inline-block">
                                            <button @click="showConfirm = true" type="button"
                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                Supprimer
                                            </button>
                                            <div x-show="showConfirm" @click.away="showConfirm = false" x-cloak
                                                class="absolute z-10 bottom-full left-0 mb-2 w-48 p-3 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 text-xs">
                                                <p class="mb-2 text-gray-700 dark:text-gray-300 font-medium">Confirmer la suppression ?</p>
                                                <div class="flex gap-2 justify-end">
                                                    <button @click="showConfirm = false" type="button"
                                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-200 transition-colors">Non</button>
                                                    <form action="{{ route('my-visit-passes.destroy', $visitPass) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-red-600 rounded text-white hover:bg-red-700 transition-colors">Oui</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $visitPasses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection