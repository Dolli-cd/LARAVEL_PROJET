<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Produits - PharmFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .badge {
            padding: 5px 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Mes Produits</h1>
        <hr>
        @if (session('confirmer'))
            <div class='alert alert-success'>
                {{ session('confirmer') }}
            </div>
        @endif
        @if (session('erreur'))
            <div class='alert alert-danger'>
                {{ session('erreur') }}
            </div>
        @endif

        <a href="{{ route('ajouter_produit') }}" class="btn btn-primary mb-3">Ajouter un produit</a>

        <div class="row">
            @forelse($produits as $produit)
                @forelse($produit->pharmacies as $pharmacie)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="product-card">
                            <h5 class="card-title">{{ $produit->name }}</h5>
                            <p class="card-text">
                                <strong>Code :</strong> {{ $produit->code }}<br>
                                <strong>Type :</strong> {{ $produit->type }}<br>
                                <strong>Prix :</strong> {{ number_format($produit->price, 2) }} FCFA<br>
                                <strong>Pharmacie :</strong> {{ $pharmacie->name }}<br>
                                <strong>Statut :</strong> 
                                <span class="badge bg-{{ $pharmacie->pivot->status === 'available' ? 'success' : 'danger' }}">
                                    {{ $pharmacie->pivot->status }}
                                </span><br>
                                <strong>Commentaire :</strong> 
                                {{ $pharmacie->pivot->comment ?? 'Aucun commentaire' }}
                            </p>
                            <a href="{{ route('produit.detail', ['id' => $produit->id, 'pharmacie_id' => $pharmacie->id]) }}" class="btn btn-primary btn-sm">Voir détails</a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Aucun produit disponible pour le moment.
                        </div>
                    </div>
                @endforelse
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Aucun produit trouvé.
                    </div>
                </div>
            @endforelse
        </div>

        {{ $produits->links() }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>