<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Reservation;
use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\Geolocalisation;
use App\Notifications\ReservationStatusNotification;
use App\Notifications\CommandeStatusNotification;

// Ajoutez cet import en haut de AdminAuthController.php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ClientAuthController extends Controller
{
    public function accueil()
    {
        $produits = Produit::all();
        $pharmacies = Pharmacie::with([
            'user',
            'geolocalisation.arrondissement.commune.departement'
        ])->get();
        return view('accueil', compact('produits', 'pharmacies'));
    }
    public function dashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['auth' => 'Vous devez être connecté pour accéder à votre dashboard.']);
        }
        $client= $user->client;
        $geo = $client->geolocalisation;
        $arr = $geo ? $geo->arrondissement : null;
        $com = $arr ? $arr->commune : null;
        $dep = $com ? $com->departement : null;

        $produits = Produit::all();
        $pharmacies = Pharmacie::whereHas('geolocalisation.arrondissement.commune', function($q) use ($com,$dep){
            $q->where('id',$com->id)
                ->where('departement_id',$dep->id); 
        })->get();
        return view('accueil', compact('produits', 'pharmacies'));
    }

    //pour la recherche
    public function search(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');

        $produits = collect();
        $pharmacies = collect();

        if ($type === 'produit' || !$type) {
            $produitsQuery = Produit::with('pharmacies.user');
            
            // Filtre par catégorie si spécifiée
            if ($request->filled('categorie_id')) {
                $produitsQuery->where('categorie_id', $request->categorie_id);
            }
            
            $produits = $produitsQuery
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($q2) use ($query) {
                        $q2->where('name', 'ILIKE', "%{$query}%")
                            ->orWhere('code', 'ILIKE', "%{$query}%")
                            ->orWhere('type', 'ILIKE', "%{$query}%")
                            ->orWhereHas('pharmacies', function ($q3) use ($query) {
                                $q3->where('pharmacie_produit.price', 'ILIKE', "%{$query}%");
                            });
                    });
                })
                ->paginate(8, ['*'], 'produits_page');;
        }

        if ($type === 'pharmacie' || !$type) {
            $pharmacies = Pharmacie::with('user')
                ->when($query, function ($q) use ($query) {
                    $q->whereHas('user', function ($q2) use ($query) {
                        $q2->where('name', 'ILIKE', "%{$query}%")
                            ->orWhere('address', 'ILIKE', "%{$query}%")
                            ->orWhere('phone', 'ILIKE', "%{$query}%");
                    })
                    ->orWhere('insurance_name', 'ILIKE', "%{$query}%")
                    ->orWhere('schedule', 'ILIKE', "%{$query}%")
                    ->orWhere('online', 'ILIKE', "%{$query}%");
                })
                ->paginate(8, ['*'], 'pharmacies_page');
        }

        return view('client.search', [
            'produits' => $produits,
            'pharmacies' => $pharmacies,
            'query' => $query,
            'type' => $type,
            
        ]);

    }
    public function suggestions(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $results = collect();

        if ($type === 'produit') {
            $produits = \App\Models\Produit::with(['pharmacies' => function($q) {
                $q->withPivot('price');
            }])
            ->where('name', 'ILIKE', "%{$search}%")
            ->limit(5)
            ->get()
            ->map(function($produit) {
                $price = $produit->pharmacies->first()?->pivot?->price ?? null;
                return [
                    'type' => 'produit',
                    'name' => $produit->name,
                    'file' => $produit->file ? asset('storage/' . $produit->file) : asset('img/default-product.png'),
                    
                ];
            });
            $results = $produits;
        } elseif ($type === 'pharmacie') {
            // Suggestions de pharmacies avec avatar
            $pharmacies = \App\Models\Pharmacie::with('user')
                ->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('address', 'ILIKE', "%{$search}%")
                      ->orWhere('guard_time', 'ILIKE', "%{$search}%");
                })
                ->limit(5)
                ->get()
                ->map(function($pharmacie) {
                    return [
                        'type' => 'pharmacie',
                        'name' => $pharmacie->user->name . ' (' . $pharmacie->guard_time . ')',
                        'avatar' => $pharmacie->user->avatar ? asset('storage/' . $pharmacie->user->avatar) : asset('img/default-avatar.png'),
                    ];
                });
            $results = $pharmacies;
        } else {
            // Suggestions mixtes
            $produits = \App\Models\Produit::with(['pharmacies' => function($q) {
                $q->withPivot('price');
            }])
            ->where('name', 'ILIKE', "%{$search}%")
            ->limit(5)
            ->get()
            ->map(function($produit) {
                $price = $produit->pharmacies->first()?->pivot?->price ?? null;
                return [
                    'type' => 'produit',
                    'name' => $produit->name,
                    'file' => $produit->file ? asset('storage/' . $produit->file) : asset('img/default-product.png'),
                    'price' => $price,
                ];
            });

            $pharmacies = \App\Models\Pharmacie::with('user')
                ->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('address', 'ILIKE', "%{$search}%")
                      ->orWhere('guard_time', 'ILIKE', "%{$search}%");
                })
                ->limit(5)
                ->get()
                ->map(function($pharmacie) {
                    return [
                        'type' => 'pharmacie',
                        'name' => $pharmacie->user->name . ' (' . $pharmacie->guard_time . ')',
                        'avatar' => $pharmacie->user->avatar ? asset('storage/' . $pharmacie->user->avatar) : asset('img/default-avatar.png'),
                    ];
                });

            $results = $produits->concat($pharmacies);
        }

        return response()->json($results->values());
    }

    public function show($id, $pharmacie_id)
    {
        $produit = Produit::with(['pharmacies' => function ($query) use ($pharmacie_id) {
            $query->where('pharmacie_produit.pharmacie_id', $pharmacie_id);
        }])->findOrFail($id);
        return view('client.produit-detail', compact('produit'));
    }
    
    public function pharmacieProduits($id, Request $request)
    {
        $pharmacie = Pharmacie::with(['user', 'produits.categorie'])->findOrFail($id);
        $produits = $pharmacie->produits()->paginate(12);
        $query = $request->input('query');
        
        return view('client.pharmacie-produits', compact('pharmacie', 'produits', 'query'));
    }
    
    public function recherchePharmacie($id, Request $request)
    {
        $pharmacie = Pharmacie::with(['user', 'produits.categorie'])->findOrFail($id);
        $query = $request->input('search');
        $category = $request->input('category');
        
        // Recherche dans les produits de cette pharmacie spécifique
        $produits = Produit::whereHas('pharmacies', function ($q) use ($id) {
                $q->where('pharmacie_produit.pharmacie_id', $id);
            })
            ->with(['pharmacies' => function ($q) use ($id) {
                $q->where('pharmacie_produit.pharmacie_id', $id);
            }, 'categorie'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($q2) use ($query) {
                    $q2->where('name', 'ILIKE', "%{$query}%")
                        ->orWhere('code', 'ILIKE', "%{$query}%")
                        ->orWhere('type', 'ILIKE', "%{$query}%")
                        ->orWhereHas('categorie', function ($q3) use ($query) {
                            $q3->where('name', 'ILIKE', "%{$query}%");
                        });
                });
            })
            ->when($category, function ($q) use ($category) {
                $q->where('categorie_id', $category);
            })
            ->paginate(8);
            foreach ($produits as $produit) {
                $produit->setRelation('pivot', $produit->pharmacies->first()->pivot ?? null);
            }
            
        
        return view('client.pharmacie-produits', compact('pharmacie', 'produits', 'query'));
    }
    
     public function formsignup()
     {
        if (Auth::check()) {
            return redirect()->route('wel');
        }
        return view('Client.inscri');
    }
    public function signup (Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone'=> ['required', 'string', 'regex:/^(\+229)?[\s0-9]{8,20}$/'],
            'birth_date'=> ['required', 'date', 'before:today', 'after:1900-01-01'],
            'gender'=> ['required', 'in:male,female'],
            'address'=> ['string', 'max:100', 'min:4'],
            'departement_id' => [ 'exists:departements,id'],
            'commune_id' => ['exists:communes,id'],
            'arrondissement_id' => [ 'exists:arrondissements,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'client',
            'password' => Hash::make($request->password),
        ]);
        $client = $user->client()->create([
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
        ]);
        // Création de la géolocalisation liée au client
        Geolocalisation::create([
            'client_id' => $client->id,
            'departement_id' => $request->departement_id,
            'commune_id' => $request->commune_id,
            'arrondissement_id' => $request->arrondissement_id,
        ]);
        return redirect()->route('login')->with('great', 'Vous vous êtes inscrit avec succès.');
   }
   // formulaire de connexion
    public function formlogin()
    {
        if (Auth::check()) {
            return redirect()->route('wel');
        }
        return view('Client.login');
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
            if ($user->role=='client'){
                return redirect()->route('client.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['auth' => 'Accès non autorisé pour ce rôle.']);
            }
        }
        return back()->withErrors(['auth' => 'Email ou mot de passe incorrect.']);
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout(); // "Auth::logout()"
        return redirect('/');
    }
    
    public function notifications()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(10);

        // Marquer toutes les notifications comme lues
        $user->unreadNotifications->markAsRead();

        return view('Client.notification', compact('notifications'));
    }

    public function commandes()
    {
        $client = Client::where('user_id', auth()->id())->first();
        $commandes = [];
        if ($client) {
            $commandes = Commande::with(['produits', 'pharmacie'])
                ->where('client_id', $client->id)
                ->latest()
                ->get();
        }
        return view('Client.cmd', compact('commandes'));
    }

    public function reservations()
    {
        $client= Client::where('user_id',auth()->id())->first();
        $reservations=[];
        if ($client){
        $reservations = Reservation::with(['produits', 'pharmacie'])
            ->where('client_id', $client->id)
            ->latest()
            ->get();
        }

        return view('Client.reserv', compact('reservations'));
    }

    public function historique()
    {
        $client= Client::where('user_id', auth()->id())->first();
        $commandes=[];
        $reservations=[];
        if($client){
            $commandes = Commande::with(['produits', 'pharmacie.user'])
                ->where('client_id', $client->id)
                ->whereIn('status', ['delivered', 'cancelled']) // adapte selon tes statuts
                ->latest()
                ->get();
          
            $reservations = Reservation::with(['produits', 'pharmacie.user'])
                ->where('client_id', $client->id)
                ->whereIn('status', ['confirmed', 'cancelled']) // adapte selon tes statuts
                ->latest()
                ->get();
            }
        return view('Client.historique', compact('commandes', 'reservations'));
    }

    public function pharmaciesProches(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = 5; // rayon en km

        $sub = \DB::table('pharmacies')
            ->join('geolocalisations', 'geolocalisations.pharmacie_id', '=', 'pharmacies.id')
            ->select(
                'pharmacies.*',
                'geolocalisations.latitude',
                'geolocalisations.longitude',
                \DB::raw('(6371 * acos(
                    cos(radians('.$lat.')) * cos(radians(geolocalisations.latitude)) *
                    cos(radians(geolocalisations.longitude) - radians('.$lng.')) +
                    sin(radians('.$lat.')) * sin(radians(geolocalisations.latitude))
                )) as distance')
            );

        $pharmacies = \DB::table(\DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub)
            ->join('users', 'sub.user_id', '=', 'users.id')
            ->where('distance', '<=', $radius)
            ->orderBy('distance')
            ->select('sub.*', 'users.name as user_name', 'users.avatar as user_avatar', 'users.phone as user_phone','users.email as user_email')
            ->get();

        return view('client.pharmacies_proches', compact('pharmacies'));
    }
}
