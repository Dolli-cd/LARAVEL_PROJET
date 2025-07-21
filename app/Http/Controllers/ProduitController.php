<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;
use App\Models\Pharmacie;
use App\Services\ProduitSearchService;

class ProduitController extends Controller
{
    // Liste des produits liés à la pharmacie authentifiée
    public function liste_pro()
    {
        $pharmacie = Auth::user()->pharmacie;

        if (!$pharmacie) {
            abort(403, 'Accès refusé : vous n\'êtes pas une pharmacie.');
        }

        $produits = $pharmacie->produits()
            ->with('categorie')
            ->orderBy('pharmacie_produit.created_at', 'asc')
            ->paginate(10); 

        return view('pharmacie.produit', compact('produits'));
    }

    // Suppression multiple (detach de la table pivot uniquement)
    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        $pharmacie = Auth::user()->pharmacie;

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Aucun produit sélectionné'], 400);
        }

        if ($pharmacie) {
            $pharmacie->produits()->detach($ids);
        }

        $produits = $pharmacie->produits()->with('categorie')->paginate(10);
        $html = view('pharmacie.search', compact('produits'))->render();

        return response()->json($html);
    }

    // Recherche dans les produits de la pharmacie connectée
    public function search_pro(Request $request)
    {
        $search = $request->input('search');
        $pharmacie = Auth::user()->pharmacie;

        $query = $pharmacie->produits()->with('categorie')->orderBy('pharmacie_produit.created_at', 'asc');
        $query = ProduitSearchService::apply($query, $search);

        $produits = $query->paginate(10);

        return view('pharmacie.search', compact('produits'))->render();
    }

    // Ajout de produit + liaison avec la pharmacie et champs pivot
    public function ajouter_pro_traitement(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' =>  'required|numeric|min:2',
            'status' => 'required|string',
            'comment' => 'nullable|string',
            'quantity' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'prescription' => 'nullable|boolean',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf', // adapte les extensions si besoin
        ]);

        $produit = new Produit();
        $produit->name = $request->name;
        $produit->code = $request->code;
        $produit->type = $request->type;
        $produit->categorie_id = $request->categorie_id;
        $produit->prescription = $request->has('prescription');
        if ($request->hasFile('file')) {
            $produit->file = $request->file('file')->store('produits', 'public');
        }
        $produit->save();

        $pharmacie = Auth::user()->pharmacie;

        if ($pharmacie) {
            $pharmacie->produits()->attach($produit->id, [
                'price' => $request->price,
                'status' => $request->status,
                'comment' => $request->comment,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('liste_produit')->with('confirmer', 'Le produit a bien été ajouté');
    }

    // Formulaire de modification
    public function update_pro($id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }

        return view('pharmacie.update', compact('produit'));
    }

    // Traitement de modification
    public function update_pro_traitement(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'price' =>  'required|numeric|min:2',
            'comment' => 'nullable|string',
            'quantity' => 'required|integer',
            'categorie_id' => 'required|exists:categories,id',
            'prescription' => 'nullable|boolean',
        ]);

        $produit = Produit::find($request->id);

        if (!$produit) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }

        $produit->name = $request->name;
        $produit->code = $request->code;
        $produit->type = $request->type;
        $produit->categorie_id = $request->categorie_id;
        $produit->prescription = $request->has('prescription');
        $produit->save();
        // Mise à jour de la table pivot
        $pharmacie = Auth::user()->pharmacie;
        if ($pharmacie) {
            $pivotExists = $pharmacie->produits()->where('produit_id', $produit->id)->exists();
            if ($pivotExists) {
                $pharmacie->produits()->updateExistingPivot($produit->id, [
                    'price' => $request->price,
                    'status' => $request->status,
                    'comment' => $request->comment,
                    'quantity' => $request->quantity,
                ]);
            } else {
                $pharmacie->produits()->attach($produit->id, [
                    'price' => $request->price,
                    'status' => $request->status,
                    'comment' => $request->comment,
                    'quantity' => $request->quantity,
                ]);
            }
        }
        return redirect()->route('liste_produit')->with('confirmer', 'Produit bien modifié');
        
    }

    // Suppression d'un seul produit (détaché de la pharmacie, mais pas supprimé globalement)
    public function delete_pro($id)
    {
        $produit = Produit::findOrFail($id);

        // Vérifie si l'utilisateur est admin
        if (auth()->user()->role === 'admin') {
            $produit->delete();
            return back()->with('success', 'Produit supprimé.');
        }
        $pharmacie = Auth::user()->pharmacie;

        if (!$pharmacie) {
            abort(403, 'Non autorisé');
        }

        $produit = Produit::find($id);

        if (!$produit) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }

        $pharmacie->produits()->detach($id); // détache le produit uniquement de cette pharmacie

        return redirect()->route('liste_produit')->with('confirmer', "Produit {$produit->name} détaché avec succès");
    }
    
}
