<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une pharmacie - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<style>
    :root {
        --primary-color: #00d2ff;
        --secondary-color: #3a7bd5;
        --accent-color: #ff6b6b;
        --success-color: #51cf66;
        --text-dark: #2d3748;
        --text-light: #718096;
        --bg-light: #f7fafc;
        --shadow: 0 10px 25px rgba(0,0,0,0.1);
        --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        min-height: 100vh;
        padding: 20px 0;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .container-fluid {
        position: relative;
        z-index: 1;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-container:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-5px);
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 2rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .form-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .form-header p {
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .form-body {
        padding: 2.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
        position: relative;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: var(--primary-color);
        width: 16px;
        text-align: center;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #fafafa;
        position: relative;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 210, 255, 0.1);
        background-color: white;
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: #cbd5e0;
        background-color: white;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        color: #a0aec0;
        z-index: 2;
        transition: color 0.3s ease;
    }

    .form-control.with-icon {
        padding-left: 2.5rem;
    }

    .form-control:focus + .input-icon,
    .input-group:hover .input-icon {
        color: var(--primary-color);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 210, 255, 0.4);
        background: linear-gradient(135deg, #00c4e6, #3a7bd5);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .alert-success {
        background: linear-gradient(135deg, var(--success-color), #40c057);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, var(--accent-color), #fa5252);
        color: white;
    }

    .invalid-feedback {
        display: block;
        color: var(--accent-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .form-text {
        color: var(--text-light);
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }

    /* Animations d'entrée */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        animation: slideInUp 0.6s ease forwards;
    }

    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.6s; }
    .form-group:nth-child(7) { animation-delay: 0.7s; }
    .form-group:nth-child(8) { animation-delay: 0.8s; }
    .form-group:nth-child(9) { animation-delay: 0.9s; }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            margin: 10px;
            border-radius: 16px;
        }
        
        .form-header {
            padding: 1.5rem;
        }
        
        .form-header h2 {
            font-size: 1.5rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .btn-primary {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 10px 0;
        }
        
        .form-header {
            padding: 1.25rem;
        }
        
        .form-header h2 {
            font-size: 1.3rem;
        }
        
        .form-body {
            padding: 1.25rem;
        }
        
        .form-control {
            padding: 0.75rem;
        }
    }

    /* Custom select styling */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    /* Floating label effect */
    .floating-label {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .floating-label input,
    .floating-label textarea,
    .floating-label select {
        padding: 1rem 1rem 0.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background-color: #fafafa;
        transition: all 0.3s ease;
    }

    .floating-label label {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        background-color: transparent;
        color: #a0aec0;
        font-size: 1rem;
        font-weight: 400;
        pointer-events: none;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .floating-label input:focus + label,
    .floating-label input:not(:placeholder-shown) + label,
    .floating-label textarea:focus + label,
    .floating-label textarea:not(:placeholder-shown) + label,
    .floating-label select:focus + label,
    .floating-label select:not([value=""]) + label {
        top: 0.5rem;
        font-size: 0.75rem;
        color: var(--primary-color);
        font-weight: 600;
        background-color: white;
        padding: 0 0.5rem;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="form-container">
                <div class="form-header">
                    <h2><i class="fas fa-pills me-2"></i>Créer une pharmacie</h2>
                    <p>Inscription d'une nouvelle pharmacie par l'administrateur</p>
                </div>

               <div class="form-body">
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                    <form id="Form" method="POST" action="{{ route('registerpharma') }}">
                        @csrf
                        <!-- Nom de la pharmacie -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-hospital"></i>Nom de la pharmacie
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   placeholder="Ex: Pharmacie Centrale"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>Adresse email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   name="email" 
                                   placeholder="contact@pharmacie.com"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Téléphone -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>Numéro de téléphone
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   name="phone" 
                                   placeholder="Ex: +229 XX XX XX XX"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Adresse -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Adresse complète
                            </label>
                            <textarea class="form-control" 
                                      name="address" 
                                      placeholder="Ex: Rue des Martyrs, Quartier Zongo, Cotonou"
                                      rows="3"
                                      required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Horaires d'ouverture -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>Horaires d'ouverture
                            </label>
                            <textarea class="form-control" 
                                      name="schedule" 
                                      placeholder="Ex: Lundi-Vendredi: 8h-18h, Samedi: 8h-12h, Dimanche: Fermé"
                                      rows="2"
                                      required></textarea>
                            <div class="form-text">Décrivez les horaires d'ouverture de la pharmacie</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Horaire de garde -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-shield-alt"></i>Service de garde
                            </label>
                            <textarea class="form-control" 
                                      name="guard_time" 
                                      placeholder="Ex: Service de garde les weekends de 20h à 8h, ou 24h/24 7j/7"
                                      rows="2"
                                      required></textarea>
                            <div class="form-text">Précisez les créneaux de garde assurés</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Assurances -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-umbrella"></i>Assurances acceptées
                            </label>
                            <textarea class="form-control" 
                                      name="insurance_name" 
                                      placeholder="Ex: CNSS, Mutuelle Santé Bénin, Assurance Colina, Carte d'indigent..."
                                      rows="2"
                                      required></textarea>
                            <div class="form-text">Listez toutes les assurances prises en charge</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>Mot de passe
                            </label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control" 
                                       name="password" 
                                       placeholder="Minimum 6 caractères"
                                       minlength="6"
                                       required>
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3"
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Le mot de passe doit contenir au moins 6 caractères</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>Confirmation du mot de passe
                            </label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       placeholder="Répétez le mot de passe"
                                       required>
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3"
                                        onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-2"></i>
                                Créer la pharmacie
                                <span class="spinner-border spinner-border-sm ms-2 d-none" id="loading-spinner"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fonction pour basculer l'affichage du mot de passe
function togglePassword(fieldName) {
    const passwordField = document.querySelector(`input[name="${fieldName}"]`);
    const eyeIcon = document.getElementById(`${fieldName}-eye`);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}




// Animation au scroll
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const header = document.querySelector('.form-header');
    if (header) {
        header.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Effet de particules dans le header (optionnel)
function createParticles() {
    const header = document.querySelector('.form-header');
    const particle = document.createElement('div');
    particle.style.cssText = `
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(255,255,255,0.6);
        border-radius: 50%;
        top: ${Math.random() * 100}%;
        left: ${Math.random() * 100}%;
        animation: float 3s ease-in-out infinite;
        animation-delay: ${Math.random() * 3}s;
    `;
    
    header.appendChild(particle);
    
    setTimeout(() => {
        if (particle.parentNode) {
            particle.parentNode.removeChild(particle);
        }
    }, 3000);
}

// Créer des particules périodiquement
setInterval(createParticles, 500);

// Ajouter l'animation CSS pour les particules
const style = document.createElement('style');
style.textContent = `
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
        50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>