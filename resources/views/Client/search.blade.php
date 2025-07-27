@extends('layouts.accueil')

@section('content')
<style>
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
    .section-header { text-align: center; margin-bottom: 20px; }
    .section-title { font-size: 24px; font-weight: bold; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    .product-card, .pharmacy-card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff; }
    .product-image, .pharmacy-image { width: 100%; height: 160px; overflow: hidden; }
    .product-image img, .pharmacy-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-info, .pharmacy-info { padding: 15px; }
    .product-name, .pharmacy-name { font-size: 18px; font-weight: bold; }
    .product-prescription, .pharmacy-hours, .pharmacy-insurance, .pharmacy-location { font-size: 14px; color: #555; margin: 5px 0; }
    .product-location { font-size: 12px; color: #777; }
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
    .bg-success { background: #28a745; color: #fff; }
    .bg-danger { background: #dc3545; color: #fff; }
    .product-footer { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-top: 10px; }
    .product-price { font-weight: bold; }
    .product-btn, .pharmacy-btn, .form-btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
    .product-btn { background: #ffc107; color: #000; }
    .btn-success { background: #28a745; color: #fff; }
    .btn-info { background: #17a2b8; color: #fff; }
    .btn-secondary { background: #6c757d; color: #fff; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;opacity:0;transition: opacity 0.3s ease }
    .modal.active { display: flex; justify-content: center; align-items: center;opacity:1 }
    .modal-content { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%; position: relative; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .modal-title { font-size: 18px; font-weight: bold; }
    .close-btn { background: none; border: none; font-size: 20px; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
    .form-group input[type="text"], .form-group input[type="number"], .form-group input[type="file"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    .form-group input[readonly] { background: #e9ecef; }
    .mt-4 { margin-top: 20px; }
    .text-muted { color: #6c757d; font-size: 14px; }
</style>

<div class="container">
            
    <!-- Affichage des produits -->
    @if(($type === 'produit' || !$type) && $produits->count() > 0)
        <div class="section-header">
            <h2 class="section-title">Nos Produits </h2>
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
                            <div>
                                @foreach($produit->pharmacies as $phIndex => $pharmacie)
                                    @php $pivot = $pharmacie->pivot; $modalKey = $produit->id . '-' . $pharmacie->id . '-' . $pIndex . '-' . $phIndex; @endphp
                                    <div style="margin-bottom: 5px;">
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
                                        <div class="product-footer">
                                            <div class="product-price">
                                                {{ number_format($pivot->price ?? 0, 0, ',', ' ') }} FCFA
                                            </div>
                                            @if($pivot->status === 'available')
                                                @auth
                                                    <button type="button" class="btn-success form-btn" onclick="toggleModal('commandeModal-{{ $modalKey }}')">
                                                        <i class="fas fa-check-circle"></i> Commander
                                                    </button>
                                                    <div class="modal" id="commandeModal-{{ $modalKey }}">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Commander {{ $produit->name }} chez {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}</h5>
                                                                <button type="button" class="close-btn" onclick="toggleModal('commandeModal-{{ $modalKey }}')">&times;</button>
                                                            </div>
                                                            <form action="{{ route('client.commander') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Produit</label>
                                                                    <input type="text" value="{{ $produit->name }}" readonly>
                                                                </div>
                                                                <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                                                <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                                <div class="form-group">
                                                                    <label>Quantité</label>
                                                                    <input type="number" name="quantity" min="1" required>
                                                                </div>
                                                                @if($produit->prescription)
                                                                    <div class="form-group">
                                                                        <label>Ordonnance (obligatoire)</label>
                                                                        <input type="file" name="prescription_file" accept="image/*,application/pdf" required>
                                                                    </div>
                                                                @endif
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn-success form-btn">Valider la commande</button>
                                                                    <button type="button" class="btn-secondary form-btn" onclick="toggleModal('commandeModal-{{ $modalKey }}')">Annuler</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-info form-btn" onclick="toggleModal('reservationModal-{{ $modalKey }}')">
                                                        <i class="fas fa-clock"></i> Réserver
                                                    </button>
                                                    <div class="modal" id="reservationModal-{{ $modalKey }}">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Réserver {{ $produit->name }} chez {{ $pharmacie->user->name ?? 'Pharmacie inconnue' }}</h5>
                                                                <button type="button" class="close-btn" onclick="toggleModal('reservationModal-{{ $modalKey }}')">&times;</button>
                                                            </div>
                                                            <form action="{{ route('client.reserver') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Produit</label>
                                                                    <input type="text" value="{{ $produit->name }}" readonly>
                                                                    <input type="hidden" name="nom" value="{{ $produit->name }}">
                                                                </div>
                                                                <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                                                <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                                <div class="form-group">
                                                                    <label>Quantité</label>
                                                                    <input type="number" name="quantity" min="1" required>
                                                                </div>
                                                                @if($produit->prescription)
                                                                    <div class="form-group">
                                                                        <label>Ordonnance (obligatoire)</label>
                                                                        <input type="file" name="prescription_file" accept="image/*,application/pdf" required>
                                                                    </div>
                                                                @endif
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn-info form-btn">Valider la réservation</button>
                                                                    <button type="button" class="btn-secondary form-btn" onclick="toggleModal('reservationModal-{{ $modalKey }}')">Annuler</button>
                                                                </div>
                                                            </form>
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
                                                    <a href="{{ route('login') }}" class="btn-success form-btn"style=" text-decoration:none;">
                                                        <i class="fas fa-check-circle"></i> Commander
                                                    </a>
                                                    <a href="{{ route('login') }}" class="btn-info form-btn" style="text-decoration:none;">
                                                        <i class="fas fa-clock"></i> Réserver
                                                    </a>
                                                    <a href="{{ route('login') }}" class="product-btn" style="text-decoration:none;">
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
        <div class="mt-4">{{ $produits->appends(request()->except('produits_page'))->links() }}</div>
    @endif

    <!-- Affichage des pharmacies -->
    @if(($type === 'pharmacie' || !$type) && $pharmacies->count() > 0)
        <div class="section-header">
            <h2 class="section-title">Pharmacies trouvées</h2>
        </div>
        <div class="products-grid">
            @foreach($pharmacies as $pharmacie)
                <div class="pharmacy-card">
                    <div class="pharmacy-image" style="width: 100%; aspect-ratio:1/1;  overflow: hidden; background: #f5f5f5; display:flex, align-items: center; justify-content: center">
                        @if($pharmacie->user && $pharmacie->user->avatar)
                            <img src="{{ asset('storage/' . $pharmacie->user->avatar) }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/default-avatar.png') }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: contain; background:#f5f5f5;">
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
                        <a href="{{ route('pharmacie.produits', $pharmacie->id) }}"><button class="pharmacy-btn"><i class="fas fa-pills"></i> Voir les produits</button></a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4"> {{ $pharmacies->appends(request()->except('pharmacies_page'))->links() }}</div>
    @endif

    @if($produits->count() == 0 && $pharmacies->count() == 0)
        <p>Aucun résultat trouvé pour "{{ $query }}"</p>
    @endif
</div>

<script>
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    const isActive = modal.classList.contains('active');
    // Close all modals
    document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
    // Toggle the clicked modal
    if (!isActive) {
        modal.classList.add('active');
    }
}
</script>
@endsection