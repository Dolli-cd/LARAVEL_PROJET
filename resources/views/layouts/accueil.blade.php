<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','PharmaFind') - PharmaFind</title>

    <!-- 🟢 jQuery doit être en premier -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- 2. SweetAlert2 juste après -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- AOS CSS -->
        <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column;">
    <header class="gromuse-header">
        <div class="gromuse-container">
            <div class="gromuse-nav">
                <!-- Logo -->
                <a href="#" class="gromuse-logo">
                    <div class="gromuse-logo-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    PharmaFind
                </a>
    
                <!-- Barre de recherche ou message de bienvenue -->
                @if(Auth::check() && (Auth::user()->role === 'pharmacie' || Auth::user()->role === 'admin'))
                    <h2 class="gromuse-welcome-msg">Bienvenue dans votre espace!</h2>
                @else
                    @php
                        $searchPlaceholder = ($type ?? request('type')) === 'pharmacie'
                            ? 'Rechercher une pharmacie...'
                            : ((($type ?? request('type')) === 'produit')
                                ? 'Rechercher un médicament...'
                                : 'Rechercher un produit, une pharmacie...');
                    @endphp
                    @include('layouts.searchbar',[
                    
                    'searchRoute' => route('client.search'),
                    'query' => $query ?? '',
                    'placeholder' => (($type ?? request('type')) === 'pharmacie'
                        ? 'Rechercher une pharmacie...'
                        : ((($type ?? request('type')) === 'produit')
                            ? 'Rechercher un médicament...'
                            : 'Rechercher un produit, une pharmacie...')),
                    'type' => $type ?? request('type')
                ])
                @endif
    
                <!-- Actions utilisateur -->
                <div class="gromuse-actions">
                    @if(Auth::check() && Auth::user()->role === 'client')
                        <a href="{{route('panier')}}" class="gromuse-btn" title="Panier">
                            <i class="fas fa-shopping-cart" style="font-size: 18px;"></i>
                            @if($panierCount > 0)
                                <span class="gromuse-cart-badge">{{$panierCount}}</span>
                            @endif
                        </a>
                        <button id="geo-btn" class="gromuse-btn" title="Trouver les pharmacies proches">
                            <i class="fas fa-map-marker-alt" style="font-size: 18px;"></i>
                        </button>
                    @endif
    
                    @if(Auth::check() && Auth::user()->role === 'pharmacie')
                        @php
                            $pharmacie = Auth::user()->pharmacie ?? null;
                        @endphp
                        <form action="{{ route('pharmacie.online') }}" method="POST" class="form-switch">
                            @csrf
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="online" 
                                   name="online" 
                                   value="1"
                                   {{ $pharmacie->online ? 'checked' : '' }} 
                                   onchange="this.form.submit()">
                            <label for="online">
                                {{ $pharmacie->online ? 'En ligne' : 'Hors ligne' }}
                            </label>
                        </form>
                    @endif
    
                    @auth
                    <div class="gromuse-avatar gromuse-avatar-1">
                        <a href="{{ route('profil') }}" class="gromuse-btn" title="Mon profil">
                            <i class="fas fa-user" style="font-size: 18px;"></i>
                        </a>
                        </div>
                    
                    @else
                        <div class="gromuse-auth-buttons">
                            <a href="{{ route('login') }}" class="gromuse-auth-btn">Connexion</a>
                            <a href="{{ route('inscription') }}" class="gromuse-auth-btn">Inscription</a>
                            <a href="{{route('panier')}}" class="gromuse-btn" title="Panier">
                                <i class="fas fa-shopping-cart" style="font-size: 18px;"></i>
                            </a>
                            <button id="geo-btn" class="gromuse-btn" title="Trouver les pharmacies proches">
                                <i class="fas fa-map-marker-alt" style="font-size: 18px;"></i>
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
    
            <!-- Navigation secondaire -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <nav class="gromuse-subnav">
                    <div class="navbar navbar-expand-lg p-0">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarAdmin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarAdmin">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item"><a href="{{route('users')}}" class="nav-link">Utilisateurs</a></li>
                                <li class="nav-item"><a href="{{route('statistique')}}" class="nav-link">Statistiques</a></li>
                                <li class="nav-item"><a href="{{ route('produit') }}" class="nav-link">Produits</a></li>
                                <li class="nav-item"><a href="{{ route('pharmaliste') }}" class="nav-link">Pharmacies</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @elseif(Auth::check() && Auth::user()->role === 'pharmacie')
                <nav class="gromuse-subnav">
                    <div class="navbar navbar-expand-lg p-0">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarPharma">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarPharma">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item"><a href="{{ route('pharmacie.dashboard') }}" class="nav-link">Tableau de bord</a></li>
                                <li class="nav-item"><a href="{{ route('liste_produit') }}" class="nav-link">Produits</a></li>
                                <li class="nav-item"><a href="{{ route('pharmacie.reservations') }}" class="nav-link">Réservations</a></li>
                                <li class="nav-item"><a href="{{ route('pharmacie.commandes') }}" class="nav-link">Commandes</a></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pharmacie.notifications') }}">
                                        Notifications
                                        @if($notificationCount > 0)
                                            <span class="gromuse-notification-badge">{{ $notificationCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item"><a href="{{ route('pharmacie.historiques') }}" class="nav-link">Historique</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @elseif(Auth::check() && Auth::user()->role === 'client')
                <nav class="gromuse-subnav">
                    <div class="navbar navbar-expand-lg p-0">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarclient">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarclient">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item"><a href="{{ route('reserv') }}" class="nav-link">Réservation</a></li>
                                <li class="nav-item"><a href="{{ route('cmd') }}" class="nav-link">Commandes</a></li>
                                <li class="nav-item"><a href="{{ route('historique') }}" class="nav-link">Historique</a></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('notification') }}">
                                        Notifications
                                        @if($notificationCount > 0)
                                            <span class="gromuse-notification-badge">{{ $notificationCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @endif
        </div>
    </header>
    <main style="flex: 1 0 auto;">
    {{-- Affichage des messages de session tout en haut --}}
    @if(session('error'))
        <div class="alert alert-danger" style="margin-top: 20px;">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success" style="margin-top: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Contenu principal --}}
    @yield('content')
 </div>
    </main>

    <!-- Bootstrap Bundle (avec Popper inclus) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
    <!-- script  dynamiques -->
  @stack('scripts') 
  <!-- Inclure SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>