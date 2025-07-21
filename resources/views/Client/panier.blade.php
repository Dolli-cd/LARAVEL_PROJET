@extends('layouts.accueil')

@section('content')
        @if(empty($panier))
        <div class="text-center" style="background: #f8f9fa; border: none;">
                <h4 class="mb-3">Votre panier est vide</h4>
                <p>Ajoutez des produits à votre panier pour les retrouver ici.</p>
                <img src="{{ asset('img/cart.png') }}" alt="Panier vide" style="width: 120px; margin: 24px auto; display: block;">
                <a href="{{ route('client.search') }}" class="btn btn-success mt-3">Continuer mes achats</a>
            </div>
        @else
        <div class="container py-3">
            <div class="row g-3">
                    @php $total = 0; @endphp
                    @foreach($panier as $key => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        // On va chercher le produit pour savoir s'il nécessite une ordonnance
                        $produitModel = \App\Models\Produit::find($item['id']);
                        @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100 border-success-subtle p-2 d-flex flex-column justify-content-between" style="min-width: 0;">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/' . $item['file']) }}" alt="{{ $item['name'] }}" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
                                <div>
                                    <div class="fw-bold small">{{ $item['name'] }}</div>
                                    <div class="text-muted small"><i class="fas fa-clinic-medical"></i> {{ $item['pharmacie'] }}</div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <form action="{{ route('panier.update', $key) }}" method="POST" class="d-flex align-items-center gap-2 mb-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm w-50" style="max-width: 70px;">
                                    <button class="btn btn-outline-success btn-sm px-2 py-1" title="Mettre à jour"><i class="fas fa-sync"></i></button>
                                </form>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="small">Prix: <span class="fw-semibold">{{ number_format($item['price'], 0, ',', ' ') }} FCFA</span></span>
                                <span class="small">Sous-total: <span class="fw-semibold text-success">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span></span>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-2">
                                @if(isset($item['pharmacie_id']))
                                    <!-- Commander bouton/modal -->
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#commandeModal-{{ $key }}">
                                        <i class="fas fa-check-circle"></i> Commander
                                    </button>
                                    <!-- Réserver bouton/modal -->
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal-{{ $key }}">
                                        <i class="fas fa-clock"></i> Réserver
                                    </button>
                                @else
                                    <div class="alert alert-warning">Produit mal ajouté, veuillez le retirer du panier et le rajouter.</div>
                                @endif
                                <form action="{{ route('panier.supprimer', $key) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm px-2 py-1" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Commander -->
                    <div class="modal fade" id="commandeModal-{{ $key }}" tabindex="-1" aria-labelledby="commandeModalLabel-{{ $key }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commandeModalLabel-{{ $key }}">Commander {{ $item['name'] }} chez {{ $item['pharmacie'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('client.commander') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="produit_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="pharmacie_id" value="{{ $item['pharmacie_id'] }}">
                                        <div class="mb-3">
                                            <label>Produit</label>
                                            <input type="text" class="form-control" value="{{ $item['name'] }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label>Quantité</label>
                                            <input type="number" class="form-control" name="quantity" min="1" value="{{ $item['quantity'] }}" required>
                                        </div>
                                        @if($produitModel && $produitModel->prescription)
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

                    <!-- Modal Réserver -->
                    <div class="modal fade" id="reservationModal-{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel-{{ $key }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservationModalLabel-{{ $key }}">Réserver {{ $item['name'] }} chez {{ $item['pharmacie'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('client.reserver') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="produit_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="pharmacie_id" value="{{ $item['pharmacie_id'] }}">
                                        <div class="mb-3">
                                            <label>Produit</label>
                                            <input type="text" class="form-control" value="{{ $item['name'] }}" readonly>
        </div>
                                        <div class="mb-3">
                                            <label>Quantité</label>
                                            <input type="number" class="form-control" name="quantity" min="1" value="{{ $item['quantity'] }}" required>
        </div>
                                        @if($produitModel && $produitModel->prescription)
                                            <div class="mb-3">
                                                <label>Ordonnance (obligatoire)</label>
                                                <input type="file" class="form-control" name="prescription_file" accept="image/*,application/pdf" required>
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
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <div class="card p-3 shadow-sm" style="min-width: 250px;">
                        <div class="fw-bold text-end">Total : <span class="text-success">{{ number_format($total, 0, ',', ' ') }} FCFA</span></div>
                    </div>
                </div>
    </div>
</div>
    @endif
@endsection
