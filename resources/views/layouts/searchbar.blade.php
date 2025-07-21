
@php
    use Illuminate\Support\Facades\Route;
//très important pour la recherche intelligente
    // Détection automatique du contexte
    $routeName = Route::currentRouteName();
    $pharmacieId = request()->route('id');

    // Valeurs par défaut
    $query = $query ?? request('search', '');
    $category = $category ?? request('category', '');
    $type = $type ?? request('type', '');

    // Détermination dynamique de la route d'action et du placeholder
    if (
        ($routeName === 'pharmacie.produits' || $routeName === 'pharmacie.recherche')
        && $pharmacieId
    ) {
        $searchRoute = route('pharmacie.recherche', $pharmacieId);
        $placeholder = 'Rechercher un produit dans cette pharmacie...';
        $type = null; // Pas besoin de type ici
    } else {
        // Valeurs par défaut ou héritées
        $searchRoute = $searchRoute ?? route('client.search');
        $placeholder = $placeholder ?? ($type === 'pharmacie'
            ? 'Rechercher une pharmacie...'
            : 'Rechercher un produit, une pharmacie...');
    }
@endphp

<div class="search-container flex-grow-1 mx-3">
    <form action="{{ $searchRoute }}" method="GET" class="search-wrapper">
        @if($type !== 'pharmacie')
            <span class="search-select-wrapper">
                @php
                    $categories = \App\Models\Categorie::all();
                @endphp
                <select class="search-select" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(request('categorie_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <span class="search-select-arrow">▼</span>
            </span>
            <span class="pipe-separator"></span>
        @endif
        <input type="text" class="search-input" name="search" value="{{ $query }}" placeholder="{{ $placeholder }}">
        @if($type)
            <input type="hidden" name="type" value="{{ $type }}">
        @endif
        <button class="search-btn" tabindex="-1"><i class="fas fa-search"></i></button>
    </form>
</div>
