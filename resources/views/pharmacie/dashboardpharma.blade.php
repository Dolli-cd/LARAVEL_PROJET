@extends('layouts.app')

@section('title', 'Dashboard Pharmacie - ' . auth()->user()->name)
@section('page-title', 'Tableau de bord - ' . auth()->user()->name)

@section('sidebar')
<div class="p-3">
    <a href="{{ route('pharmacie.dashboard') }}" class="nav-link {{ request()->routeIs('pharmacie.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
    </a>
    <a href="{{ route('liste_produit') }}" class="nav-link {{ request()->routeIs('liste_produit') ? 'active' : '' }}">
        <i class="fas fa-boxes me-2"></i>Produits
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-calendar-check me-2"></i>Réservations
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart me-2"></i>Commandes
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('pharmacie.notifications') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i>Notifications
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-history me-2"></i>Historique
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('pharmacie.profile') ? 'active' : '' }}">
        <i class="fas fa-user me-2"></i>Mon Profil
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
<div class="container py-4">
    <!-- En-tête -->
    <p class="text-muted">
    Heure Bénin : {{ now()->timezone('Africa/Porto-Novo')->format('h:i A T, l, F j, Y') }} |
    Heure UTC : {{ now()->timezone('UTC')->format('h:i A T, l, F j, Y') }}
</p>


    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques générales -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 text-center">
                <h6 class="text-muted">Réservations en attente</h6>
                <h3 class="fw-bold text-warning">{{ $pendingReservations ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 text-center">
                <h6 class="text-muted">Réservations acceptées</h6>
                <h3 class="fw-bold text-success">{{ $acceptedReservations ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 text-center">
                <h6 class="text-muted">Réservations refusées</h6>
                <h3 class="fw-bold text-danger">{{ $rejectedReservations ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 text-center">
                <h6 class="text-muted">Réservations expirées</h6>
                <h3 class="fw-bold text-secondary">{{ $expiredReservations ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Notifications 
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Notifications récentes</h5>
                    if(notifications->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            foreach(notifications ?? [] as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{ $notification->message }}</span>
                                    <small class="text-muted">{ $notification->created_at->diffForHumans() }}</small>
                                </li>
                            endforeach
                        </ul>
                    else
                        <p class="text-muted text-center">Aucune notification pour le moment.</p>
                    endif
                </div>
            </div>
        </div>
    </div>-->

    <!-- Historique (commenté pour l'instant, à décommenter si les données sont prêtes) -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Historique</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history ?? [] as $entry)
                                <tr>
                                    <td>{{ $entry->date ?? 'N/A' }}</td>
                                    <td>{{ $entry->action ?? 'N/A' }}</td>
                                    <td>{{ $entry->details ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun historique disponible.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function welReservation(id) {
        if (confirm('Confirmer la réservation ' + id + ' ?')) {
            window.location.href = "{{ route('wel', ['id' => ':id']) }}".replace(':id', id);
        }
    }

    function rejectReservation(id) {
        if (confirm('Confirmer le refus de la réservation ' + id + ' ?')) {
            window.location.href = "{{ route('wel', ['id' => ':id']) }}".replace(':id', id);
        }
    }
</script>

@stack('scripts')
@endsection