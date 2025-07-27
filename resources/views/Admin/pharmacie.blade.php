@extends('layouts.accueil')

@section('title', 'Liste des Pharmacies')

@section('content')
    <div class="container-fluid p-4">
        <!-- Bouton pour ouvrir la modale -->
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPharmaModal">
                <i class="fas fa-plus me-2"></i>Ajouter une pharmacie
            </button>
        </div>

        <!-- Tableau des pharmacies -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des Pharmacies</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Horaire</th>
                                <th>Heure de garde</th>
                                <th>Assurance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pharmacies as $pharmacie)
                                <tr>
                                    <td>{{ $pharmacie->user->name }}</td>
                                    <td>{{ $pharmacie->user->email }}</td>
                                    <td>{{ $pharmacie->user->phone }}</td>
                                    <td>{{ $pharmacie->user->address }}</td>
                                    <td>{{ $pharmacie->schedule }}</td>
                                    <td>{{ $pharmacie->guard_time }}</td>
                                    <td>{{ $pharmacie->insurance_name }}</td>
                                    <td>
                                        <a href="{{route('admin.pharmacie.produits', $pharmacie->id)}}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(isset($pharmacie->user->id))
                                            <form id="delete-pharma-{{ $pharmacie->user->id }}" action="{{ route('userdelete', ['id' => $pharmacie->user->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect" value="pharmaliste">
                                                <input type="hidden" name="message" value="Pharmacie supprimée avec succès">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeletePharma({{ $pharmacie->user->id }}, '{{ $pharmacie->user->name }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucune pharmacie enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addPharmaModal" tabindex="-1" aria-labelledby="addPharmaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="addPharmaLabel"><i class="fas fa-pills me-2"></i>Nouvelle pharmacie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('registerpharma') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label"><i class="fas fa-hospital"></i> Nom</label>
                                <input type="text" id="name" name="name" autocomplete="organization" class="form-control @error('name') is-invalid @enderror" placeholder="Pharmacie Centrale" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" name="email" autocomplete="email" class="form-control @error('email') is-invalid @enderror" placeholder="contact@pharmacie.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label"><i class="fas fa-phone"></i> Téléphone</label>
                                <input type="text" id="phone" name="phone" autocomplete="tel" class="form-control @error('phone') is-invalid @enderror" placeholder="+229 XX XX XX XX" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="departement_id" class="form-label"><i class="fas fa-map"></i> Département</label>
                                <select id="departement_id" name="departement_id" class="form-control" required>
                                    <option value="">Choisissez...</option>
                                    @foreach(\App\Models\Departement::all() as $departement)
                                        <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>{{ $departement->name }}</option>
                                    @endforeach
                                </select>
                                @error('departement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="schedule" class="form-label"><i class="fas fa-clock"></i> Horaire</label>
                                <input type="text" id="schedule" name="schedule" class="form-control @error('schedule') is-invalid @enderror" placeholder="Lundi-Vendredi: 8h-18h" value="{{ old('schedule') }}">
                                @error('schedule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="commune_id" class="form-label"><i class="fas fa-city"></i> Commune</label>
                                <select id="commune_id" name="commune_id" class="form-control" required>
                                    <option value="">Choisissez d'abord un département</option>
                                </select>
                                @error('commune_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="insurance_name" class="form-label"><i class="fas fa-umbrella"></i> Assurance</label>
                                <input type="text" id="insurance_name" name="insurance_name" class="form-control @error('insurance_name') is-invalid @enderror" placeholder="CNSS, Mutuelle Santé Bénin" value="{{ old('insurance_name') }}">
                                @error('insurance_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="arrondissement_id" class="form-label"><i class="fas fa-map-pin"></i> Arrondissement</label>
                                <select id="arrondissement_id" name="arrondissement_id" class="form-control" required>
                                    <option value="">Choisissez d'abord une commune</option>
                                </select>
                                @error('arrondissement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                                <input type="text" id="address" name="address" autocomplete="street-address" class="form-control @error('address') is-invalid @enderror" placeholder="Rue des Martyrs, Cotonou" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 caractères" minlength="6" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" autocomplete="off" value="{{ old('latitude') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" autocomplete="off" value="{{ old('longitude') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guard_time" class="form-label"><i class="fas fa-shield-alt"></i> Heure de garde</label>
                                <input type="text" id="guard_time" name="guard_time" class="form-control @error('guard_time') is-invalid @enderror" placeholder="Weekends de 20h à 8h" value="{{ old('guard_time') }}">
                                @error('guard_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirmation mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Répétez le mot de passe" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ old('online', $pharmacie->online ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="online">
                                    Pharmacie en ligne
                                </label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-outline-secondary" id="btn-geolocaliser">
                                    <i class="fas fa-map-marker-alt"></i> Géolocaliser
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                var addPharmaModal = new bootstrap.Modal(document.getElementById('addPharmaModal'));
                addPharmaModal.show();
            @endif
        });
        $(document).ready(function() {
            console.log('Script Nominatim chargé');
            $('#departement_id').on('change', function() {
                var departementId = $(this).val();
                $('#commune_id').html('<option value="">Chargement...</option>');
                $('#arrondissement_id').html('<option value="">Choisissez d\'abord une commune</option>');
                if (departementId) {
                    $.get('/api/communes/' + departementId, function(data) {
                        var options = '<option value="">Choisissez...</option>';
                        data.forEach(function(commune) {
                            options += '<option value="' + commune.id + '">' + commune.name + '</option>';
                        });
                        $('#commune_id').html(options);
                    });
                } else {
                    $('#commune_id').html('<option value="">Choisissez d\'abord un département</option>');
                }
            });
            $('#commune_id').on('change', function() {
                var communeId = $(this).val();
                $('#arrondissement_id').html('<option value="">Chargement...</option>');
                if (communeId) {
                    $.get('/api/arrondissements/' + communeId, function(data) {
                        var options = '<option value="">Choisissez...</option>';
                        data.forEach(function(arr) {
                            options += '<option value="' + arr.id + '">' + arr.name + '</option>';
                        });
                        $('#arrondissement_id').html(options);
                    });
                } else {
                    $('#arrondissement_id').html('<option value="">Choisissez d\'abord une commune</option>');
                }
            });

            // --- Script d'auto-remplissage latitude/longitude ---
            function updateLatLng() {
                var $modal = $('#addPharmaModal');
                var address = $modal.find('#address').val().trim();
                var arrondissement = $modal.find('#arrondissement_id option:selected').text().trim();
                var commune = $modal.find('#commune_id option:selected').text().trim();
                var departement = $modal.find('#departement_id option:selected').text().trim();

                // Filtrer les valeurs non pertinentes
                var ignoreValues = [
                    '', 'Chargement...', 'Choisissez...', "Choisissez d'abord une commune", "Choisissez d'abord un département"
                ];
                var parts = [address, arrondissement, commune, departement].filter(function(val, idx, arr) {
                    return ignoreValues.indexOf(val) === -1 && arr.indexOf(val) === idx;
                });

                var fullAddress = parts.join(', ');

                console.log('Adresse envoyée à Nominatim :', fullAddress);

                if (fullAddress.length < 5) return;

                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(fullAddress), {
                    headers: {
                        'User-Agent': '/1.0 (abriellebandeira@gmail.com)'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Réponse Nominatim :', data);
                    if (data && data.length > 0) {
                        $modal.find('#latitude').val(data[0].lat);
                        $modal.find('#longitude').val(data[0].lon);
                    }
                })
                .catch(function(error) {
                    console.error('Erreur Nominatim :', error);
                });
            }

            $('#addPharmaModal').on('shown.bs.modal', function () {
                console.log('Modale ouverte');
                var $modal = $(this);
                $modal.find('#address').on('blur change', function() {
                    console.log('Adresse modifiée');
                    updateLatLng();
                });
                $modal.find('#arrondissement_id').on('change', function() {
                    console.log('Arrondissement changé');
                    updateLatLng();
                });
                $modal.find('#commune_id').on('change', function() {
                    console.log('Commune changée');
                    updateLatLng();
                });
                // NE PAS appeler updateLatLng sur departement_id !
            });

            $('#btn-geolocaliser').on('click', function() {
                updateLatLng();
            });
        });
   
        function confirmDeletePharma(id, name) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Voulez-vous vraiment supprimer la pharmacie : " + name + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-pharma-' + id).submit();
                }
            });
        }
    </script>
    @endpush
@endsection