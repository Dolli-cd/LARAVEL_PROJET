<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/inscription.css'])
    <title>@yield('title', 'PharmaFind')</title>
    
    <!-- Styles par défaut -->
    <style>
        .error {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }
        
        .success {
            color: #27ae60;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .alert {
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
    
    <!-- Styles spécifiques à la page -->
    @yield('styles')
</head>
<body>
    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Contenu principal -->
    @yield('content')

    <!-- Scripts -->
    @yield('scripts')
</body>
</html>