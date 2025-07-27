<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un Produit - PharmaFind</title>
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
     <!-- Tu t'imagines à cause de la virgule qui séparait la route et la variable ça ne marchait pas j'ai dû séparé par un champ caché d'abord <form action="{{ route('update_produit_traitement', $produit->id) }}" method="POST" id="addProductForm"> -->
      <form action="{{ route('update_produit_traitement') }}" method="POST" id="editProductForm">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $produit->id }}">
        <div class="mb-3">
          <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $produit->name) }}" required>
        </div>

        <div class="mb-3">
          <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $produit->code) }}" required readonly>
          <small class="form-note">Le code ne peut pas être modifié.</small>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $produit->type) }}" required>
        </div>

        <div class="mb-3">
          <label for="categorie_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
          <select class="form-control" id="categorie_id" name="categorie_id" required>
            <option value="">Sélectionner une catégorie</option>
            @foreach(\App\Models\Categorie::all() as $categorie)
              <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                {{ $categorie->name}}
              </option>
            @endforeach
          </select>
        </div>

        @php
          $pharmacieAssociation = $produit->pharmacies->where('id', auth()->user()->pharmacie->id)->first();
          $price = old('price', $pharmacieAssociation ? $pharmacieAssociation->pivot->price : '');
          $status = old('status', $pharmacieAssociation ? $pharmacieAssociation->pivot->status : 'available');
          $comment = old('comment', $pharmacieAssociation ? $pharmacieAssociation->pivot->comment : '');
          $quantity = old('quantity', $pharmacieAssociation ? $pharmacieAssociation->pivot->quantity : '');
        @endphp
        <div class="mb-3">
          <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
          <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{$price }}" required>
        </div>
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

        <div class="mb-3">
          <label for="quantity" class="form-label">Quantité</label>
          <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $quantity }}">
        </div>

        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
          <a href="{{ route('liste_produit') }}" class="btn btn-danger ms-2">Retour à la liste</a>
        </div>
      </form>

      <hr>
      <div class="alert alert-info">
        <strong>Note :</strong>Les champs marqués d'un * sont obligatoires.
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>