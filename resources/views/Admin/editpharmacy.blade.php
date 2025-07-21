<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer une pharmacie - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #45a049; /* Vert principal */
            --secondary-color: #e6ece6; /* Gris clair pour le fond */
            --text-dark: #2d3748;
            --text-light: #718096;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--secondary-color);
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }

        .container-fluid {
            position: relative;
            z-index: 1;
        }

        .form-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            max-width: 600px;
            margin: 0 auto;
            transition: transform 0.2s ease;
        }

        .form-container:hover {
            transform: translateY(-2px);
        }

        .form-header {
            background: var(--primary-color);
            padding: 1.5rem;
            text-align: center;
            color: white;
        }

        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .form-header p {
            opacity: 0.8;
            margin: 0;
            font-size: 0.9rem;
        }

        .form-body {
            padding: 2rem;
        }

        .wizard-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .wizard-step {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            font-weight: 500;
            color: var(--text-light);
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .wizard-step.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
        }

        .wizard-content {
            display: none;
        }

        .wizard-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(69, 160, 73, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            color: white;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #3d8e40;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #e6f3e6;
            color: var(--primary-color);
            border: 1px solid rgba(69, 160, 73, 0.2);
        }

        .alert-danger {
            background: #fce8e8;
            color: #991b1b;
            border: 1px solid rgba(153, 27, 27, 0.2);
        }

        .invalid-feedback {
            display: block;
            color: #991b1b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
            }

            .form-header {
                padding: 1rem;
            }

            .form-header h2 {
                font-size: 1.3rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .wizard-nav {
                flex-direction: column;
            }

            .wizard-step {
                border-bottom: 1px solid var(--border-color);
                margin-bottom: 0.5rem;
            }

            .btn-primary {
                padding: 0.625rem 1.25rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px 0;
            }

            .form-header h2 {
                font-size: 1.2rem;
            }

            .form-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="form-container">
                    <div class="form-header">
                        <h2><i class="fas fa-pills me-2"></i>Éditer la pharmacie</h2>
                        <p>Modifier les informations de la pharmacie</p>
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

                        <form id="editpharmacieForm" method="POST" action="{{ route('update_pharma', $pharmacie->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="wizard-nav">
                                <div class="wizard-step active" data-step="1">Informations générales</div>
                                <div class="wizard-step" data-step="2">Horaires</div>
                                <div class="wizard-step" data-step="3">Confirmation</div>
                            </div>

                            <!-- Étape 1: Informations générales -->
                            <div class="wizard-content active" data-step="1">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-hospital"></i>Nom de la pharmacie</label>
                                    <input type="text" class="form-control" name="name" placeholder="Ex: Pharmacie Centrale" value="{{ old('name', $pharmacie->name) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-envelope"></i>Adresse email</label>
                                    <input type="email" class="form-control" name="email" placeholder="contact@pharmacie.com" value="{{ old('email', $pharmacie->email) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-phone"></i>Numéro de téléphone</label>
                                    <input type="tel" class="form-control" name="phone" placeholder="Ex: +229 XX XX XX XX" value="{{ old('phone', $pharmacie->phone) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-map-marker-alt"></i>Adresse complète</label>
                                    <textarea class="form-control" name="address" placeholder="Ex: Rue des Martyrs, Quartier Zongo, Cotonou" rows="3" required>{{ old('address', $pharmacie->address) }}</textarea>
                                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-primary next-step">Suivant</button>
                                </div>
                            </div>

                            <!-- Étape 2: Horaires -->
                            <div class="wizard-content" data-step="2">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-clock"></i>Horaires d'ouverture</label>
                                    <textarea class="form-control" name="schedule" placeholder="Ex: Lundi-Vendredi: 8h-18h, Samedi: 8h-12h, Dimanche: Fermé" rows="3" required>{{ old('schedule', $pharmacie->schedule) }}</textarea>
                                    <div class="form-text">Décrivez les horaires d'ouverture</div>
                                    <div class="invalid-feedback">{{ $errors->first('schedule') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-shield-alt"></i>Service de garde</label>
                                    <textarea class="form-control" name="guard_time" placeholder="Ex: Service de garde les weekends de 20h à 8h" rows="3" required>{{ old('guard_time', $pharmacie->guard_time) }}</textarea>
                                    <div class="form-text">Précisez les créneaux de garde</div>
                                    <div class="invalid-feedback">{{ $errors->first('guard_time') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-umbrella"></i>Assurances acceptées</label>
                                    <textarea class="form-control" name="insurance_name" placeholder="Ex: CNSS, Mutuelle Santé Bénin" rows="3" required>{{ old('insurance_name', $pharmacie->insurance_name) }}</textarea>
                                    <div class="form-text">Listez les assurances prises en charge</div>
                                    <div class="invalid-feedback">{{ $errors->first('insurance_name') }}</div>
                                </div>
                                <div class="d-flex justify-content-between gap-2">
                                    <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                                    <button type="button" class="btn btn-primary next-step">Suivant</button>
                                </div>
                            </div>

                            <!-- Étape 3: Confirmation -->
                            <div class="wizard-content" data-step="3">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-lock"></i>Mot de passe (laisser vide pour ne pas modifier)</label>
                                    <input type="password" class="form-control" name="password" placeholder="Minimum 6 caractères" minlength="6">
                                    <div class="form-text">Laissez vide pour conserver le mot de passe actuel</div>
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-lock"></i>Confirmation du mot de passe</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Répétez le mot de passe">
                                    <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                </div>
                                <div class="d-flex justify-content-between gap-2">
                                    <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3බ

System: .3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion du wizard
        const steps = document.querySelectorAll('.wizard-step');
        const contents = document.querySelectorAll('.wizard-content');
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        let currentStep = 1;

        function updateWizard() {
            steps.forEach(step => step.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));
            steps[currentStep - 1].classList.add('active');
            contents[currentStep - 1].classList.add('active');

            // Afficher les erreurs de validation si présentes
            contents[currentStep - 1].querySelectorAll('.form-control').forEach(input => {
                if (input.classList.contains('is-invalid')) {
                    input.classList.add('is-invalid');
                }
            });
        }

        nextButtons.forEach(button => {
            button.addEventListener('click', () => {
                const currentInputs = contents[currentStep - 1].querySelectorAll('input, textarea');
                let valid = true;

                currentInputs.forEach(input => {
                    if (!input.checkValidity()) {
                        valid = false;
                        input.classListaconda

System: .add('is-invalid');
                        input.nextElementSibling.textContent = input.validationMessage;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (valid) {
                    currentStep++;
                    if (currentStep > steps.length) currentStep = steps.length;
                    updateWizard();
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener('click', () => {
                currentStep--;
                if (currentStep < 1) currentStep = 1;
                updateWizard();
            });
        });

        // Validation du formulaire
        document.getElementById('editpharmacieForm').addEventListener('submit', function (e) {
            const inputs = this.querySelectorAll('input, textarea');
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    valid = false;
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = input.validationMessage;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                currentStep = 1;
                updateWizard();
            }
        });
    </script>
</body>
</html>