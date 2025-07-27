@extends('layouts.accueil')
@section('title', 'Pharmacies proches de vous')

@section('content')
<div class="container mt-4">
    <h2>Pharmacies proches de vous</h2>
    <div class="row">
        @forelse($pharmacies as $pharmacie)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    {{-- Image pharmacie --}}
                    <div style="width: 100%; height: 160px; overflow: hidden; background: #f5f5f5;">
                        @if($pharmacie->user_avatar)
                            <img src="{{ asset('storage/' . $pharmacie->user_avatar) }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/default-avatar.png') }}" alt="Logo pharmacie" style="width: 100%; height: 100%; object-fit: contain; background: #f5f5f5;">
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-2">
                            <i class="fas fa-clinic-medical text-success me-2"></i>
                            {{ $pharmacie->user_name ?? '' }}
                        </h5>
                        <div class="mb-2 d-flex align-items-center">
                            <span class="me-2"><i class="fas fa-phone-alt text-primary"></i></span>{{$pharmacie->user_phone ?? '' }}
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <span class="me-2"><i class="fas fa-envelope text-primary"></i></span>
                            {{ $pharmacie->user_email ?? '' }}
                        </div>
                        <p class="mb-1">
                            <span class="badge bg-primary">
                                {{ number_format($pharmacie->distance, 2) }} km
                            </span>
                            @if($pharmacie->online)
                                <span class="badge bg-success ms-2">En ligne</span>
                            @else
                                <span class="badge bg-danger ms-2">Hors ligne</span>
                            @endif
                        </p>
                        @if($pharmacie->guard_time)
                            <div class="mb-1"><i class="fas fa-shield-alt me-1"></i>De garde : {{ $pharmacie->guard_time }}</div>
                        @endif
                        @if($pharmacie->schedule)
                            <div class="mb-1"><i class="fas fa-clock me-1"></i>Horaires : {{ $pharmacie->schedule }}</div>
                        @endif
                        @if($pharmacie->insurance_name)
                            <div class="mb-1"><i class="fas fa-umbrella me-1"></i>Assurance : {{ $pharmacie->insurance_name }}</div>
                        @endif
                         <a href="{{ route('pharmacie.produits', $pharmacie->id) }}" class="btn btn-outline-success btn-sm mt-2">Voir la pharmacie</a> 
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">Aucune pharmacie trouvée à proximité.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
