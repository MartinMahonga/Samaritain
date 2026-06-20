{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-gray-900 border-t border-gray-800">
    <div class="container mx-auto px-4 py-10 md:py-12">

        {{-- 1ère ligne : Logo + Brand + Newsletter --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-gray-800 pb-10">
            {{-- Logo + Nom --}}
            <div class="flex gap-2 items-center">
                <img src="{{ asset('light_logo.svg') }}" alt="light logo"
                    class="block w-10 h-10 md:w-14 md:h-14 dark:hidden">
                <img src="{{ asset('dark_logo.svg') }}" alt="dark logo"
                    class="hidden w-10 h-10 md:w-14 md:h-14 dark:block">
                <span class="text-xl md:text-2xl font-bold text-primary dark:text-accent">Samartian Immobilier</span>
            </div>

            {{-- Newsletter --}}
            {{-- <div class="w-full md:w-auto">
                <p class="text-secondary dark:text-gray-300 text-sm mb-2 font-medium">Recevez nos offres exclusives</p>
                <form action="#" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    <input type="email" name="email" placeholder="Votre email"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-accent w-full sm:w-64">
                    <button type="submit"
                        class="bg-accent hover:bg-primary text-white font-semibold px-5 py-2 rounded-lg transition duration-200 shadow-sm">
                        S'abonner
                    </button>
                </form>
            </div> --}}
        </div>

        {{-- 2ème ligne : Liens colonnes --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 py-10">
            {{-- Explorer --}}
            <div>
                <h3 class="font-bold text-primary dark:text-accent mb-4 text-lg">Explorer</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Acheter</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Vendre</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Louer</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Estimation
                            gratuite</a></li>
                </ul>
            </div>

            {{-- L'agence --}}
            <div>
                <h3 class="font-bold text-primary dark:text-accent mb-4 text-lg">L'agence</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">À
                            propos</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Nos
                            conseillers</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Carrières</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Avis
                            clients</a></li>
                </ul>
            </div>

            {{-- Ressources --}}
            <div>
                <h3 class="font-bold text-primary dark:text-accent mb-4 text-lg">Ressources</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Guides
                            & conseils</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Documentation</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Statut
                            du marché</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Blog
                            immobilier</a></li>
                </ul>
            </div>

            {{-- Aide --}}
            <div>
                <h3 class="font-bold text-primary dark:text-accent mb-4 text-lg">Aide</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">FAQ</a>
                    </li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Nous
                            contacter</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Signaler
                            un souci</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Support
                            technique</a></li>
                </ul>
            </div>

            {{-- Légal --}}
            <div>
                <h3 class="font-bold text-primary dark:text-accent mb-4 text-lg">Légal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Politique
                            de confidentialité</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Conditions
                            générales</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Mentions
                            légales</a></li>
                    <li><a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition">Cookies</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- 3ème ligne : Réseaux sociaux + Copyright + Badge --}}
        <div
            class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-800 gap-4">
            {{-- Social Links --}}
            <div class="flex gap-5">
                <a href="https://facebook.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary transition"
                    aria-label="Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z" />
                    </svg>
                </a>
                <a href="https://instagram.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary transition"
                    aria-label="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.975.975 1.247 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.063 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.247-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.063-2.633-.335-3.608-1.31-.975-.975-1.247-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.063-1.366.335-2.633 1.31-3.608.975-.975 2.242-1.247 3.608-1.31C8.416 2.175 8.796 2.163 12 2.163zM12 0C8.741 0 8.332.014 7.052.072 5.197.158 3.356.623 2.06 1.92.763 3.217.298 5.058.212 6.914.154 8.194.14 8.603.14 11.866c0 3.263.014 3.672.072 4.952.086 1.856.551 3.697 1.848 4.993 1.296 1.296 3.137 1.762 4.993 1.848 1.28.058 1.689.072 4.952.072 3.263 0 3.672-.014 4.952-.072 1.856-.086 3.697-.551 4.993-1.848 1.296-1.296 1.762-3.137 1.848-4.993.058-1.28.072-1.689.072-4.952 0-3.263-.014-3.672-.072-4.952-.086-1.856-.551-3.697-1.848-4.993C19.843.623 18.002.158 16.146.072 14.866.014 14.457 0 11.194 0H12zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.88 0z" />
                    </svg>
                </a>
                <a href="https://linkedin.com/company/samartian" target="_blank" rel="noopener noreferrer"
                    class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary transition"
                    aria-label="LinkedIn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C0.792 0 0 0.774 0 1.729v20.542C0 23.227 0.792 24 1.771 24h20.451c0.979 0 1.771-0.773 1.771-1.729V1.729C24 0.774 23.205 0 22.225 0z" />
                    </svg>
                </a>
                <a href="https://twitter.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary transition"
                    aria-label="X (Twitter)">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
            </div>

            {{-- Copyright --}}
            <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                <span>© {{ date('Y') }} Samartian Immobilier</span>
                <span class="hidden sm:inline mx-1">•</span>
                <span>Tous droits réservés</span>
            </div>

            {{-- Badge "Made with" --}}
            <div
                class="flex items-center gap-1 text-xs text-gray-500 bg-gray-800/50 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <span>Made with passion</span>
                <span class="font-medium text-primary dark:text-accent">par l'équipe Samartian</span>
            </div>
        </div>

        {{-- Liens supplémentaires style Zen --}}
        <div
            class="mt-6 text-center text-xs text-gray-600 flex flex-wrap justify-center gap-x-4 gap-y-1">
            <a href="#" class="hover:text-primary">Statut des services</a>
            <span>•</span>
            <a href="#" class="hover:text-primary">Sécurité</a>
            <span>•</span>
            <a href="#" class="hover:text-primary">Plan du site</a>
            <span>•</span>
            <a href="#" class="hover:text-primary">Accessibilité</a>
        </div>
    </div>
</footer>
