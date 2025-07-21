<?php

namespace App\Http\Controllers;
// Ajoutez cet import en haut de AdminAuthController.php
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Reservation;
use App\Models\Geolocalisation;
use App\Models\Pharmacie;
use App\Notifications\ReservationCreated; // à créer avec artisan
use App\Notifications\CommandeCreated; // à créer avec artisan

class PharmacieAuthController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'pharmacie') {
            return redirect()->route('loginpharma');
        }
        $pharmacie = Auth::user()->pharmacie;

        $pendingReservations = $pharmacie->reservations()->where('status', 'pending')->count();
        $confirmedReservations = $pharmacie->reservations()->where('status', 'confirmed')->count();
        $rejectedReservations = $pharmacie->reservations()->where('status', 'rejected')->count();
        $expiredReservations = $pharmacie->reservations()->where('status', 'expired')->count();
        $totalProduits = $pharmacie->produits()->count();
        $produitsDisponibles = $pharmacie->produits()->wherePivot('status', 'available')->count();
        $produitsIndisponibles =$pharmacie->produits()->wherePivot('status', 'unavailable')->count();
        $totalCommandes =$pharmacie->commandes()->count(); // à condition que tu aies cette relation

        return view('pharmacie.dashboardpharma', compact(
            'pendingReservations',
            'confirmedReservations',
            'rejectedReservations',
            'expiredReservations',
            'totalProduits',
            'produitsDisponibles',
            'produitsIndisponibles',
            'totalCommandes'
        ));
    }
    public function formsign()
     {
        if (Auth::check()) {
            return redirect()->route('pharmacie.dashboard');
        }
        return view('pharmacie.login');
    }
     // formulaire de connexion
    public function formlogin()
    {
        if (Auth::check()) {
            return redirect()->route('wel');
        }
        return view('pharmacie.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);


      
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'pharmacie') {
                return redirect()->route('pharmacie.dashboard');
            }
            return back()->withErrors(['email' => 'Accès non autorisé pour ce rôle.']);
        }
            
        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }
            
   
    public function produits()
    {
        $produits = auth()->user()->pharmacie->produits()->orderBy('id','asc')->get();
        return view('pharmacie.produit', compact('produits'));
    }
   
    public function acceptReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->pharmacie_id === auth()->user()->pharmacie->id) {
            $reservation->status = 'acceptée';
            $reservation->save();
        }
        return redirect()->back()->with('success', 'Réservation acceptée');
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->pharmacie_id === auth()->user()->pharmacie->id) {
            $reservation->status = 'refusée';
            $reservation->save();
        }
        return redirect()->back()->with('error', 'Réservation refusée');
    }


    public function historique()
    {
        $pharmacie = auth()->user()->pharmacie;
        $commandes = $pharmacie->commandes()->with('client', 'produits')->latest()->paginate(10);
        $reservations = $pharmacie->reservations()->with('client', 'produits')->latest()->paginate(10);
        return view('pharmacie.historique', compact('commandes', 'reservations'));
    }


    public function notifications()
    {
        $pharmacie = auth()->user()->pharmacie;
        $notifications = $pharmacie->notifications()->paginate(10);

        // Marquer toutes les notifications comme lues
        $pharmacie->unreadNotifications->markAsRead();

        return view('pharmacie.notification', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    public function commandes()
    {
        $pharmacie = auth()->user()->pharmacie;
        $commandes = $pharmacie->commandes()->with(['client', 'produits'])->where('status','pending')->latest()->paginate(10);
        return view('pharmacie.commande', compact('commandes'));
    }





    public function reservations()
    {
        $pharmacie = auth()->user()->pharmacie;
        $reservations = $pharmacie->reservations()->with(['client', 'produits'])->where('status', 'pending')->latest()->paginate(5);
        return view('pharmacie.reservation', compact('reservations'));
    }



}

