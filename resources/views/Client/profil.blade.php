@extends('layouts.accueil')

@section('title', 'Mon Profil - PharmFind')

@section('content')
<div class="container" style="max-width: 700px; margin: 40px auto;">
    <div class="card shadow p-4">
        <div class="d-flex align-items-center mb-4">
            <div>
                @php

                $client =App\Models\Client::where('user_id', Auth::id())->first();
                @endphp
                @if(!empty($user->avatar))
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 90px; height: 90px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2.5rem; color: #4CAF50;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="ms-4">
                <h2 class="mb-1">{{ $user->name ?? 'Nom non défini' }}</h2>
                <p class="mb-1 text-muted">{{ $user->email }}</p>
                <h2 class="mb-1">{{ $client->user->birth_date }}</h2>
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

<div class="container" style="max-width: 700px; margin: 40px auto;">
   

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <!-- Formulaire modification profil -->
<form action="{{ route('profilupdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- Nom -->
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

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
</form>


           
        </div>
    </div>
</div>

@endsection