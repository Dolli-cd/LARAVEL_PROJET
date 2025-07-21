<?php
/*
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ClientAuthController, AdminAuthController,PharmacieAuthController,PanierController,ReservationController,ProfileController, ProduitController,Geolocalisation,CommandeController,PaiementController};



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
*/


