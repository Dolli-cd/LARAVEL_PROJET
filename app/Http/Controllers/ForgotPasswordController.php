<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
class ForgotPasswordController extends Controller
{
    public function formulaire()
    {
        return view('Auth.forgot-password');
    }

    public function envoyer(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // Message de succès personnalisé
            return back()->with('status', 'Un email de réinitialisation vient de vous être envoyé si l\'adresse existe dans notre base.');
        } else {
            // Message d'erreur personnalisé
            return back()->withErrors(['email' => "Impossible d'envoyer le mail à cette adresse. Vérifiez l'adresse saisie."]);
        }
    }
    
}
