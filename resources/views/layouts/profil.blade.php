<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'PharmFind')</title>

    <!-- Styles CSS -->
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="profil-container">  {{-- Container adapté CSS profil --}}
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-pills"></i>
                    <a href="{{ route('wel') }}">PharmFind</a> {{-- route 'wel' définie --}}
                </div>
                
                @auth
                <div class="user-menu">
                    <span class="user-name">{{ Auth::user()->nom }}</span>
                    <div class="dropdown">
                        <button class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('profil') }}">Mon Profil</a> {{-- route profil définie --}}
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main-content profil-container"> {{-- Ajout classe container pour le padding centré --}}
        {{-- Messages flash --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Navigation bottom pour mobile -->
    @auth
    <nav class="bottom-nav">
        <a href="{{ route('wel') }}" class="nav-item {{ request()->routeIs('wel') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-home"></i>
            </div>
            <span>Accueil</span>
        </a>
        
        <a href="{{ route('search') }}" class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-search"></i>
            </div>
            <span>Recherche</span>
        </a>
        
        <a href="{{ route('reserv') }}" class="nav-item {{ request()->routeIs('reserv') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span>Réservations</span>
        </a>
        
        <a href="{{ route('profil') }}" class="nav-item {{ request()->routeIs('profil') ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="fas fa-user"></i>
            </div>
            <span>Profil</span>
        </a>
    </nav>
    @endauth

    <!-- Scripts JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
