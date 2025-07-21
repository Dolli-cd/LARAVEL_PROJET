<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PharmFind') - Système de Gestion Pharmaceutique</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2E7D32;
            padding: 20px 0;
            border-right: 1px solid #1B5E20;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 30px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: #4CAF50;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 14px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 5px;
            }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #4CAF50;
            color: white;
        }

        .nav-link.active {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
            }

        .nav-icon {
            width: 16px;
            height: 16px;
            margin-right: 12px;
            opacity: 0.7;
            font-size: 16px;
            }

        /* Main Content */
        .main-content {
            flex: 1;
            background-color: white;
            display: flex;
            flex-direction: column;
            overflow: visible;
            margin-left: 250px;
        }

        /* Header */
        .header {
            display: flex;
            flex-direction: column;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            flex-shrink: 0;
            background-color: white;
        }

        .header-section-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .header-section-top h1 {
            color: #2E7D32;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .header-separator-horizontal {
            width: 100%;
            height: 2px;
            background-color: #4CAF50;
            margin: 0 0 15px 0;
        }

        .header-section-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-right-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-info p {
            color: #2E7D32;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            font-weight: 600;
        }

        .print-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        .print-icon {
            width: 20px;
            height: 20px;
            opacity: 0.6;
            font-size: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
        }

        .user-name {
            color: #2E7D32;
            font-size: 14px;
            font-weight: 600;
        }

        /* Search and Content */
        .content-section {
            padding: 20px 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 150px;
            padding: 12px 16px 12px 45px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            font-size: 11px;
            outline: none;
            transition: border-color 0.2s;
            background-color: #EAF5EE;
        }

        .search-input:focus {
            border-color: #4CAF50;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            opacity: 0.5;
            font-size: 16px;
        }

        .stock-alert {
            color: #dc3545;
            font-size: 12px;
            font-weight: 500;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            overflow-y: auto;
            flex: 1;
        }

        .table {
            width: 100%;
            min-width: unset;
            border-collapse: collapse;
            font-size: 15px;
        }

        .table th, .table td {
            padding: 10px 8px;
            font-size: 15px;
            text-align: center;
            white-space: nowrap;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .status-icons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-icon {
            width: 10px;
            height: 10px;
            font-size: 10px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .status-edit {
            color: #2196F3;
        }

        .status-edit:hover {
            color: #1976D2;
        }

        .status-delete {
            color: #f44336;
        }

        .status-delete:hover {
            color: #d32f2f;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            flex-shrink: 0;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            color: #495057;
            transition: all 0.2s;
        }

        .page-btn:hover {
            border-color: #4CAF50;
            color: #4CAF50;
        }

        .page-btn.active {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }

        .page-info {
            color: #6c757d;
            font-size: 14px;
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .print-button:hover {
            background-color: #45a049;
        }

        /* Navbar */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #2E7D32;
        }

        .navbar h5 {
            color: #2E7D32;
            font-weight: 600;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: white;
        }

        /* Buttons */
        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .btn-outline-success {
            color: #4CAF50;
            border-color: #4CAF50;
            font-weight: 500;
        }

        .btn-outline-success:hover {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            color: #0f5132;
            background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
            border-left: 4px solid #198754;
        }

        .alert-danger {
            color: #721c24;
            background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
            border-left: 4px solid #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block;
            }
        }

        @media (min-width: 992px) {
            .navbar-toggler {
                display: none;
            }
        }

        /* Dropdown styles */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1050 !important;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Animation for alerts */
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            animation: slideInDown 0.3s ease-out;
        }

        /* Animation for alerts that disappear */
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .alert.fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        .wrap-text {
            white-space: normal !important;
            word-break: break-word;
            min-width: 80px;
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
                        <i class="fas fa-pills me-2"></i> PharmFind
                    </h4>
                    <p class="text-white-50 small mb-0">{{ auth()->user()->role_label ?? 'Utilisateur' }}</p>
                </div>
                <nav class="mt-4">@yield('sidebar')</nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">
                        <div class="container-fluid">
                            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>

                            <div class="d-flex align-items-center">
                                <!-- Notifications -->
                                <div class="dropdown me-3">
                                    <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Notifications</h6></li>
                                        <li><span class="dropdown-item text-muted">Aucune notification</span></li>
                                    </ul>
                                </div>

                                <!-- Profile -->
                                <div class="dropdown">
                                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                        <div class="user-avatar me-2">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <span>{{ auth()->user()->name }}</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('wel') }}"><i class="fas fa-user me-2"></i> Mon profil</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Flash Messages -->
                    <div class="container-fluid p-4">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🟢 jQuery doit être en premier -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle (avec Popper inclus) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Responsive Sidebar JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.navbar-toggler');

            toggleButton?.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 991 && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });

            // Fonction pour faire disparaître les alertes après 3 secondes
            function autoHideAlerts() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.classList.add('fade-out');
                        setTimeout(() => {
                            alert.remove();
                        }, 200);
                    }, 3000);
                });
            }

            // Fonction pour fermer manuellement une alerte
            function closeAlert(button) {
                const alert = button.closest('.alert');
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 200);
            }

            // Exécuter l'auto-hide des alertes
            autoHideAlerts();
            
            // Ajouter les événements de clic pour les boutons de fermeture
            const closeButtons = document.querySelectorAll('.btn-close');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    closeAlert(this);
                });
            });
        });
    </script>

    <!-- Scripts dynamiques -->
    @stack('scripts')
</body>
</html>
