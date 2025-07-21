@extends('layouts.accueil')

@section('title', 'Mes Réservations')

@section('content')

<div class="container dashboard-layout" style="display: flex; gap: 24px; margin-top: 32px; margin-bottom: 48px;">
    <section class="dashboard-content" style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Réservations</h5>
                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Médicament</th>
                                    <th>Pharmacie</th>
                                    <th>Date</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $id=1;@endphp
                                @forelse ($reservations as $reservation)
                                    @foreach ($reservation->produits as $produit)
                                        <tr>
                                            <td>{{ $id }}</td>
                                            <td>{{ $produit->name }}</td>
                                            <td>{{ $reservation->pharmacie->user->name ?? '-' }}</td>
                                            <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $produit->pivot->quantity }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($reservation->status_res == 'confirmée') bg-info
                                                    @elseif($reservation->status_res == 'Expirée') bg-secondary
                                                    @elseif($reservation->status_res == 'En attente') bg-warning
                                                    @else bg-light text-dark
                                                    @endif
                                                ">
                                                    {{ ucfirst($reservation->status_res) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php $id++; @endphp
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center"> Aucune réservation effectuée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
@endsection