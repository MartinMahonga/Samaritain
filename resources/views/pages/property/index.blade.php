@extends('layouts.base')

@section('title', 'Tous les biens')

@section('content')
    <style>
        :root {
            --cream: #FAFAF8;
            --ink: #1A1A18;
            --gold: #C9A96E;
            --gold-lt: #F0E6D3;
            --muted: #6B6B66;
            --border: #E8E6E1;
            --white: #FFFFFF;
            --card-shadow: 0 2px 16px rgba(0, 0, 0, .07);
            --card-shadow-hover: 0 12px 40px rgba(0, 0, 0, .13);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* ── Page header ── */
        .listing-header {
            padding: 2.5rem 0 1.5rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 2rem;
        }

        .listing-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 700;
            color: var(--ink);
            line-height: 1.2;
        }

        .listing-header .subtitle {
            font-size: .85rem;
            color: var(--muted);
            margin-top: .35rem;
        }

        .listing-header .subtitle strong {
            color: var(--ink);
            font-weight: 600;
        }

        /* ── Top filter bar ── */
        .filter-bar {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            align-items: flex-end;
            margin-bottom: 1.75rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: .3rem;
            flex: 1;
            min-width: 130px;
        }

        .filter-group label {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--muted);
        }

        .filter-group select,
        .filter-group input {
            font-family: 'Inter', sans-serif;
            font-size: .85rem;
            color: var(--ink);
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: .55rem;
            padding: .55rem .8rem;
            outline: none;
            transition: border-color .2s;
            appearance: none;
            -webkit-appearance: none;
            background-image: none;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: var(--gold);
        }

        .filter-group.select-wrap {
            position: relative;
        }

        .filter-group.select-wrap::after {
            content: '';
            position: absolute;
            bottom: .75rem;
            right: .8rem;
            width: .5rem;
            height: .5rem;
            border-right: 1.5px solid var(--muted);
            border-bottom: 1.5px solid var(--muted);
            transform: rotate(45deg);
            pointer-events: none;
        }

        .btn-filter {
            font-family: 'Inter', sans-serif;
            font-size: .85rem;
            font-weight: 600;
            padding: .58rem 1.5rem;
            border-radius: .55rem;
            cursor: pointer;
            border: none;
            transition: background .2s, color .2s;
            white-space: nowrap;
            align-self: flex-end;
        }

        .btn-filter.primary {
            background: var(--ink);
            color: #fff;
        }

        .btn-filter.primary:hover {
            background: #333;
        }

        .btn-filter.reset {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .btn-filter.reset:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        /* ── Active filters ── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            margin-bottom: 1.25rem;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .75rem;
            font-weight: 500;
            color: var(--ink);
            background: var(--gold-lt);
            border: 1px solid #dfc89a;
            padding: .3rem .7rem;
            border-radius: 999px;
        }

        .filter-tag button {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 0;
            line-height: 1;
            display: flex;
        }

        .filter-tag button:hover {
            color: var(--ink);
        }

        /* ── Toolbar (count + view toggle + sort) ── */
        .listing-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1.5rem;
        }

        .count-label {
            font-size: .85rem;
            color: var(--muted);
        }

        .count-label strong {
            color: var(--ink);
            font-weight: 600;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .sort-select {
            font-family: 'Inter', sans-serif;
            font-size: .8rem;
            color: var(--ink);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: .55rem;
            padding: .45rem 2rem .45rem .75rem;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236B6B66'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .65rem center;
        }

        .view-toggle {
            display: flex;
            border: 1px solid var(--border);
            border-radius: .55rem;
            overflow: hidden;
            background: var(--white);
        }

        .view-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--muted);
            transition: background .15s, color .15s;
        }

        .view-btn.active {
            background: var(--ink);
            color: #fff;
        }

        .view-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* ── Grid layout ── */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
        }

        .properties-grid.list-view {
            grid-template-columns: 1fr;
        }

        /* ── Property card ── */
        .prop-card {
            background: var(--white);
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--card-shadow);
            transition: box-shadow .25s, transform .25s;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .prop-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-3px);
        }

        .list-view .prop-card {
            flex-direction: row;
            border-radius: .875rem;
        }

        /* Photo */
        .prop-photo {
            position: relative;
            overflow: hidden;
            aspect-ratio: 3/2;
            flex-shrink: 0;
            background: var(--border);
        }

        .list-view .prop-photo {
            aspect-ratio: unset;
            width: 260px;
            min-height: 180px;
        }

        .prop-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }

        .prop-card:hover .prop-photo img {
            transform: scale(1.05);
        }

        /* Price overlay */
        .photo-price {
            position: absolute;
            bottom: .75rem;
            left: .75rem;
            background: rgba(26, 26, 24, .82);
            backdrop-filter: blur(6px);
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            padding: .35rem .8rem;
            border-radius: .5rem;
            display: flex;
            align-items: baseline;
            gap: .3rem;
        }

        .photo-price sub {
            font-family: 'Inter', sans-serif;
            font-size: .65rem;
            font-weight: 400;
            color: #bbb;
            vertical-align: middle;
        }

        /* Status badge on photo */
        .photo-status {
            position: absolute;
            top: .75rem;
            right: .75rem;
            font-family: 'Inter', sans-serif;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            padding: .28rem .65rem;
            border-radius: 999px;
        }

        .photo-status.available {
            background: #e6f4ea;
            color: #2e7d32;
        }

        .photo-status.rented {
            background: #fff8e1;
            color: #b45309;
        }

        .photo-status.sold {
            background: #fce4e4;
            color: #991b1b;
        }

        /* Wishlist button */
        .btn-wishlist {
            position: absolute;
            top: .75rem;
            left: .75rem;
            width: 2rem;
            height: 2rem;
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(4px);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            transition: color .2s, background .2s;
        }

        .btn-wishlist:hover {
            color: #e53e3e;
            background: #fff;
        }

        .btn-wishlist svg {
            width: .9rem;
            height: .9rem;
        }

        /* Card body */
        .prop-body {
            padding: 1.1rem 1.25rem 1.25rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .list-view .prop-body {
            padding: 1.25rem 1.5rem;
        }

        .prop-type {
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gold);
            margin-bottom: .4rem;
        }

        .prop-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.35;
            margin-bottom: .4rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .list-view .prop-title {
            font-size: 1.15rem;
            -webkit-line-clamp: 1;
        }

        .prop-location {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: .85rem;
        }

        .prop-location svg {
            width: .85rem;
            height: .85rem;
            flex-shrink: 0;
        }

        .prop-description {
            font-size: .8rem;
            color: #555;
            line-height: 1.65;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: .85rem;
        }

        /* Feature chips */
        .prop-features {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-top: auto;
            padding-top: .85rem;
            border-top: 1px solid var(--border);
        }

        .feat-chip {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            font-weight: 500;
            color: var(--muted);
            background: var(--cream);
            border: 1px solid var(--border);
            padding: .25rem .6rem;
            border-radius: .4rem;
        }

        .feat-chip svg {
            width: .75rem;
            height: .75rem;
        }

        /* ── Empty state ── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-state svg {
            color: var(--border);
            width: 3.5rem;
            height: 3.5rem;
            margin: 0 auto 1.25rem;
            display: block;
        }

        .empty-state h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--ink);
            margin-bottom: .5rem;
        }

        .empty-state p {
            font-size: .85rem;
            color: var(--muted);
            max-width: 36ch;
            margin: 0 auto;
        }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .page-btn {
            font-family: 'Inter', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .5rem;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--ink);
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, border-color .15s, color .15s;
        }

        .page-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .page-btn.active {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
        }

        .page-btn.disabled {
            opacity: .35;
            pointer-events: none;
        }

        .page-btn svg {
            width: .85rem;
            height: .85rem;
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .properties-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .properties-grid {
                grid-template-columns: 1fr;
            }

            .filter-bar {
                flex-direction: column;
            }

            .filter-group {
                min-width: 100%;
            }

            .list-view .prop-card {
                flex-direction: column;
            }

            .list-view .prop-photo {
                width: 100%;
                min-height: unset;
                aspect-ratio: 3/2;
            }
        }
    </style>
    <div style="padding-bottom: 4rem;">
        <x-blade-components::layout.container>

            {{-- ── Page header ── ──────────────────────────── --}}
            <div class="listing-header">
                <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                    <div>
                        <h1>Nos biens disponibles</h1>
                        <p class="subtitle">
                            <strong>{{ $properties->total() }}</strong> bien{{ $properties->total() > 1 ? 's' : '' }}
                            trouvé{{ $properties->total() > 1 ? 's' : '' }}
                            @if (request('city'))
                                dans <strong>{{ request('city') }}</strong>
                            @endif
                        </p>
                    </div>
                    {{-- Breadcrumb --}}
                    <nav style="display:flex; align-items:center; gap:.4rem; font-size:.78rem; color:var(--muted);">
                        <a href="{{ route('home') }}" style="color:var(--muted); text-decoration:none;">Accueil</a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="width:.7rem;height:.7rem;">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                        <span style="color:var(--ink);">Propriétés</span>
                    </nav>
                </div>
            </div>

            {{-- ── Filter bar ── ─────────────────────────────── --}}
            <form method="GET" action="{{ route('property.index') }}" id="filter-form">
                <div class="filter-bar">

                    {{-- Type --}}
                    <div class="filter-group select-wrap">
                        <label for="f-type">Type de bien</label>
                        <select name="type" id="f-type">
                            <option value="">Tous les types</option>
                            <option value="maison" {{ request('type') === 'maison' ? 'selected' : '' }}>Maison</option>
                            <option value="appartement"{{ request('type') === 'appartement' ? 'selected' : '' }}>Appartement
                            </option>
                            <option value="villa" {{ request('type') === 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="bureau" {{ request('type') === 'bureau' ? 'selected' : '' }}>Bureau</option>
                            <option value="terrain" {{ request('type') === 'terrain' ? 'selected' : '' }}>Terrain
                            </option>
                        </select>
                    </div>

                    {{-- Ville --}}
                    <div class="filter-group select-wrap">
                        <label for="f-city">Ville</label>
                        <select name="city" id="f-city">
                            <option value="">Toutes les villes</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Transaction --}}
                    <div class="filter-group select-wrap">
                        <label for="f-transaction">Transaction</label>
                        <select name="transaction" id="f-transaction">
                            <option value="">Location & vente</option>
                            <option value="rent" {{ request('transaction') === 'rent' ? 'selected' : '' }}>À louer
                            </option>
                            <option value="sale" {{ request('transaction') === 'sale' ? 'selected' : '' }}>À vendre
                            </option>
                        </select>
                    </div>

                    {{-- Budget max --}}
                    <div class="filter-group">
                        <label for="f-budget">Budget max (XAF)</label>
                        <input type="number" name="max_price" id="f-budget" placeholder="ex. 500 000"
                            value="{{ request('max_price') }}">
                    </div>

                    {{-- Surface min --}}
                    <div class="filter-group">
                        <label for="f-surface">Surface min (m²)</label>
                        <input type="number" name="min_surface" id="f-surface" placeholder="ex. 80"
                            value="{{ request('min_surface') }}">
                    </div>

                    <button type="submit" class="btn-filter primary">Rechercher</button>
                    @if (request()->hasAny(['type', 'city', 'transaction', 'max_price', 'min_surface']))
                        <a href="{{ route('property.index') }}" class="btn-filter reset"
                            style="text-decoration:none;">Effacer</a>
                    @endif
                </div>
            </form>

            {{-- ── Active filter tags ── ───────────────────────── --}}
            @if (request()->hasAny(['type', 'city', 'transaction', 'max_price', 'min_surface']))
                <div class="active-filters">
                    @if (request('type'))
                        <span class="filter-tag">
                            {{ ucfirst(request('type')) }}
                            <button type="button" onclick="removeFilter('type')" title="Retirer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" style="width:.7rem;height:.7rem;">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if (request('transaction'))
                        <span class="filter-tag">
                            {{ request('transaction') === 'rent' ? 'À louer' : 'À vendre' }}
                            <button type="button" onclick="removeFilter('transaction')" title="Retirer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" style="width:.7rem;height:.7rem;">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if (request('max_price'))
                        <span class="filter-tag">
                            Max {{ number_format(request('max_price'), 0, ',', ' ') }} XAF
                            <button type="button" onclick="removeFilter('max_price')" title="Retirer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" style="width:.7rem;height:.7rem;">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if (request('min_surface'))
                        <span class="filter-tag">
                            Min {{ request('min_surface') }} m²
                            <button type="button" onclick="removeFilter('min_surface')" title="Retirer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" style="width:.7rem;height:.7rem;">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>
            @endif

            {{-- ── Toolbar ── ───────────────────────────────────── --}}
            <div class="listing-toolbar">
                <p class="count-label">
                    Affichage de <strong>{{ $properties->firstItem() ?? 0 }}–{{ $properties->lastItem() ?? 0 }}</strong>
                    sur <strong>{{ $properties->total() }}</strong> résultats
                </p>
                <div class="toolbar-right">
                    <select class="sort-select" name="sort" onchange="applySort(this.value)">
                        <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Plus récents</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant
                        </option>
                        <option value="price_desc"{{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant
                        </option>
                        <option value="surface" {{ request('sort') === 'surface' ? 'selected' : '' }}>Surface</option>
                    </select>
                    <div class="view-toggle">
                        <button class="view-btn active" id="btn-grid" onclick="setView('grid')" title="Vue grille"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="14" y="14" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                            </svg>
                        </button>
                        <button class="view-btn" id="btn-list" onclick="setView('list')" title="Vue liste"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6" />
                                <line x1="8" y1="12" x2="21" y2="12" />
                                <line x1="8" y1="18" x2="21" y2="18" />
                                <line x1="3" y1="6" x2="3.01" y2="6" />
                                <line x1="3" y1="12" x2="3.01" y2="12" />
                                <line x1="3" y1="18" x2="3.01" y2="18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Property grid ── ────────────────────────────── --}}
            <div class="properties-grid" id="properties-grid">

                @forelse ($properties as $property)
                    <x-ui.property-card :property="$property" />
                @empty
                    {{-- Empty state --}}
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <h3>Aucun bien trouvé</h3>
                        <p>Essayez d'élargir vos critères de recherche ou de retirer certains filtres.</p>
                        <a href="{{ route('property.index') }}"
                            style="display:inline-block; margin-top:1.25rem; font-size:.85rem; font-weight:600; color:var(--gold); text-decoration:none;">
                            Voir tous les biens →
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- ── Pagination ── ──────────────────────────────── --}}
            @if ($properties->hasPages())
                <div class="pagination">
                    {{-- Previous --}}
                    @if ($properties->onFirstPage())
                        <span class="page-btn disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $properties->previousPageUrl() }}" class="page-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($properties->getUrlRange(max(1, $properties->currentPage() - 2), min($properties->lastPage(), $properties->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}"
                            class="page-btn {{ $page == $properties->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if ($properties->hasMorePages())
                        <a href="{{ $properties->nextPageUrl() }}" class="page-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                    @else
                        <span class="page-btn disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </span>
                    @endif
                </div>
            @endif

        </x-blade-components::layout.container>
    </div>
@endsection

@push('scripts')
    <script>
        // ── View toggle (grid / list) ──────────────────────
        function setView(mode) {
            const grid = document.getElementById('properties-grid');
            const btnG = document.getElementById('btn-grid');
            const btnL = document.getElementById('btn-list');

            if (mode === 'list') {
                grid.classList.add('list-view');
                btnL.classList.add('active');
                btnG.classList.remove('active');
            } else {
                grid.classList.remove('list-view');
                btnG.classList.add('active');
                btnL.classList.remove('active');
            }
            localStorage.setItem('prop_view', mode);
        }

        // Restore preference
        const savedView = localStorage.getItem('prop_view');
        if (savedView) setView(savedView);

        // ── Sort ───────────────────────────────────────────
        function applySort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }

        // ── Remove single filter tag ───────────────────────
        function removeFilter(param) {
            const url = new URL(window.location.href);
            url.searchParams.delete(param);
            url.searchParams.delete('page'); // reset pagination
            window.location.href = url.toString();
        }

        // ── Wishlist stub ──────────────────────────────────
        function toggleWishlist(btn, id) {
            const svg = btn.querySelector('svg');
            const saved = btn.dataset.saved === '1';
            btn.dataset.saved = saved ? '0' : '1';
            svg.style.fill = saved ? 'none' : '#e53e3e';
            svg.style.stroke = saved ? 'currentColor' : '#e53e3e';
            // Hook your AJAX / Livewire call here
        }
    </script>
@endpush
