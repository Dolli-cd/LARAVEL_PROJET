@extends('layouts.accueil')

@section('title', 'Mon Profil - PharmFind')

@section('content')
<div class="container" style="max-width: 700px; margin: 40px auto;">
    <div class="card shadow p-4">
        <div class="d-flex align-items-center mb-4">
            <div>
                @php
                $pharmacie = App\Models\Pharmacie::where('user_id', Auth::id())->first();
                @endphp
                @if(!empty($user->avatar))
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 90px; height: 90px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2.5rem; color: #4CAF50;">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                @endif
            </div>
            <div class="ms-4">
                <h2 class="mb-1">{{ $user->name ?? 'Nom de la pharmacie non défini' }}</h2>
                <p class="mb-1 text-muted">{{ $user->email }}</p>
                <p class="mb-1 text-muted">{{ $user->address }}</p>
                @if(!empty($user->phone))
                    <p class="mb-0"><i class="fas fa-phone"></i> {{ $user->phone }}</p>
                @endif
                @if($pharmacie)
                    <p class="mb-1 text-muted"><i class="fas fa-clock"></i> Horaires: {{ $pharmacie->schedule }}</p>
                    <p class="mb-1 text-muted"><i class="fas fa-shield-alt"></i> Garde: {{ $pharmacie->guard_time }}</p>
                    <p class="mb-1 text-muted"><i class="fas fa-building"></i> Assurance: {{ $pharmacie->insurance_name }}</p>
                    <p class="mb-0">
                        <span class="badge {{ $pharmacie->online ? 'bg-success' : 'bg-secondary' }}">
                            <i class="fas {{ $pharmacie->online ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $pharmacie->online ? 'En ligne' : 'Hors ligne' }}
                        </span>
                    </p>
                @endif
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfilModal">
                    Modifier mes informations
                </button>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfilModalLabel">Modifier le profil de la pharmacie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profilupdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations de base -->
                    <h6 class="mb-3 text-primary">Informations de base</h6>
                    
                    <!-- Nom pharmacie -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la pharmacie</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <!-- Téléphone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}" required>
                    </div>

                    <!-- Avatar -->
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Logo de la pharmacie</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 80px; margin-top: 10px;">
                        @endif
                    </div>

                    <!-- Informations spécifiques à la pharmacie -->
                    @if($pharmacie)
                        <hr class="my-4">
                        <h6 class="mb-3 text-primary">Informations de la pharmacie</h6>
                        
                        <!-- Horaires -->
                        <div class="mb-3">
                            <label for="schedule" class="form-label">Horaires d'ouverture</label>
                            <input type="text" class="form-control" id="schedule" name="schedule" value="{{ old('schedule', $pharmacie->schedule) }}" placeholder="Ex: 8h-18h du lundi au vendredi">
                        </div>

                        <!-- Garde -->
                        <div class="mb-3">
                            <label for="guard_time" class="form-label">Service de garde</label>
                            <input type="text" class="form-control" id="guard_time" name="guard_time" value="{{ old('guard_time', $pharmacie->guard_time) }}" placeholder="Ex: 24h/24, 7j/7">
                        </div>

                        <!-- Assurance -->
                        <div class="mb-3">
                            <label for="insurance_name" class="form-label">Nom de l'assurance</label>
                            <input type="text" class="form-control" id="insurance_name" name="insurance_name" value="{{ old('insurance_name', $pharmacie->insurance_name) }}" placeholder="Nom de votre assurance">
                        </div>

                       
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
