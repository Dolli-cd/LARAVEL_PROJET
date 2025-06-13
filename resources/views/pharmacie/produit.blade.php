<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRODUIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
   <nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">Produit gestion</span>

    <form id="logout" action="{{ route('logout') }}" method="POST" class="d-flex">
        @csrf
        <button type="submit" class="btn btn-danger mb-3">
            Déconnexion
        </button>
    </form>
  </div>
</nav>

  <div class="container mt-4">     

<form id="searchForm" class="mb-3">
  <div class="input-group">
    <input type="text" name="search_etu" id="searchInput" class="form-control" placeholder="Rechercher...">
  </div>
</form>
      <div class="d-flex justify-content-between mb-3">
  <a href="{{ route('ajouter_pro') }}" class="btn btn-primary btn-sm">AJOUTER UN PRODUIT</a>
</div>

      <hr>
<div class="container mt-4">

  <button id="deleteSelected" class="btn btn-danger mb-3 btn-sm" disabled>Supprimer la sélection</button>

  <div id="tableproduit">
    @include('pharmacie.search', ['produits' => $produits])
  </div>

</div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  //j'ai essayé de faire sans le bouton genre en temps réel mais ca n'a pas marché
  let debounceTimer;

$('#searchInput').on('input', function () {
  clearTimeout(debounceTimer); // annule le timer précédent
  const query = $(this).val();

  debounceTimer = setTimeout(() => {
    $.ajax({
      url: "{{ route('search') }}",
      type: 'GET',
      data: { search: query },
      success: function (data) {
        $('#tableproduit').html(data);
      },
      error: function () {
        alert("Erreur lors de la recherche.");
      }
    });
  }, 200); // délai 200 ms
});

</script>
<script>
$(document).ready(function () {
  // Gérer la checkbox "select all"
  $('#selectAll').on('change', function () {
    $('.selectproduit').prop('checked', $(this).is(':checked'));
    toggleDeleteButton();
  });

  // Gérer la sélection individuelle
  $(document).on('change', '.selectproduit', function () {
    // Si toutes les checkbox sont cochées, coche la checkbox globale
    $('#selectAll').prop('checked', $('.selectproduit:checked').length === $('.selectproduit').length);
    toggleDeleteButton();
  });

  // Activer/désactiver le bouton de suppression selon la sélection
  function toggleDeleteButton() {
    const selectedCount = $('.selectproduit:checked').length;
    $('#deleteSelected').prop('disabled', selectedCount === 0);
  }

  // Quand on clique sur supprimer
  $('#deleteSelected').on('click', function () {
    const ids = $('.selectproduit:checked').map(function () {
      return $(this).val();
    }).get();

    if(ids.length === 0) return;

    if(!confirm("Voulez-vous vraiment supprimer lesproduits sélectionnés ?")) return;

    $.ajax({
      url: "{{ route('produit.deleteMultiple') }}", // route à créer
      type: 'POST',
      data: {
        ids: ids,
        _token: "{{ csrf_token() }}"
      },
      success: function (data) {
        // Par exemple, rafraîchir la liste des produits après suppression
        $('#tableproduit').html(data);
        $('#selectAll').prop('checked', false);
        toggleDeleteButton();
        alert('Les produits ont bien été supprimés ');
      },
      error: function () {
        alert('Erreur lors de la suppression.');
      }
    });
  });
});
</script>
</body>
</html>
