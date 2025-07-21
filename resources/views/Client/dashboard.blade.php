@extends('layouts.accueil')

@section('title', 'Tableau de bord')
            
@section('content')
@if (Auth::check () ||  Auth::user()->role==='client')

    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Médicaments Populaires</h2>
                {{-- <a href="#" class="section-link">Voir plus →</a> --}}
            </div>
            <div class="products-grid">
                @forelse($produits as $produit)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ asset('storage/' . $produit->file) }}" alt="{{ $produit->name }}">
                        </div>
                        <div class="product-info">
                            <div class="product-pharmacy">
                                @if($produit->pharmacies->count())
                                    {{ $produit->pharmacies->first()->user->name ?? 'Pharmacie inconnue' }}
                                @else
                                    Pharmacie inconnue
                                @endif
                            </div>
                            <div class="product-name">{{ $produit->name }}</div>
                            <div class="product-location">
                                @if($produit->pharmacies->count())
                                    {{ $produit->pharmacies->first()->user->address ?? '' }}
                                @endif
                            </div>
                            <div class="product-prescription">
                                {{ $produit->prescription ? 'Ordonnance requise' : 'Sans ordonnance' }}
                            </div>
                            <div class="product-footer">
                                <div class="product-price">
                                    @if($produit->pharmacies->count())
                                        {{ number_format($produit->pharmacies->first()->pivot->price ?? 0, 0, ',', ' ') }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="product-status">
                                    @if($produit->pharmacies->count())
                                        {{ ucfirst($produit->pharmacies->first()->pivot->status_prod ?? 'indisponible') }}
                                    @else
                                        Indisponible
                                    @endif
                                </div>
                            </div>
                        </div>
                        @foreach($produit->pharmacies as $pharmacie)
                            <form action="{{ route('panier.ajouter') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $produit->id }}">
                                <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                <button type="submit" class="product-btn">
                                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </form>
                        @endforeach
                    </div>
                @empty
                    <div class="alert alert-warning">Aucun médicament trouvé.</div>
                @endforelse
            </div>
        </div>
    </section>          
    <!-- Product Showcase (Promo ou Produit Vedette) -->
  

        </div>
    </section>
    <!-- Pharmacies proches -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Pharmacies proches</h2>
                <a href="#" class="section-link">Voir plus →</a>
            </div>
            <div class="products-grid">
                <div class="pharmacy-card">
                    <div class="pharmacy-image"></div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-rating">★★★★★</div>
                        <div class="pharmacy-badge">De garde</div>
                        <div class="pharmacy-name">Pharmacie Étoile</div>
                        <div class="pharmacy-hours">Ouverte • Ferme à 22h</div>
                        <div class="pharmacy-location">Cotonou - Zogbo</div>
                        <button class="pharmacy-btn">Voir les détails →</button>
                    </div>
                </div>
                <div class="pharmacy-card">
                    <div class="pharmacy-image"></div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-rating">★★★★★</div>
                        <div class="pharmacy-badge">De garde</div>
                        <div class="pharmacy-name">Pharmacie Étoile</div>
                        <div class="pharmacy-hours">Ouverte • Ferme à 22h</div>
                        <div class="pharmacy-location">Cotonou - Zogbo</div>
                        <button class="pharmacy-btn">Voir les détails →</button>
                    </div>
                </div>
                <div class="pharmacy-card">
                    <div class="pharmacy-image"></div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-rating">★★★★★</div>
                        <div class="pharmacy-badge">De garde</div>
                        <div class="pharmacy-name">Pharmacie Étoile</div>
                        <div class="pharmacy-hours">Ouverte • Ferme à 22h</div>
                        <div class="pharmacy-location">Cotonou - Zogbo</div>
                        <button class="pharmacy-btn">Voir les détails →</button>
                    </div>
                </div>
                <div class="pharmacy-card">
                    <div class="pharmacy-image"></div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-rating">★★★★★</div>
                        <div class="pharmacy-badge">De garde</div>
                        <div class="pharmacy-name">Pharmacie Étoile</div>
                        <div class="pharmacy-hours">Ouverte • Ferme à 22h</div>
                        <div class="pharmacy-location">Cotonou - Zogbo</div>
                        <button class="pharmacy-btn">Voir les détails →</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

@endsection