<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion - PharmaMarket</title>
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
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Connexion à PharmaMarket</h2>
    <form method="POST" action="{{ route('loginin') }}">
      @csrf
      @if(session('great'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong> {{ session('great') }}</strong>
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
      <a href="{{route ('inscription')}}">Créer un compte</a>
    </div>
  </div>

 
</body>
</html>