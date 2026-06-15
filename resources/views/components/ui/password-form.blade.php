<form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <x-form.input name="current_password" label="Mot de passe actuel" />
    <x-form.input name="password" label="Nouveau mot de passe" />
    <x-form.input name="password_confirmation" label="Confirmer le nouveau mot de passe" />

    <div class="pt-2">
        {{-- <button type="submit"
            class="inline-flex items-center justify-center rounded-md bg-[var(--primary)] px-4 py-2 text-sm font-medium text-[var(--primary-foreground)] shadow hover:bg-[var(--primary)]/90 transition-colors">
            Mettre à jour le mot de passe
        </button> --}}
        <x-btn type="submit">
            Mettre à jour le mot de passe
        </x-btn>
    </div>
</form>
