@extends('layouts.app')
@section('title', 'Dashboard Client')
@section('page-title')
    <h1>Votre Espace, {{ $data['name'] }} !</h1>
@endsection
@section('content')
<div class="container">
@section('sidebar')
<div class="p-3">
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-home-alt me-2"></i>Home
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('liste_produit') ? 'active' : '' }}">
        <i class="fas fa-calendar-check me-2"></i>Réservations
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart me-2"></i>Commandes
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-history me-2"></i>Historique
    </a>
    <a href="{{ route('wel') }}" class="nav-link {{ request()->routeIs('wel') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i>Notifications
    </a>
    <a href="{{ route('logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
@endsection


    {{-- Si tu as des commandes liées au client, tu peux les afficher ici --}}
    {{-- 
    <p>Total commandes : {{ $data['total_commandes'] }}</p>
    @if($data['derniere_commande'])
        <p>Dernière commande : {{ $data['derniere_commande']->created_at->format('d/m/Y') }}</p>
    @else
        <p>Aucune commande enregistrée.</p>
    @endif
    --}}
</div>
@endsection
