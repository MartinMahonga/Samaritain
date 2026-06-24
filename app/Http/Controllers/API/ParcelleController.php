<?php

// app/Http/Controllers/API/ParcelleController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Parcelle;
use App\Models\ParcelleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParcelleController extends Controller
{
    // ✅ Liste avec filtres
    public function index(Request $request)
    {
        $query = Parcelle::with(['images', 'imagePrincipale']);

        // Filtres
        if ($request->filled('ville')) {
            $query->where('ville', 'like', '%'.$request->ville.'%');
        }

        if ($request->filled('quartier')) {
            $query->where('quartier', 'like', '%'.$request->quartier.'%');
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->filled('superficie_min')) {
            $query->where('superficie', '>=', $request->superficie_min);
        }

        if ($request->filled('superficie_max')) {
            $query->where('superficie', '<=', $request->superficie_max);
        }

        if ($request->filled('viabilisee')) {
            $query->where('viabilisee', $request->boolean('viabilisee'));
        }
        $parcelles = $query->latest()->paginate($request->input('per_page', 12));

        return response()->json($parcelles);
    }

    // ✅ Détail d'une parcelle
    public function show($id)
    {
        $parcelle = Parcelle::with(['images', 'imagePrincipale'])->findOrFail($id);

        return response()->json($parcelle);
    }

    // ✅ Création avec upload d'images
    public function store(Request $request)
    {
        Gate::authorize('create', Parcelle::class);

        $request->validate([
            'titre' => 'required|string|max:255',
            'localisation' => 'required|string',
            'quartier' => 'required|string',
            'ville' => 'required|string',
            'superficie' => 'required|numeric|min:1',
            'prix' => 'required|numeric|min:0',
            'statut' => 'in:disponible,vendu,réservé',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        // Génération de la référence unique
        $reference = 'PARC-'.date('Y').'-'.strtoupper(Str::random(6));

        $parcelle = Parcelle::create([
            ...$request->except('images'),
            'reference' => $reference,
        ]);

        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('parcelles', 'public');
                $url = asset('storage/'.$path);

                ParcelleImage::create([
                    'parcelle_id' => $parcelle->id,
                    'path' => $path,
                    'url' => $url,
                    'principale' => $index === 0, // La première image est principale
                ]);
            }
        }

        return response()->json($parcelle->load('images'), 201);
    }

    // ✅ Mise à jour
    public function update(Request $request, $id)
    {
        $parcelle = Parcelle::findOrFail($id);
        Gate::authorize('update', $parcelle);

        $request->validate([
            'titre' => 'sometimes|string|max:255',
            'superficie' => 'sometimes|numeric|min:1',
            'prix' => 'sometimes|numeric|min:0',
            'statut' => 'sometimes|in:disponible,vendu,réservé',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $parcelle->update($request->except('images'));

        // Ajout de nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('parcelles', 'public');
                $url = asset('storage/'.$path);

                ParcelleImage::create([
                    'parcelle_id' => $parcelle->id,
                    'path' => $path,
                    'url' => $url,
                    'principale' => false,
                ]);
            }
        }

        return response()->json($parcelle->load('images'));
    }

    // ✅ Suppression d'une parcelle
    public function destroy($id)
    {
        $parcelle = Parcelle::findOrFail($id);
        Gate::authorize('delete', $parcelle);

        // Supprimer les images du stockage
        foreach ($parcelle->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $parcelle->delete();

        return response()->json(['message' => 'Parcelle supprimée avec succès']);
    }

    // ✅ Suppression d'une image spécifique
    public function deleteImage($imageId)
    {
        $image = ParcelleImage::findOrFail($imageId);
        Gate::authorize('deleteImage', $image->parcelle);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['message' => 'Image supprimée avec succès']);
    }
}
