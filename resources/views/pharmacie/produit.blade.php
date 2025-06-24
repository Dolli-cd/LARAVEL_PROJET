<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit Gestion - PharmFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
        }
        .navbar {
            background: linear-gradient(90deg, #007bff, #0056b3);
            padding: 10px 20px;
        }
        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .navbar-brand:hover {
            color: #e9ecef !important;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .search-bar {
            transition: all 0.3s ease;
        }
        .search-bar:focus-within {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 10px;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
            .search-bar {
                margin-bottom: 10px;
            }
            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('wel') }}">PharmFind <i class="fas fa-pills"></i></a>
            <form id="logout" action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-danger">Déconnexion <i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <form id="searchForm" class="search-bar w-50">
                <div class="input-group">
                    <input type="text" name="search_etu" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <a href="{{ route('ajouter_produit') }}" class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> Ajouter un produit</a>
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="deleteSelected" class="btn btn-danger btn-sm" disabled><i class="fas fa-trash"></i> Supprimer la sélection</button>
        </div>

        <div id="tableproduit">
            @include('pharmacie.search', ['produits' => $produits])
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Recherche en temps réel
        let debounceTimer;
        $('#searchInput').on('input', function () {
            clearTimeout(debounceTimer);
            const query = $(this).val().trim();

            debounceTimer = setTimeout(() => {
                $.ajax({
                    url: "{{ route('search') }}",
                    type: 'GET',
                    data: { search: query },
                    success: function (data) {
                        $('#tableproduit').html(data);
                    },
                    error: function () {
                        alert("Erreur lors de la recherche.");
                    }
                });
            }, 300); // Ajusté à 300ms pour une meilleure réactivité
        });

        // Gestion des checkboxes
        $(document).ready(function () {
            $('#selectAll').on('change', function () {
                $('.selectproduit').prop('checked', $(this).is(':checked'));
                toggleDeleteButton();
            });

            $(document).on('change', '.selectproduit', function () {
                $('#selectAll').prop('checked', $('.selectproduit:checked').length === $('.selectproduit').length);
                toggleDeleteButton();
            });

            function toggleDeleteButton() {
                const selectedCount = $('.selectproduit:checked').length;
                $('#deleteSelected').prop('disabled', selectedCount === 0);
            }

            // Suppression multiple
            $('#deleteSelected').on('click', function () {
                const ids = $('.selectproduit:checked').map(function () {
                    return $(this).val();
                }).get();

                if (ids.length === 0) return;

                if (confirm("Voulez-vous vraiment supprimer les produits sélectionnés ?")) {
                    $.ajax({
                        url: "{{ route('produit.deleteMultiple') }}",
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            $('#tableproduit').html(data);
                            $('#selectAll').prop('checked', false);
                            toggleDeleteButton();
                            alert('Les produits ont bien été supprimés.');
                        },
                        error: function () {
                            alert('Erreur lors de la suppression.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>