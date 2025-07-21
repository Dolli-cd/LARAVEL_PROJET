@extends('layouts.pharma')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Réinitialiser le mot de passe</h3>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Votre email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nouveau mot de passe" required value="">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Changer le mot de passe</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="btn btn-success w-100">Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if(togglePassword && passwordInput && icon) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
@endpush
@endsection
