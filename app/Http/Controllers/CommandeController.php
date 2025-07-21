<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Pharmacie;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Notifications\CommandeCreated; // à créer si pas déjà fait
use App\Notifications\CommandeStatusNotification; // à créer si pas déjà fait

class CommandeController extends Controller
{
    public function commander(Request $request)
    {
        $client =Client::where('user_id', Auth::id())->first();
    if (!$client) {
        return back()->with('error', 'Client non trouvé.');
    }

    // Récupérer la quantité disponible dans la pharmacie pour ce produit
    $pivot = \DB::table('pharmacie_produit')
        ->where('pharmacie_id', $request->pharmacie_id)
        ->where('produit_id', $request->produit_id)
        ->first();

    if (!$pivot || $pivot->quantity < $request->quantity) {
        return back()->with('error', "La quantité demandée n'est pas disponible dans cette pharmacie.");
    }

        // Logique de création d'une commande
        
        $commande = Commande::create([
            'pharmacie_id' => $request->pharmacie_id,
            'client_id' => $client->id,
            'status' => 'pending',
            'order_date' => now(),
        ]);

        $prescriptionPath = null;
        if ($request->hasFile('prescription_file')) {
            $prescriptionPath = $request->file('prescription_file')->store('ordonnances', 'public');
        }

        $commande->produits()->attach($request->produit_id, [
            'quantity' => $request->quantity,
            'prescription_file' => $prescriptionPath,
        ]);

        $pharmacie = Pharmacie::findOrFail($request->pharmacie_id);
        $pharmacie->notify(new CommandeCreated($commande));

        return back()->with('success', 'Commande envoyée à la pharmacie.');
    }


    public function confirmer($id)
    {
        $commande = \App\Models\Commande::findOrFail($id);
        // Décrémenter le stock pour chaque produit de la commande
        foreach ($commande->produits as $produit) {
            $pivot = \DB::table('pharmacie_produit')
                ->where('pharmacie_id', $commande->pharmacie_id)
                ->where('produit_id', $produit->id)
                ->first();
            if ($pivot && $pivot->quantity >= $produit->pivot->quantity) {
                \DB::table('pharmacie_produit')
                    ->where('pharmacie_id', $commande->pharmacie_id)
                    ->where('produit_id', $produit->id)
                    ->update([
                        'quantity' => $pivot->quantity - $produit->pivot->quantity
                    ]);
            }
        }
        $commande->status = 'confirmed'; // valeur brute en base
        $commande->save();
        $commande->client->user->notify(new CommandeStatusNotification('confirmed', $commande));
        return back()->with('success', 'Commande confirmée.');
    }
    // notification au client et payement

    public function annuler($id)
    {
        $commande = \App\Models\Commande::findOrFail($id);
        $commande->status = 'cancelled'; // valeur brute en base
        $commande->save();
        $commande->client->user->notify(new CommandeStatusNotification('cancelled', $commande));
        return back()->with('success', 'Commande refusée.');
        //notification à envoyer au client
    }


}
