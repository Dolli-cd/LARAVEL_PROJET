@if($produits->count())
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-secondary"><i class="fas fa-pills me-2"></i> Résultats de la recherche</h5>
            <span class="badge bg-light text-secondary border">{{ $produits->total() }} produit(s) trouvé(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Prix (FCFA)</th>
                            <th>Pharmacies</th>
                            <th scope="col">Prescription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                            <tr>
                                <td><strong>{{ $produit->name }}</strong></td>
                                <td>{{ $produit->code }}</td>
                                <td>{{ $produit->type }}</td>
                                <td>
                                    @forelse($produit->pharmacies as $pharmacie)
                                        <span class="badge bg-light text-secondary border me-1">
                                            {{ number_format($pharmacie->pivot->price, 0, ',', ' ') }}
                                        </span>
                                    @empty
                                        <span class="text-muted">Voir pharmacies</span>
                                    @endforelse
                                </td>
                                <td>
                                    @forelse($produit->pharmacies as $pharmacie)
                                        <span class="badge bg-light text-secondary border me-1">
                                            {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}
                                        </span>
                                    @empty
                                        <span class="text-muted">Aucune pharmacie</span>
                                    @endforelse
                                </td>
                                <td>{{ $produit->prescription ? 'Oui' : 'Non' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <form id="delete-form-{{ $produit->id }}" action="{{ route('delete_produit', $produit->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            onclick="confirmDelete({{ $produit->id }}, '{{ $produit->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $produits->links() }}
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        Aucun produit trouvé pour votre recherche
    </div>
@endif 