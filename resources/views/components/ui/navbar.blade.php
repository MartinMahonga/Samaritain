<nav x-data="{ isOpen: false, scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })" :class="scrolled ? 'bg-background/95 shadow-sm' : 'bg-background/80'"
    class="sticky top-0 z-50 backdrop-blur-md border-b border-gray-100 transition-all duration-200">

    {{-- Desktop --}}
    <div class="hidden md:flex justify-between items-center max-w-7xl mx-auto px-6 h-16">

        {{-- Logo --}}
        <a href="{{ route('index') }}" class="flex items-center gap-2 group">
            <x-ui.logo />
        </a>

        {{-- Nav links --}}
        <div class="flex items-center gap-1">
            <a href="{{ route('index') }}"
                class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition">
                Accueil
            </a>
            <a href="{{ route('property.index') }}"
                class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition">
                Maisons
            </a>
            <a href="{{ route('parcelles.index') }}"
                class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition">
                Parcelles à vendre
            </a>
            <a href="{{ route('artisans.index') }}"
                class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition">
                Services
            </a>
            @if (!auth()->user())
                <a href="{{ route('property.create') }}" class="text-sm underline hover:text-primary">
                    Publier une annonce
                </a>
                <a href="{{ route('artisan.create') }}" class="text-sm underline hover:text-primary">
                    Devenir artisan
                </a>
            @endif
        </div>

        {{-- CTA + Auth --}}
        <div class="flex items-center gap-3">

            @if (auth()->user())
                {{-- Avatar + menu --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 hover:opacity-80 transition">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image }}" alt="{{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover border-2 border-primary/20 shadow-sm">
                        @else
                            <div
                                class="w-8 h-8 rounded-full bg-primary/10 text-primary font-semibold text-sm flex items-center justify-center border-2 border-primary/20">
                                {{ strtoupper(auth()->user()->name[0]) }}
                            </div>
                        @endif
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400" :class="open && 'rotate-180'"
                            style="transition: transform .2s"></i>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 text-sm">
                        <p class="px-3 py-2 text-xs text-gray-400 border-b border-gray-100">
                            {{ auth()->user()->name }}</p>
                        <a href="#"
                            class="flex items-center rounded-xl gap-2 m-1 px-2 py-2 text-gray-700 hover:bg-gray-50">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i> Mon profil
                        </a>
                        <a href="{{ route('property.dashboard') }}"
                            class="flex items-center rounded-xl gap-2 m-1 px-2 py-2 text-gray-700 hover:bg-gray-50">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-gray-400"></i> Tableau de bord
                        </a>
                        <a href="{{ route('property.create') }}" @click="isOpen = false"
                            class="flex items-center mb-2 gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i data-lucide="warehouse" class="w-4 h-4 text-gray-400"></i> Publier un bien
                        </a>
                        @if (!auth()->user()->artisan())
                            <a href="{{ route('artisan.create') }}" @click="isOpen = false"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i data-lucide="drill" class="w-4 h-4 text-gray-400"></i> Devenir artisan
                            </a>
                        @else
                            <a href="{{ route('artisan.dashboard') }}" @click="isOpen = false"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i data-lucide="drill" class="w-4 h-4 text-gray-400"></i> Profil artisan
                            </a>
                        @endif
                        <a href="{{ route('favorite') }}"
                            class="flex items-center rounded-xl gap-2 m-1 px-2 py-2 text-gray-700 hover:bg-gray-50">
                            <i data-lucide="heart" class="w-3.5 h-3.5 text-gray-400"></i> Mes favoris
                        </a>
                        <div class="border-t border-gray-100 mt-1 p-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-1 rounded-xl px-2 py-2 text-red-500 hover:bg-red-50 text-left">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-primary transition">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                    class="bg-primary text-white text-sm font-semibold px-4 py-2 rounded-full hover:opacity-90 transition shadow-sm">
                    S'inscrire
                </a>
            @endif
        </div>
    </div>

    {{-- Mobile --}}
    <div class="flex md:hidden justify-between items-center px-4 h-14">

        <a href="{{ route('index') }}" class="flex items-center gap-2">
            <x-ui.logo />
        </a>

        <div class="flex items-center gap-2">
            @if (auth()->user())
                @if (auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image }}" alt="{{ auth()->user()->name }}"
                        class="w-7 h-7 rounded-full object-cover border border-gray-200">
                @else
                    <div
                        class="w-7 h-7 rounded-full bg-primary/10 text-primary font-semibold text-xs flex items-center justify-center">
                        {{ strtoupper(auth()->user()->name[0]) }}
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="text-xs font-medium text-gray-700 border border-gray-200 px-3 py-1.5 rounded-full hover:border-primary hover:text-primary transition">
                    Se connecter
                </a>
            @endif

            <button @click="isOpen = !isOpen"
                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition">
                <i x-show="!isOpen" data-lucide="menu" class="w-4 h-4 text-gray-700"></i>
                <i x-show="isOpen" x-cloak data-lucide="x" class="w-4 h-4 text-gray-700"></i>
            </button>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="isOpen" x-cloak @click.outside="isOpen = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden absolute inset-x-0 top-full z-50 bg-white border-t border-gray-100 shadow-xl rounded-b-2xl">
        <div class="flex flex-col gap-1 max-w-7xl mx-auto px-4 py-4">
            <a href="{{ route('index') }}" @click="isOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i data-lucide="home" class="w-4 h-4 text-gray-400"></i> Accueil
            </a>
            <a href="{{ route('property.index') }}" @click="isOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i data-lucide="warehouse" class="w-4 h-4 text-gray-400"></i> Maisons
            </a>
            <a href="{{ route('parcelles.index') }}" @click="isOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i data-lucide="land-plot" class="w-4 h-4 text-gray-400"></i> Parcelles à vendre
            </a>
            <a href="{{ route('artisans.index') }}" @click="isOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i> Services
            </a>
            <a href="{{ route('property.create') }}" @click="isOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i data-lucide="warehouse" class="w-4 h-4 text-gray-400"></i> Publier un bien
            </a>
            @if (!auth()->user()->artisan())
                <a href="{{ route('artisan.create') }}" @click="isOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i data-lucide="drill" class="w-4 h-4 text-gray-400"></i> Devenir artisan
                </a>
            @else
                <a href="{{ route('artisan.dashboard') }}" @click="isOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i data-lucide="drill" class="w-4 h-4 text-gray-400"></i> Profil artisan
                </a>
            @endif

            <div class="border-t border-gray-100 my-2"></div>
            <a href="tel:+242068007138"
                class="flex items-center justify-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-3 rounded-xl">
                <i data-lucide="phone" class="w-4 h-4"></i> +242 06 800 71 38
            </a>

            @if (auth()->user())
                <form action="{{ route('logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 border border-red-200 text-red-500 text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-red-50 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}"
                    class="flex items-center justify-center gap-2 bg-gray-900 text-white text-sm font-semibold px-4 py-3 rounded-xl mt-1 hover:opacity-90 transition">
                    S'inscrire gratuitement
                </a>
            @endif
        </div>
    </div>
</nav>
