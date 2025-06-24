<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Pharmacie;
class ProduitController extends Controller
{
    public function liste_pro(){
        $produits = Produit::paginate(20);
        return view('pharmacie.produit', compact('produits'));
    }

    //suppression ami
    public function deleteMultiple(Request $request){
    $ids = $request->input('ids');

    if(!$ids || !is_array($ids)) {
        return response()->json(['message' => 'Aucun produit sélectionné'], 400);
    }

    Produit::whereIn('id', $ids)->delete();

    // Retourne la vue partielle mise à jour avec la liste paginée
    $produits = Produit::paginate(10);
    $html = view('produit.partials.table', compact('produits'))->render();

    return response()->json($html);
}

    public function search_pro(Request $request){
        $search=$request->input('search');
        $produits = produit:: where('name','like',"%$search%")
        ->orwhere ('code','like',"%$search%")
        ->orwhere ('type','like',"%$search%")
        ->orwhere ('price','like',"%$search%")
        ->paginate(10);
        //retourne uniquement une portion de la vue
        return view('pharmacie.search',compact('produits'))->render();
    }
    
    public function ajouter_pro(){
        return view('pharmacie.ajouter');
    }
    
    public function ajouter_pro_traitement(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);
        
        $produit = new Produit();
        $produit->name = $request->name;
        $produit->code = $request->code;
        $produit->type = $request->type;
        $produit->price = $request->price;
        $produit->save();
        
        return redirect()->route('ajouter_produit')->with('confirmer','Le produit a bien été ajouté');
    }
    
    public function update_pro($id){
        $produits = produit::find($id);
        
        // Vérification si l'Produit existe
        if (!$produits) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }
        
        return view('pharmacie.update', compact('produits'));
    }
    
    public function update_pro_traitement(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);
        
        $produit = produit::find($request->id);
        
        // Vérification si le Produit existe
        if (!$produit) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }
        
        $produit->name = $request->name;
        $produit->code = $request->code;
        $produit->type = $request->type;
        $produit->price = $request->type;
        $produit->save();
        
        return redirect()->route('update_produit',$produit->id)->with('confirmer','Produit a bien été modifié');
    }
    
    public function delete_pro($id){
        $produit = produit::find($id);
        
        // Vérification si l'Produit existe
        if (!$produit) {
            return redirect()->route('liste_produit')->with('erreur', 'Produit non trouvé');
        }
        
        $Complet = $produit->name;
        $produit->delete();
        
        return redirect()->route('liste_produit')->with('confirmer',"Produit $Complet a bien été supprimé ");
    }
}
