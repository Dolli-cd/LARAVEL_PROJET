<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion à votre pharmacie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f6f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background-color: white;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #2a9d8f;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    label {
      display: block;
      margin-bottom: 0.4rem;
      font-weight: bold;
      color: #333;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .toggle-password {
      float: right;
      margin-top: -30px;
      margin-right: 10px;
      cursor: pointer;
      font-size: 0.9rem;
      color: #2a9d8f;
    }

    .btn-login {
      width: 100%;
      padding: 0.75rem;
      background-color: #2a9d8f;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }

    .btn-login:hover {
      background-color: #21867a;
    }

    .links {
      text-align: center;
      margin-top: 1rem;
    }

    .links a {
      color: #2a9d8f;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .alert {
      margin-bottom: 0.5rem;
    }
    .alert:empty {
      display: none;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Connexion à votre pharmacie</h2>

    <form method="POST" action="{{ route('loginsave') }}">
      @csrf
      @if ($errors->has('auth'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <p>{{ $errors->first('auth') }}</p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('great'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>{{ session('great') }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" name="email" id="email" placeholder="exemple@pharma.com" required />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="********" required />
        <span class="toggle-password" onclick="togglePassword()">Afficher</span>
      </div>

      <button type="submit" class="btn-login">Se connecter</button>
    </form>
    <div class="links">
      <a href="#">Mot de passe oublié ?</a><br>
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