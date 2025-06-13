<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le profil de l'utilisateur
     */
    public function show()
    {
        $user = Auth::user();
        
        // Si l'utilisateur n'est pas connecté, rediriger vers login
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('auth.profil', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('auth.edit', compact('user'));
    }

    /**
     * Met à jour le profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Validation des données
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'enum', 'max:20'],
            'address' => ['required', 'string', 'max:200'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
        ]);

        try {
            // Gestion de l'upload d'avatar
            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar s'il existe
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                // Sauvegarder le nouvel avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Mettre à jour les informations de l'utilisateur
            $user->update($validated);

            return redirect()
                ->route('profil')
                ->with('success', 'Votre profil a été mis à jour avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de votre profil.');
        }
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        try {
            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Votre mot de passe a été mis à jour avec succès !');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du mot de passe.');
        }
    }

    /**
     * Supprime l'avatar de l'utilisateur
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
            }

            return redirect()
                ->route('profil')
                ->with('success', 'Votre photo de profil a été supprimée.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la photo.');
        }
    }
}