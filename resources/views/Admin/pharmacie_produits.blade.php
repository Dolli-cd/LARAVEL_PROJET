@extends('layouts.accueil')
@section('title', 'Produits de la pharmacie')
@section('content')
<div class="container mt-4">
    <h2>Produits de {{ $pharmacie->user->name }}</h2>
    <a href="{{ route('pharmaliste') }}" class="btn btn-secondary mb-3">Retour à la liste des pharmacies</a>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits as $produit)
                            <tr>
                                <td>{{ $produit->name }}</td>
                                <td>{{ $produit->code }}</td>
                                <td>{{ $produit->type }}</td>
                                <td>{{ $produit->categorie->name ?? 'Non définie' }}</td>
                                <td>{{ number_format($produit->pivot->price) }} FCFA</td>
                                <td>{{ $produit->pivot->quantity }}</td>
                                <td>{{ $produit->pivot->status_prod }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun produit pour cette pharmacie.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $produits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
