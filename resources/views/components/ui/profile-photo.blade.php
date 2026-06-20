@props(['user'])

<div x-data="{
    photoPreview: null,
    updatePhotoPreview(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.photoPreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}" class="flex flex-col items-center gap-4">

    <!-- Avatar Preview -->
    <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-[var(--sidebar-border)] bg-[var(--muted)] flex items-center justify-center">
        <!-- New Preview -->
        <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-cover" x-cloak>

        <!-- Current Photo -->
        @if ($user->profile_image)
            <img x-show="!photoPreview" src="{{ $user->profileUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
        @else
            <!-- Initials Fallback -->
            <span x-show="!photoPreview" class="text-3xl font-semibold text-[var(--muted-foreground)]">
                {{ $user->initials }}
            </span>
        @endif
    </div>

    <!-- Upload Form -->
    <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-2">
            <label for="photo" class="cursor-pointer inline-flex items-center justify-center rounded-md border border-[var(--input)] bg-[var(--background)] px-4 py-2 text-sm font-medium text-[var(--foreground)] hover:bg-[var(--accent)] hover:text-[var(--accent-foreground)] transition-colors">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                Choisir une image
            </label>
            <input type="file" name="photo" id="photo" class="hidden" accept="image/jpeg,image/png,image/webp" @change="updatePhotoPreview">

            @error('photo')
                <p class="text-xs text-[var(--destructive)] text-center">{{ $message }}</p>
            @enderror

            <button x-show="photoPreview" type="submit" x-cloak
                class="inline-flex items-center justify-center rounded-md bg-[var(--primary)] px-4 py-2 text-sm font-medium text-[var(--primary-foreground)] hover:bg-[var(--primary)]/90 transition-colors">
                Enregistrer la photo
            </button>
        </div>
    </form>

    <!-- Delete Button -->
    @if ($user->profile_image)
        <form action="{{ route('profile.delete-photo') }}" method="POST" class="w-full mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full text-sm text-[var(--destructive)] hover:underline">
                Supprimer la photo actuelle
            </button>
        </form>
    @endif
</div>
