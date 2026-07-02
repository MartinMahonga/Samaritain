@extends('layouts.base')

@section('title', 'Contacter l\'agence - '.($contactable->title ?? $contactable->titre))

@section('content')
    <div class="font-body bg-background dark:bg-gray-950 text-[#0F0E0C] dark:text-white antialiased min-h-screen">
        <div class="max-w-4xl mx-auto px-6 py-10 pb-20">

            {{-- Breadcrumb --}}
            <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-[#6B6660] dark:text-gray-400 mb-10 font-body">
                <a href="{{ route('index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Accueil</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                @if ($type === 'property')
                    <a href="{{ route('property.index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Propriétés</a>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <a href="{{ route('property.show', $contactable) }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">{{ $contactable->title }}</a>
                @else
                    <a href="{{ route('parcelles.index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Parcelles</a>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <a href="{{ route('parcelles.show', $contactable) }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">{{ $contactable->titre }}</a>
                @endif
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="dark:text-gray-300">Contacter l'agence</span>
            </nav>

            {{-- Header --}}
            <header class="mb-10">
                <h1 class="font-display font-semibold leading-[1.1] tracking-tight text-[#0F0E0C] dark:text-white"
                    style="font-size: clamp(1.8rem, 3.5vw, 2.5rem);">
                    Contacter l'agence
                </h1>
                <p class="text-[#6B6660] dark:text-gray-400 mt-2 text-sm">
                    Remplissez le formulaire ci-dessous pour nous envoyer un message concernant ce bien.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 items-start">

                {{-- ── FORM ── --}}
                <div>
                    <form method="POST" action="{{ $type === 'property' ? route('property.contact.store', $contactable) : route('parcelles.contact.store', $contactable) }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <x-form.input
                                name="name"
                                label="Nom"
                                placeholder="Votre nom"
                                :value="old('name')"
                                required
                            />

                            <x-form.input
                                name="email"
                                type="email"
                                label="Email"
                                placeholder="votre@email.com"
                                :value="old('email')"
                                required
                            />
                        </div>

                        <x-form.input
                            name="phone"
                            type="tel"
                            label="Téléphone (optionnel)"
                            placeholder="+242 XX XXX XX XX"
                            :value="old('phone')"
                        />

                        <x-form.input
                            name="subject"
                            label="Sujet"
                            placeholder="Sujet de votre message"
                            :value="old('subject', $type === 'property' ? 'Question à propos de '.$contactable->title : 'Question à propos de '.$contactable->titre)"
                            required
                        />

                        <x-form.textarea
                            name="message"
                            label="Message"
                            placeholder="Écrivez votre message ici..."
                            :value="old('message')"
                            rows="6"
                            required
                        />

                        <div class="pt-2">
                            <button type="submit"
                                class="inline-flex w-full sm:w-auto items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] bg-[var(--primary)] text-[var(--primary-foreground)] hover:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] focus:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] active:bg-[var(--primary)] h-9 text-center px-6 py-2 cursor-pointer">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── RIGHT COLUMN : Property info ── --}}
                <div class="lg:sticky lg:top-6">
                    <div class="border border-accent rounded-2xl p-5 space-y-4">
                        {{-- Image --}}
                        @php
                            $imageUrl = null;
                            if ($type === 'property' && $contactable->cover_image_url) {
                                $imageUrl = $contactable->cover_image_url;
                            } elseif ($type === 'parcelle' && $contactable->imagePrincipale) {
                                $imageUrl = $contactable->imagePrincipale->url;
                            }
                        @endphp

                        @if ($imageUrl)
                            <div class="rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800" style="height: 180px;">
                                <img src="{{ $imageUrl }}" alt="{{ $contactable->title ?? $contactable->titre }}" class="w-full h-full object-cover" />
                            </div>
                        @endif

                        <div>
                            <p class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                {{ $type === 'property' ? 'Bien immobilier' : 'Parcelle' }}
                            </p>
                            <h3 class="font-display font-semibold text-lg text-[#0F0E0C] dark:text-white">
                                {{ $contactable->title ?? $contactable->titre }}
                            </h3>
                        </div>

                        <hr class="border-accent">

                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">Référence</span>
                                <p class="text-[#0F0E0C] dark:text-white font-medium">{{ $contactable->reference }}</p>
                            </div>

                            <div>
                                <span class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">Localisation</span>
                                <p class="text-[#6B6660] dark:text-gray-300">
                                    @if ($type === 'property')
                                        {{ $contactable->address }}{{ $contactable->address && $contactable->city ? ', ' : '' }}{{ $contactable->city->name ?? '' }}
                                    @else
                                        {{ $contactable->quartier }}, {{ $contactable->ville }}
                                    @endif
                                </p>
                            </div>

                            @php
                                $price = $type === 'property' ? $contactable->price : $contactable->prix;
                            @endphp
                            @if ($price)
                                <div>
                                    <span class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">Prix</span>
                                    <p class="text-primary font-bold text-lg">{{ number_format($price, 0, ',', ' ') }} FCFA</p>
                                </div>
                            @endif
                        </div>

                        <hr class="border-accent">

                        <a href="{{ $type === 'property' ? route('property.show', $contactable) : route('parcelles.show', $contactable) }}"
                            class="inline-flex w-full items-center justify-center gap-x-1.5 text-sm/5 font-medium text-primary hover:text-primary/80 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Retour à la fiche
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection