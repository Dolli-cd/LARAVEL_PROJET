<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Reservation;
use App\Models\Geolocalisation;
use App\Models\Departement;
use App\Models\Commune;
use App\Models\Arrondissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\ProduitSearchService;

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
         $users= User::all();
         return view('admin.dashboard', compact('users'));   
    }

    public function formSignup()
    {
        // Ne pas rediriger si déjà connecté - permettre création d'autres admins
        return view('admin.signadmin');
    }
    public function produit()
    {
        // Récupérer tous les produits avec leurs relations pharmacies
        $allProduits = Produit::with('pharmacies.user')->paginate(10);
        return view('admin.produit', ['produits'=>$allProduits]);
    }

    public function searchProduits(Request $request)
    {
        $search = $request->input('search');

        $query = Produit::with('pharmacies.user');
        $query = ProduitSearchService::apply($query, $search);

        $produits = $query->paginate(10);

        if (request()->ajax()) {
            return view('admin.partials_produit_table', compact('produits'))->render();
        }
        return view('admin.produit', compact('produits', 'search'));
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^(\+229)?[\s0-9]{8,20}$/'],
            'address' => ['required', 'string', 'max:100', 'min:4'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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
        return redirect()->route('users')->with('great', 'Admin créé avec succès.');
    }
  public function createPharma(Request $request)
  {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'password' => 'required|string|min:6|confirmed',
        'schedule' => 'required|string',
        'guard_time' => 'required|string',
        'insurance_name' => 'required|string',
        'departement_id' => [ 'required','exists:departements,id'],
        'commune_id' => ['required','exists:communes,id'],
        'arrondissement_id' => [ 'required','exists:arrondissements,id'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ]);
    try {
        \Log::info('Début de création de pharmacie', $request->only(['name', 'email', 'phone', 'address']));
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'pharmacie',
        ]);

        $pharmacie = $user->pharmacie()->create([
            'schedule' => $request->schedule,
            'guard_time' => $request->guard_time,
            'insurance_name' => $request->insurance_name,
            'online' => $request->has('online'),
        ]);

        // Construction de l'adresse complète pour Nominatim
        $adresse = $request->input('address');
        $arrondissement = Arrondissement::find($request->arrondissement_id);
        $commune = $arrondissement ? $arrondissement->commune : null;
        $departement = $commune ? $commune->departement : null;
        $adresse_complete = $adresse;
        if ($arrondissement) $adresse_complete .= ', ' . $arrondissement->name;
        if ($commune) $adresse_complete .= ', ' . $commune->name;
        if ($departement) $adresse_complete .= ', ' . $departement->name;

        // Appel à Nominatim pour latitude/longitude
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'PharmFind/1.0 (abriellebandeira@gmail.com)' // Remplace par ton vrai nom d'app/email
            ])->get('https://nominatim.openstreetmap.org/search', [
                'format' => 'json',
                'q' => $adresse_complete,
            ]);
            $data = $response->json();
            $latitude = !empty($data) ? $data[0]['lat'] : null;
            $longitude = !empty($data) ? $data[0]['lon'] : null;
        } catch (\Exception $e) {
            \Log::warning('Nominatim API error: ' . $e->getMessage());
        }

        Geolocalisation::create([
            'pharmacie_id' => $pharmacie->id,
            'departement_id' => $request->departement_id,
            'commune_id' => $request->commune_id,
            'arrondissement_id' => $request->arrondissement_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        \Log::info('Pharmacie créée avec succès', ['user_id' => $user->id, 'pharmacie_id' => $pharmacie->id]);

        return redirect()->route('users')
            ->with('success', 'Pharmacie créée avec succès.');
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création de la pharmacie : ' . $e->getMessage());
        return redirect()->back()
            ->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])
            ->withInput();
    }
  }


    public function deleteUser($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();
        
        // Redirection dynamique selon la provenance
        $redirect = $request->input('redirect', 'users');
        $message = $request->input('message', 'Utilisateur supprimé avec succès');
        return redirect()->route($redirect)->with('success', $message);
    }

   public function pharmaliste()
   {
        $pharmacies = Pharmacie::with('user')->get();
        return view('admin.pharmacie', compact('pharmacies'));

    }

    public function pharmacieProduits($id)
    {
        $pharmacie = \App\Models\Pharmacie::with(['user', 'produits.categorie'])->findOrFail($id);
        $produits = $pharmacie->produits()->with('categorie')->paginate(10);

        return view('admin.pharmacie_produits', compact('pharmacie', 'produits'));
    }


 


    public function formloginadmin()
    {
        // Simple vérification - si connecté ET admin, rediriger
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('users');
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
                return redirect()->route('users');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès non autorisé pour ce rôle.']);
            }
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    public function statistique(){
        // Statistiques de base
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_pharmacies' => User::where('role', 'pharmacie')->count(),
            'total_produits' => Produit::count(),
            'total_commandes' => Commande::count(),
            'total_reservations' => Reservation::count(),
        ];  

        // Statistiques avancées
        $stats['commandes_ce_mois'] = Commande::whereMonth('created_at', now()->month)->count();
        $stats['reservations_ce_mois'] = Reservation::whereMonth('created_at', now()->month)->count();
        $stats['nouveaux_clients_ce_mois'] = User::where('role', 'client')->whereMonth('created_at', now()->month)->count();
        $stats['nouveaux_pharmacies_ce_mois'] = User::where('role', 'pharmacie')->whereMonth('created_at', now()->month)->count();

        // Top 5 pharmacies par nombre de commandes
        $topPharmacies = Pharmacie::with('user')
            ->withCount(['commandes' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }])
            ->orderBy('commandes_count', 'desc')
            ->limit(5)
            ->get();

        // Statistiques par département
        $statsParDepartement = \App\Models\Departement::withCount(['communes as pharmacies_count' => function($query) {
            $query->whereHas('arrondissements.geolocalisations', function($q) {
                $q->whereHas('pharmacie.user', function($q2) {
                    $q2->where('role', 'pharmacie');
                });
            });
        }])
        ->orderBy('pharmacies_count', 'desc')
        ->get();

        // Évolution des commandes sur les 6 derniers mois
        $evolutionCommandes = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $evolutionCommandes[] = [
                'mois' => $mois->format('M'),
                'commandes' => Commande::whereYear('created_at', $mois->year)
                    ->whereMonth('created_at', $mois->month)
                    ->count()
            ];
        }

        // Évolution des réservations sur les 6 derniers mois
        $evolutionReservations = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $evolutionReservations[] = [
                'mois' => $mois->format('M'),
                'reservations' => Reservation::whereYear('created_at', $mois->year)
                    ->whereMonth('created_at', $mois->month)
                    ->count()
            ];
        }

        // Produits les plus populaires
        $produitsPopulaires = Produit::withCount(['pharmacies' => function($query) {
            $query->where('pharmacie_produit.status', 'available');
        }])
        ->orderBy('pharmacies_count', 'desc')
        ->limit(10)
        ->get();

        return view("admin.statistique", compact(
            'stats', 
            'topPharmacies', 
            'statsParDepartement', 
            'evolutionCommandes', 
            'evolutionReservations',
            'produitsPopulaires'
        ));
    }
}