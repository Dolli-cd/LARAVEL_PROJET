<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ClientAuthController, AdminAuthController,PharmacieAuthController,PanierController,ReservationController,ProfileController, ProduitController,Geolocalisation,CommandeController,PaiementController,ForgotPasswordController, ResetPasswordController};
/*Route::get('/', function () {
    return view("welcome");
});*/

Route::get('/',[ClientAuthController::class, 'accueil'])->name('wel');


// pharma espace
Route::get('pharmacie/dashboard',[PharmacieAuthController::class,'dashboard'])->name('pharmacie.dashboard');
Route::prefix('pharmacie')->middleware('auth')->group(function(){
Route::get('/liste', [ProduitController::class, 'liste_pro'])->name('liste_produit');
Route::get('/search', [ProduitController::class, 'search_pro'])->name('search');
Route::post('/ajouter/traitement', [ProduitController::class, 'ajouter_pro_traitement'])->name('ajouter_produit_traitement');
Route::get('/update/{id}', [ProduitController::class, 'update_pro'])->name('update_produit');
Route::put('/update/traitement', [ProduitController::class, 'update_pro_traitement'])->name('update_produit_traitement');
Route::post('/Produits/delete-multiple', [ProduitController::class, 'deleteMultiple'])->name('produit.deleteMultiple');});
Route::get('loginpharma',[PharmacieAuthController::class, 'formlogin'])->name('loginpharma');
Route::post('loginsave',[PharmacieAuthController::class, 'login'])->name('loginsave');
Route::get('pharmacie/notifications', [PharmacieAuthController::class, 'notifications'])->name('pharmacie.notifications');
Route::post('pharmacie/notifications/{id}/read', [PharmacieAuthController::class, 'markAsRead'])->name('pharmacie.notification.read');
Route::get('pharmacie/historique', [PharmacieAuthController::class, 'historique'])->name('pharmacie.historiques');
Route::get('pharmacie/commandes', [PharmacieAuthController::class, 'commandes'])->name('pharmacie.commandes');
Route::get('pharmacie/reservations', [PharmacieAuthController::class, 'reservations'])->name('pharmacie.reservations');
Route::post('/pharmacie/commande/{id}/confirmer', [CommandeController::class, 'confirmer'])->name('pharmacie.commande.confirmer');
Route::post('/pharmacie/commande/{id}/annuler', [CommandeController::class, 'annuler'])->name('pharmacie.commande.annuler');
Route::post('/pharmacie/réservation/{id}/confirmer', [ReservationController::class, 'confirmer'])->name('pharmacie.reservation.valider');
Route::post('/pharmacie/réservation/{id}/annuler', [ReservationController::class, 'annuler'])->name('pharmacie.reservation.annuler');
Route::post('/pharmacie/online', [ProfileController::class, 'updateOnline'])->name('pharmacie.online');
Route::delete('/delete/{id}', [ProduitController::class, 'delete_pro'])->name('delete_produit');
//Espace admin
Route::get('/admin/login', [AdminAuthController::class, 'formloginadmin'])->name('admin.login');
Route::post('/admin/loginadmin', [AdminAuthController::class, 'loginadmin'])->name('adminlog');
Route::get('/admin/signup', [AdminAuthController::class, 'formSignup'])->name('admin.signupadmin');
Route::post('/admin/signupadmin', [AdminAuthController::class, 'signup'])->name('admin.signup');
Route::get('inscriptionpharmacie', [AdminAuthController::class, 'createUser'])->name('inscriptionpharma');
Route::post('pharmainscription',[AdminAuthController::class,'createPharma' ])->name('registerpharma');
Route::get('/pharmacies/liste', [AdminAuthController::class, 'pharmaliste'])->name('pharmaliste');
Route::put('/pharmacies/{id}', [AdminAuthController::class, 'update'])->name('update_pharma');
Route::get('admin/statistique',[AdminAuthController::class,'statistique' ])->name('statistique');
Route::get('admin/client',[AdminAuthController::class,'produit' ])->name('produit');
Route::get('admin/search/produits',[AdminAuthController::class,'searchProduits' ])->name('admin.search.produits');
Route::get('/pharmacies/{id}/produits', [AdminAuthController::class, 'pharmacieProduits'])->name('admin.pharmacie.produits');
Route::get('admin/dashboard',[AdminAuthController::class,'dashboard' ])->name('users');
Route::DELETE('admin/delete/users/{id}',[AdminAuthController::class,'deleteUser' ])->name('userdelete');


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
//Route::get('modifierprofil',[ProfileController::class,'edit'])->name('profiledit');
Route::put('/profil/modifier', [ProfileController::class, 'update'])->name('profilupdate');
Route::get('/password', [ProfileController::class, 'editPassword'])->name('profileditpassword');
Route::put('/profil/modifier/password', [ProfileController::class, 'updatePassword'])->name('profilpassword');
Route::post('/commande', [CommandeController::class, 'commander'])->name('client.commander');
Route::post('/reservation', [ReservationController::class, 'reserver'])->name('client.reserver');
Route::get('/paiement/reservation/{id}', [PaiementController::class, 'payerReservation'])->name('reservation.paiement');
Route::get('/paiement/commande/{id}', [PaiementController::class, 'payerCommande'])->name('commande.paiement');

//pas encore gérer
Route::get('/qui-sommes-nous', function() {
    return view ('layouts.sommes nous');
})->name('quiSommesNous');
Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::post('/panier/update/{id}', [PanierController::class, 'update'])->name('panier.update');
Route::get('/panier', [PanierController::class, 'afficher'])->name('panier');
Route::post('/panier/supprimer/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');

Route::get('inscription', [ClientAuthController::class, "formsignup"])->name('inscription');
Route::post('inscription',[ClientAuthController::class,'signup' ])->name('register');
Route::get('login',[ClientAuthController::class, 'formlogin'])->name('login');
Route::post('loginin',[ClientAuthController::class, 'login'])->name('loginin');
Route::post('/déconnexion', [ClientAuthController::class, 'logout'])->name('logout');
Route::get('/suggestions', [ClientAuthController::class, 'suggestions'])->name('suggestions');
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


Route::get('mot-de-passe-oublie', [ForgotPasswordController::class, 'formulaire'])->name('password.request');
Route::post('mot-de-passe-oublie', [ForgotPasswordController::class, 'envoyer'])->name('password.email');

Route::get('reinitialisation/{token}', [ResetPasswordController::class, 'formulaire'])->name('password.reset');
Route::post('reinitialisation', [ResetPasswordController::class, 'mettreAJour'])->name('password.update');
