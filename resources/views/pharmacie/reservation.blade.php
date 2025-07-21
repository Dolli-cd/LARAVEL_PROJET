@extends('layouts.accueil')

@section('title', 'Réservations reçues')

@section('content')
<div class="container mt-4">
    <h3>Réservations en attente</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
                <tr>
                    <td>{{ $res->booking_date}}</td>
                    <td>{{ $res->client->user->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($res->produits as $produit)
                            {{ $produit->name }} (x{{ $produit->pivot->quantity }})<br>
                            @if($produit->prescription && $produit->pivot->prescription_file)
                                <div class="card mb-3" style="max-width: 400px;">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Ordonnance pour {{ $produit->name }}</h6>
                                        <a href="{{ asset('storage/' . $produit->pivot->prescription_file) }}" download class="btn btn-success mb-2">
                                            <i class="fas fa-download"></i> Télécharger l’ordonnance
                                        </a>
                                        @php
                                            $ext = strtolower(pathinfo($produit->pivot->prescription_file, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        @if($isImage)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $produit->pivot->prescription_file) }}" alt="Ordonnance" style="max-width: 100%; border-radius: 8px; box-shadow: 0 2px 8px #0001;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        {{
                            $res->produits->sum(function($prod) use ($res) {
                                $prix = $prod->pharmacies->where('id', $res->pharmacie_id)->first()?->pivot->price ?? 0;
                                return $prod->pivot->quantity * $prix;
                            })
                        }} FCFA
                    </td>
                    <td>{{ $res->status_res ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Aucune réservation en attente.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $reservations->links() }}
    </div>
</div>
@endsection