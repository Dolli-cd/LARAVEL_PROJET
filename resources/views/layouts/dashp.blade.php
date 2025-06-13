<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'PharmaFind - Gestion des Pharmacies')</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        :root {
            --primary-color: #10B981; /* Vert néon vibrant */
            --secondary-color: #F9FAFB;
            --accent-color: #F97316; /* Orange éclatant */
            --dark-color: #1F2937;
            --light-color: #F3F4F6;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--light-color) 0%, #E0E7FF 100%);
            color: var(--dark-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            flex: 1 0 auto;
        }

        header {
            background: linear-gradient(90deg, var(--primary-color), #059669);
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            text-shadow: 1px 1px 5px rgba(255, 255, 255, 0.3);
        }

        .logo i {
            margin-right: 0.5rem;
            font-size: 2rem;
            color: var(--accent-color);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-links a {
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent-color);
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            background: linear-gradient(90deg, var(--primary-color), #34D399);
            color: #FFFFFF;
            padding: 1rem 0;
            text-align: center;
            flex-shrink: 0;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                text-align: center;
            }

            .logo {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-pills"></i>
                PharmaFind
            </div>
                        <h1 class="h3 mb-0">Dashboard Pharmacie - [Nom de la pharmacie]</h1>
            <div>
                <span class="me-3">Date : {{ date('h:i A T, l, F j, Y') }}</span> <!-- 12:10 PM WAT, Friday, June 13, 2025 -->
        </div>
            <nav class="nav-links">

                <a href="{{ route('profil') }}">Profil</a>
                @guest
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('logout') }}">Déconnexion</a>
                @endguest
                @auth
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container py-6">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} PharmaFind. Tous droits réservés.</p>
            <p>Contact : support@pharmafind.com | [Nom de la pharmacie]</p>
        </div>
    </footer>
</body>
</html>