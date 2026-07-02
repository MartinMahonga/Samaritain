<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    // Afficher tous les avis


    public function index()
    {
        $avis = collect(); // liste vide par défaut

        if (auth()->user()->is_staff) {
            $avis = Avis::with('user')->latest()->get();
        }

        return view('avis.index', compact('avis'));
    }
    
    // Enregistrer un nouvel avis
    public function store(Request $request)
    {
        $request->validate([
            'commentaire' => 'required|string|max:500',
            'note' => 'required|integer|min:1|max:5',
        ]);

        Avis::create([
            'user_id' => auth()->id(),
            'commentaire' => $request->commentaire,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Votre avis a été transmis avec succès. Nous vous remercions pour votre contribution');
    }

        public function destroy(Avis $avis)
    {
        if (!auth()->user()->is_staff) {
            abort(403);
        }

        $avis->delete();

        return redirect()->back()->with('success', 'Avis supprimé avec succès !');
    }
}