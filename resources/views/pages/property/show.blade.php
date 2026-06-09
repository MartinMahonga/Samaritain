@extends('layouts.base')

@section('title', $property->title)

@section('content')

    <div class="font-body bg-background text-[#0F0E0C] antialiased min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-10 pb-20">

            {{-- Breadcrumb --}}
            <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-[#6B6660] mb-10 font-body">
                <a href="{{ route('index') }}" class="hover:text-primary transition-colors">Accueil</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('property.index') }}" class="hover:text-primary transition-colors">Propriétés</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span>{{ $property->title }}</span>
            </nav>

            {{-- Header --}}
            <header class="mb-8">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        {{-- Status + type --}}
                        <div class="flex items-center gap-3 mb-3">
                            @php
                                $statusMap = ['disponible' => 'available', 'loué' => 'rented', 'vendu' => 'sold'];
                                $statusKey = $statusMap[strtolower($property->status->value)] ?? 'available';
                                $statusStyles = [
                                    'available' => 'bg-[#D6F0DC] text-[#1E6B35]',
                                    'rented' => 'bg-[#FEF3C7] text-[#92400E]',
                                    'sold' => 'bg-[#FEE2E2] text-[#991B1B]',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center gap-1.5 text-[0.68rem] font-semibold tracking-widest px-3 py-1 rounded-full {{ $statusStyles[$statusKey] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $property->status }}
                            </span>
                            @if ($property->category)
                                <span
                                    class="text-[0.68rem] font-medium tracking-widest text-[#6B6660]">{{ $property->category->name }}</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="font-display font-semibold leading-[1.1] tracking-tight text-[#0F0E0C]"
                            style="font-size: clamp(2rem, 4.5vw, 3.5rem); max-width: 28ch;">
                            {{ $property->title }}
                        </h1>

                        {{-- Location --}}
                        <div class="flex items-center gap-2 mt-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#6B6660]"></i>
                            <span class="text-[0.83rem] text-[#6B6660]">
                                {{ $property->address ?? '' }}{{ $property->address && $property->city ? ', ' : '' }}{{ $property->city->name ?? 'Brazzaville' }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ── Main grid ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-12 items-start">

                {{-- ── LEFT COLUMN ── --}}
                <div class="min-w-0">

                    {{-- Gallery --}}
                    <x-ui.property-gallery :property="$property" />

                    {{-- Feature strip --}}
                    <x-ui.feature-strip :property="$property" />

                    {{-- Description --}}
                    @if ($property->description)
                        <x-ui.property-description :property="$property" />
                    @endif

                    {{-- Amenities --}}
                    @if ($property->amenities->isNotEmpty())
                        <x-ui.property-amenity :property="$property" />
                    @endif

                </div>

                <x-ui.info-aside :property="$property" />

            </div>


            <x-ui.similar-properties :properties="$similarProperties" />
        </div>
    </div>
@endsection
