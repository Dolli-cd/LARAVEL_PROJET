@extends('layouts.accueil')
@section('title', 'Paiement Réservation')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Paiement de la réservation N°{{ $reservation->id }}</h3>
            <p>Montant total de la réservation : <b>{{ number_format($montant * 2, 0, ',', ' ') }} FCFA</b></p>
            <p>Montant à payer (acompte 50%) : <b>{{ number_format($montant, 0, ',', ' ') }} FCFA</b></p>
            <form action="{{ route('reservation.paiement.effectuer', $reservation->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Payer maintenant</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection 