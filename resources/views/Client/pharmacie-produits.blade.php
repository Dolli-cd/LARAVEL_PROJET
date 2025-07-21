@extends('layouts.search')

@section('content')
<div class="container dashboard-layout" style="display: flex; gap: 24px; margin-top: 32px; margin-bottom: 48px;">
    <section class="dashboard-content" style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Informations de la pharmacie -->
        <div class="card mb-4 border-primary-subtle">
            <div class="card-body">
                <h3 class="card-title text-primary-emphasis">
                    <i class="fas fa-clinic-medical"></i> {{ $pharmacie->user->name }}
                </h3>
                <div style="margin-bottom: 8px;">
                    @if($pharmacie->online)
                        <span class="badge bg-success" style="font-size:13px;vertical-align:middle;">En ligne</span>
                    @else
                        <span class="badge bg-danger" style="font-size:13px;vertical-align:middle;">Hors ligne</span>
                    @endif
                </div>
                <div class="row">
                    
                    <div class="col-md-6">
                        <p class="mb-1">
                            <i class="fas fa-phone"></i> <strong>Téléphone:</strong> {{ $pharmacie->user->phone }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($pharmacie->insurance_name)
                            <p class="mb-1">
                                <i class="fas fa-shield-alt"></i> <strong>Assurance:</strong> {{ $pharmacie->insurance_name }}
                            </p>
                        @endif
                        @if($pharmacie->schedule)
                            <p class="mb-1">
                                <i class="fas fa-clock"></i> <strong>Horaire:</strong> {{ $pharmacie->schedule }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        @if($produits->count() > 0)
            <h4 class="mb-3">
                <i class="fas fa-pills"></i> Produits disponibles ({{ $produits->total() }})
            </h4>
            <div class="row g-4">
                @foreach($produits as $produit)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-success-subtle">
                            <div class="card-body">
                                @if($produit->file)
                                    <img src="{{ asset('storage/' . $produit->file) }}" alt="{{ $produit->name }}" class="product-img mb-3" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 8px;">
                                @endif
                                <h5 class="card-title text-success-emphasis">
                                    <i class="fas fa-pills"></i> {{ $produit->name }}
                                </h5>
                                <p class="card-text mb-1">
                                    <strong>Type:</strong> {{ $produit->type }}
                                </p>
                                @if($produit->categorie)
                                    <p class="card-text mb-1">
                                        <strong>Catégorie:</strong> {{ $produit->categorie->name }}
                                    </p>
                                @endif
                                <p class="card-text mb-1">
                                    <strong>Besoin d'ordonnance:</strong> {{ $produit->prescription ? 'Oui' : 'Non' }}
                                </p>
                                
                                @php $pivot = $produit->pivot; @endphp
                                @if($pivot)
                                    <p class="card-text mb-1">
                                        <strong>Prix:</strong> {{ number_format($pivot->price, 0, ',', ' ') }} FCFA
                                    </p>
                                    
                                    @if($pivot->status === 'available')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($pivot->status === 'unavailable')
                                        <span class="badge bg-danger">Indisponible</span>
                                    @endif
                                    @if(!empty($pivot->comment))
                                        <div class="text-muted mt-2">
                                            <i class="fas fa-comment-dots"></i> {{ $pivot->comment }}
                                        </div>
                                    @endif
                                @endif

                                <hr>

                                <!-- Actions -->
                                @if($pivot && $pivot->status === 'available')
                                    @auth
                                        <div class="btn-group-vertical w-100" role="group">
                                            <button type="button" class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#commandeModal-{{ $produit->id }}">
                                                <i class="fas fa-check-circle"></i> Commander
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#reservationModal-{{ $produit->id }}">
                                                <i class="fas fa-clock"></i> Réserver
                                            </button>
                                            <form action="{{ route('panier.ajouter') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $produit->id }}">
                                                <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                                <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="btn-group-vertical w-100" role="group">
                                            <a href="{{ route('login') }}" class="btn btn-success btn-sm mb-1">
                                                <i class="fas fa-check-circle"></i> Commander
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-info btn-sm mb-1">
                                                <i class="fas fa-clock"></i> Réserver
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                                            </a>
                                        </div>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Commander Modal -->
                    <div class="modal fade" id="commandeModal-{{ $produit->id }}" tabindex="-1" aria-labelledby="commandeModalLabel-{{ $produit->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commandeModalLabel-{{ $produit->id }}">Commander {{ $produit->name }}</h5>
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
                                            <input type="number" class="form-control" name="quantity" min="1" max="{{ $pivot->quantity ?? 1 }}" required>
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

                    <!-- Réserver Modal -->
                    <div class="modal fade" id="reservationModal-{{ $produit->id }}" tabindex="-1" aria-labelledby="reservationModalLabel-{{ $produit->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservationModalLabel-{{ $produit->id }}">Réserver {{ $produit->name }}</h5>
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
                                            <label for="quantity-res-{{ $produit->id }}" class="form-label">Quantité</label>
                                            <input type="number" class="form-control" id="quantity-res-{{ $produit->id }}" name="quantity" min="1" max="{{ $pivot->quantity ?? 1 }}" required>
                                        </div>
                                        @if($produit->prescription)
                                            <div class="mb-3">
                                                <label for="ordonnance-res-{{ $produit->id }}" class="form-label">Ordonnance (obligatoire)</label>
                                                <input type="file" class="form-control" id="prescription_file-{{ $produit->id }}" name="prescription_file" accept="image/*,application/pdf" required>
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
                @endforeach
            </div>
            <div class="mt-4">{{ $produits->links() }}</div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Aucun produit disponible dans cette pharmacie pour le moment.
            </div>
        @endif

        <!-- Bouton retour -->
        <div class="mt-4">
            @if($query)
                <a href="{{ route('client.search') }}?search={{ urlencode($query) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux résultats de recherche
                </a>
            @else
                <a href="{{ route('wel') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            @endif
        </div>

    </section>
</div>
@endsection 