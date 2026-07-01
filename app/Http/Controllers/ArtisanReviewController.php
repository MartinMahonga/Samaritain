<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisanReviewRequest;
use App\Models\Artisan;
use App\Models\ArtisanReview;
use Illuminate\Support\Facades\Gate;

class ArtisanReviewController extends Controller
{
    public function store(StoreArtisanReviewRequest $request, Artisan $artisan)
    {
        $existingReview = ArtisanReview::where('artisan_id', $artisan->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour cet artisan.');
        }

        $artisan->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Votre avis a été publié avec succès.');
    }

    public function update(StoreArtisanReviewRequest $request, Artisan $artisan, ArtisanReview $review)
    {
        Gate::authorize('update', $review);

        $review->update($request->validated());

        return back()->with('success', 'Votre avis a été mis à jour.');
    }

    public function destroy(Artisan $artisan, ArtisanReview $review)
    {
        Gate::authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Votre avis a été supprimé.');
    }
}
