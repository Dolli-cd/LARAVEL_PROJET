<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($commande)
    {
      $this->commande =$commande;
    }

   
    public function via( $notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase( $notifiable)
    {
        return [
            'commande_id' => $this->commande->id,
            'client_name' => $this->commande->client->user->name,
            'message' => 'Nouvelle commande  de ' . $this->commande->client->user->name,
        ];
    }

}
