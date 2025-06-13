<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ProfileController, ProduitController};
/*Route::get('/', function () {
    return view('welcome');
});*/

// pharma espace
Route::get('dashboard',function(){
    return view('pharmacie.dashboardpharma');
});
Route::get('/liste', [ProduitController::class, 'liste_pro'])->name('liste_produit');
Route::get('/search', [ProduitController::class, 'search_pro'])->name('search');
Route::get('/ajouter', [ProduitController::class, 'ajouter_pro'])->name('ajouter_pro');
Route::post('/ajouter/traitement', [ProduitController::class, 'ajouter_pro_traitement'])->name('ajouter_produit_traitement');
Route::get('/update/{id}', [ProduitController::class, 'update_pro'])->name('update_produit');
Route::post('/update/traitement', [ProduitController::class, 'update_pro_traitement'])->name('update_produit_traitement');
Route::get('/delete/{id}', [ProduitController::class, 'delete_pro'])->name('delete_produit');
Route::post('/Produits/delete-multiple', [ProduitController::class, 'deleteMultiple'])->name('produit.deleteMultiple');

//Espace admin


// Espace connexion et inscriptio
Route::get('inscription', [AuthController::class, "formsignup"])->name('inscription');
Route::post('inscription',[AuthController::class,'signup' ])->name('register');
Route::get('login',[AuthController::class, 'formlogin'])->name('login');
Route::post('loginin',[AuthController::class, 'login'])->name('loginin');
Route::post('/déconnexion', [AuthController::class, 'logout'])->name('logout');

Route::get('reserv',function(){
    return view ('reserv');
})->name('reserv');

Route::get('/',function(){
    return view ('wel');
})->name('wel');

Route::get('profil',[ProfileController::class,'show'])->name('profil');
Route::put('mofifierprofil',[ProfileController::class,'edit'])->name('profiledit');
