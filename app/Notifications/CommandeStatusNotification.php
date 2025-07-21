<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeStatusNotification extends Notification
{
    use Queueable;
    public $status;
    public $commande;

    public function __construct($status, $commande)
    {
        $this->status = $status;
        $this->commande = $commande;
    }

   
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase( $notifiable)
    {
        $pharmacieName = $this->commande->pharmacie->user->name ?? 'Pharmacie';
        // Si la Commande peut avoir plusieurs produits, prends le premier ou liste-les
        $produits = $this->commande->produits; // relation many-to-many
        $produitNames = $produits->pluck('name')->implode(', ');

        $message = match($this->status) {
            'confirmed' => "Votre Commande N°{$this->commande->id} pour <strong>{$produitNames}</strong> a été acceptée par la pharmacie <strong>{$pharmacieName}</strong>.",
            'cancelled'  => "Votre Commande N°{$this->commande->id} pour <strong>{$produitNames}</strong> a été refusée par la pharmacie <strong>{$pharmacieName}</strong>.",
            default     => "Mise à jour de votre Commande #{$this->commande->id}.",
        };

        return [
            'message' => $message,
            'commande_id' => $this->commande->id,
            'status' => $this->status,
        ];
    }


}
