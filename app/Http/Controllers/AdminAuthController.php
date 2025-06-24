<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function dashboard()
    {
        // Vérifier si l'utilisateur est connecté ET admin
        if (!Auth::check()) {
            return redirect()->route('admin.login')->withErrors(['auth' => 'Vous devez être connecté.']);
        }
        
        if (Auth::user()->role !== 'admin') {
            Auth::logout(); // Déconnecte si pas admin
            return redirect()->route('admin.login')->withErrors(['auth' => 'Accès non autorisé.']);
        }

        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_pharmacies' => User::where('role', 'pharmacie')->count(),
            'total_produits' => Produit::count(),
            'total_commandes' => Commande::count(),
            'total_reservations' => Reservation::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function formSignup()
    {
        // Ne pas rediriger si déjà connecté - permettre création d'autres admins
        return view('admin.signadmin');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^(\+229)?[\s0-9]{8,20}$/'],
            'address' => ['required', 'string', 'max:100', 'min:4'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('great', 'Admin créé avec succès.');
    }

    public function createUser()
    {
        return view('admin.pharmacie');
    }

  public function createPharma(Request $request)
  {
    \Log::info('Début de createPharma', $request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'password' => 'required|string|min:6|confirmed',
        'schedule' => 'required|string',
        'guard_time' => 'required|string',
        'insurance_name' => 'required|string',
    ]);
    try {
        \Log::info('Création de l\'utilisateur');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'pharmacie',
        ]);

        \Log::info('Utilisateur créé', ['user_id' => $user->id]);

        \Log::info('Création de la pharmacie');
        $user->pharmacie()->create([
            'schedule' => $request->schedule,
            'guard_time' => $request->guard_time,
            'insurance_name' => $request->insurance_name,
        ]);

        \Log::info('Pharmacie créée');

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pharmacie créée avec succès.');
        }catch (\Exception $e) {
            \Log::error('Erreur lors de la création : ' . $e->getMessage());
            return redirect()->back()
            ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])
            ->withInput();
        }
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function formloginadmin()
    {
        // Simple vérification - si connecté ET admin, rediriger
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Si connecté mais pas admin, déconnecter
        if (Auth::check() && Auth::user()->role !== 'admin') {
            Auth::logout();
        }
        
        return view('admin.login');
    }

    public function loginadmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            if ($user->role === 'admin') {
                $request->session()->regenerate(); // Sécurité
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès non autorisé pour ce rôle.']);
            }
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    public function statistique(){
        return view("admin.statistique");
    }
}