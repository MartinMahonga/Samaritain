<div x-data="{ open: false }">
    <button type="button" @click="open = true"
        class="inline-flex items-center justify-center rounded-md bg-[var(--destructive)] px-4 py-2 text-sm font-medium text-[var(--destructive-foreground)] hover:bg-[var(--destructive)]/90 transition-colors">
        Supprimer le compte
    </button>

    <!-- Modal Alpine.js -->
    <div x-show="open" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-[var(--card)] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[var(--border)]">

                    <form action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="bg-[var(--card)] px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[var(--destructive)]/20 sm:mx-0 sm:h-10 sm:w-10">
                                    <i data-lucide="alert-octagon" class="h-6 w-6 text-[var(--destructive)]"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-[var(--foreground)]" id="modal-title">
                                        Supprimer le compte
                                    </h3>
                                    <div class="mt-2 space-y-4">
                                        <p class="text-sm text-[var(--muted-foreground)]">
                                            Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible. Pour confirmer, veuillez saisir votre mot de passe.
                                        </p>

                                        <div>
                                            <label for="delete_password" class="sr-only">Mot de passe</label>
                                            <input type="password" name="password" id="delete_password" placeholder="Mot de passe" required
                                                class="w-full rounded-md border-[var(--input)] bg-[var(--background)] px-3 py-2 text-sm text-[var(--foreground)] focus:outline-none focus:ring-2 focus:ring-[var(--destructive)] focus:border-transparent">
                                            @error('password', 'userDeletion')
                                                <p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[var(--muted)] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-[var(--destructive)] px-3 py-2 text-sm font-semibold text-[var(--destructive-foreground)] shadow-sm hover:bg-[var(--destructive)]/90 sm:ml-3 sm:w-auto transition-colors">
                                Supprimer définitivement
                            </button>
                            <button type="button" @click="open = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-[var(--background)] px-3 py-2 text-sm font-semibold text-[var(--foreground)] shadow-sm ring-1 ring-inset ring-[var(--border)] hover:bg-[var(--accent)] sm:mt-0 sm:w-auto transition-colors">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Re-open modal automatically if there were validation errors -->
    @if ($errors->has('password') && session('_old_input'))
        <div x-init="open = true"></div>
    @endif
</div>
