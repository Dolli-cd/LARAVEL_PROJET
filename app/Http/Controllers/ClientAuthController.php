<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Produit;

// Ajoutez cet import en haut de AdminAuthController.php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ClientAuthController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['auth' => 'Vous devez être connecté pour accéder à votre dashboard.']);
        }
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        return view('Client.dashboard', compact('data'));
    }

    //pour la recherche
    public function search(Request $request)
    {
        $query = $request->input('search');

        // Rechercher les produits et leurs relations avec les pharmacies
        $produits = Produit::with(['pharmacies' => function ($query) {
            $query->wherePivot('status', 'available'); // Filtrer les produits disponibles
        }])
            ->where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->orWhere('type', 'like', "%$query%")
            ->get();

        // Retourner les résultats sous forme de HTML partiel
        return view('client.search', compact('produits'))->render();
    }
    public function show($id, $pharmacie_id)
    {
        $produit = Produit::with(['pharmacies' => function ($query) use ($pharmacie_id) {
            $query->where('pharmacie_produit.pharmacie_id', $pharmacie_id);
        }])->findOrFail($id);
        return view('client.produit-detail', compact('produit'));
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
            'address'=> ['required', 'string', 'max:100', 'min:4'],
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
        $user->client()->create([
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
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
}
