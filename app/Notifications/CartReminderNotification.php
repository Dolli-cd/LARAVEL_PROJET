<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CartReminderNotification extends Notification
{
    use Queueable;

    public $cartItems;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $cartItems)
    {
        $this->user = $user;
        $this->cartItems = $cartItems;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $totalItems = count($this->cartItems);
        $totalPrice = 0;
        
        foreach ($this->cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return (new MailMessage)
            ->subject('🛒 Rappel : Vos produits vous attendent dans votre panier !')
            ->greeting('Bonjour ' . $this->user->name . ' !')
            ->line('Nous avons remarqué que vous avez ' . $totalItems . ' produit(s) dans votre panier depuis plus de 10 heures.')
            ->line('Ne manquez pas l\'opportunité de finaliser votre commande !')
            ->line('Total de votre panier : ' . number_format($totalPrice, 0, ',', ' ') . ' FCFA')
            ->action('Voir mon panier', url('/panier'))
            ->line('Ces produits pourraient ne plus être disponibles demain.')
            ->salutation('Cordialement, l\'équipe PharmFind');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'cart_reminder',
            'message' => 'Rappel : Vous avez des produits dans votre panier depuis plus de 10h',
            'cart_items_count' => count($this->cartItems),
        ];
    }
}
 