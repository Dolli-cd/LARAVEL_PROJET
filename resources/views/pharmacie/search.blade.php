@if (session('confirmer'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('confirmer') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-dark table-hover">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">N°</th>
                <th scope="col">Nom</th>
                <th scope="col">Code</th>
                <th scope="col">Type</th>
                <th scope="col">Prix</th>
                <th scope="col">Statut</th>
                <th scope="col">Commentaire</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $id = 1; @endphp
            @forelse($produits as $produit)
                <tr class="align-middle">
                    <td>
                        <input type="checkbox" class="selectEtu form-check-input" value="{{ $produit->id }}">
                    </td>
                    <td>{{ $id }}</td>
                    <td>{{ $produit->name }}</td>
                    <td>{{ $produit->code }}</td>
                    <td>{{ $produit->type }}</td>
                    <td>{{ number_format($produit->price, 2) }} FCFA</td>
                    <td>
                        @if(isset($produit->status))
                            <span class="badge bg-{{ $produit->status === 'actif' ? 'success' : 'warning' }}">
                                {{ $produit->status }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Non défini</span>
                        @endif
                    </td>
                    <td>
                        @if(isset($produit->comment))
                            <span class="text-muted">{{ $produit->comment }}</span>
                        @else
                            <span class="text-muted">Aucun commentaire</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('update_produit', $produit->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('delete_produit', $produit->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @php $id++; @endphp
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Aucun produit enregistré.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($produits, 'links'))
    <div class="mt-3">
        {{ $produits->links() }}
    </div>
@endif