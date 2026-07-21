<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisanRequest;
use App\Http\Requests\UpdateArtisanRequest;
use App\Models\Arrondissement;
use App\Models\Artisan;
use App\Models\ArtisanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::query()->verified()->active()
            ->with('categories:id,name,slug')
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        if ($request->filled('arrondissement_id')) {
            $query->where('arrondissement_id', $request->arrondissement_id);
        }

        if ($request->filled('rating')) {
            $query->having('average_rating', '>=', $request->rating);
        }

        $artisans = $query->orderBy('average_rating', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = ArtisanCategory::orderBy('id')->get();
        $cities = Artisan::verified()->active()->distinct()->pluck('city')->filter();
        $arrondissements = Arrondissement::with('city')->orderBy('name')->get();
        $count = $artisans->count();

        return view('pages.artisans.index', [
            'artisans' => $artisans,
            'categories' => $categories,
            'cities' => $cities,
            'arrondissements' => $arrondissements,
            'count' => $count,
        ]);
    }

    public function show(Artisan $artisan)
    {
        $artisan->load(['categories', 'arrondissement', 'projects' => function ($query) {
            $query->with('images')->latest()->limit(12);
        }, 'reviews' => function ($query) {
            $query->with('user:id,name,profile_image')->latest();
        }]);

        if (! auth()->check() || ! (auth()->user()->isAdmin() || auth()->id() === $artisan->user_id)) {
            $artisan->increment('views');
        }

        $userReview = null;
        if (auth()->check()) {
            $userReview = $artisan->reviews()->where('user_id', auth()->id())->first();
        }

        return view('pages.artisans.show', compact('artisan', 'userReview'));
    }

    public function create()
    {
        $categories = ArtisanCategory::orderBy('name')->get();
        $arrondissements = Arrondissement::with('city')->orderBy('name')->get();

        return view('pages.artisans.create', compact('categories', 'arrondissements'));
    }

    public function store(StoreArtisanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['business_name']).'-'.Str::random(6);
        $data['verified'] = false;
        $data['is_active'] = false;

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

        return redirect()->route('artisan.dashboard')
            ->with('success', 'Votre profil artisan a été créé et est en attente de validation.');
    }

    public function edit(Artisan $artisan)
    {
        Gate::authorize('update', $artisan);

        $categories = ArtisanCategory::orderBy('name')->get();
        $selectedCategories = $artisan->categories->pluck('id')->toArray();
        $arrondissements = Arrondissement::with('city')->orderBy('name')->get();

        return view('pages.artisans.edit', compact('artisan', 'categories', 'selectedCategories', 'arrondissements'));
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

        return redirect()->route('artisan.dashboard')
            ->with('success', 'Votre profil a été mis à jour.');
    }

    public function dashboard()
    {
        $artisan = auth()->user()->artisan;

        if (! $artisan) {
            return redirect()->route('artisan.create');
        }

        $stats = [
            'reviews_count' => $artisan->reviews()->count(),
            'average_rating' => $artisan->average_rating,
            'projects_count' => $artisan->projects()->count(),
            'contacts_count' => $artisan->contacts()->count(),
            'views_count' => $artisan->views,
            'projects_views' => $artisan->projects()->sum('views'),
        ];

        $recentReviews = $artisan->reviews()->with('user:id,name,profile_image')->latest()->limit(5)->get();
        $recentContacts = $artisan->contacts()->latest()->limit(5)->get();

        return view('pages.artisan.dashboard', compact('artisan', 'stats', 'recentReviews', 'recentContacts'));
    }
}
