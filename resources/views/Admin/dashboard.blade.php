@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord administrateur')

@section('sidebar')
<div class="p-3">
    <a href="{{ route('statistique') }}" class="nav-link {{ request()->routeIs('statistique') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i>Statistiques
    </a>
    <a href="{{ route('liste_produit') }}" class="nav-link {{ request()->routeIs('liste_produit') ? 'active' : '' }}">
        <i class="fas fa-boxes me-2"></i>Produits
    </a>
    <a href="{{ route('inscriptionpharma') }}" class="nav-link {{ request()->routeIs('inscriptionpharma') ? 'active' : '' }}">
        <i class="fas fa-hospital me-2"></i>Nouvelle Pharmacie
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i>Notifications
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-history me-2"></i>Historique
    </a>
    <a href="{{ route('logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
@endsection

@section('content')
<div class="container py-5">
    @if(session('great'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('great') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques générales -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Utilisateurs</h5>
                <h2 class="fw-bold text-primary">{{ $stats['total_users'] }}</h2>
            </div>
        </div>
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
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Commandes</h5>
                <h2 class="fw-bold text-danger">{{ $stats['total_commandes'] }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <h5 class="text-muted">Réservations</h5>
                <h2 class="fw-bold text-secondary">{{ $stats['total_reservations'] }}</h2>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4">
                <h4 class="fw-bold mb-3">Utilisateurs par rôle</h4>
                <ul class="list-group list-group-flush">
                    @foreach($stats['users_by_role'] ?? [] as $row)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($row->role) }}
                            <span class="badge bg-primary">{{ $row->count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h4 class="fw-bold mb-3">Commandes par statut</h4>
                <ul class="list-group list-group-flush">
                    @foreach($stats['commandes_by_status'] ?? [] as $row)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($row->status) }}
                            <span class="badge bg-success">{{ $row->count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h4 class="fw-bold mb-3">Réservations par statut</h4>
                <ul class="list-group list-group-flush">
                    @foreach($stats['reservations_by_status'] ?? [] as $row)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($row->status) }}
                            <span class="badge bg-info">{{ $row->count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection