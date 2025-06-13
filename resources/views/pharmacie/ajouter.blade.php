<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container mt-5">
      <h1 class="text-center">AJOUTER UN PRODUIT</h1>
      <hr>
      @if (session('confirmer'))
      <div class='alert alert-success'>
        {{session('confirmer')}}
      </div>
      @endif

      <form action="{{ route('ajouter_produit_traitement') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Nom</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
          <label for="code" class="form-label">Code</label>
          <input type="text" class="form-control" id="code" name="code" required>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type</label>
          <input type="text" class="form-control" id="type" name="type" required>
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Prix</label>
          <input type="text" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Statut</label>
          <input type="text" class="form-control" id="status" name="status" required>
        </div>
        <div class="mb-3">
          <label for="comment" class="form-label">Commentaire</label>
          <input type="text" class="form-control" id="comment" name="comment" required>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">Ajouter</button>
          <a href="{{ route('liste_produit') }}" class="btn btn-danger ms-2">Consulter la liste des produits</a>
        </div>
      </form>
      
      <hr>
      <div class="alert alert-info">
        <strong>Note :</strong> Faites vos ajouts 😉.
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>