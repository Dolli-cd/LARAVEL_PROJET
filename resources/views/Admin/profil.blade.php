@extends('layouts.accueil')
@section('title', 'Mon Profil Administrateur')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <!-- Profil admin (lecture seule) -->
            <div class="card shadow p-4">
                <div class="d-flex align-items-center mb-4">
                    <div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2.5rem; color: #4CAF50;">
                            <i class="fas fa-user-cog"></i>
                        </div>
                    </div>
                    <div class="ms-4">
                        <h2 class="mb-1">{{ $user->name ?? 'Nom non défini' }}</h2>
                        <p class="mb-1 text-muted">{{ $user->email }}</p>
                        <p class="mb-1 text-muted">{{ $user->address }}</p>
                        @if(!empty($user->phone))
                            <p class="mb-0"><i class="fas fa-phone"></i> {{ $user->phone }}</p>
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
    </div>
</div>

<!-- Modal Bootstrap pour modification -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('profilupdate') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editProfilModalLabel">Modifier mes informations</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
        <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
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
                    <label for="avatar" class="form-label">Photo de profil</label>
                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width: 80px; margin-top: 10px;">
                    @endif
                </div>

                @if($errors->password->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->password->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



          <!-- Champs nom, email, téléphone, adresse, etc. -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
