document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('#sidebarToggle');

    // Vérifier que sidebar et toggleButton existent AVANT de les utiliser
    if (sidebar && toggleButton) {
        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 991 && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    // Fonction pour faire disparaître les alertes après 3 secondes
    function autoHideAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 200);
            }, 3000);
        });
    }

    // Fonction pour fermer manuellement une alerte
    function closeAlert(button) {
        const alert = button.closest('.alert');
        if (alert) {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.remove();
            }, 200);
        }
    }

    // Exécuter l'auto-hide des alertes
    autoHideAlerts();
    
    // Ajouter les événements de clic pour les boutons de fermeture
    const closeButtons = document.querySelectorAll('.btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this);
        });
    });

    const searchForm = document.querySelector('form[action*="search"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const input = searchForm.querySelector('input[name="search"]');
            if (input && input.value.trim() === '') {
                e.preventDefault();
            }
        });
    }
});

