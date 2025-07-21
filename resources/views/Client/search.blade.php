@extends('layouts.search')

@section('content')

<div class="container">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Affichage des produits -->
    @if(($type === 'produit' || !$type) && $produits->count() > 0)
        <div class="section-header">
            <h2 class="section-title">Produits trouvés</h2>
        </div>
        <div class="products-grid">
        @foreach($produits as $pIndex => $produit)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $produit->file) }}" alt="{{ $produit->name }}">
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $produit->name }}</div>
                        <div class="product-prescription">
                            {{ $produit->prescription ? 'Ordonnance requise' : 'Sans ordonnance' }}
                        </div>
                        @if($produit->pharmacies->count())
                            <div >
                                @foreach($produit->pharmacies as $phIndex => $pharmacie)
                                    @php $pivot = $pharmacie->pivot; $modalKey = $produit->id . '-' . $pharmacie->id . '-' . $pIndex . '-' . $phIndex; @endphp
                                    <div style="margin-bottom: 8px;">
                                        <i class="fas fa-clinic-medical"></i> {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}
                                        <span class="product-location">{{ $pharmacie->user->address ?? '' }}</span>
                                @if($pivot->status === 'available')
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($pivot->status === 'unavailable')
                                    <span class="badge bg-danger">Indisponible</span>
                                @endif
                                @if(!empty($pivot->comment))
                                    <div class="text-muted mt-1">
                                        <i class="fas fa-comment-dots"></i> {{ $pivot->comment }}
                                    </div>
                                @endif
                                        <div class="product-footer" style="margin-top: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <div class="product-price">
                                                {{ number_format($pivot->price ?? 0, 0, ',', ' ') }} FCFA
                                            </div>
                                @if($pivot->status === 'available')
                                    @auth
                                        <button type="button" class="btn btn-success btn-sm btn-xs" data-bs-toggle="modal" data-bs-target="#commandeModal-{{ $modalKey }}">
                                            <i class="fas fa-check-circle"></i> Commander
                                        </button>
                                        <!-- Commander Modal -->
                                        <div class="modal fade" id="commandeModal-{{ $modalKey }}" tabindex="-1" aria-labelledby="commandeModalLabel-{{ $modalKey }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="commandeModalLabel-{{ $modalKey }}">Commander {{ $produit->name }} chez {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('client.commander') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                                            <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                            <div class="mb-3">
                                                                <label>Produit</label>
                                                                <input type="text" class="form-control" value="{{ $produit->name }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Quantité</label>
                                                                <input type="number" class="form-control" name="quantity" min="1" required>
                                                            </div>
                                                            @if($produit->prescription)
                                                                <div class="mb-3">
                                                                    <label>Ordonnance (obligatoire)</label>
                                                                    <input type="file" class="form-control" name="prescription_file" accept="image/*,application/pdf" required>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-success">Valider la commande</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-info btn-sm btn-xs" data-bs-toggle="modal" data-bs-target="#reservationModal-{{ $modalKey }}">
                                            <i class="fas fa-clock"></i> Réserver
                                        </button>
                                        <!-- Réserver Modal -->
                                        <div class="modal fade" id="reservationModal-{{ $modalKey }}" tabindex="-1" aria-labelledby="reservationModalLabel-{{ $modalKey }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reservationModalLabel-{{ $modalKey }}">Réserver {{ $produit->name }} chez {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('client.reserver') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                                            <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                            <div class="mb-3">
                                                                <label class="form-label">Produit</label>
                                                                <input type="text" class="form-control" value="{{ $produit->name }}" readonly>
                                                                <input type="hidden" name="nom" value="{{ $produit->name }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="quantity-res-{{ $modalKey }}" class="form-label">Quantité</label>
                                                                <input type="number" class="form-control" id="quantity-res-{{ $modalKey }}" name="quantity" min="1" required>
                                                            </div>
                                                            @if($produit->prescription)
                                                                <div class="mb-3">
                                                                    <label for="ordonnance-res-{{ $modalKey }}" class="form-label">Ordonnance (obligatoire)</label>
                                                                    <input type="file" class="form-control" id="prescription_file-{{ $modalKey }}" name="prescription_file" accept="image/*,application/pdf" required>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-info">Valider la réservation</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('panier.ajouter') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $produit->id }}">
                                            <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                        <button type="submit" class="product-btn">
                                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-success btn-sm btn-xs">
                                            <i class="fas fa-check-circle"></i> Commander
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-info btn-sm btn-xs">
                                            <i class="fas fa-clock"></i> Réserver
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm btn-xs">
                                            <i class="fas fa-cart-plus"></i> Ajouter au panier
                                        </a>
                                    @endauth
                                @endif
                            </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="product-pharmacy">Pharmacie inconnue</div>
                        @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $produits->links() }}</div>
@endif

    <!-- Affichage des pharmacies-->
    @if(($type === 'pharmacie' || !$type) && $pharmacies->count() > 0)
        <div class="section-header">
            <h2 class="section-title">Pharmacies trouvées</h2>
        </div>
        <div class="products-grid">
        @foreach($pharmacies as $pharmacie)
                <div class="pharmacy-card">
                    <div class="pharmacy-image" style="width: 100%; height: 160px; overflow: hidden; background: #f5f5f5;">
                        @if($pharmacie->user && $pharmacie->user->avatar)
                            <img src="{{ asset('storage/' . $pharmacie->user->avatar) }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                        @else
                            <img src="{{ asset('img/default-avatar.png') }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                        @endif
                    </div>
                    <div class="pharmacy-info">
                        <div class="pharmacy-name">{{ $pharmacie->user->name }}</div>
                        @if($pharmacie->online)
                            <span class="badge bg-success">En ligne</span>
                        @else
                            <span class="badge bg-danger">Hors ligne</span>
                        @endif
                        @if($pharmacie->guard_time)
                            <div class="pharmacy-badge">De garde : {{ $pharmacie->guard_time }}</div>
                        @endif
                        @if($pharmacie->schedule)
                            <div class="pharmacy-hours">Horaires : {{ $pharmacie->schedule }}</div>
                        @endif
                        @if($pharmacie->insurance_name)
                            <div class="pharmacy-insurance">Assurance : {{ $pharmacie->insurance_name }}</div>
                        @endif
                        @if($pharmacie->geolocalisation && $pharmacie->geolocalisation->arrondissement)
                            <div class="pharmacy-location">
                                {{ $pharmacie->geolocalisation->arrondissement->name ?? '' }}
                                @if($pharmacie->geolocalisation->arrondissement->commune)
                                    , {{ $pharmacie->geolocalisation->arrondissement->commune->name }}
                                    @if($pharmacie->geolocalisation->arrondissement->commune->departement)
                                        , {{ $pharmacie->geolocalisation->arrondissement->commune->departement->name }}
                                    @endif
                                @endif
                        </div>
                        @endif
                        <a href="{{route('pharmacie.produits', $pharmacie->id)}}"> <button class="pharmacy-btn"><i class="fas fa-pills"></i>Voir les produits</button></a>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $pharmacies->links() }}</div>
@endif
</div>

@if($produits->count() == 0 && $pharmacies->count() == 0)
    <p>Aucun résultat trouvé pour "{{ $query }}"</p>
@endif


</section>
</div>
@endsection
