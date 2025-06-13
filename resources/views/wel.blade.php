<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmFind - Recherche Pharmaceutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            background: linear-gradient(135deg, var(--light-color) 0%, #E0E7FF 100%);
            color: var(--dark-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(90deg, var(--primary-color), #059669);
            box-shadow: var(--shadow);
            padding: 15px 0;
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
            font-size: 28px;
            font-weight: 700;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            text-shadow: 1px 1px 5px rgba(255, 255, 255, 0.3);
        }

        .logo i {
            margin-right: 10px;
            font-size: 32px;
            color: var(--accent-color);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .auth-links {
            display: flex;
            gap: 10px;
        }

        .auth-links a {
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .auth-links a:hover {
            background: var(--accent-color);
            transform: scale(1.05);
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), #34D399);
            color: #FFFFFF;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .search-section {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
            margin: -60px auto 60px;
            max-width: 900px;
            position: relative;
            z-index: 10;
            border: 2px solid var(--accent-color);
            animation: popIn 0.5s ease-out;
        }

        @keyframes popIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .search-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .search-input, .location-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #D1D5DB;
            border-radius: 50px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus, .location-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
        }

        .search-btn {
            background: linear-gradient(45deg, var(--primary-color), #34D399);
            color: #FFFFFF;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }

        .geolocation-btn {
            background: var(--secondary-color);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 15px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .geolocation-btn:hover {
            background: var(--primary-color);
            color: #FFFFFF;
            transform: rotate(360deg) scale(1.1);
            transition: all 0.6s ease;
        }

        .search-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .suggestion {
            background: var(--light-color);
            color: var(--dark-color);
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .suggestion:hover {
            background: var(--accent-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .features {
            margin: 60px 0;
            background: #FFFFFF;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: var(--dark-color);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: linear-gradient(135deg, #FFFFFF, var(--secondary-color));
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 3.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            animation: spinSlow 4s infinite linear;
        }

        @keyframes spinSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        .feature-card p {
            color: #4B5563;
            line-height: 1.6;
        }

        .popular-section {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 40px;
            margin: 60px 0;
            box-shadow: var(--shadow);
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

        .popular-section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark-color);
            font-size: 2.5rem;
        }

        .popular-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .popular-item {
            background: var(--light-color);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .popular-item:hover {
            background: var(--accent-color);
            color: #FFFFFF;
            border-color: var(--primary-color);
            transform: translateY(-5px) rotate(-5deg);
        }

        .popular-item i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .popular-item h4 {
            margin-bottom: 5px;
            color: var(--dark-color);
            font-weight: 600;
        }

        .popular-item p {
            font-size: 12px;
            color: #6B7280;
        }

        .status-message {
            display: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
            animation: fadeInOut 3s ease-in-out;
        }

        .status-success {
            background: #D1FAE5;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .status-error {
            background: #FEE2E2;
            color: #DC2626;
            border: 2px solid #DC2626;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(10px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(10px); }
        }

        .loading {
            display: none;
            text-align: center;
            padding: 40px;
            color: #6B7280;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease-in;
        }

        .loading-spinner {
            border: 4px solid #F3F4F6;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(90deg, var(--primary-color), #34D399);
            display: flex;
            justify-content: space-around;
            padding: 15px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #FFFFFF;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 5px;
        }

        .nav-item.active {
            color: var(--accent-color);
            transform: scale(1.2);
        }

        .nav-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .nav-item span {
            font-size: 12px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .hero p { font-size: 1rem; }
            .search-bar { flex-direction: column; }
            .search-input, .location-input { width: 100%; margin-bottom: 10px; }
            .auth-links { flex-direction: column; gap: 5px; }
            .auth-links a { padding: 8px 16px; font-size: 14px; }
            .features-grid { grid-template-columns: 1fr; }
            .popular-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
        }

        .hidden { display: none; }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-pills"></i>
                PharmFind
            </div>
            <div class="auth-links">
                <a href="{{ route('login') }}" id="loginBtn">Se connecter</a>
                <a href="{{ route('register') }}" id="registerBtn">Créer un compte</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Trouve tes médocs en un clic !</h1>
            <p>Recherche rapide et pharmacies à portée de main</p>
        </div>
    </section>

    <div class="container">
        <div class="search-section">
            <div class="search-bar">
                <input type="text" id="searchInput" class="search-input" placeholder="Médicament ou produit...">
                <input type="text" id="locationInput" class="location-input" placeholder="Ville ou code postal...">
                <button class="geolocation-btn" id="geoBtn" title="Utiliser ma position">
                    <i class="fas fa-location-arrow"></i>
                </button>
                <button class="search-btn" id="searchBtn">
                    <i class="fas fa-search"></i>
                    Go !
                </button>
            </div>

            <div class="search-suggestions">
                <div class="suggestion">Doliprane</div>
                <div class="suggestion">Aspirine</div>
                <div class="suggestion">Ibuprofène</div>
                <div class="suggestion">Paracétamol</div>
                <div class="suggestion">Amoxicilline</div>
                <div class="suggestion">Vitamines</div>
            </div>

            <div id="searchMessage" class="status-message"></div>
            <div id="loadingState" class="loading">
                <div class="loading-spinner"></div>
                <p>Chargement en cours... 🎉</p>
            </div>
        </div>
    </div>

    <div class="container">
        <section class="features">
            <h2>Nos super-pouvoirs !</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Localisation Flash</h3>
                    <p>Découvre les pharmacies les plus proches en un clin d'œil !</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h3>Commande 😉 </h3>
                    <p>Passes ta commande en toute sécurité !</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-phone"></i></div>
                    <h3>Réservation Rapide</h3>
                    <p>Réserve et récupère en un tour de main !</p>
                </div>
            </div>
        </section>

        <section class="popular-section">
            <h2>Les stars des médocs !</h2>
            <div class="popular-grid">
                <div class="popular-item" data-medicine="doliprane">
                    <i class="fas fa-tablets"></i>
                    <h4>Doliprane</h4>
                    <p>Antidouleur</p>
                </div>
                <div class="popular-item" data-medicine="aspirine">
                    <i class="fas fa-pills"></i>
                    <h4>Aspirine</h4>
                    <p>Anti-inflammatoire</p>
                </div>
                <div class="popular-item" data-medicine="ibuprofene">
                    <i class="fas fa-capsules"></i>
                    <h4>Ibuprofène</h4>
                    <p>Anti-inflammatoire</p>
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
                    <p>Soins</p>
                </div>
            </div>
        </section>
    </div>

    <div class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="nav-icon fas fa-home"></i>
            <span>Accueil</span>
        </a>
        <a href="{{ route('search') }}" class="nav-item">
            <i class="nav-icon fas fa-search"></i>
            <span>Recherche</span>
        </a>
        <a href="#" class="nav-item">
            <i class="nav-icon fas fa-bookmark"></i>
            <span>Réservations</span>
        </a>
        <a href="{{ route('profil') }}" class="nav-item">
            <i class="nav-icon fas fa-user"></i>
            <span>Profil</span>
        </a>
    </div>

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

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => e.key === 'Enter' && performSearch());
        locationInput.addEventListener('keypress', (e) => e.key === 'Enter' && performSearch());
        geoBtn.addEventListener('click', getCurrentLocation);

        suggestions.forEach(suggestion => {
            suggestion.addEventListener('click', () => {
                searchInput.value = suggestion.textContent;
                searchInput.focus();
            });
        });

        popularItems.forEach(item => {
            item.addEventListener('click', () => {
                searchInput.value = item.querySelector('h4').textContent;
                performSearch();
            });
        });

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
                console.log('Navigation to:', item.querySelector('span').textContent);
            });
        });

        function performSearch() {
            const medicine = searchInput.value.trim();
            const location = locationInput.value.trim();
            const messageElement = searchMessage;

            if (!medicine) {
                messageElement.textContent = 'Oups ! Entrez un médicament !';
                messageElement.className = 'status-message status-error';
                messageElement.style.display = 'block';
                setTimeout(() => messageElement.style.display = 'none', 3000);
                searchInput.focus();
                return;
            }
            if (!location) {
                messageElement.textContent = 'Dis-moi où tu es !';
                messageElement.className = 'status-message status-error';
                messageElement.style.display = 'block';
                setTimeout(() => messageElement.style.display = 'none', 3000);
                locationInput.focus();
                return;
            }

            showLoading(true);
            setTimeout(() => {
                showLoading(false);
                messageElement.textContent = `Recherche lancée : ${medicine} à ${location} ! 🎉`;
                messageElement.className = 'status-message status-success';
                messageElement.style.display = 'block';
                setTimeout(() => messageElement.style.display = 'none', 3000);
            }, 2000);
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                searchMessage.textContent = 'Géoloc non dispo, désolé !';
                searchMessage.className = 'status-message status-error';
                searchMessage.style.display = 'block';
                setTimeout(() => searchMessage.style.display = 'none', 3000);
                return;
            }

            const originalContent = geoBtn.innerHTML;
            geoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            geoBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    locationInput.value = `Moi ! (${lat.toFixed(2)}, ${lng.toFixed(2)})`;
                    geoBtn.innerHTML = originalContent;
                    geoBtn.disabled = false;
                    searchMessage.textContent = 'Position lockée ! 🚀';
                    searchMessage.className = 'status-message status-success';
                    searchMessage.style.display = 'block';
                    setTimeout(() => searchMessage.style.display = 'none', 3000);
                },
                (error) => {
                    geoBtn.innerHTML = originalContent;
                    geoBtn.disabled = false;
                    searchMessage.textContent = `Oups ! ${error.message}`;
                    searchMessage.className = 'status-message status-error';
                    searchMessage.style.display = 'block';
                    setTimeout(() => searchMessage.style.display = 'none', 3000);
                }
            );
        }

        function showLoading(show) {
            loadingState.classList.toggle('hidden', !show);
            searchBtn.disabled = show;
            searchBtn.innerHTML = show ? '<i class="fas fa-spinner fa-spin"></i> Chargement...' : '<i class="fas fa-search"></i> Go !';
        }

        searchInput.addEventListener('input', () => {
            if (searchInput.value.length > 2) console.log('Auto-complète en route !');
        });

        document.addEventListener('DOMContentLoaded', () => {
            searchInput.focus();
            console.log('PharmFind est prêt à décoller !');
        });
    </script>
</body>
</html>