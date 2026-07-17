<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisanProjectRequest;
use App\Models\Artisan;
use App\Models\ArtisanProject;
use App\Models\ArtisanProjectImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArtisanProjectController extends Controller
{
    public function index(Artisan $artisan)
    {
        $projects = $artisan->projects()->with('images')->latest()->paginate(12);

        return view('pages.artisan.projects.index', compact('artisan', 'projects'));
    }

    public function show(Artisan $artisan, ArtisanProject $project)
    {
        $project->load('images');

        if (! auth()->check() || ! (auth()->user()->isAdmin() || auth()->id() === $artisan->user_id)) {
            $project->increment('views');
        }

        return view('pages.artisan.projects.show', compact('artisan', 'project'));
    }

    public function create(Artisan $artisan)
    {
        Gate::authorize('update', $artisan);

        return view('pages.artisan.projects.create', compact('artisan'));
    }

    public function store(StoreArtisanProjectRequest $request, Artisan $artisan)
    {
        Gate::authorize('update', $artisan);

        $data = $request->validated();

        DB::transaction(function () use ($request, $data, $artisan) {
            $project = $artisan->projects()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('artisans/projects');
                    $project->images()->create(['image_path' => $path]);
                }
            }
        });

        return redirect()->route('artisan.projects.index', $artisan)
            ->with('success', 'Réalisation ajoutée avec succès.');
    }

    public function edit(Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('update', $project);

        $project->load('images');

        return view('pages.artisan.projects.edit', compact('artisan', 'project'));
    }

    public function update(StoreArtisanProjectRequest $request, Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('update', $project);

        $data = $request->validated();

        DB::transaction(function () use ($request, $data, $project) {
            $project->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('artisans/projects');
                    $project->images()->create(['image_path' => $path]);
                }
            }
        });

        return redirect()->route('artisan.projects.index', $artisan)
            ->with('success', 'Réalisation mise à jour.');
    }

    public function destroyImage(Artisan $artisan, $imageId)
    {
        Gate::authorize('update', $artisan);

        $image = ArtisanProjectImage::findOrFail($imageId);

        Storage::delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image supprimée.');
    }

    public function destroy(Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('delete', $project);

        DB::transaction(function () use ($project) {
            foreach ($project->images as $image) {
                Storage::delete($image->image_path);
                $image->delete();
            }

            $project->delete();
        });

        return back()->with('success', 'Réalisation supprimée.');
    }
}
