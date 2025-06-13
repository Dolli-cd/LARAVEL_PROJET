<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - PharmaFind</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Inclure les styles existants de votre fichier */
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #E0F2F1;
            --accent-color: #FF5722;
            --dark-color: #333;
            --light-color: #f9f9f9;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: white;
            box-shadow: var(--shadow);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
            font-size: 28px;
        }

        .search-bar, .filters, .results-grid, .pharmacy-card, .pharmacy-header, .pharmacy-content, .pharmacy-info, .product-info, .action-btns, .reserve-btn, .map-btn, .product-details, .product-header, .product-image, .product-title, .product-category, .product-description, .availability-map, .map-container, .tab-buttons, .tab-btn, .tab-content, .pharmacy-list, .pharmacy-list-item, .pharmacy-details, .pharmacy-actions, .distance, .bottom-nav, .nav-item, .nav-icon, .screens, .screen, .screen-title {
            /* Réutiliser les styles existants */
        }

        .reservation-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .reservation-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .reservation-form input, .reservation-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .reservation-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="screen">
        <header>
            <div class="container header-content">
                <div class="logo"><i class="fas fa-pills"></i> PharmFind</div>
                <div>
                    <span>{{ date('h:i A T, l, d F Y') }}</span> <!-- 06:40 PM WAT, Friday, 13 June 2025 -->
                </div>
            </div>
        </header>

        <div class="container">
            <h1 style="margin: 30px 0 20px;">Mes réservations</h1>

            <!-- Formulaire de réservation -->
            <div class="reservation-form">
                <h3>Nouvelle réservation</h3>
                <form method="POST" action="{{ route('wel') }}">
                    @csrf
                    <label for="client_name">Nom complet</label>
                    <input type="text" id="client_name" name="client_name" value="{{ auth()->user()->name ?? '' }}" required>

                    <label for="product_name">Produit</label>
                    <input type="text" id="product_name" name="product_name" required>

                    <label for="pickup_date">Date de récupération</label>
                    <input type="date" id="pickup_date" name="pickup_date" required>

                    <label for="pickup_time_start">Heure de début</label>
                    <input type="time" id="pickup_time_start" name="pickup_time_start" required>

                    <label for="pickup_time_end">Heure de fin</label>
                    <input type="time" id="pickup_time_end" name="pickup_time_end" required>

                    <button type="submit">Réserver</button>
                </form>
            </div>

            <div class="tab-buttons">
                <button class="tab-btn active">En cours ({{ $reservations->where('status', 'EN ATTENTE')->count() + $reservations->where('status', 'CONFIRMEE')->count() }})</button>
                <button class="tab-btn">Historique</button>
            </div>

            <div class="tab-content">
                <div class="pharmacy-list">
                    @foreach($reservations->where('status', 'EN ATTENTE')->orWhere('status', 'CONFIRMEE') as $reservation)
                        <div class="pharmacy-list-item" style="border-left: 4px solid var(--primary-color);">
                            <div class="pharmacy-details" style="flex: 1;">
                                <div style="font-weight: bold;">{{ $reservation->product_name }}</div>
                                <div>Pharmacie [Nom] • {{ $reservation->pickup_date }}</div>
                                <div style="color: var(--primary-color); font-weight: bold;">
                                    @if($reservation->status === 'EN ATTENTE')
                                        En attente de confirmation
                                    @else
                                        À récupérer aujourd'hui • {{ $reservation->pickup_time_start }}-{{ $reservation->pickup_time_end }}
                                    @endif
                                </div>
                            </div>
                            <div class="pharmacy-actions">
                                <button class="map-btn">Itinéraire</button>
                                <button class="reserve-btn">Détails</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logique simple pour les onglets
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                // Ajouter logique pour changer le contenu des onglets
            });
        });
    </script>
</body>
</html>