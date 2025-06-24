@extends('layouts.app')

@section('title', 'Mon Profil - PharmFind')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
<style>
    /* Styles temporaires pour garantir un affichage de base */
    .profil-container {
        background: white;
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .page-title {
        color: #1e3a8a;
        font-size: 2rem;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
    }
    .user-info-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 40px;
        padding: 20px;
        background-color: #e0f2f1;
        border-radius: 8px;
    }
    .user-avatar .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #e0f2f1;
        color: #4CAF50;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    .menu-item {
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .menu-item:hover {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .logout-btn {
        color: #FF5722;
        font-weight: 600;
    }
    .logout-btn:hover {
        color: #d32f2f;
    }
    /* Style pour l'alerte intégrée */
    .logout-alert {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        text-align: center;
    }
    .logout-alert.show {
        display: block;
    }
    .alert-buttons {
        margin-top: 20px;
    }
    .alert-buttons button {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .alert-confirm {
        background-color: #4CAF50;
        color: white;
    }
    .alert-cancel {
        background-color: #f44336;
        color: white;
    }
</style>
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
                <h2 class="user-name">{{ $user->nom ?? 'Nom non défini' }}</h2>
                <p class="user-email">{{ $user->email }}</p>
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
                @if(route('wel'))
                <a href="{{ route('wel') }}" class="menu-action">
                    <i class="fas fa-chevron-right"></i>
                </a>
                @endif
            </div>
            
            <div class="menu-item logout-item">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alerte intégrée pour la déconnexion -->
    <div id="logoutAlert" class="logout-alert">
        <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
        <div class="alert-buttons">
            <button type="button" class="alert-confirm" id="confirmLogout">Oui</button>
            <button type="button" class="alert-cancel" id="cancelLogout">Non</button>
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

    const logoutForm = document.getElementById('logout-form');
    const logoutAlert = document.getElementById('logoutAlert');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');

    if (logoutForm) {
        logoutForm.addEventListener('submit', (e) => {
            e.preventDefault();
            logoutAlert.classList.add('show');
        });

        confirmLogout.addEventListener('click', () => {
            logoutForm.submit();
        });

        cancelLogout.addEventListener('click', () => {
            logoutAlert.classList.remove('show');
        });
    }
});
</script>
@endpush