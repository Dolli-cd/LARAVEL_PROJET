<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

use App\Notifications\ReservationStatusNotification; // à créer avec artisan
use App\Notifications\ReservationCreated; // à créer avec artisan

class ReservationController extends Controller
{
    public function reserver(Request $request)
    {
        $client = Client::where('user_id', Auth::id())->first();
    if (!$client) {
        return back()->with('error', 'Client non trouvé.');
    }
    
    $pivot = \DB::table('pharmacie_produit')
        ->where('pharmacie_id', $request->pharmacie_id)
        ->where('produit_id', $request->produit_id)
        ->first();

    if (!$pivot || $pivot->quantity < $request->quantity) {
        return back()->with('error', "La quantité demandée n'est pas disponible dans cette pharmacie.");
    }
        // Logique de création d'une réservation
        
        $reservation = Reservation::create([
            'pharmacie_id' => $request->pharmacie_id,
            'client_id' => $client->id,
            'status' => 'pending',
            'booking_date' => now(),
        ]);

        $prescriptionPath = null;
        if ($request->hasFile('prescription_file')) {
            $prescriptionPath = $request->file('prescription_file')->store('ordonnances', 'public');
        }

        $produit = Produit::find($request->produit_id);

        $reservation->produits()->attach($request->produit_id, [
            'quantity' => $request->quantity,
            'prescription_file' => $prescriptionPath,
        ]);

        // Notifier la pharmacie
        $pharmacie = Pharmacie::findOrFail($request->pharmacie_id);
        $pharmacie->notify(new ReservationCreated($reservation));

        return back()->with('success', 'Demande de réservation envoyée.');
    }


    // notification au client et payement

        //notification à envoyer au client
    

/*public function confirmer($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->pharmacie_id === auth()->user()->pharmacie->id) {
            $reservation->status = 'accepted';
            $reservation->save();
           
            $notification = auth()->user()->notifications()
            ->where('data->reservation_id', $id)
            ->whereNull('read_at')
            ->first();
        if ($notification) {
            $notification->markAsRead();
        }
         // Notifier le client
    $reservation->client->notify(new \App\Notifications\ReservationStatusNotification('accepted', $reservation));
        }
        return back()->with('success', 'Réservation acceptée');
    }*/

    public function annuler($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->pharmacie_id === auth()->user()->pharmacie->id) {
            $reservation->status = 'rejected';
            $reservation->save();
        }
         // Notifier le client
    $reservation->client->user->notify(new \App\Notifications\ReservationStatusNotification('rejected', $reservation));
        return back()->with('error', 'Réservation refusée');
    }

    public function confirmer($id)
    {
        $reservation = Reservation::findOrFail($id);
        // Décrémenter le stock pour chaque produit de la réservation
        foreach ($reservation->produits as $produit) {
            $pivot = \DB::table('pharmacie_produit')
                ->where('pharmacie_id', $reservation->pharmacie_id)
                ->where('produit_id', $produit->id)
                ->first();
            if ($pivot && $pivot->quantity >= $produit->pivot->quantity) {
                \DB::table('pharmacie_produit')
                    ->where('pharmacie_id', $reservation->pharmacie_id)
                    ->where('produit_id', $produit->id)
                    ->update([
                        'quantity' => $pivot->quantity - $produit->pivot->quantity
                    ]);
            }
        }
        $reservation->status = 'confirmed';
        $reservation->save();
        // Notifier le client (le user lié au client)
        $reservation->client->user->notify(new \App\Notifications\ReservationStatusNotification('confirmed', $reservation));
        return back()->with('success', 'Réservation acceptée');
    }


    

}
