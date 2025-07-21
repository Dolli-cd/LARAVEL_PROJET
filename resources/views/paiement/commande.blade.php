@extends('layouts.accueil')
@section('title', 'Paiement Commande')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-4">Paiement de la commande N°{{ $commande->id }}</h3>
            <p>Montant total à payer : <b>{{ number_format($montant, 0, ',', ' ') }} FCFA</b></p>
            <form action="{{ route('client.commander', $commande->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Payer maintenant</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection 