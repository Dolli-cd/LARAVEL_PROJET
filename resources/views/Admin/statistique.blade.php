<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - PharmFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #343a40 0%, #2c3338 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #495057;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }

        .logo::before {
            content: "🏥";
            margin-right: 10px;
            font-size: 24px;
        }

        .user-info {
            padding: 15px 20px;
            border-bottom: 1px solid #495057;
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 0;
        }

        .header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .breadcrumb {
            color: #6c757d;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification {
            position: relative;
            background: #dc3545;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .content {
            padding: 5px;
        }

        .page-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #343a40;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        .view-report {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .card-body {
            padding: 15px 20px;
        }

        .metric {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .metric-value {
            font-size: 28px; /* Réduit pour mobile */
            font-weight: bold;
            color: #343a40;
            margin-right: 10px;
        }

        .metric-change {
            display: flex;
            align-items: center;
            font-size: 12px; /* Réduit pour mobile */
            color: #28a745;
        }

        .metric-change.negative {
            color: #dc3545;
        }

        .metric-label {
            color: #6c757d;
            font-size: 12px; /* Réduit pour mobile */
            margin-bottom: 10px;
        }

        .chart-container {
            height: 180px; /* Réduit pour mobile */
            position: relative;
        }

        .bottom-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px; /* Réduit pour mobile */
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-avatar {
            width: 30px; /* Réduit pour mobile */
            height: 30px;
            border-radius: 50%;
            background: #6c757d;
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        .status-positive {
            color: #28a745;
        }

        .status-warning {
            color: #ffc107;
        }

        .overview-metrics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .overview-metric {
            text-align: center;
            flex: 1;
        }

        .overview-metric-value {
            font-size: 20px; /* Réduit pour mobile */
            font-weight: bold;
            color: #007bff;
        }

        .overview-metric-label {
            font-size: 10px; /* Réduit pour mobile */
            color: #6c757d;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
            .dashboard-grid,
            .bottom-section {
                grid-template-columns: 1fr;
            }
            .header-actions .grid {
                display: none;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .view-report {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">


        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div>
                    <h1 class="page-title">Statistiques</h1>
                    <div class="breadcrumb">Dashboard / Statistiques</div>
                </div>
                <div class="header-actions">
                    <a href="dashboard" class="btn btn-primary">Retour au Dashboard Admin</a>
                </div>
            </div>

            <div class="content">
                <div class="dashboard-grid">
                    <!-- Pharmacies Actives -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Pharmacies Actives</h3>
                            <a href="#" class="view-report">Voir le rapport</a>
                        </div>
                        <div class="card-body">
                            <div class="metric">
                                <div class="metric-value">1,247</div>
                                <div class="metric-change">
                                    ↗️ 15.2%
                                </div>
                            </div>
                            <div class="metric-label">Pharmacies connectées</div>
                            <div class="metric-label">Depuis la semaine dernière</div>
                            <div class="chart-container">
                                <canvas id="visitorsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recherches Quotidiennes -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Recherches Quotidiennes</h3>
                            <a href="#" class="view-report">Voir le rapport</a>
                        </div>
                        <div class="card-body">
                            <div class="metric">
                                <div class="metric-value">8,456</div>
                                <div class="metric-change">
                                    ↗️ 28.7%
                                </div>
                            </div>
                            <div class="metric-label">Recherches effectuées</div>
                            <div class="metric-label">Depuis le mois dernier</div>
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bottom-section">
                    <!-- Pharmacies les Plus Recherchées -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Pharmacies Populaires</h3>
                            <div style="display: flex; gap: 10px;">
                                <span>🔽</span>
                                <span>≡</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Pharmacie</th>
                                            <th>Localisation</th>
                                            <th>Recherches</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="product-info">
                                                    <div class="product-avatar" style="background: #28a745;">PC</div>
                                                    Pharmacie du Centre
                                                </div>
                                            </td>
                                            <td>Cotonou, Littoral</td>
                                            <td><span class="status-positive">↗️ 18%</span> 2,847 recherches</td>
                                            <td><a href="#">🔍</a></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="product-info">
                                                    <div class="product-avatar" style="background: #007bff;">PP</div>
                                                    Pharmacie de la Paix
                                                </div>
                                            </td>
                                            <td>Porto-Novo, Ouémé</td>
                                            <td><span class="status-positive">↗️ 12%</span> 1,956 recherches</td>
                                            <td><a href="#">🔍</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu Système -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Aperçu du Système</h3>
                            <div style="display: flex; gap: 10px;">
                                <span>🔽</span>
                                <span>≡</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="overview-metrics">
                                <div class="overview-metric">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid #28a745; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                        <span style="color: #28a745; font-size: 14px;">98%</span>
                                    </div>
                                    <div class="overview-metric-label">DISPONIBILITÉ</div>
                                </div>
                                <div class="overview-metric">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <div style="font-size: 32px;">⚡</div>
                                        <div style="margin-top: 10px;">
                                            <div style="font-size: 18px; font-weight: bold;">2.3s</div>
                                            <div class="overview-metric-label">TEMPS RÉPONSE</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Visitors Chart
        const visitorsCtx = document.getElementById('visitorsChart').getContext('2d');
        new Chart(visitorsCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Cette semaine',
                    data: [950, 1100, 1200, 1350, 1400, 1450, 1247],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Semaine dernière',
                    data: [800, 900, 950, 1000, 1050, 1100, 1080],
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
            }
        });

        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Cette année',
                    data: [5200, 6800, 7500, 8200, 8900, 8456, 9100],
                    backgroundColor: '#007bff'
                }, {
                    label: 'Année dernière',
                    data: [4800, 5500, 6200, 6800, 7200, 6580, 7800],
                    backgroundColor: '#e9ecef'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
            }
        });
    </script>
</body>
</html>

