@if (auth()->check() && !auth()->user()->hasVerifiedEmail())
    <div class="bg-yellow-500 px-4 py-4 relative overflow-hidden" x-data="{ dismissed: false }" x-show="!dismissed" x-transition>
        <div class="flex items-center">
            <div class="flex-shrink-0 mt-0.5">
                <i data-lucide="alert-triangle" class="h-5 w-5 text-foreground"></i>
            </div>
            <div class="ml-3 flex-1 md:flex md:items-center md:justify-between">
                <p class="text-sm text-foreground font-medium">
                    Votre adresse email n'est pas vérifiée. Veuillez vérifier vos emails pour activer toutes les fonctionnalités.
                </p>
                <div class="mt-3 text-sm md:ml-6 md:mt-0 flex gap-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-medium text-foreground hover:text-primary hover:underline">
                            Renvoyer le lien
                        </button>
                    </form>
                    <button type="button" x-on:click="dismissed = true" class="font-medium text-foreground hover:text-accent">
                        Ignorer pour l'instant
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
