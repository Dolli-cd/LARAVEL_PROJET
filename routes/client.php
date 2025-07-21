<?php
/*
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ClientAuthController, AdminAuthController,PharmacieAuthController,PanierController,ReservationController,ProfileController, ProduitController,Geolocalisation,CommandeController,PaiementController};


Route::get('/',[ClientAuthController::class, 'accueil'])->name('wel');




// Espace connexion et inscription de client
Route::get('/pharmacies/proximite', [ClientAuthController::class, 'pharmaciesProches'])->name('pharmacies.proximite');
Route::get('client/dashboard', [ClientAuthController::class, 'dashboard'])
    ->name('client.dashboard');
Route::get('notification',[ClientAuthController:: class, 'notifications'])->name('notification');
     
Route::get('/produit/{id}/{pharmacie_id}', [ClientAuthController::class, 'show'])->name('produit.detail');
Route::get('/produit/search', [ClientAuthController::class, 'search'])->name('client.search');
Route::get('/pharmacie/{id}/produits', [ClientAuthController::class, 'pharmacieProduits'])->name('pharmacie.produits');
Route::get('/pharmacie/{id}/recherche', [ClientAuthController::class, 'recherchePharmacie'])->name('pharmacie.recherche');
Route::get('profil',[ProfileController::class,'show'])->name('profil');
Route::get('modifierprofil',[ProfileController::class,'edit'])->name('profiledit');
Route::put('/profil/modifier', [ProfileController::class, 'update'])->name('profilupdate');
Route::get('/password', [ProfileController::class, 'editPassword'])->name('profileditpassword');
Route::put('/profil/modifier/password', [ProfileController::class, 'updatePassword'])->name('profilpassword');
Route::post('/commande', [CommandeController::class, 'commander'])->name('client.commander');
Route::post('/reservation', [ReservationController::class, 'reserver'])->name('client.reserver');
Route::get('/paiement/{reservation}', [PaiementController::class, 'acompte'])->name('client.paiement');

Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::post('/panier/update/{id}', [PanierController::class, 'update'])->name('panier.update');
Route::get('/panier', [PanierController::class, 'afficher'])->name('panier');
Route::post('/panier/supprimer/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');

Route::get('inscription', [ClientAuthController::class, "formsignup"])->name('inscription');
Route::post('inscription',[ClientAuthController::class,'signup' ])->name('register');
Route::get('login',[ClientAuthController::class, 'formlogin'])->name('login');
Route::post('loginin',[ClientAuthController::class, 'login'])->name('loginin');
Route::post('/déconnexion', [ClientAuthController::class, 'logout'])->name('logout');

//Géolocalisation
Route::get('/api/communes/{departement}', [Geolocalisation::class, 'getCommunes']);
Route::get('/api/arrondissements/{commune}', [Geolocalisation::class, 'getArrondissements']);
//pas encore gérer cette route
Route::get('reservation',[ClientAuthController::class,'reservations'])->name('reserv');
Route::get('commande',[ClientAuthController::class,'commandes'])->name('cmd');
Route::get('historique',[ClientAuthController::class,'historique'])->name('historique');

// Route de test pour les rappels de panier
Route::get('/test-cart-reminder', function() {
    // Simuler un panier avec des produits
    $cartItems = [
        [
            'name' => 'Paracétamol 500mg',
            'quantity' => 2,
            'price' => 1500,
            'pharmacie' => 'Pharmacie Centrale'
        ],
        [
            'name' => 'Vitamine C',
            'quantity' => 1,
            'price' => 2500,
            'pharmacie' => 'Pharmacie Centrale'
        ]
    ];
    
    // Enregistrer le temps du panier (il y a 11 heures)
    session(['panier_time' => now()->subHours(11)]);
    session(['panier' => $cartItems]);
    
    // Envoyer la notification de test
    $user = Auth::user();
    if ($user) {
        $user->notify(new \App\Notifications\CartReminderNotification($user, $cartItems));
        return 'Email de rappel envoyé ! Vérifiez les logs.';
    }
    
    return 'Utilisateur non connecté';
})->middleware('auth');

Route::post('/notifications/supprimer/{id}', [ProfileController::class, 'deleteNotification'])->name('notificationdelete');
Route::post('/notifications/supprimer', [ProfileController::class, 'deleteNotification'])->name('notificationdeleteAll');

*/
