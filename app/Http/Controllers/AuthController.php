<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
     public function formsignup()
     {
        if (Auth::check()) {
            return redirect()->route('wel');
        }
        return view('auth.inscri');
    }
    public function signup (Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone'=> ['required', 'string', 'regex:/^(\+22901)?[0-9]{8}$/'],
            'birth_date'=> ['required', 'date', 'before:today', 'after:1900-01-01'],
            'gender'=> ['required', 'in:male,female'],
            'address'=> ['required', 'string', 'max:100', 'min:16'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('great', 'Vous vous êtes inscrit avec succès.');
   }
   // formulaire de connexion
    public function formlogin()
    {
        if (Auth::check()) {
            return redirect()->route('wel');
        }
        return view('auth.login');
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
            return redirect()->route('wel');
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout(); // "Auth::logout()"
        return redirect('wel');
    }
}
