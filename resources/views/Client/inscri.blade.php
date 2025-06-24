@extends('layouts.forme')

@section('title', 'Inscription - PharmaFind')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/inscription.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h2>Créer un compte</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom complet</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required />
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required />
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+229 01 XX XX XX XX " required />
            @error('phone')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="birth_date">Date de naissance</label>
            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required />
            @error('birth_date')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="gender">Sexe</label>
            <select id="gender" name="gender" required>
              <option value="">Choisissez...</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femme</option>
            </select>
            @error('gender')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Adresse</label>
            <textarea id="address" name="address" placeholder="Ex : ab-calavi , quartier Aifa" required>{{ old('address') }}</textarea>
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required minlength="6" />
            <div class="form-text">Minimum 6 caractères.</div>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmation</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required />
        </div>

        <div class="form-group full-width">
            <button type="submit" class="btn-register">S'inscrire</button>
        </div>

        <div class="links">
                <p class="mb-0">Déjà un compte ?
                    <a  href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">Se connecter</a>
                </p>
            </div>
    </form>
</div>
@endsection