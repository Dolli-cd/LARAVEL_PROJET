<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion à votre dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background-color: #EAF5EE;
    }

    .container {
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .main-content {
      display: flex;
      background-color: white;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 900px;
      width: 100%;
      height: 500px;
    }

    /* Partie gauche : logo */
    .left {
      flex: 1;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      border-right: 1px solid #eee;
    }

    .logo {
      text-align: center;
    }

    .logo-text {
      font-size: 40px;
      color: #2DA85D;
      font-weight: 600;
    }

    /* Partie droite : formulaire */
    .right {
      flex: 1;
      background-color: #EAF5EE;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;
      padding: 40px;
      position: relative;
      height: 100%;
    }

    .right h1 {
      font-size: 20px;
      margin-bottom: 25px;
      color: #1d1d1d;
      text-align: left;
      margin-top: 0;
    }

    .form-vertical-center {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100%;
      padding-bottom: 60px;
    }

    .form-box {
      width: 100%;
      max-width: 400px;
      text-align: center;
      margin: 0 auto;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    form input {
      padding: 12px;
      border: 1px solid #ddd;
      border-left: none;
      border-right: none;
      border-top: none;
      margin-bottom: 20px;
      font-size: 16px;
      width: 100%;
      max-width: 340px;
      outline: none;
      transition: border-bottom 0.3s ease;
    }

    form input:focus {
      border-bottom: 1px solid #2DA85D;
      border-left: none;
      border-right: none;
      border-top: none;
    }

    .password-wrapper {
      position: relative;
      width: 100%;
      max-width: 340px;
      display: flex;
      justify-content: center;
    }

    .password-wrapper input {
      width: 100%;
      padding-right: 40px;
      padding-left: 10px;
      height: 40px;
      border: 1px solid #ddd;
      border-left: none;
      border-right: none;
      border-top: none;
      font-size: 1rem;
      outline: none;
      transition: border-bottom 0.3s ease;
    }

    .password-wrapper input:focus {
      border-bottom: 1px solid #2DA85D;
      border-left: none;
      border-right: none;
      border-top: none;
    }

    .password-wrapper i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }

    form button {
      padding: 14px;
      background-color: #00994C;
      color: white;
      border: none;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
      width: 100%;
      max-width: 340px;
    }

    form button:hover {
      background-color: #007f3e;
    }

    .register {
      margin-top: auto;
      font-size: 14px;
      color: #333;
      text-align: left;
      align-self: flex-start;
      position: absolute;
      bottom: 40px;
      left: 40px;
    }

    .register a {
      color: #00994C;
      text-decoration: none;
      font-weight: 600;
    }

    .links {
      text-align: center;
      margin-top: 2.5rem;
    }

    .links a {
      color: #2a9d8f;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .links a:hover {
      text-decoration: underline;
    }

    /* Styles Bootstrap pour les alertes */
    .alert {
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      border: none;
      border-radius: 12px;
      width: 100%;
      max-width: 340px;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
    }

    .alert::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
    }

    .alert-success {
      color: #0f5132;
      background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
      border-left: 4px solid #198754;
    }

    .alert-success::before {
      background-color: #198754;
    }

    .alert-danger {
      color: #721c24;
      background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
      border-left: 4px solid #dc3545;
    }

    .alert-danger::before {
      background-color: #dc3545;
    }

    .alert-info {
      color: #055160;
      background: linear-gradient(135deg, #cff4fc 0%, #b6effb 100%);
      border-left: 4px solid #0dcaf0;
    }

    .alert-info::before {
      background-color: #0dcaf0;
    }

    .alert-warning {
      color: #664d03;
      background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
      border-left: 4px solid #ffc107;
    }

    .alert-warning::before {
      background-color: #ffc107;
    }

    .alert-dismissible {
      padding-right: 3rem;
    }

    .alert-dismissible .btn-close {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      padding: 0.25rem;
      margin: 0;
      background: transparent;
      border: none;
      font-size: 1.25rem;
      line-height: 1;
      color: inherit;
      opacity: 0.7;
      cursor: pointer;
      transition: opacity 0.2s ease;
    }

    .alert-dismissible .btn-close:hover {
      opacity: 1;
      color: inherit;
      text-decoration: none;
    }

    .alert-dismissible .btn-close::before {
      content: '×';
      font-weight: bold;
    }

    .alert strong {
      font-weight: 600;
    }

    .alert p {
      margin: 0;
      line-height: 1.5;
    }

    /* Animation d'entrée pour les alertes */
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

    /* Styles pour les erreurs de validation */
    .error-message {
      color: #dc3545;
      font-size: 12px;
      margin-top: -15px;
      margin-bottom: 15px;
      padding: 8px 12px;
      background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
      border-radius: 8px;
      border-left: 3px solid #dc3545;
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
    }

    /* Style pour les champs avec erreur */
    .input-error {
      border-bottom: 2px solid #dc3545 !important;
      background-color: rgba(220, 53, 69, 0.05);
    }

    .input-error:focus {
      border-bottom: 2px solid #dc3545 !important;
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
    }

    /* Animation pour les alertes qui disparaissent */
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

    /* Style pour les messages de succès */
    .success-message {
      background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
      border: 1px solid #198754;
      color: #0f5132;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
      box-shadow: 0 2px 8px rgba(25, 135, 84, 0.1);
    }

    /* Style pour les messages d'info */
    .info-message {
      background: linear-gradient(135deg, #cff4fc 0%, #b6effb 100%);
      border: 1px solid #0dcaf0;
      color: #055160;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
      box-shadow: 0 2px 8px rgba(13, 202, 240, 0.1);
    }

    /* Style pour le checkbox "Se souvenir de moi" */
    .remember-wrapper {
      width: 100%;
      max-width: 340px;
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      justify-content: flex-start;
    }

    .remember-wrapper input[type="checkbox"] {
      width: auto;
      margin: 0;
      margin-right: 8px;
      cursor: pointer;
    }

    .remember-wrapper label {
      font-size: 14px;
      color: #333;
      cursor: pointer;
      margin: 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="main-content">
      <!-- Partie gauche -->
      <div class="left">
        <div class="logo">
          <div class="logo-text">PharmaFind</div>
        </div>
      </div>

      <!-- Partie droite -->
      <div class="right">
        <form method="POST" action="{{ route('loginin') }}">
          @csrf
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              @foreach ($errors->all() as $error)
               <p>{{$error}}</p>
               @endforeach
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          @if($errors->has('great'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>{{ $errors->first('great') }}</strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <h1>Bienvenue dans <strong>PharmaFind</strong></h1>
          <div class="form-vertical-center">
            <div class="form-box">
              <input type="email" name="email" id="email" placeholder="exemple@pharma.com" required />
            </div>
            <div class="password-wrapper">
              <input type="password" id="password" name="password" placeholder="********" required />
              <i id="toggleIcon" class="fa-solid fa-eye" onclick="togglePassword()"></i>
            </div>
            
            <div class="remember-wrapper">
              <input type="checkbox" class="form-check-input" name="remember" id="remember">
              <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
          </form>
          <div class="links">
            <a href="{{route('password.request')}}">Mot de passe oublié ?</a><br>
            <a href="{{ route('inscription') }}">Créer un compte</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    // Fonction pour faire disparaître les alertes après 3 secondes
    function autoHideAlerts() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.classList.add('fade-out');
          setTimeout(() => {
            alert.remove();
          }, 100);
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

    // Exécuter quand le DOM est chargé
    document.addEventListener('DOMContentLoaded', function() {
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
</body>
</html>