<div x-data="{ open: false }">
    <button @click="open = true" class="inline-flex items-center justify-center rounded-md bg-[var(--primary)] px-4 py-2 text-sm font-medium text-[var(--primary-foreground)] hover:bg-[var(--primary)]/90 transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
        Inviter
    </button>

    <div x-show="open" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" @click.away="open = false" x-transition
                    class="relative transform overflow-hidden rounded-lg bg-[var(--card)] text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-[var(--border)]">
                    
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[var(--border)]">
                        <h3 class="text-lg font-semibold text-[var(--foreground)]">Inviter un nouveau membre</h3>
                        <button @click="open = false" class="text-[var(--muted-foreground)] hover:text-[var(--foreground)]">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.team.invite') }}" method="POST" class="p-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-[var(--foreground)] mb-1">Adresse email</label>
                                <input type="email" name="email" id="email" required placeholder="collegue@exemple.com"
                                    class="w-full rounded-md border-[var(--input)] bg-[var(--background)] px-3 py-2 text-sm text-[var(--foreground)] focus:ring-[var(--ring)]">
                                @error('email') <p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-[var(--foreground)] mb-1">Rôle dans l'entreprise</label>
                                <select name="role" id="role" required class="w-full rounded-md border-[var(--input)] bg-[var(--background)] px-3 py-2 text-sm text-[var(--foreground)] focus:ring-[var(--ring)]">
                                    <option value="{{ \App\Enums\AgencyRole::ADMIN->value }}">Administrateur</option>
                                    <option value="{{ \App\Enums\AgencyRole::AGENT->value }}" selected>Agent immobilier</option>
                                    <option value="{{ \App\Enums\AgencyRole::ASSISTANT->value }}">Assistant(e)</option>
                                </select>
                                <p class="mt-1 text-xs text-[var(--muted-foreground)]">Les administrateurs peuvent tout gérer sauf l'équipe. Les agents gèrent les biens. Les assistants ont un accès lecture.</p>
                                @error('role') <p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="open = false" class="rounded-md px-4 py-2 text-sm font-medium border border-[var(--border)] text-[var(--foreground)] hover:bg-[var(--muted)]">
                                Annuler
                            </button>
                            <button type="submit" class="rounded-md bg-[var(--primary)] px-4 py-2 text-sm font-medium text-[var(--primary-foreground)] hover:bg-[var(--primary)]/90">
                                Envoyer l'invitation
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    @if($errors->any() && session()->has('_old_input'))
        <div x-init="open = true"></div>
    @endif
</div>
