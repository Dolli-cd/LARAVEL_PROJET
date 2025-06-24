<?php

namespace App\Http\Controllers;
// Ajoutez cet import en haut de AdminAuthController.php
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Reservation;
use App\Models\Notification;

class PharmacieAuthController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('loginpharma')->withErrors(['auth' => 'Vous devez être connecté pour accéder à votre dashboard.']);
        }
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        return view('pharmacie.dashboardpharma', compact('data'));
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

        $remember = $request->has('remember'); //  vérifier si la case "remember" est cochée

        // Auth::attempt prend juste les identifiants, pas le remember dans le tableau
        if (Auth::attempt($credentials, $remember)) {
            $user = auth()->user();
            if ($user->role=='pharmacie'){
                return redirect()->route('pharmacie.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès non autorisé pour ce rôle.']);
            }
        }
        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    public function index()
    {
        $pharmacie = auth()->user()->pharmacie;

        $pendingReservations = $pharmacie->reservations()->where('status', 'en_attente')->count();
        $confirmedReservations = $pharmacie->reservations()->where('status', 'acceptée')->count();
        $rejectedReservations = $pharmacie->reservations()->where('status', 'refusée')->count();
        $expiredReservations = $pharmacie->reservations()->where('status', 'expirée')->count();

        $notifications = auth()->user()->notifications()->latest()->take(10)->get();

        return view('pharmacie.dashboard', compact(
            'pendingReservations',
            'confirmedReservations',
            'rejectedReservations',
            'expiredReservations',
            'notifications'
        ));
    }

    public function produits()
    {
        $produits = auth()->user()->pharmacie->produits;
        return view('pharmacie.produit', compact('produits'));
    }

    public function commandes()
    {
        $commandes = auth()->user()->pharmacie->commandes;
        return view('pharmacie.commandes.index', compact('commandes'));
    }

    public function reservations()
    {
        $reservations = auth()->user()->pharmacie->reservations;
        return view('pharmacie.reservations.index', compact('reservations'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        return view('pharmacie.notifications', compact('notifications'));
    }

    public function historique()
    {
        $history = auth()->user()->activityLogs()->latest()->get();
        return view('pharmacie.historique', compact('history'));
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
}

