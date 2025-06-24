<?php

namespace App\Http\Controllers;
// Ajoutez ces imports
use App\Models\User;
use App\Models\Client;
use App\Models\Pharmacie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche le profil selon le rôle
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        return match ($user->role) {
            'client' => view('client.profil', compact('user')),
            'pharmacie' => view('pharmacie.profil', compact('user')),
            'admin' => view('admin.profil', compact('user')),
            default => abort(403)
        };
    }

    /**
     * Affiche le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        return match ($user->role) {
            'client' => view('client.edit', compact('user')),
            'pharmacie' => view('pharmacie.edit', compact('user')),
            'admin' => view('admin.edit', compact('user')),
            default => abort(403)
        };
    }

    /**
     * Met à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:200'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Ajout des règles spécifiques au rôle
        if ($user->role === 'client') {
            $rules['birth_date'] = ['required', 'date'];
            $rules['gender'] = ['required', 'in:male,female'];
        }

        $validated = $request->validate($rules);

        try {
            // Gestion de l'avatar
            if ($request->hasFile('avatar')) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user->update($validated);

            // Mettre à jour les infos liées (par exemple Client)
            if ($user->role === 'client' && $user->client) {
                $user->client->update([
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                ]);
            }

            return redirect()->route('profil')->with('success', 'Profil mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        try {
            $user->update(['password' => Hash::make($validated['password'])]);

            return redirect()->route('profil')->with('success', 'Mot de passe mis à jour !');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du mot de passe.');
        }
    }

    /**
     * Supprime l’avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
            }

            return redirect()->route('profil')->with('success', 'Photo supprimée.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la photo.');
        }
    }
}
