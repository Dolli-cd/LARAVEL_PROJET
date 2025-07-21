<?php
/*
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ClientAuthController, AdminAuthController,PharmacieAuthController,PanierController,ReservationController,ProfileController, ProduitController,Geolocalisation,CommandeController,PaiementController};


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
*/