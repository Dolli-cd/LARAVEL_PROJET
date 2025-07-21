@extends('layouts.accueil')
@section('title', 'Historique')
@section('content')
<div class="container mt-4">
    <h3>Historique des commandes</h3>
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
            @forelse($commandes as $cmd)
                <tr>
                    <td>{{ $cmd->order_date }}</td>
                    <td>{{ $cmd->client->user->name ?? 'Non défini' }}</td>
                    <td>
                        @foreach($cmd->produits as $prod)
                            {{ $prod->name }} (x{{ $prod->pivot->quantity }})<br>
                        @endforeach
                    </td>
                    <td> {{
                            $cmd->produits->sum(function($prod) use ($cmd) {
                                $prix = $prod->pharmacies->where('id', $cmd->pharmacie_id)->first()?->pivot->price ?? 0;
                                return $prod->pivot->quantity * $prix;
                            })
                        }} FCFA</td>
                    <td>{{ $cmd->status_cmd ?? 'Non défini' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Aucune commande.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $commandes->links() }}
    </div>

    <h3 class="mt-5">Historique des réservations</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
                <tr>
                    <td>{{ $res->booking_date }}</td>
                    <td>{{ $res->client->user->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($res->produits as $prod)
                            {{ $prod->name }} (x{{ $prod->pivot->quantity }})<br>
                        @endforeach
                    </td>
                    <td>{{ $res->status_res ?? 'Non défini' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Aucune réservation.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $reservations->links() }}
    </div>
</div>
@endsection