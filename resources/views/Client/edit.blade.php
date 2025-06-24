@extends('layouts.app')

@section('title', 'Modifier mon profil - PharmFind')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
<div class="profil-container">
    <div class="page-header">
        <a href="{{ route('profil') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="page-title">Modifier mon profil</h1>
    </div>
    
    <div class="profil-card">
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="profil-form">
            @csrf
            @method('PUT')
            
            <!-- Avatar -->
            <div class="avatar-section">
                <div class="current-avatar">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar actuel" id="avatar-preview">
                    @else
                        <div class="avatar-placeholder" id="avatar-preview">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                
                <div class="avatar-upload">
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="file-input">
                    <label for="avatar" class="upload-btn">
                        <i class="fas fa-camera"></i> Changer la photo
                    </label>
                    <p class="upload-help">JPG, PNG ou GIF (max 2MB)</p>
                </div>
            </div>
            
            <!-- Informations personnelles -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i> Informations personnelles
                </h3>
                
                <div class="form-group">
                    <label for="nom" class="form-label">Nom complet *</label>
                    <input type="text" id="nom" name="nom" class="form-input @error('nom') error @enderror" value="{{ old('nom', $user->nom) }}" required>
                    @error('nom')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="form-input @error('telephone') error @enderror" value="{{ old('telephone', $user->telephone) }}">
                    @error('telephone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('profil') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Prévisualisation de l'avatar
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (avatarPreview.tagName === 'IMG') {
                    avatarPreview.src = e.target.result;
                } else {
                    // Remplacer le placeholder par une image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Nouvel avatar';
                    img.id = 'avatar-preview';
                    avatarPreview.parentNode.replaceChild(img, avatarPreview);
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Animation d'entrée des champs
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.animationDelay = `${index * 0.1}s`;
        group.classList.add('fade-in');
    });
});
</script>
@endpush