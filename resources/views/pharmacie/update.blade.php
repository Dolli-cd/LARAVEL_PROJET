<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un Produit - PharmFind</title>
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
      <h1 class="text-center">MODIFIER UN PRODUIT</h1>
      <hr>
      @if (session('confirmer'))
        <div class='alert alert-success'>
          {{ session('confirmer') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('update_produit_traitement', $produits->id) }}" method="POST" id="editProductForm">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $produits->name) }}" required>
        </div>

        <div class="mb-3">
          <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $produits->code) }}" required readonly>
          <small class="form-note">Le code ne peut pas être modifié.</small>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $produits->type) }}" required>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
          <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $produits->price) }}" required>
        </div>

        <h4 class="mt-4">Détails de l'association avec votre pharmacie</h4>
        @php
          $pharmacieAssociation = $produits->pharmacies->where('id', auth()->user()->pharmacie->id)->first();
          $status = old('status', $pharmacieAssociation ? $pharmacieAssociation->pivot->status : 'available');
          $comment = old('comment', $pharmacieAssociation ? $pharmacieAssociation->pivot->comment : '');
        @endphp
        <div class="mb-3">
          <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
          <select class="form-control" id="status" name="status" required>
            <option value="available" {{ $status === 'available' ? 'selected' : '' }}>Disponible</option>
            <option value="unavailable" {{ $status === 'unavailable' ? 'selected' : '' }}>Indisponible</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="comment" class="form-label">Commentaire</label>
          <input type="text" class="form-control" id="comment" name="comment" value="{{ $comment }}">
        </div>

        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
          <a href="{{ route('liste_produit') }}" class="btn btn-danger ms-2">Retour à la liste</a>
        </div>
      </form>

      <hr>
      <div class="alert alert-info">
        <strong>Note :</strong> Modifiez les informations du produit et de son association avec votre pharmacie. Les champs marqués d'un * sont obligatoires.
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>