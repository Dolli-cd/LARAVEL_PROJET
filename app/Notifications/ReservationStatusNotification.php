<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationStatusNotification extends Notification
{
    use Queueable;

    public $status;
    public $reservation;

    public function __construct($status, $reservation)
    {
        $this->status = $status;
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $pharmacieName = $this->reservation->pharmacie->user->name ?? 'Pharmacie';
        // Si la réservation peut avoir plusieurs produits, prends le premier ou liste-les
        $produits = $this->reservation->produits; // relation many-to-many
        $produitNames = $produits->pluck('name')->implode(', ');

        $message = match($this->status) {
            'confirmed' => "Votre réservation N°{$this->reservation->id} pour <strong>{$produitNames}</strong> a été acceptée par la pharmacie <strong>{$pharmacieName}</strong>.",
            'rejected'  => "Votre réservation N°{$this->reservation->id} pour <strong>{$produitNames}</strong> a été refusée par la pharmacie <strong>{$pharmacieName}</strong>.",
            'expired'   => "Votre réservation N°{$this->reservation->id} pour <strong>{$produitNames}</strong> a expiré.",
            default     => "Mise à jour de votre réservation #{$this->reservation->id}.",
        };

        return [
            'message' => $message,
            'reservation_id' => $this->reservation->id,
            'status' => $this->status,
        ];
    }
}
