@extends('layouts.dashp')

@section('content')
<div class="container py-4">
    <!-- En-tête -->
    <header class="mb-4 p-3 bg-primary text-black rounded shadow-sm">
        <div class="d-flex justify-content-center align-items-center">
            
            <div class='text-center'>
                
                <a href="{{ route('liste_produit') }}" class="text-black text-decoration-none me-3">Produit</a>
                <a href="{{ route('profil') }}" class="text-black text-decoration-none me-3">Réservation</a>
                <a href="{{ route('logout') }}" class="text-black text-decoration-none me-3">Commande</a>
                <a href="{{ route('logout') }}" class="text-black text-decoration-none">Notifications</a>
                <a href="{{ route('logout') }}" class="text-black text-decoration-none">Historique</a>
            </div>
        </div>
    </header>

    <!-- Vue d'ensemble -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title h5">Vue d'ensemble</h2>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card" style="background-color: #69ffa3;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Réservations en attente</h5>
                                    <p class="display-6">{{ $pendingReservations ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card"  style="background-color: #aefffe;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Réservations acceptées</h5>
                                    <p class="display-6">{{ $weledReservations ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-black">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Réservations refusées</h5>
                                    <p class="display-6">{{ $rejectedReservations ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>                
        </div>
        

                    <div class="mt-3">
                        <ul class="list-group">
                            @foreach($notifications ?? [] as $notification)
                                <li class="list-group-item">{{ $notification->message }} - {{ $notification->created_at }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <!-- Historique 
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title h5">Historique</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            foreach($history ?? [] as $entry)
                                <tr>
                                    <td>{ $entry->date }}</td>
                                    <td>{ $entry->action }}</td>
                                    <td>{ $entry->details }}</td>
                                </tr>
                            endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>-->

<script>
    function welReservation(id) {
        if (confirm('Confirmer l\'welation de la réservation ' + id + ' ?')) {
            // Logique pour weler (ex. appel AJAX vers une route)
            window.location.href = "{{ route('wel', ['id' => ':id']) }}".replace(':id', id);
        }
    }

    function rejectReservation(id) {
        if (confirm('Confirmer le refus de la réservation ' + id + ' ?')) {
            // Logique pour refuser (ex. appel AJAX vers une route)
            window.location.href = "{{ route('wel', ['id' => ':id']) }}".replace(':id', id);
        }
    }
</script>
@endsection