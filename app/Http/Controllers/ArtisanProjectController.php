<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisanProjectRequest;
use App\Models\Artisan;
use App\Models\ArtisanProject;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArtisanProjectController extends Controller
{
    public function index(Artisan $artisan)
    {
        $projects = $artisan->projects()->latest()->paginate(12);

        return view('pages.artisan.projects.index', compact('artisan', 'projects'));
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

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('artisans/projects');
        }

        $artisan->projects()->create($data);

        return redirect()->route('artisan.projects.index', $artisan)
            ->with('success', 'Réalisation ajoutée avec succès.');
    }

    public function edit(Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('update', $project);

        return view('pages.artisan.projects.edit', compact('artisan', 'project'));
    }

    public function update(StoreArtisanProjectRequest $request, Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('update', $project);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::delete($project->image);
            }
            $data['image'] = $request->file('image')->store('artisans/projects');
        } elseif (! $request->hasFile('image') && $project->image) {
            unset($data['image']);
        }

        $project->update($data);

        return redirect()->route('artisan.projects.index', $artisan)
            ->with('success', 'Réalisation mise à jour.');
    }

    public function destroy(Artisan $artisan, ArtisanProject $project)
    {
        Gate::authorize('delete', $project);

        if ($project->image) {
            Storage::delete($project->image);
        }

        $project->delete();

        return back()->with('success', 'Réalisation supprimée.');
    }
}
