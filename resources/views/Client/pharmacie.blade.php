@extends('layouts.search')


@section('content')

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    
    <!-- Affichage des pharmacies-->
    @if(!empty($query) && $pharmacies->count() > 0)
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