<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produit->name }} - PharmFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>{{ $produit->name }}</h2>
        <p><strong>Code :</strong> {{ $produit->code }}</p>
        <p><strong>Type :</strong> {{ $produit->type }}</p>
        <p><strong>Prix :</strong> {{ number_format($produit->price, 2) }} FCFA</p>
        @forelse($produit->pharmacies as $pharmacie)
            <p><strong>Pharmacie :</strong> {{ $pharmacie->name }}</p>
            <p><strong>Statut :</strong> 
                <span class="badge bg-{{ $pharmacie->pivot->status === 'available' ? 'success' : 'danger' }}">
                    {{ $pharmacie->pivot->status }}
                </span>
            </p>
            <p><strong>Commentaire :</strong> {{ $pharmacie->pivot->comment ?? 'Aucun commentaire' }}</p>
        @empty
            <p>Aucune pharmacie associée.</p>
        @endforelse
        <a href="{{ route('accueil') }}" class="btn btn-secondary">Retour</a>
    </div>
</body>
</html>