@extends('layouts.accueil')
@section('title', 'Commandes reçues')


@section('content')
<div class="container mt-4">
    <h3>Commandes en cours</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
               
            </tr>
        </thead>
        <tbody>
            @forelse($commandes as $cmd)
                <tr>
                    <td>{{ $cmd->order_date}}</td>
                    <td>{{ $cmd->client->user->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($cmd->produits as $prod)
                            <div style="margin-bottom: 1.5rem;">
                                <span style="font-weight: 500;">{{ $prod->name }}</span> <span class="badge bg-secondary">x{{ $prod->pivot->quantity }}</span>
                                @if($prod->prescription && $prod->pivot->prescription_file)
                                    <div style="
                                        background: linear-gradient(90deg, #e8f5e9 0%, #f1f8e9 100%);
                                        border: 1px solid #b2dfdb;
                                        border-radius: 10px;
                                        padding: 1rem;
                                        margin-top: 0.5rem;
                                        box-shadow: 0 2px 8px #0001;
                                        display: flex;
                                        align-items: flex-start;
                                        gap: 1rem;
                                    ">
                                        <div style="flex: 0 0 60px;">
                                            <i class="fas fa-file-medical fa-2x text-success"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-size: 1rem; font-weight: 600; color: #388e3c;">Ordonnance</div>
                                            <a href="{{ asset('storage/' . $prod->pivot->prescription_file) }}" download class="btn btn-success btn-sm mt-2">
                                                <i class="fas fa-download"></i> Télécharger
                                            </a>
                                            @php
                                                $ext = strtolower(pathinfo($prod->pivot->prescription_file, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            @endphp
                                            @if($isImage)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $prod->pivot->prescription_file) }}" alt="Ordonnance" style="max-width: 120px; border-radius: 6px; border: 1px solid #c8e6c9;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </td>
                    <td>
                        {{
                            $cmd->produits->sum(function($prod) use ($cmd) {
                                $prix = $prod->pharmacies->where('id', $cmd->pharmacie_id)->first()?->pivot->price ?? 0;
                                return $prod->pivot->quantity * $prix;
                            })
                        }} FCFA
                    </td>
                    <td>{{ $cmd->status_cmd ?? 'N/A' }}</td>
                    <td>
                        @if($cmd->status_cmd == 'En attente')
                            <form method="POST" action="{{ route('pharmacie.commande.confirmer', $cmd->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm">Confirmer</button>
                            </form>
                            <form method="POST" action="{{ route('pharmacie.commande.annuler', $cmd->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger btn-sm">Annuler</button>
                            </form>
                        @else
                            <em>Aucune action</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Aucune commande en cours.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $commandes->links() }}
    </div>
</div>
@endsection