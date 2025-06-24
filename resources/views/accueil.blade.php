<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmFind - Recherche Pharmaceutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo" style='font-size:28px;'>
                <i class="fas fa-capsules"></i>
                PharmFind
            </div>

            <div class="container header-content">
        <a href="client.search" class="nav-item">
            <i class="nav-icon fas fa-search"></i>
            <span>Recherche</span>
        </a>
        <a href="#" class="nav-item">
            <i class="nav-icon fas fa-history"></i>
            <span>Historique</span>
        </a>
        <a href="#" class="nav-item">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <span>Commande</span>
        </a>
        <a href="#" class="nav-item">
            <i class="nav-icon fas fa-calendar-check"></i>
            <span>Réservation</span>
        </a>
    </div>

            <div class="auth-links">
                <a href="login" id="loginBtn">Se connecter</a>
                <a href="inscription" id="registerBtn">Créer un compte</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Votre santé, notre priorité</h1>
            <p>Trouvez rapidement les médicaments et pharmacies près de chez vous</p>
        </div>
    </section>

    <div class="container">
        <div class="search-section">
            <div class="search-bar">
                <input type="text" id="searchInput" class="search-input" placeholder="Rechercher un médicament...">
                <input type="text" id="locationInput" class="location-input" placeholder="Votre ville ou code postal...">
                <button class="geolocation-btn" id="geoBtn" title="Utiliser ma position actuelle">
                    <i class="fas fa-location-arrow"></i>
                </button>
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>
            </div>

            <div class="search-suggestions">
                <div class="suggestion">Doliprane</div>
                <div class="suggestion">Aspirine</div>
                <div class="suggestion">Ibuprofène</div>
                <div class="suggestion">Paracétamol</div>
                <div class="suggestion">Amoxicilline</div>
                <div class="suggestion">Vitamines</div>
                <div class="suggestion">Antiseptique</div>
            </div>

            <div id="searchMessage" class="status-message"></div>
            <div id="loadingState" class="loading">
                <div class="loading-spinner"></div>
                <p>Recherche en cours...</p>
            </div>
        </div>

        <div id="resultsSection" class="results-section hidden">
            <h2 class="text-2xl font-bold mb-4 text-dark-color">Résultats de la recherche</h2>
            <div id="productResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Résultats insérés via AJAX -->
            </div>
        </div>
    </div>

    <div class="container">
        <section class="features">
            <h2>Nos services</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Géolocalisation précise</h3>
                    <p>Trouvez instantanément les pharmacies les plus proches de votre position avec nos cartes interactives.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h3>Commande en ligne</h3>
                    <p>Commandez vos médicaments en ligne et récupérez-les directement en pharmacie selon vos disponibilités.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h3>Réservation rapide</h3>
                    <p>Réservez vos médicaments en quelques clics et évitez les ruptures de stock grâce à notre système intelligent.</p>
                </div>
            </div>
        </section>

        <section class="popular-section">
            <h2>Médicaments populaires</h2>
            <div class="popular-grid">
                <div class="popular-item" data-medicine="doliprane">
                    <i class="fas fa-tablets"></i>
                    <h4>Doliprane</h4>
                    <p>Antalgique</p>
                </div>
                <div class="popular-item" data-medicine="aspirine">
                    <i class="fas fa-pills"></i>
                    <h4>Aspirine</h4>
                    <p>Anti-inflammatoire</p>
                </div>
                <div class="popular-item" data-medicine="ibuprofene">
                    <i class="fas fa-capsules"></i>
                    <h4>Ibuprofène</h4>
                    <p>Antalgique</p>
                </div>
                <div class="popular-item" data-medicine="vitamines">
                    <i class="fas fa-leaf"></i>
                    <h4>Vitamines</h4>
                    <p>Compléments</p>
                </div>
                <div class="popular-item" data-medicine="antiseptique">
                    <i class="fas fa-hand-sparkles"></i>
                    <h4>Antiseptique</h4>
                    <p>Désinfectant</p>
                </div>
                <div class="popular-item" data-medicine="pansements">
                    <i class="fas fa-band-aid"></i>
                    <h4>Pansements</h4>
                    <p>Premiers secours</p>
                </div>
            </div>
        </section>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const locationInput = document.getElementById('locationInput');
        const searchBtn = document.getElementById('searchBtn');
        const geoBtn = document.getElementById('geoBtn');
        const loadingState = document.getElementById('loadingState');
        const searchMessage = document.getElementById('searchMessage');
        const suggestions = document.querySelectorAll('.suggestion');
        const popularItems = document.querySelectorAll('.popular-item');
        const navItems = document.querySelectorAll('.nav-item');
        const resultsSection = document.getElementById('resultsSection');
        const productResults = document.getElementById('productResults');

        // Event listeners
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => e.key === 'Enter' && performSearch());
        locationInput.addEventListener('keypress', (e) => e.key === 'Enter' && performSearch());
        geoBtn.addEventListener('click', getCurrentLocation);

        // Suggestions click handlers
        suggestions.forEach(suggestion => {
            suggestion.addEventListener('click', () => {
                searchInput.value = suggestion.textContent;
                performSearch();
            });
        });

        // Popular items click handlers
        popularItems.forEach(item => {
            item.addEventListener('click', () => {
                searchInput.value = item.getAttribute('data-medicine');
                performSearch();
            });
        });

        // Navigation handlers
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
            });
        });

        function performSearch() {
            const medicine = searchInput.value.trim();
            const location = locationInput.value.trim();
            const messageElement = searchMessage;

            if (!medicine) {
                showMessage('Veuillez saisir le nom d\'un médicament', 'error');
                searchInput.focus();
                return;
            }
            
            if (!location) {
                showMessage('Veuillez indiquer votre localisation', 'error');
                locationInput.focus();
                return;
            }

            showLoading(true);
            resultsSection.classList.add('hidden'); // Masquer les résultats pendant la recherche

            $.ajax({
                url: "{{ route('client.search') }}",
                type: 'GET',
                data: {
                    search: medicine,
                    location: location // À implémenter côté serveur si nécessaire
                },
                success: function (data) {
                    showLoading(false);
                    productResults.innerHTML = data;
                    resultsSection.classList.remove('hidden');
                    showMessage(`Résultats pour "${medicine}" à ${location}`, 'success');
                },
                error: function (xhr) {
                    showLoading(false);
                    showMessage('Erreur lors de la recherche : ' + (xhr.responseText || 'Veuillez réessayer'), 'error');
                }
            });
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                showMessage('La géolocalisation n\'est pas supportée par votre navigateur', 'error');
                return;
            }

            const originalContent = geoBtn.innerHTML;
            geoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            geoBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude.toFixed(4);
                    const lng = position.coords.longitude.toFixed(4);
                    locationInput.value = `Position actuelle (${lat}, ${lng})`;
                    geoBtn.innerHTML = originalContent;
                    geoBtn.disabled = false;
                    showMessage('Position détectée avec succès', 'success');
                },
                (error) => {
                    geoBtn.innerHTML = originalContent;
                    geoBtn.disabled = false;
                    showMessage(`Erreur de géolocalisation: ${error.message}`, 'error');
                }
            );
        }

        function showLoading(show) {
            loadingState.style.display = show ? 'block' : 'none';
            searchBtn.disabled = show;
            searchBtn.innerHTML = show ? 
                '<i class="fas fa-spinner fa-spin"></i> Recherche...' : 
                '<i class="fas fa-search"></i> Rechercher';
        }

        function showMessage(text, type) {
            const messageElement = searchMessage;
            messageElement.textContent = text;
            messageElement.className = `status-message status-${type}`;
            messageElement.style.display = 'block';
            
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 4000);
        }

        // Auto-complete simulation
        searchInput.addEventListener('input', () => {
            if (searchInput.value.length > 2) {
                console.log('Auto-completion activée'); // À remplacer par une vraie logique si nécessaire
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            searchInput.focus();
            console.log('PharmFind initialisé');
        });
    </script>
</body>
</html>