<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $user = auth()->user();
            if ($user) {
                $panier = session('panier_' . $user->id, []);
                // Si c'est une pharmacie, compter les notifications de la pharmacie
                if ($user->role === 'pharmacie' && $user->pharmacie) {
                    $notificationCount = $user->pharmacie->unreadNotifications()->count();
                } else {
                    $notificationCount = $user->unreadNotifications()->count();
                }
            } else {
            $panier = session('panier', []);
                $notificationCount = 0;
            }
            $panierTotalQuantity = 0;
            if (is_array($panier)) {
                foreach ($panier as $item) {
                    $panierTotalQuantity += isset($item['quantity']) ? (int)$item['quantity'] : 1;
                }
            }
            $view->with('panierCount', $panierTotalQuantity)
                 ->with('notificationCount', $notificationCount);
        });
     
    }
}
