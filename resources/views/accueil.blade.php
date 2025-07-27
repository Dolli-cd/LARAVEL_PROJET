    @extends('layouts.accueil')
   @section('title','accueil')
   @section('content')
    <section class="hero" >
        <div data-aos="fade-up" class="container">
            <h1>Trouvez votre pharmacie,<br>en un instant.</h1>
            <p>Localisez une pharmacie, vérifiez vos médicaments
et réservez en toute simplicité où que vous soyez.</p>
            <div data-aos="fade-right" class="hero-buttons">
                <a href="{{ route('client.search', ['type' => 'pharmacie']) }}" class="btn btn-yellow">Trouver une pharmacie</a>
                
                <a href="{{ route('client.search', ['type' => 'produit']) }}" class="btn btn-outline" style="background: white;"><span>Chercher un médicament</span><span class="arrow"></span></a>
            </div>
        </div>
    </section>

    <!-- Popular Medications -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (!Auth::check() ||  Auth::user()->role==='client')

    <section class="section" >
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Prenez soin de vous</h2>
            </div>
            <div class="position-relative carrousel-section my-4">
                <div class="carrousel-scroll d-flex flex-nowrap overflow-auto" style="scroll-behavior: smooth;">
                @forelse($produits as $produit)
                    <div class="carrousel-card  flex-shrink-0" data-aos="fade-up" data-aos-delay="{{$loop->index *50}}" style="width: 220px; margin-right:16px;">
                        <div class="product-image">
                        @if($produit->file)
                       <img src="{{ asset('storage/' . $produit->file) }}" alt="{{ $produit->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                         @else
                        <img src="{{ asset('img/default-product.png') }}" alt="Produit" style="width: 100%; height: 100%; object-fit: contain;">
                        @endif
                        </div>
                        <div class="product-info">
                            <div class="product-pharmacy" >
                                @if($produit->pharmacies->count())
                                    {{ $produit->pharmacies->first()->user->name ?? 'Pharmacie inconnue' }}
                                @else
                                    Pharmacie inconnue
                                @endif
                            </div>
                            <div class="product-name">{{ $produit->name }}</div>
                            <div class="product-location">
                                @if($produit->pharmacies->count())
                                    {{ $produit->pharmacies->first()->user->address ?? '' }}
                                @endif
                            </div>
                            <div class="product-prescription">
                                {{ $produit->prescription ? 'Ordonnance requise' : 'Sans ordonnance' }}
                            </div>
                            <div class="product-footer">
                                <div class="product-price">
                                    @if($produit->pharmacies->count())
                                        {{ number_format($produit->pharmacies->first()->pivot->price ?? 0, 0, ',', ' ') }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="product-status">
                                    @if($produit->pharmacies->count())
                                        @php
                                            $status = strtolower($produit->pharmacies->first()->pivot->status_prod ?? 'indisponible');
                                            $isIndisponible = $status === 'indisponible';
                                        @endphp
                                        <span class="badge {{ $isIndisponible ? 'bg-danger bg-opacity-25 text-danger' : 'bg-success bg-opacity-25 text-success' }} product-status">
                                            {{ ucfirst($status) }}
                                        </span>
                                    @else
                                        Indisponible
                                    @endif
                                </div>
                            </div>
                        </div>
                        @foreach($produit->pharmacies as $pharmacie)
                            <form action="{{ route('panier.ajouter') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $produit->id }}">
                                <input type="hidden" name="pharmacie_id" value="{{ $pharmacie->id }}">
                                <button type="submit" class="product-btn">
                                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </form>
                        @endforeach
                    </div>
                @empty
                    <div class="alert alert-warning">Aucun médicament trouvé.</div>
                @endforelse
            </div>
            @if($produits->count() > 8)
                <button class="carrousel-arrow carrousel-arrow-right btn btn-light position-absolute top-50 end-0 translate-middle-y" style="z-index:2;">
                    <i class="fas fa-chevron-right fa-lg"></i>
                </button>
                <button class="carrousel-arrow carrousel-arrow-left btn btn-light position-absolute top-50 start-0 translate-middle-y" style="z-index:2; display:none;">
                    <i class="fas fa-chevron-left fa-lg"></i>
                </button>
            @endif
        </div>
    </section>         
    <!-- Product Showcase (Promo ou Produit Vedette) -->
  

        </div>
    </section>
    <!-- Pharmacies proches -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Pharmacies proches</h2>
            </div>
            <div class="position-relative carrousel-section">
                <div class="carrousel-scroll d-flex flex-nowrap overflow-auto" style="scroll-behavior: smooth;">
                    @foreach($pharmacies as $pharmacie)
                        <div class="pharmacy-card flex-shrink-0" data-aos="fade-down" data-aos-delay="{{$loop->index *50}}" style="width: 270px; margin-right: 16px;">
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
                             <a href="{{route('pharmacie.produits', $pharmacie->id)}}"> <button class="pharmacy-btn">Visiter →</button></a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($pharmacies->count() > 4)
                    <button class="carrousel-arrow carrousel-arrow-right btn btn-light position-absolute top-50 end-0 translate-middle-y" style="z-index:2;">
                        <i class="fas fa-chevron-right fa-lg"></i>
                    </button>
                    <button class="carrousel-arrow carrousel-arrow-left btn btn-light position-absolute top-50 start-0 translate-middle-y" style="z-index:2; display:none;">
                        <i class="fas fa-chevron-left fa-lg"></i>
                    </button>
                @endif
            </div>
        </div>
    </section>
   @endif
   <footer class="footer">
    <div class=" product-footer">
        <div style="margin-bottom: 10px; margin-top: 10px;">
            <span class="logo" style="display: flex; flex-direction: column; align-items: center;">
                <i class="fas fa-pills" style="font-size: 30px; margin-bottom: 5px;"></i>
                <span style="font-size: 1.2rem; font-weight: 500;">PharmaFind</span>              
            </span>
        </div>
        <a href="/qui-sommes-nous" style="font-size: 1rem; font-weight: 500; margin-bottom: 5px; color: #fff; text-decoration: none;">Qui sommes-nous?</a>
        <a href="tel:+2290199017260" style="color: inherit; text-decoration: none;">
            <div class="contact-item" style="font-size: 1rem; margin-bottom: 5px;">
                <span>📞</span>
                <span>+229 01 99 01 72 60</span>
            </div>
        </a>
        <a href="malito:contactpharmaFind.com" style="color:inherit; text-decoration:none;"><div style="font-size: 1rem;">
            <span>📧</span>
            <span>contact@pharmaFind.com</span>
        </div> </a>
    </div>
</footer>
    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const geoBtn = document.getElementById('geo-btn');
    if (geoBtn) {
        geoBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Voir les pharmacies proches de vous ?',
                text: "Pour obtenir les pharmacies les plus proches, autorisez l'application à utiliser votre position exacte.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Utiliser la position exacte',
                cancelButtonText: 'Pas maintenant',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        window.location.href = `/pharmacies/proximite?lat=${lat}&lng=${lng}`;
                    }, function(error) {
                        Swal.fire('Erreur', 'Impossible d\'obtenir la position.', 'error');
                        // Optionnel : afficher le formulaire de recherche par zone
                        const formZone = document.getElementById('formulaire-zone');
                        if (formZone) formZone.style.display = 'block';
                    });
                } else {
                    // Optionnel : afficher le formulaire de recherche par zone
                    const formZone = document.getElementById('formulaire-zone');
                    if (formZone) formZone.style.display = 'block';
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.carrousel-section').forEach(function(section) {
        const scrollContainer = section.querySelector('.carrousel-scroll');
        const btnRight = section.querySelector('.carrousel-arrow-right');
        const btnLeft = section.querySelector('.carrousel-arrow-left');
        const card = section.querySelector('.carrousel-card');
        const scrollStep = card ? card.offsetWidth + 16 : 300; // 16 = margin-right

        if(btnRight) {
            btnRight.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: scrollStep, behavior: 'smooth' });
                if(btnLeft) btnLeft.style.display = 'block';
            });
        }
        if(btnLeft) {
            btnLeft.addEventListener('click', function() {
                scrollContainer.scrollBy({ left: -scrollStep, behavior: 'smooth' });
                setTimeout(function() {
                    if(scrollContainer.scrollLeft <= 0 && btnLeft) {
                        btnLeft.style.display = 'none';
                    }
                }, 300);
            });
            btnLeft.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
