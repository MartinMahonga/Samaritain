<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

    public function show(Artisan $artisan)
    {
        Gate::authorize('view', $artisan);

        $artisan->load(['user', 'categories', 'reviews.user', 'projects', 'contacts']);

        return view('pages.admin.artisans.show', compact('artisan'));
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
