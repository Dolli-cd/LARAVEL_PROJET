@extends('layouts.accueil')

@section('title', 'Dashboard Pharmacie ')

@php
    use Carbon\Carbon;
    Carbon::setLocale('fr');
    $now = Carbon::now('Africa/Porto-Novo');
@endphp



@section('content')
<div class="container-fluid p-4">
    <!-- En-tête -->
    <p class="text-muted">
        Heure Bénin : {{ $now->format('H') }}h:{{ $now->format('i') }}mn, {{ ucfirst($now->translatedFormat('l d F Y')) }}
    </p>


    <!-- Alertes -->
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
                <h3 class="fw-bold text-success">{{ $confirmedReservations ?? 0 }}</h3>
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
        <div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100 text-center">
            <h6 class="text-muted">Produits Total</h6>
            <h3 class="fw-bold text-primary">{{ $totalProduits }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100 text-center">
            <h6 class="text-muted">Produits Disponibles</h6>
            <h3 class="fw-bold text-success">{{ $produitsDisponibles }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100 text-center">
            <h6 class="text-muted">Produits Indisponibles</h6>
            <h3 class="fw-bold text-danger">{{ $produitsIndisponibles }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100 text-center">
            <h6 class="text-muted">Commandes Reçues</h6>
            <h3 class="fw-bold text-warning">{{ $totalCommandes }}</h3>
        </div>
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