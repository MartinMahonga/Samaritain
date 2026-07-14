<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreArtisanRequest;
use App\Http\Requests\UpdateArtisanRequest;
use App\Models\Artisan;
use App\Models\ArtisanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Artisan::class);

        $query = Artisan::with(['user', 'categories'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $artisans = $query->latest()->paginate(15)->withQueryString();

        $pendingCount = Artisan::where('verified', false)->count();
        $totalCount = Artisan::count();

        return view('pages.admin.artisans.index', [
            'artisans' => $artisans,
            'pendingCount' => $pendingCount,
            'totalCount' => $totalCount,
        ]);
    }

    public function pending()
    {
        Gate::authorize('viewAny', Artisan::class);

        $artisans = Artisan::with(['user', 'categories'])
            ->where('verified', false)
            ->latest()
            ->paginate(15);

        return view('pages.admin.artisans.pending', compact('artisans'));
    }

    public function create()
    {
        Gate::authorize('create', Artisan::class);

        $categories = ArtisanCategory::orderBy('name')->get();

        return view('pages.admin.artisans.create', compact('categories'));
    }

    public function store(AdminStoreArtisanRequest $request)
    {
        Gate::authorize('create', Artisan::class);

        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            $data['slug'] = Str::slug($data['business_name']).'-'.Str::random(6);
            $data['verified'] = true;
            $data['is_active'] = true;
            $data['created_by'] = auth()->id();

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar')->store('artisans/avatars');
            }

            if ($request->hasFile('cover')) {
                $data['cover'] = $request->file('cover')->store('artisans/covers');
            }

            $categories = $data['categories'];
            unset($data['categories']);

            $artisan = Artisan::create($data);
            $artisan->categories()->sync($categories);
        });

        return redirect()->route('admin.artisans.index')
            ->with('success', 'Profil artisan créé avec succès.');
    }

    public function show(Artisan $artisan)
    {
        Gate::authorize('view', $artisan);

        $artisan->load(['user', 'categories', 'reviews.user', 'projects.images', 'contacts']);

        return view('pages.admin.artisans.show', compact('artisan'));
    }

    public function edit(Artisan $artisan)
    {
        Gate::authorize('update', $artisan);

        $categories = ArtisanCategory::orderBy('name')->get();
        $selectedCategories = $artisan->categories->pluck('id')->toArray();

        return view('pages.admin.artisans.edit', compact('artisan', 'categories', 'selectedCategories'));
    }

    public function update(UpdateArtisanRequest $request, Artisan $artisan)
    {
        Gate::authorize('update', $artisan);

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($artisan->avatar) {
                Storage::delete($artisan->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('artisans/avatars');
        }

        if ($request->hasFile('cover')) {
            if ($artisan->cover) {
                Storage::delete($artisan->cover);
            }
            $data['cover'] = $request->file('cover')->store('artisans/covers');
        }

        $categories = $data['categories'];
        unset($data['categories']);

        $artisan->update($data);
        $artisan->categories()->sync($categories);

        return redirect()->route('admin.artisans.show', $artisan)
            ->with('success', 'Profil artisan mis à jour avec succès.');
    }

    public function verify(Artisan $artisan)
    {
        Gate::authorize('verify', $artisan);

        $artisan->update([
            'verified' => true,
            'is_active' => true,
        ]);

        return back()->with('success', 'Artisan validé avec succès.');
    }

    public function suspend(Artisan $artisan)
    {
        Gate::authorize('suspend', $artisan);

        $artisan->update([
            'is_active' => ! $artisan->is_active,
        ]);

        $status = $artisan->is_active ? 'activé' : 'suspendu';

        return back()->with('success', "Artisan {$status} avec succès.");
    }

    public function destroy(Artisan $artisan)
    {
        Gate::authorize('delete', $artisan);

        $artisan->delete();

        return redirect()->route('admin.artisans.index')
            ->with('success', 'Artisan supprimé définitivement.');
    }
}
