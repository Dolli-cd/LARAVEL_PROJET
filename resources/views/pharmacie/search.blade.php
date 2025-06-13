@if (session('confirmer'))
        <div class='alert alert-success'>
          {{ session('confirmer') }}
        </div>
      @endif
      <div class="table-responsive">
        <table class="table table-dark table-borderless">
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
            <tr>
                <td>
          <input type="checkbox" class="selectEtu" value="{{ $produit->id }}">
        </td>
              <td>{{ $id }}</td>
              <td>{{ $produit->name }}</td>
              <td>{{ $produit->code }}</td>
              <td>{{ $produit->type }}</td>
              <td>{{ $produit->price }}</td>
             <!--  <td>{$enum->status }}</td>
              <td>{ $enum->comment }}</td>-->
              <td>
                <div class="d-flex flex-wrap gap-1">
                  <a href="{{ route('update_produit', $produit->id) }}" class="btn btn-info btn-sm">Modifier</a>
                  <a href="{{ route('delete_produit', $produit->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</a>
                </div>
              </td>
            </tr>
            @php $id++; @endphp
            @empty
              <tr><td colspan="5">Aucun produit enregistré.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($produits, 'links'))
        <div class="mt-3">
          {{ $produits->links() }}
        </div>
      @endif
    </div>
