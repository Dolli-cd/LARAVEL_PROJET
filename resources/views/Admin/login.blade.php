<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion - Admin PharmaMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f3f6f9 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }
        .login-container h2 {
            text-align: center;
            color: #2a9d8f;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #2a9d8f;
            font-size: 0.9rem;
        }
        .btn-login {
            background-color: #2a9d8f;
            color: #fff;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #21867a;
        }
        .links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .links a {
            color: #2a9d8f;
            text-decoration: none;
            font-weight: 500;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 1.5rem;
        }
        .alert:empty {
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion Administrateur</h2>

        @if ($errors->any())
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('great'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('great') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('adminlog') }}">
            @csrf
            <div class="form-group position-relative">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="admin@pharmamarket.com" required />
            </div>

            <div class="form-group position-relative">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required />
                <span class="toggle-password" onclick="togglePassword()">Afficher</span>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>
        <div class="links">
            <a href="#">Mot de passe oublié ?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleText = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleText.textContent = 'Masquer';
            } else {
                passwordField.type = 'password';
                toggleText.textContent = 'Afficher';
            }
        }
    </script>
</body>
</html>