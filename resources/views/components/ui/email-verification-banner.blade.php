@if (auth()->check() && !auth()->user()->hasVerifiedEmail())
    <div class="bg-[var(--warning)]/10 border border-[var(--warning)]/20 rounded-md p-4 mb-6 shadow-sm relative overflow-hidden" x-data="{ dismissed: false }" x-show="!dismissed" x-transition>
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <i data-lucide="alert-triangle" class="h-5 w-5 text-[var(--warning)]"></i>
            </div>
            <div class="ml-3 flex-1 md:flex md:items-center md:justify-between">
                <p class="text-sm text-[var(--warning)] font-medium">
                    Votre adresse email n'est pas vérifiée. Veuillez vérifier vos emails pour activer toutes les fonctionnalités.
                </p>
                <div class="mt-3 text-sm md:ml-6 md:mt-0 flex gap-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-medium text-[var(--warning)] hover:text-[var(--warning)]/80 hover:underline">
                            Renvoyer le lien
                        </button>
                    </form>
                    <button type="button" @click="dismissed = true" class="font-medium text-[var(--warning)]/70 hover:text-[var(--warning)]">
                        Ignorer pour l'instant
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
