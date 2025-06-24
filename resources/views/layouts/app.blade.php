<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PharmApp') - Système de Gestion Pharmaceutique</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1e3a8a; /* Bleu profond */
            --secondary-color: #64748b; /* Gris bleuté */
            --success-color: #166534; /* Vert foncé */
            --danger-color: #991b1b; /* Rouge foncé */
            --warning-color: #92400e; /* Orange foncé */
            --info-color: #075985; /* Bleu cyan */
            --light-bg: #f9fafb; /* Fond clair */
            --dark-bg: #111827; /* Fond sombre */
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-bg);
            color: #1f2937;
            margin: 0;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--dark-bg));
            color: white;
            transition: width 0.3s ease;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.9rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.3rem 0;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(8px);
        }

        .main-content {
            min-height: 100vh;
            padding: 1rem;
            background: var(--light-bg);
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff, var(--light-bg));
            border-left: 5px solid var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #1e40af;
            border-color: #1e40af;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem;
        }

        .table {
            border-radius: 0.75rem;
            overflow: hidden;
            background: white;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.7rem;
            font-weight: 500;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-4 border-bottom">
                    <h4 class="text-white fw-bold">
                        <i class="fas fa-pills me-2"></i>
                        PharmApp
                    </h4>
                    <p class="text-white-50 small mb-0">{{ auth()->user()->role_label ?? 'Utilisateur' }}</p>
                </div>
                
                <nav class="mt-4">
                    @yield('sidebar')
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navbar -->
                    <nav class="navbar navbar-expand-lg shadow-sm">
                        <div class="container-fluid">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <!-- Notifications -->
                                <div class="dropdown me-3">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-bell"></i>
                                        @if(auth()->user()->role === 'client' && auth()->user()->client->notifications()->where('read', false)->count() > 0)
                                            <span class="badge bg-danger rounded-circle">{{ auth()->user()->client->notifications()->where('read', false)->count() }}</span>
                                        @endif
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header text-dark">Notifications</h6></li>
                                        @if(auth()->user()->role === 'client')
                                            @forelse(auth()->user()->client->notifications()->where('read', false)->limit(3)->get() as $notification)
                                                <li><a class="dropdown-item text-dark" href="{{ route('wel') }}">
                                                    <small>{{ $notification->title }}</small>
                                                </a></li>
                                            @empty
                                                <li><span class="dropdown-item text-muted">Aucune notification</span></li>
                                            @endforelse
                                        @else
                                            <li><span class="dropdown-item text-muted">Aucune notification</span></li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <!-- User Menu -->
                                <div class="dropdown">
                                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-avatar me-2">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <span class="text-dark">{{ auth()->user()->name }}</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if(auth()->user()->role === 'client')
                                            <li><a class="dropdown-item text-dark" href="{{ route('profil') }}">
                                                <i class="fas fa-user me-2"></i>Mon Profil
                                            </a></li>
                                        @elseif(auth()->user()->role === 'pharmacie')
                                            <li><a class="dropdown-item text-dark" href="{{ route('wel') }}">
                                                <i class="fas fa-user me-2"></i>Mon Profil
                                            </a></li>
                                        @elseif(auth()->user()->role === 'admin')
                                            <li><a class="dropdown-item text-dark" href="{{ route('wel') }}">
                                                <i class="fas fa-user me-2"></i>Mon Profil
                                            </a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Page Content -->
                    <div class="container-fluid p-4">
                        <!-- Alerts -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Erreurs de validation :</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>