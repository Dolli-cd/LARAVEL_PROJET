@extends('layouts.profil')

@section('title', 'Mon Profil - PharmFind')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
<div class="profil-container">
    <h1 class="page-title">Mon Profil</h1>
    
    <div class="profil-card">
        <div class="user-info-section">
            <div class="user-avatar">
                @if(!empty($user->avatar))
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar de {{ $user->nom }}">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            
            <div class="user-details">
                <h2 class="user-name">{{ $user['nom'] ?? 'Nom non défini' }}</h2>
                <p class="user-email">{{ $user['email'] }}</p>
                @if(!empty($user->telephone))
                    <p class="user-phone"><i class="fas fa-phone"></i> {{ $user->telephone }}</p>
                @endif
            </div>
        </div>
        
        <div class="profil-menu">
            <div class="menu-item">
                <div class="menu-content">
                    <div class="menu-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="menu-text">
                        <h3>Informations personnelles</h3>
                        <p>Modifier vos données personnelles</p>
                    </div>
                </div>
                <a href="{{ route('profiledit') }}" class="menu-action">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            
            <div class="menu-item">
                <div class="menu-content">
                    <div class="menu-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="menu-text">
                        <h3>Historique de recherche</h3>
                        <p>Vos dernières recherches</p>
                    </div>
                </div>
                {{-- Décommentez cette ligne quand la route sera prête --}}
                {{-- <a href="{{ route('profil.history') }}" class="menu-action">
                    <i class="fas fa-chevron-right"></i>
                </a> --}}
            </div>
            
            <div class="menu-item logout-item">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('fade-in');
    });
    
    const logoutForm = document.querySelector('.logout-form');
    logoutForm.addEventListener('submit', e => {
        if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush