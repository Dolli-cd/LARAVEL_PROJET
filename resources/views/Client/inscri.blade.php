@extends('layouts.forme')

@section('title', 'Inscription - PharmaFind')


@section('content')
<div class="register-container">
    <h2>Créer un compte</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Identité -->
        <div class="form-section">
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required />
                @error('name')
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
        </div>
        <!-- Contact -->
        <div class="form-section">
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
                <label for="address">Adresse</label>
                <textarea id="address" name="address" placeholder="Ex : ab-calavi , quartier Aifa" required>{{ old('address') }}</textarea>
                @error('address')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- Localisation -->
        <div class="form-section">
            <div class="form-group">
                <label for="departement_id">Département</label>
                <select id="departement_id" name="departement_id" class="form-control" required>
                    <option value="">Choisissez...</option>
                    @foreach(\App\Models\Departement::all() as $departement)
                        <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>{{ $departement->name }}</option>
                    @endforeach
                </select>
                @error('departement_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="commune_id">Commune</label>
                <select id="commune_id" name="commune_id" class="form-control" required>
                    <option value="">Choisissez d'abord un département</option>
                </select>
                @error('commune_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="arrondissement_id">Arrondissement</label>
                <select id="arrondissement_id" name="arrondissement_id" class="form-control" required>
                    <option value="">Choisissez d'abord une commune</option>
                </select>
                @error('arrondissement_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <!-- Sécurité -->
        <div class="form-section">
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

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#departement_id').on('change', function() {
        var departementId = $(this).val();
        $('#commune_id').html('<option value="">Chargement...</option>');
        $('#arrondissement_id').html('<option value="">Choisissez d\'abord une commune</option>');
        if (departementId) {
            $.get('/api/communes/' + departementId, function(data) {
                var options = '<option value="">Choisissez...</option>';
                data.forEach(function(commune) {
                    options += '<option value="' + commune.id + '">' + commune.name + '</option>';
                });
                $('#commune_id').html(options);
            });
        } else {
            $('#commune_id').html('<option value="">Choisissez d\'abord un département</option>');
        }
    });

    $('#commune_id').on('change', function() {
        var communeId = $(this).val();
        $('#arrondissement_id').html('<option value="">Chargement...</option>');
        if (communeId) {
            $.get('/api/arrondissements/' + communeId, function(data) {
                var options = '<option value="">Choisissez...</option>';
                data.forEach(function(arr) {
                    options += '<option value="' + arr.id + '">' + arr.name + '</option>';
                });
                $('#arrondissement_id').html(options);
            });
        } else {
            $('#arrondissement_id').html('<option value="">Choisissez d\'abord une commune</option>');
        }
    });

    // Si une ancienne valeur existe, recharge les communes et arrondissements
    var oldDepartement = "{{ old('departement_id') }}";
    var oldCommune = "{{ old('commune_id') }}";
    var oldArrondissement = "{{ old('arrondissement_id') }}";

    if (oldDepartement) {
        $('#departement_id').val(oldDepartement).trigger('change');
        if (oldCommune) {
            // Attendre que les communes soient chargées
            $.get('/api/communes/' + oldDepartement, function(data) {
                var options = '<option value=\"\">Choisissez...</option>';
                data.forEach(function(commune) {
                    options += '<option value=\"' + commune.id + '\"' + (commune.id == oldCommune ? ' selected' : '') + '>' + commune.name + '</option>';
                });
                $('#commune_id').html(options);

                if (oldArrondissement) {
                    // Charger les arrondissements
                    $.get('/api/arrondissements/' + oldCommune, function(data) {
                        var options = '<option value=\"\">Choisissez...</option>';
                        data.forEach(function(arr) {
                            options += '<option value=\"' + arr.id + '\"' + (arr.id == oldArrondissement ? ' selected' : '') + '>' + arr.name + '</option>';
                        });
                        $('#arrondissement_id').html(options);
                    });
                }
            });
        }
    }
});
</script>
@endsection

