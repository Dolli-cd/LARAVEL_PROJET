@extends('layouts.accueil')

@section('title', 'Mes Commandes')

@section('content')

<div class="container dashboard-layout" style="display: flex; gap: 24px; margin-top: 32px; margin-bottom: 48px;">
   
    <section class="dashboard-content" style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Commandes</h5>
                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Médicament</th>
                                    <th>Pharmacie</th>
                                    <th>Date</th>
                                    <th>Prix (FCFA)</th>
                                    <th>statut</th>
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
                                            <td>{{ $id }}</td>
                                            <td>{{ $produit->name }}</td>
                                            <td>{{ $commande->pharmacie->user->name ?? '-' }}</td>
                                            <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                {{ $produit->pivot->quantity }} x {{ $prix }} = {{ $produit->pivot->quantity * $prix }} FCFA
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($commande->status_cmd == 'Confirmée') bg-success
                                                    @elseif($commande->status_cmd == 'Annulée') bg-danger
                                                    @elseif($commande->status_cmd == 'En attente') bg-warning
                                                    @else bg-light text-dark
                                                    @endif
                                                ">
                                                    {{ $commande->status_cmd }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @php
                                    $total = 0;
                                    foreach ($commande->produits as $produit){
                                        $pharmacieProduit = $produit->pharmacies->where('id',$commande->pharmacie_id)->first();
                                        $prix=$pharmacieProduit ? $pharmacieProduit->pivot->price:0;
                                        $total += $produit->pivot->quantity * $prix;
                                        };
                                    @endphp
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total</td>
                                        <td class="fw-bold">{{ $total }} FCFA</td>
                                        <td></td>
                                    </tr>
                                    @php $id++;@endphp
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune commande effectuée.</td>
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