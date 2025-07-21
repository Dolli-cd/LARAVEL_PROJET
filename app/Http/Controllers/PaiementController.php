<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Commande;
class PaiementController extends Controller
{
    public function payerReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $montant = 0;
        foreach ($reservation->produits as $produit) {
            $pivotPharma = \DB::table('pharmacie_produit')
                ->where('pharmacie_id', $reservation->pharmacie_id)
                ->where('produit_id', $produit->id)
                ->first();
            $prix = $pivotPharma ? $pivotPharma->price : 0;
            $montant += $produit->pivot->quantity * $prix;
        }
        $montant = $montant * 0.5; // 50% pour l'acompte
        return view('paiement.reservation', compact('reservation', 'montant'));
    }

    public function payerCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $montant = 0;
        foreach ($commande->produits as $produit) {
            $pivotPharma = \DB::table('pharmacie_produit')
                ->where('pharmacie_id', $commande->pharmacie_id)
                ->where('produit_id', $produit->id)
                ->first();
            $prix = $pivotPharma ? $pivotPharma->price : 0;
            $montant += $produit->pivot->quantity * $prix;
        }
        return view('paiement.commande', compact('commande', 'montant'));
    }

    public function process(Request $request, $reservationId)
    {
        $reservation = \App\Models\Reservation::findOrFail($reservationId);

        // Montant à payer (50%)
        $amount = $reservation->total_price / 2;

        // Numéro du client (payeur)
        $clientPhone = $request->input('phone');

        // Numéro de la pharmacie (bénéficiaire) - le fameux "TO"
        $pharmaciePhone = $reservation->pharmacie->user->phone; // ou un champ spécifique

        // Ici tu appelles l'API Orange Money ou autre prestataire
        // Exemple fictif :
        /*
        $response = Http::post('https://api.orange.com/payment', [
            'from' => $clientPhone,
            'to' => $pharmaciePhone, // le "TO" = bénéficiaire
            'amount' => $amount,
            'reference' => 'RES-'.$reservation->id,
        ]);
        */

        // Pour l’instant, simule le succès
        return back()->with('success', 'Paiement simulé (à intégrer avec Orange Money)');
    }
}
