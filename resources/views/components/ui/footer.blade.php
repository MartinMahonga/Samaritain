<footer class="hidden md:block border-t border-gray-100 bg-white mt-8">
    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- Top row --}}
        <div class="flex items-start justify-between gap-12 mb-8">
            {{-- Brand --}}
            <div class="flex flex-col gap-3 max-w-xs">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-primary rounded-lg flex items-center justify-center">
                        <i data-lucide="home" class="w-3.5 h-3.5 text-white"></i>
                    </div>
                    <span class="font-semibold text-gray-800 text-sm">Samaritain</span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Trouvez votre logement sans frais d'agence.<br>
                    Location directe entre propriétaires et locataires.
                </p>
            </div>

            {{-- Links --}}
            <div class="flex gap-16 text-xs">
                <div class="flex flex-col gap-2.5">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider text-[10px]">Plateforme</span>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Parcourir les
                        annonces</a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Publier un bien</a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Comment ça marche</a>
                </div>
                <div class="flex flex-col gap-2.5">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider text-[10px]">Légal</span>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Conditions
                        d'utilisation</a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Politique de
                        confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">Contact</a>
                </div>
            </div>
        </div>

        {{-- Bottom row --}}
        <div class="border-t border-gray-100 pt-5 flex items-center justify-between">
            <p class="text-xs text-gray-400">© {{ date('Y') }} Samaritain. Tous droits réservés.</p>
            <span class="text-xs text-gray-300">Location sans commission</span>
        </div>
    </div>
</footer>
