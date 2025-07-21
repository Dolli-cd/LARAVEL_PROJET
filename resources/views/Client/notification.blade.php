@extends('layouts.accueil')

@section('title', 'Mes Notifications')

@section('content')

<div class="container dashboard-layout" style="display: flex; gap: 24px; margin-top: 32px; margin-bottom: 48px;">
   
    <section class="dashboard-content" style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 32px;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Notifications</h5>
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
                                    @if($reservation)
                                        {!! $data['message'] ?? 'Réservation #' . $reservation->id !!}
                                        <br>
                                        <a href="{{ route('client.reserver', $reservation->id) }}" class="btn btn-primary btn-sm mt-2">Voir la réservation</a>
                                        @if($reservation->status === 'confirmed')
                                            <form action="{{ route('reservation.paiement', $reservation->id) }}" method="GET" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm mt-2">Payer 50%</button>
                                            </form>
                                        @endif
                                    @elseif($commande)
                                        {!! $data['message'] ?? 'Commande N°' . $commande->id !!}
                                        <br>
                                        <a href="{{ route('client.commander', $commande->id) }}" class="btn btn-primary btn-sm mt-2">Voir la commande</a>
                                        @if($commande->status === 'confirmed')
                                            <form action="{{ route('commande.paiement', $commande->id) }}" method="GET" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm mt-2">Payer</button>
                                            </form>
                                        @endif
                                    @else
                                        Notification sans réservation ni commande liée.
                                    @endif
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            le {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                        </small>
                                        @if(is_null($notification->read_at))
                                            <span class="badge bg-success ms-2">Nouveau</span>
                                        @else
                                            <span class="badge bg-secondary ms-2">Ancien</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Aucune notification.</p>
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