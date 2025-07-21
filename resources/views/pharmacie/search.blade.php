@if (session('confirmer'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('confirmer') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div >
    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th scope="col">N°</th>
                <th scope="col">Nom</th>
                <th scope="col">Code</th>
                <th scope="col">Type</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Prix</th>
                <th scope="col">Quantité</th>
                <th scope="col">Statut</th>
                <th scope="col">Prescription</th>
                <th scope="col" class="wrap-text">Commentaire</th>
                <th scope="col" class="wrap-text">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $id = 1; @endphp
            @forelse($produits as $produit)
                <tr>
                    <td>
                        <input type="checkbox" class="selectproduit form-check-input" value="{{ $produit->id }}">
                    </td>
                    <td>{{ $id }}</td>
                    <td>{{ $produit->name }}</td>
                    <td>{{ $produit->code }}</td>
                    <td>{{ $produit->type }}</td>
                    <td>
                        @if($produit->categorie)
                            <span class="badge bg-info">{{ $produit->categorie->name}}</span>
                        @else
                            <span class="text-muted">Non définie</span>
                        @endif
                    </td>
                    <td>{{ number_format($produit->pivot->price) }} FCFA</td>
                    <td>{{($produit->pivot->quantity) }}</td>
                    <td>
                        @if(isset($produit->pivot->status_prod))
                            <span class="badge bg-{{ $produit->pivot->status_prod === 'actif' ? 'success' : 'warning' }}">
                                {{ $produit->pivot->status_prod }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Non défini</span>
                        @endif
                    </td>
                    <td>
                        {{ $produit->prescription ? 'Oui' : 'Non' }}
                    </td>
                    <td class="wrap-text">
                        @if(isset($produit->pivot->comment))
                            <span class="text-muted">{{ $produit->pivot->comment }}</span>
                        @else
                            <span class="text-muted">Aucun commentaire</span>
                        @endif
                    </td>
                    <td class="wrap-text">
                        <div class="d-flex gap-2">
                            <!-- Bouton Modifier -->
                            <a href="{{ route('update_produit', $produit->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Bouton Supprimer avec SweetAlert2 -->
                            <form action="{{ route('delete_produit', $produit->id) }}" method="POST" class="delete-form" id="delete-form-{{ $produit->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $produit->id }}" data-name="{{ $produit->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @php $id++; @endphp
            @empty
                <tr>
                    <td colspan="10" class="text-center">Aucun produit trouvé.</td>
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
