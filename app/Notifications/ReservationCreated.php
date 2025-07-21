<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReservationCreated extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'client_name' => $this->reservation->client->user->name,
            'message' => 'Nouvelle demande de réservation de ' . $this->reservation->client->user->name,
        ];
    }
}
