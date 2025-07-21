@extends('layouts.accueil')

@section('title', 'Tableau de bord')




@section('content')
<div class="container-fluid p-4">
    <!-- Statistiques principales -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-hospital"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_pharmacies'] }}</div>
                <div class="stat-label">Pharmacies Actives</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    +{{ $stats['nouveaux_pharmacies_ce_mois'] }} ce mois
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_commandes'] }}</div>
                <div class="stat-label">Total Commandes</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    {{ $stats['commandes_ce_mois'] }} ce mois
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_reservations'] }}</div>
                <div class="stat-label">Total Réservations</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    {{ $stats['reservations_ce_mois'] }} ce mois
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Utilisateurs</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>
                    +{{ $stats['nouveaux_clients_ce_mois'] }} clients ce mois
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques principaux -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        Évolution des Commandes (6 derniers mois)
                    </h5>
                </div>
                <div class="chart-body">
                    <canvas id="pharmaciesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Répartition par Département
                    </h5>
                </div>
                <div class="chart-body">
                    <canvas id="regionsChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques secondaires et Top Pharmacies -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Évolution des Réservations (6 derniers mois)
                    </h5>
                </div>
                <div class="chart-body">
                    <canvas id="searchesChart" height="150"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="table-card">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="fas fa-trophy"></i>
                        Top Pharmacies
                    </h5>
                </div>
                <div class="table-body">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Ville</th>
                                <th>Recherches</th>
                                <th>Évolution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPharmacies as $pharmacie)
                                <tr>
                                    <td><strong>{{ $pharmacie->user->name }}</strong></td>
                                    <td>{{ $pharmacie->user->address }}</td>
                                    <td>{{ $pharmacie->commandes_count }}</td>
                                    <td>
                                        @if($pharmacie->commandes_count > 0)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-warning">Aucune commande</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucune pharmacie avec des commandes ce mois</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes du bas -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Clients</h5>
                <h2 class="fw-bold text-success">{{ $stats['total_clients'] }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Pharmacies</h5>
                <h2 class="fw-bold text-info">{{ $stats['total_pharmacies'] }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Produits</h5>
                <h2 class="fw-bold text-warning">{{ $stats['total_produits'] }}</h2>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration par défaut pour tous les graphiques
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6b7280';

    // 1. Graphique des commandes (ligne)
    const pharmaciesCtx = document.getElementById('pharmaciesChart');
    if (pharmaciesCtx) {
        const evolutionData = @json($evolutionCommandes);
        new Chart(pharmaciesCtx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => item.mois),
                datasets: [{
                    label: 'Commandes',
                    data: evolutionData.map(item => item.commandes),
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // 2. Graphique des départements (camembert)
    const regionsCtx = document.getElementById('regionsChart');
    if (regionsCtx) {
        const departementsData = @json($statsParDepartement);
        new Chart(regionsCtx, {
            type: 'doughnut',
            data: {
                labels: departementsData.map(item => item.name),
                datasets: [{
                    data: departementsData.map(item => item.pharmacies_count),
                    backgroundColor: [
                        '#1e3a8a',
                        '#166534',
                        '#92400e', 
                        '#075985',
                        '#991b1b',
                        '#64748b',
                        '#7c3aed',
                        '#059669',
                        '#dc2626',
                        '#ea580c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    // 3. Graphique des réservations (barres)
    const searchesCtx = document.getElementById('searchesChart');
    if (searchesCtx) {
        const evolutionReservations = @json($evolutionReservations);
        new Chart(searchesCtx, {
            type: 'bar',
            data: {
                labels: evolutionReservations.map(item => item.mois),
                datasets: [{
                    label: 'Réservations',
                    data: evolutionReservations.map(item => item.reservations),
                    backgroundColor: '#166534',
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection