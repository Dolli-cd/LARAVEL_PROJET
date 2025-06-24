<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ClientAuthController, AdminAuthController,PharmacieAuthController,ReservationController,ProfileController, ProduitController};
/*Route::get('/', function () {
    return view("welcome");
});*/

Route::get('/',function(){
    return view ('accueil');
})->name('wel');




// pharma espace
Route::get('pharmacie/dashboard',[PharmacieAuthController::class,'dashboard'])->name('pharmacie.dashboard');
Route::prefix('pharmacie')->middleware('auth')->group(function(){
Route::get('/liste', [ProduitController::class, 'liste_pro'])->name('liste_produit');
Route::get('/search', [ProduitController::class, 'search_pro'])->name('search');
Route::get('/ajouter', [ProduitController::class, 'ajouter_pro'])->name('ajouter_produit');
Route::post('/ajouter/traitement', [ProduitController::class, 'ajouter_pro_traitement'])->name('ajouter_produit_traitement');
Route::get('/update/{id}', [ProduitController::class, 'update_pro'])->name('update_produit');
Route::post('/update/traitement', [ProduitController::class, 'update_pro_traitement'])->name('update_produit_traitement');
Route::get('/delete/{id}', [ProduitController::class, 'delete_pro'])->name('delete_produit');
Route::post('/Produits/delete-multiple', [ProduitController::class, 'deleteMultiple'])->name('produit.deleteMultiple');});
Route::get('loginpharma',[PharmacieAuthController::class, 'formlogin'])->name('loginpharma');
Route::post('loginsave',[PharmacieAuthController::class, 'login'])->name('loginsave');


//Espace admin
Route::get('/admin/login', [AdminAuthController::class, 'formloginadmin'])->name('admin.login');
Route::post('/admin/loginadmin', [AdminAuthController::class, 'loginadmin'])->name('adminlog');
Route::get('/admin/signup', [AdminAuthController::class, 'formSignup'])->name('admin.signupadmin');
Route::post('/admin/signupadmin', [AdminAuthController::class, 'signup'])->name('admin.signup');
Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
Route::get('inscriptionpharmacie', [AdminAuthController::class, 'createUser'])->name('inscriptionpharma');
Route::post('pharmainscription',[AdminAuthController::class,'createPharma' ])->name('registerpharma');
Route::get('admin/statistique',[AdminAuthController::class,'statistique' ])->name('statistique');


// Espace connexion et inscription de client
Route::get('client/dashboard', [ClientAuthController::class, 'dashboard'])
    ->name('client.dashboard');
Route::get('/produit/{id}/{pharmacie_id}', [ClientAuthController::class, 'show'])->name('produit.detail');
Route::get('/produit/search', [ClientAuthController::class, 'search'])->name('client.search');
Route::get('profil',[ProfileController::class,'show'])->name('profil');
Route::put('mofifierprofil',[ProfileController::class,'edit'])->name('profiledit');

Route::get('inscription', [ClientAuthController::class, "formsignup"])->name('inscription');
Route::post('inscription',[ClientAuthController::class,'signup' ])->name('register');
Route::get('login',[ClientAuthController::class, 'formlogin'])->name('login');
Route::post('loginin',[ClientAuthController::class, 'login'])->name('loginin');
Route::post('/déconnexion', [ClientAuthController::class, 'logout'])->name('logout');


//pas encore gérer cette route
Route::get('reserv',function(){
    return view ('reserv');
})->name('reserv');


