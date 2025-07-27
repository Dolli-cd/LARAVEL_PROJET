<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Pharmacie;

class PanierController extends Controller
{
    public function ajouter(Request $request)
    {
       
        if(Auth::check()){
            $id = $request->input('id');
            $pharmacie_id = $request->input('pharmacie_id');

            $produit = Produit::findOrFail($id);
            $pharmacie = Pharmacie::findOrFail($pharmacie_id);

            $pivot = $pharmacie->produits()->where('produit_id', $id)->first()?->pivot;

            if (!$pivot) {
                return back()->with('error', 'Produit non disponible dans cette pharmacie.');
            }

            $userId = Auth::id();
            $panier = session()->get('panier_' . $userId, []);
            $key = $produit->id . '-' . $pharmacie->id;

            $panier[$key] = [
                "name" => $produit->name,
                "quantity" => 1,
                "price" => $pivot->price,
                "pharmacie" => $pharmacie->user->name ?? 'Pharmacie inconnue',
                "file" => $produit->file,
                "id" => $produit->id,
                "pharmacie_id" => $pharmacie->id, 
            ];

            session()->put('panier_' . $userId, $panier);

            // Enregistrer le temps d'ajout au panier (seulement si c'est le premier produit)
            if (count($panier) === 1) {
                session()->put('panier_time', now());
            }

            return redirect('panier');
        }
        return redirect('login');
    }

    public function afficher()
    {
        $userId=Auth::id();
        $panier = session()->get('panier_' . $userId, []);
        return view('client.panier', compact('panier'));
    }

    public function supprimer($key)
    {
        $userId = Auth::id();
        $panier = session()->get('panier_' . $userId);
        if(isset($panier[$key])) {
            unset($panier[$key]);
            session()->put('panier_' . $userId, $panier);
        }

        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $panier = session()->get('panier_' . $userId, []);
        if (isset($panier[$id])) {
            $quantity = (int) $request->input('quantity', 1);
            if ($quantity > 0) {
                $panier[$id]['quantity'] = $quantity;
            } else {
                unset($panier[$id]);
            }
            session()->put('panier_' . $userId, $panier);
        }
        return back()->with('success', 'Quantité du produit mise à jour.');
    }

    public function vider()
    {
        $userId = Auth::id();
        session()->forget('panier_' . $userId);
        return redirect()->back()->with('success', 'Votre panier a été vidé.');
    }
}
