@extends('layouts.accueil')

@section('title', 'Historique')

@section('content')
<div class="container dashboard-layout" style="margin-top: 32px; margin-bottom: 48px;">
    <section class="dashboard-content" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">
        <h5 class="mb-4">Mes Commandes</h5>
        <div class="table-responsive mb-5">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Produit</th>
                        <th>Pharmacie</th>
                        <th>Date</th>
                        <th>Prix (FCFA)</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @php $id=1;@endphp
                    @forelse ($commandes as $commande)
                        @foreach ($commande->produits as $produit)
                        @php
                            $pharmacieProduit = $produit->pharmacies->where('id', $commande->pharmacie_id)->first();
                            $prix = $pharmacieProduit ? $pharmacieProduit->pivot->price : 0;
                        @endphp
                            <tr>
                                <td>{{$id }}</td>
                                <td>{{ $produit->name }}</td>
                                <td>{{ $commande->pharmacie->user->name ?? '-' }}</td>
                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td>
                                    {{ $produit->pivot->quantity }} x {{ $prix }} = {{ $produit->pivot->quantity * $prix }} FCFA
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($commande->status == 'delivered') bg-success
                                        @elseif($commande->status == 'cancelled') bg-danger
                                        @endif
                                    ">
                                        {{ ucfirst($commande->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @php $id++; @endphp
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune commande effectuée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h5 class="mb-4"> Mes Reservation </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Produit</th>
                        <th>Pharmacie</th>
                        <th>Date</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @php $id=1; @endphp
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
                                        @if($reservation->status == 'confirmed') bg-info
                                        @elseif($reservation->status == 'cancelled') bg-danger
                                        @endif
                                    ">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @php $id++;@endphp
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune réservation effectuée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection