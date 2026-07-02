<footer class="bg-gray-900 dark:bg-gray-950 border-t border-gray-800 dark:border-gray-800">
    <div class="container mx-auto px-4 py-10 md:py-12">

        {{-- 0ème ligne : Marque + contact --}}
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-8 pb-10 border-b border-gray-800">
            <div class="lg:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-3">
                    <span class="text-xl font-bold text-white">Samaritain</span>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed mb-4 max-w-xs">
                    La plateforme immobilière sans commission au Congo. Achetez, vendez et louez en toute
                    confiance.
                </p>

                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 text-primary dark:text-accent shrink-0"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>Brazzaville, République du Congo</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 text-primary dark:text-accent shrink-0"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z" />
                        </svg>
                        <a href="tel:+242000000000" class="hover:text-primary dark:hover:text-primary transition">+242
                            00 000 0000</a>
                    </li>
                    <li class="flex items-center gap-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 text-primary dark:text-accent shrink-0"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        <a href="mailto:contact@samartian.com"
                            class="hover:text-primary dark:hover:text-primary transition">contact@samartian.com</a>
                    </li>
                </ul>
            </div>

            {{-- Liens colonnes --}}
            <div class="lg:col-span-4 grid grid-cols-2 sm:grid-cols-4 gap-8">
                {{-- Explorer --}}
                <div>
                    <h3 class="font-bold text-primary dark:text-accent mb-4 text-sm uppercase tracking-wide">Explorer
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Acheter</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Vendre</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Louer</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Estimation
                                gratuite</a></li>
                    </ul>
                </div>

                {{-- L'agence --}}
                <div>
                    <h3 class="font-bold text-primary dark:text-accent mb-4 text-sm uppercase tracking-wide">L'agence
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">À
                                propos</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Nos
                                conseillers</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Carrières</a>
                        </li>
                        <li><a href="{{ route('avis.index') }}"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Avis
                                clients</a></li>
                    </ul>
                </div>

                {{-- Ressources --}}
                <div>
                    <h3 class="font-bold text-primary dark:text-accent mb-4 text-sm uppercase tracking-wide">
                        Ressources</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Guides
                                & conseils</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Documentation</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Statut
                                du marché</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Blog
                                immobilier</a></li>
                    </ul>
                </div>

                {{-- Aide --}}
                <div>
                    <h3 class="font-bold text-primary dark:text-accent mb-4 text-sm uppercase tracking-wide">Aide</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">FAQ</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Nous
                                contacter</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Signaler
                                un souci</a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-primary dark:hover:text-primary transition">Support
                                technique</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Légal (ligne compacte) --}}
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 py-6 text-xs text-gray-500">
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Politique de confidentialité</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Conditions générales</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Mentions légales</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Cookies</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Statut des services</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Sécurité</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Plan du site</a>
            <a href="#" class="hover:text-primary dark:hover:text-primary transition">Accessibilité</a>
        </div>

        {{-- Dernière ligne : Réseaux sociaux + Copyright + Badge --}}
        <div
            class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-800 gap-4">

            {{-- Social Links --}}
            <div class="flex gap-4">
                <a href="https://facebook.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800/60 text-gray-400 hover:bg-primary hover:text-white transition"
                    aria-label="Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z" />
                    </svg>
                </a>
                <a href="https://instagram.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800/60 text-gray-400 hover:bg-primary hover:text-white transition"
                    aria-label="Instagram">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.975.975 1.247 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.063 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.247-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.063-2.633-.335-3.608-1.31-.975-.975-1.247-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.063-1.366.335-2.633 1.31-3.608.975-.975 2.242-1.247 3.608-1.31C8.416 2.175 8.796 2.163 12 2.163zM12 0C8.741 0 8.332.014 7.052.072 5.197.158 3.356.623 2.06 1.92.763 3.217.298 5.058.212 6.914.154 8.194.14 8.603.14 11.866c0 3.263.014 3.672.072 4.952.086 1.856.551 3.697 1.848 4.993 1.296 1.296 3.137 1.762 4.993 1.848 1.28.058 1.689.072 4.952.072 3.263 0 3.672-.014 4.952-.072 1.856-.086 3.697-.551 4.993-1.848 1.296-1.296 1.762-3.137 1.848-4.993.058-1.28.072-1.689.072-4.952 0-3.263-.014-3.672-.072-4.952-.086-1.856-.551-3.697-1.848-4.993C19.843.623 18.002.158 16.146.072 14.866.014 14.457 0 11.194 0H12zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.88 0z" />
                    </svg>
                </a>
                <a href="https://linkedin.com/company/samartian" target="_blank" rel="noopener noreferrer"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800/60 text-gray-400 hover:bg-primary hover:text-white transition"
                    aria-label="LinkedIn">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C0.792 0 0 0.774 0 1.729v20.542C0 23.227 0.792 24 1.771 24h20.451c0.979 0 1.771-0.773 1.771-1.729V1.729C24 0.774 23.205 0 22.225 0z" />
                    </svg>
                </a>
                <a href="https://twitter.com/samartian" target="_blank" rel="noopener noreferrer"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800/60 text-gray-400 hover:bg-primary hover:text-white transition"
                    aria-label="X (Twitter)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
            </div>

            {{-- Copyright --}}
            <div class="text-sm text-gray-500 text-center">
                © {{ date('Y') }} Samaritain • Tous droits réservés
            </div>

            {{-- Badge "Made with" --}}
            <div
                class="flex items-center gap-1.5 text-xs text-gray-400 bg-gray-800/50 px-3 py-1.5 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3.5 h-3.5 text-accent" fill="currentColor"
                    stroke="none">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <span>Made with passion</span>
                <span class="font-medium text-primary dark:text-accent">par l'équipe Samaritain</span>
            </div>
        </div>
    </div>
</footer>