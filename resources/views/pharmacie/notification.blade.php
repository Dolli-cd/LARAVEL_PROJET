@extends('layouts.accueil')
@section('title', 'Notifications')

@section('content')
<div class="container dashboard-layout" style="display: flex; gap: 24px; margin-top: 32px; margin-bottom: 48px;">
    <section class="dashboard-content" style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Notifications</h5>
                        @if($notifications->count() > 0)
                            <div class="d-flex justify-content-end mb-2">
                                <!-- Bouton qui ouvre le modal de suppression de toutes les notifications -->
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAllNotifModal">
                                    Tout supprimer
                                </button>
                            </div>
                        @endif
                        <!-- Modal de confirmation pour tout supprimer -->
                        <div class="modal fade" id="deleteAllNotifModal" tabindex="-1" aria-labelledby="deleteAllNotifLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="deleteAllNotifLabel">Supprimer toutes les notifications</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                              </div>
                              <div class="modal-body">
                                Voulez-vous vraiment supprimer <b>toutes</b> les notifications ?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('notificationdeleteAll') }}" method="POST" style="display:inline;">
                                    @csrf
                    
                                    <button type="submit" class="btn btn-danger">Tout supprimer</button>
                                </form>
                              </div>
                            </div>
                          </div>
        </div>
                        <div class="mt-3">
                            @forelse ($notifications as $notification)
    @php
        $data = is_array($notification->data) ? $notification->data : (array) $notification->data;
        $reservation = null;
        $commande = null;
        if (isset($data['reservation_id'])) {
            $reservation = \App\Models\Reservation::find($data['reservation_id']);
        }
        if (isset($data['commande_id'])) {
            $commande = \App\Models\Commande::find($data['commande_id']);
        }
    @endphp
    <div class="card mb-2">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex:1;">
                    <p class="mb-1">{!! $data['message'] ?? '' !!}</p>
                    <small class="text-muted">
                        le {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                    </small>
                    @if(is_null($notification->read_at))
                        <span class="badge bg-success ms-2">Nouveau</span>
                    @else
                        <span class="badge bg-secondary ms-2">Ancien</span>
                    @endif
                    @if($reservation && $reservation->status === 'pending')
                        <div class="mt-2 d-flex gap-2">
                            <form method="POST" action="{{ route('pharmacie.reservation.valider', $data['reservation_id'] ?? 0) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                            </form>
                            <form method="POST" action="{{ route('pharmacie.reservation.annuler', $data['reservation_id'] ?? 0) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                            </form>
                            <a href="{{ route('pharmacie.reservations', $data['reservation_id'] ?? 0) }}" class="btn btn-primary btn-sm">
                                Voir la réservation
                            </a>
                        </div>
                    @endif
                    @if($commande)
                        <div class="mt-2">
                            Commande N°{{ $commande->id }} reçue.
                            @if($commande->status === 'pending')
                                <div class="mt-2 d-flex gap-2">
                                    <form method="POST" action="{{ route('pharmacie.commande.confirmer', $commande->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                                    </form>
                                    <form method="POST" action="{{ route('pharmacie.commande.annuler', $commande->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                    </form>
                                    <a href="{{ route('pharmacie.commandes', $commande->id) }}" class="btn btn-primary btn-sm">
                                        Voir la commande
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <!-- Bouton qui ouvre le modal de suppression individuelle -->
                <button type="button" class="btn btn-link text-danger p-0 ms-2" title="Supprimer"
                    data-bs-toggle="modal" data-bs-target="#deleteNotifModal-{{ $notification->id }}">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- Modal de confirmation individuelle -->
                <div class="modal fade" id="deleteNotifModal-{{ $notification->id }}" tabindex="-1" aria-labelledby="deleteNotifLabel-{{ $notification->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteNotifLabel-{{ $notification->id }}">Supprimer la notification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                      </div>
                      <div class="modal-body">
                        Voulez-vous vraiment supprimer cette notification ?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form action="{{ route('notificationdelete', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-center">Aucune notification pour l'instant.</p>
@endforelse
                            <div class="mt-3">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection