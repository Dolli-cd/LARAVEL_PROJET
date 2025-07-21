@extends('layouts.pharma')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Mot de passe oublié</h3>
            @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Votre adresse email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Envoyer le lien</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="btn btn-success w-100">Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>
@endsection