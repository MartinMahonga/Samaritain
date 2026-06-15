@props(['user'])

<form action="{{ route('profile.update-info') }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <x-form.input name="name" label="Nom complet" :value="$user->name" />
    <x-form.input name="email" label="Adresse email" :value="$user->email" />

    {{-- <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-[var(--foreground)]">Nom complet</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
            class="w-full rounded-md border-[var(--input)] bg-[var(--background)] px-3 py-2 text-sm text-[var(--foreground)] focus:outline-none focus:ring-2 focus:ring-[var(--ring)] focus:border-transparent">
        @error('name')
            <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
        @enderror
    </div> --}}

    <div class="pt-2">
        {{-- <button type="submit"
            class="inline-flex items-center justify-center rounded-md bg-[var(--primary)] px-4 py-2 text-sm font-medium text-[var(--primary-foreground)] shadow hover:bg-[var(--primary)]/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--ring)] transition-colors">
            Enregistrer les modifications
        </button> --}}
        <x-btn type="submit">
            Enregistrer les modifications
        </x-btn>
    </div>
</form>
