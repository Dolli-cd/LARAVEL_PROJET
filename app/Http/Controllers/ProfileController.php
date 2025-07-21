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
            'admin' => view('Admin.profil', compact('user')),
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
            'avatar' => ['nullable', 'image', 'max:6144'],
        ];

        // Ajoute les règles pour le mot de passe si le champ est rempli
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required'];
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        // Ajoute les règles spécifiques à la pharmacie
        if ($user->role === 'pharmacie') {
            $rules['schedule'] = ['required', 'string', 'max:255'];
            $rules['guard_time'] = ['required', 'string', 'max:255'];
            $rules['insurance_name'] = ['required', 'string', 'max:255'];
           // $rules['online'] = ['nullable', 'boolean'];
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

            // Gestion du mot de passe
            if ($request->filled('current_password') && $request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
                }
                $validated['password'] = Hash::make($request->password);
            } else {
                unset($validated['password']);
            }
            unset($validated['current_password']);

            // Mise à jour des données utilisateur
            $user->update($validated);

            // Mise à jour des données spécifiques à la pharmacie
            if ($user->role === 'pharmacie') {
                $pharmacie = Pharmacie::where('user_id', $user->id)->first();
                if ($pharmacie) {
                    $pharmacieData = [
                        'schedule' => $validated['schedule'],
                        'guard_time' => $validated['guard_time'],
                        'insurance_name' => $validated['insurance_name'],
                       // 'online' => $request->has('online') ? true : false,
                    ];
                    $pharmacie->update($pharmacieData);
                }
            }

            return redirect()->route('profil')->with('success', 'Profil mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
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

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.'], 'password');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profil')->with('success', 'Mot de passe mis à jour !');
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('client.password', compact('user'));
    }

    /**
     * Met à jour la présence en ligne de la pharmacie
     */
    public function updateOnline(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'pharmacie') {
            $pharmacie = $user->pharmacie;
            if ($pharmacie) {
                $pharmacie->online = $request->has('online') ? true : false;
                $pharmacie->save();
                return back()->with('success', 'Présence en ligne mise à jour.');
            }
        }
        return back()->with('error', 'Impossible de mettre à jour la présence en ligne.');
    }


    public function deleteNotification($id = null)
    {
        $user = auth()->user();
        // Si pharmacie, notifications sur le modèle Pharmacie
        if ($user->role === 'pharmacie' && $user->pharmacie) {
            $notifiable = $user->pharmacie;
        } else {
            $notifiable = $user;
        }
        if ($id) {
            $notification = $notifiable->notifications()->findOrFail($id);
            $notification->delete();
            return back()->with('success', 'Notification supprimée.');
        } else {
            $notifiable->notifications()->delete();
            return back()->with('success', 'Toutes les notifications ont été supprimées.');
        }
    }


}
