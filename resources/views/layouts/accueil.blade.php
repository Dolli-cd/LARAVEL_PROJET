<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','PharmFind') - PharmaFind</title>

    <!-- 🟢 jQuery doit être en premier -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- 2. SweetAlert2 juste après -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column;">
  
<header class="header">

        <div class="container">
        <div id="formulaire-zone" style="display:none;">
    <!-- Formulaire de recherche par zone (département, commune, etc.) -->
</div>
            <div class="header-content d-flex align-items-center justify-content-between flex-wrap">
                <div class="logo">
                    <span class="logo-text"><i class="fas fa-pills"></i> PharmFind</span>
                </div>
                @if(Auth::check() && (Auth::user()->role === 'pharmacie' || Auth::user()->role === 'admin'))
           <h2>Bienvenue dans votre espace!</h2>
           @else
                @php
    $searchPlaceholder = ($type ?? request('type')) === 'pharmacie'
        ? 'Rechercher une pharmacie...'
        : ((($type ?? request('type')) === 'produit')
            ? 'Rechercher un médicament...'
            : 'Rechercher un produit, une pharmacie...');
@endphp

@include('layouts.searchbar', [
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
@if(Auth::check() && Auth::user()->role === 'client')
                <div class="auth-buttons d-flex align-items-center">
               
                <a href="{{route('panier')}}" class="cart-btn ms-2" title="Panier">
                        <i class="fas fa-shopping-cart"></i>
                        @if($panierCount>0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$panierCount}}</span>
                    </a>
                 @endif

                 @endif
                 @if(Auth::check() && Auth::user()->role === 'pharmacie')
                    @php
                    $pharmacie = Auth::user()->pharmacie ?? null;
                @endphp
                                <form action="{{ route('pharmacie.online') }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="form-switch" style="display: flex; align-items: center; gap: 8px;">
                                        <input type="checkbox" class="form-check-input" id="online" name="online" value="1"
                                            {{ $pharmacie->online ? 'checked' : '' }} onchange="this.form.submit()"
                                            style="width: 40px; height: 22px; cursor: pointer;">
                                        <label class="form-check-label" for="online" style="margin-bottom:0; font-weight: 500;">
                                            {{ $pharmacie->online ? 'En ligne' : 'Hors ligne' }}
                                        </label>
                                    </div>
                                </form>          
                
                 @endif
                    @auth
                        <a href="{{ route('profil') }}" class="nav-link"><i class="fas fa-user"> </i></a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Connexion</a>
                        <a href="{{ route('inscription') }}" class="btn btn-outline">Inscription</a>
                        <a href="{{route('panier')}}" class="cart-btn ms-2" title="Panier">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                 
                    @endauth
                 
                </div>
            </div>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <nav class="navbar navbar-expand-lg navbar-light p-0 mt-2 small-navbar">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarAdmin" aria-controls="dashboardNavbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarAdmin">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a href="{{route('users')}}" class="nav-link">Utilisateurs</a></li>
                            <li class="nav-item"><a href="{{route('statistique')}}" class="nav-link">Statistiques</a></li>
                            <li class="nav-item"><a href="{{ route('produit') }}" class="nav-link">Produit</a></li>
                            <li class="nav-item"><a href="{{ route('pharmaliste') }}" class="nav-link">Pharmacies</a></li>
                        </ul>
                        </div>
                    </div>
                </nav>
            @elseif(Auth::check() && Auth::user()->role === 'pharmacie')
                @php
    $pharmacie = Auth::user()->pharmacie ?? null;
@endphp
                <nav class="navbar navbar-expand-lg navbar-light p-0 mt-2 small-navbar">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarPharma" aria-controls="dashboardNavbarPharma" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarPharma">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                
                                <li class="nav-item"><a href="{{ route('pharmacie.dashboard') }}" class="nav-link">Tableau de bord</a></li>
                                <li class="nav-item"><a href="{{ route('liste_produit') }}" class="nav-link">Produits</a></li>
                                <li class="nav-item"><a href="{{ route('pharmacie.reservations') }}" class="nav-link">Réservations</a></li>
                                <li class="nav-item"><a href="{{ route('pharmacie.commandes') }}" class="nav-link">Commandes</a></li>
                                <li class="nav-item position-relative">
                                    <a class="nav-link" href="{{ route('pharmacie.notifications') }}">
                                        Notifications
                                        @if($notificationCount > 0)
                                            <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $notificationCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item"><a href="{{ route('pharmacie.historiques') }}" class="nav-link">Historique</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @elseif(Auth::check() && Auth::user()->role === 'client')
                <nav class="navbar navbar-expand-lg navbar-light p-0 mt-2 small-navbar">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbarclient" aria-controls="dashboardNavbarclient" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="dashboardNavbarclient">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                               
                                <li class="nav-item"><a href="{{ route('reserv') }}" class="nav-link">Réservation</a></li>
                                <li class="nav-item"><a href="{{ route('cmd') }}" class="nav-link">Commandes</a></li>
                                <li class="nav-item"><a href="{{ route('historique') }}" class="nav-link">Historique</a></li>
                                <li class="nav-item position-relative">
                                    <a class="nav-link" href="{{ route('notification') }}">
                                        Notifications
                                        @if($notificationCount > 0)
                                            <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $notificationCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>                   <!--  <li class="nav-item"><a href="{{ route('notification') }}" class="nav-link">Notifications</a></li>-->
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
    <footer class="footer" style="flex-shrink: 0;">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact</h3>
                <div class="contact-item">
                    <span>📍</span>
                    <span>Cotonou</span>
                </div>
               <a href="tel:+22999017260" style="color: inherit; text-decoration: none;"> <div class="contact-item">
                    <span>📞</span>
                    <span>+22999017260</span>
                </div> </a>
                <div class="contact-item">
                    <span>📧</span>
                    <span>contact@pharmfind.com</span>
                </div>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Services</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Réseaux</h3>
                <div class="social-links">
                    <a href="#" class="social-link facebook">f</a>
                    <a href="#" class="social-link linkedin">in</a>
                    <a href="#" class="social-link instagram">📷</a>
                    <a href="#" class="social-link youtube">▶️</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
                <div>Votre Santé, Notre Priorité.</div>
            <div class="footer-links">
            <div>© 2025 PharmFind. Tous droits réservés.</div>
            </div>
        </div>
    </div>
</footer>

    <!-- Bootstrap Bundle (avec Popper inclus) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
    <!-- script  dynamiques -->
  @stack('scripts') 
  <!-- Inclure SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>