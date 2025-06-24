<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un Produit - PharmFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .form-note {
        font-size: 0.9rem;
        color: #666;
      }
    </style>
  </head>
  <body>
    <div class="container mt-5">
      <h1 class="text-center">AJOUTER UN PRODUIT POUR {{ auth()->user()->pharmacie->name ?? 'Votre Pharmacie' }}</h1>
      <hr>
      @if (session('confirmer'))
        <div class='alert alert-success'>
          {{ session('confirmer') }}
        </div>
      @endif

      <form action="{{ route('ajouter_produit_traitement') }}" method="POST" id="addProductForm">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
          <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="code" name="code" required>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="type" name="type" required>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
          <input type="number" step="0,50" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
          <select class="form-control" id="status" name="status" required>
            <option value="available">Disponible</option>
            <option value="unavailable">Indisponible</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="comment" class="form-label">Commentaire</label>
          <input type="text" class="form-control" id="comment" name="comment">
        </div>

        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary">Ajouter</button>
          <a href="{{ route('liste_produit') }}" class="btn btn-danger ms-2">Retour à la liste</a>
        </div>
      </form>

      <hr>
      <div class="alert alert-info">
        <strong>Note :</strong> Ajoutez les produits spécifiques à votre pharmacie. Les champs marqués d'un * sont obligatoires.
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>