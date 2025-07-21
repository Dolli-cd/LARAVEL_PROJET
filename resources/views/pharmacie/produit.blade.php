@extends('layouts.accueil')

@section('title', 'Dashboard Pharmacie ')
@section('page-title', 'Tableau de bord ')

@section('content')
<div class="container-fluid mt-4">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <form id="searchForm" class="search-bar w-50">
                <div class="input-group">
                    <input type="text" name="search_etu" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <a  class="btn btn-primary btn-sm mt-fas fa-plus"data-bs-toggle="modal" data-bs-target="#ajoutProduitModal "></i> Ajouter un produit</a>
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="deleteSelected" class="btn btn-danger btn-sm" disabled><i class="fas fa-trash"></i> Supprimer la sélection</button>
        </div>

        <div id="tableproduit" class="table-container">
            @include('pharmacie.search', ['produits' => $produits])
        </div>
    </div>

<!-- Fenêtre modale -->
<div class="modal fade" id="ajoutProduitModal" tabindex="-1" aria-labelledby="ajoutProduitLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="ajoutProduitLabel">Ajouter un produit pour {{ auth()->user()->pharmacie->name ?? 'Votre Pharmacie' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('ajouter_produit_traitement') }}" method="POST" id="addProductForm" enctype="multipart/form-data">
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
            <label for="categorie_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
            <select class="form-control" id="categorie_id" name="categorie_id" required>
              <option value="">Sélectionner une catégorie</option>
                          @foreach(\App\Models\Categorie::all() as $categorie)
                <option value="{{ $categorie->id }}">{{ $categorie->name}}</option>
            @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="file" class="form-label">Image du produit <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
            <div id="preview-container" class="mt-2">
                <img id="image-preview" src="#" alt="Aperçu de l'image" style="display: none; max-width: 200px; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" />
            </div>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
            <input type="number" step="0.50" class="form-control" id="price" name="price" required>
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantité <span class="text-danger">*</span></label>
            <input type="integer" step="1" class="form-control" id="quantity" name="quantity" required>
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

          <div class="mb-3">
            <label for="prescription" class="form-label">Nécessite une ordonnance ?</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="prescription" name="prescription" value="1">
              <label class="form-check-label" for="prescription">Oui</label>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

<script>
    jQuery(document).ready(function() {
        // Fonction pour rebinder les événements des checkboxes
        function bindCheckboxEvents() {
            // Checkbox "Sélectionner tout"
            jQuery('#selectAll').off('change').on('change', function() {
                jQuery('.selectproduit').prop('checked', jQuery(this).is(':checked'));
                toggleDeleteButton();
            });

            // Checkboxes individuelles
            jQuery(document).off('change', '.selectproduit').on('change', '.selectproduit', function() {
                const totalCheckboxes = jQuery('.selectproduit').length;
                const checkedCheckboxes = jQuery('.selectproduit:checked').length;
                
                jQuery('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                toggleDeleteButton();
            });
        }

        // Fonction pour activer/désactiver le bouton de suppression
        function toggleDeleteButton() {
            const selectedCount = jQuery('.selectproduit:checked').length;
            jQuery('#deleteSelected').prop('disabled', selectedCount === 0);
        }

        // Recherche dynamique
        let debounceTimer;
        jQuery('#searchInput').on('input', function () {
            clearTimeout(debounceTimer);
            const query = jQuery(this).val().trim();

            debounceTimer = setTimeout(() => {
                jQuery.ajax({
                    url: "{{ route('search') }}",
                    type: 'GET',
                    data: { search: query },
                    success: function (data) {
                        jQuery('#tableproduit').html(data);
                        // Rebinder les événements après la recherche
                        bindCheckboxEvents();
                        toggleDeleteButton();
                    },
                    error: function () {
                        alert("Erreur lors de la recherche.");
                    }
                });
            }, 300);
        });

        // Suppression multiple
        jQuery('#deleteSelected').on('click', function () {
            const ids = jQuery('.selectproduit:checked').map(function () {
                return jQuery(this).val();
            }).get();

            if (ids.length === 0) return;

            if (confirm("Voulez-vous vraiment supprimer les produits sélectionnés ?")) {
                jQuery.ajax({
                    url: "{{ route('produit.deleteMultiple') }}",
                    type: 'POST',
                    data: {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        jQuery('#tableproduit').html(data);
                        jQuery('#selectAll').prop('checked', false);
                        // Rebinder les événements après la suppression
                        bindCheckboxEvents();
                        toggleDeleteButton();
                        alert('Les produits ont bien été supprimés.');
                    },
                    error: function () {
                        alert('Erreur lors de la suppression.');
                    }
                });
            }
        });

        // Initialisation au chargement de la page
        bindCheckboxEvents();
        toggleDeleteButton();
    });
</script>
    @stack('scripts')
@endsection