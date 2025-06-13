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
      <h1 class="text-center">MODIFIER UN PRODUIT</h1>
      <hr>
      @if (session('confirmer'))
      <div class='alert alert-success'>
        {{(session('confirmer'))}}
      </div>
      @endif

      <form action="{{ route('update_pro_traitement') }}" method="POST">
        @csrf
        <input type="text" class="form-control"  name="id" style="display :none" value="{{$produits->id}}">
        <div class="mb-3">
          <label for="nom" class="form-label">Nom</label>
           <!--Value pour les modifiactions histoire de récupérer l'ancien nom qui était là--> 
          <input type="text" class="form-control" id="nom" name="nom" value="{{$produits->name}}">
        </div>

        <div class="mb-3">
          <label for="prenom" class="form-label">Code</label>
          <input type="text" class="form-control" id="prenom" name="prenom" value="{{$produits->code}}">
        </div>

        <div class="mb-3">
          <label for="classe" class="form-label">Type</label>
          <input type="text" class="form-control" id="classe" name="classe" value="{{$produits->type}}">
        </div>
        <div class="mb-3">
          <label for="classe" class="form-label">Prix</label>
          <input type="text" class="form-control" id="classe" name="classe" value="{{$produits->price}}">
        </div>
        <div class="mb-3">
          <label for="classe" class="form-label">Classe</label>
          <input type="text" class="form-control" id="classe" name="classe" value="{{$produits->type}}">
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">MODIFIER</button>
          <a href="liste_produit" class="btn btn-danger ms-2">Consulter la liste des produits</a>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
