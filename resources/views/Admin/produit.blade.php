@extends('layouts.accueil')
@section('title', 'Admin - Gestion des Produits')

@section('content')
<div class="container-fluid p-4">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulaire de recherche -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-secondary"><i class="fas fa-search me-2"></i> Recherche de Produits</h5>
        </div>
        <div class="card-body">
            <input type="text" id="adminSearchInput" class="form-control form-control-lg" placeholder="Rechercher un produit...">
        </div>
    </div>

    <!-- Résultats de recherche -->
    <div id="adminTableProduit">
        @include('Admin.partials_produit_table')
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let adminDebounceTimer;
    $('#adminSearchInput').on('input', function () {
        clearTimeout(adminDebounceTimer);
        const query = $(this).val().trim();

        adminDebounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.search.produits') }}",
                type: 'GET',
                data: { search: query },
                success: function (data) {
                    $('#adminTableProduit').html(data);
                },
                error: function () {
                    alert("Erreur lors de la recherche.");
                }
            });
        }, 200);
    });
});
</script>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Voulez-vous vraiment supprimer le produit : " + name + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
