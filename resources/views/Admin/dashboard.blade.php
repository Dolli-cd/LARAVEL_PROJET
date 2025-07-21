@extends('layouts.accueil')
@section('title', 'Admin')

@section('content')
@if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p>{{ session('error') }}</p>
            </div>
        @endif

    <div class="container-fluid p-4">
    <!-- Tableau des utilisateurs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="font-weight: 500; font-size: 1.1rem;">
            <span><i class="fas fa-users me-2 text-secondary"></i> Utilisateurs enregistrés</span>
        </div>
                <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                            <tr>
                        <th>Nom</th>
                        <th>Email</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->role }}</td>
                                <td>
                                <button class="btn btn-link text-danger p-0" onclick="showConfirmModal('{{ route('userdelete', ['id' => $user->id]) }}')" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                            <td colspan="6" class="text-center text-muted">Aucun utilisateur enregistré.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <!-- Modale de confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Voulez-vous vraiment supprimer cet utilisateur ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
    function showConfirmModal(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }
</script>
@endsection