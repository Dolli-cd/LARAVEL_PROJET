<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\CartReminderNotification;
use Carbon\Carbon;

class CheckCartReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Récupérer tous les utilisateurs clients
        $clients = User::where('role', 'client')->get();

        foreach ($clients as $client) {
            $cart = session('panier_' . $client->id, []);
            
            // Vérifier si le panier n'est pas vide
            if (!empty($cart)) {
                $cartTime = session('panier_time_' . $client->id);
                
                // Si le temps du panier n'est pas défini, on le définit maintenant
                if (!$cartTime) {
                    session(['panier_time_' . $client->id => now()]);
                    continue;
                }

                // Calculer la différence de temps
                $cartTime = Carbon::parse($cartTime);
                $now = Carbon::now();
                $hoursDiff = $cartTime->diffInHours($now);

                // Si plus de 10 heures se sont écoulées
                if ($hoursDiff <= 1) {
                    // Envoyer la notification
                    $client->notify(new CartReminderNotification($client, $cart));
                    
                    // Optionnel : vider le panier après envoi du rappel
                    // session()->forget('panier_' . $client->id);
                    // session()->forget('panier_time_' . $client->id);
                }
            }
        }
    }
}
