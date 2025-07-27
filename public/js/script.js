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

  // Suggestion de recherche (autocomplétion)
  const searchInput = document.querySelector('input[name="search"]');
  const suggestionsBox = document.getElementById('suggestions');
  const typeInput = document.querySelector('input[name="type"]');
  let type = typeInput ? typeInput.value : '';

  if (searchInput && suggestionsBox) {
    searchInput.addEventListener('input', function() {
      const query = this.value.trim();
      if (query.length > 1) {
        fetch(`/suggestions?search=${encodeURIComponent(query)}${type ? `&type=${encodeURIComponent(type)}` : ''}`)
          .then(response => response.json())
          .then(data => {
            let html = '';
            data.forEach(item => {
              if(item.type === 'produit') {
                html += `<li class="list-group-item list-group-item-action suggestion-item d-flex align-items-center" style="cursor:pointer;" data-value="${item.name}">
                  <img src="${item.file}" alt="Produit" style="width:32px;height:32px;object-fit:cover;border-radius:4px;margin-right:10px;">
                  <span>${item.name}${item.price ? ' - ' + item.price + ' FCFA' : ''}</span>
                </li>`;
              } else if(item.type === 'pharmacie') {
                html += `<li class="list-group-item list-group-item-action suggestion-item d-flex align-items-center" style="cursor:pointer;" data-value="${item.name.split('(')[0]}">
                  <img src="${item.avatar}" alt="Pharmacie" style="width:32px;height:32px;object-fit:cover;border-radius:50%;margin-right:10px;">
                  <span>${item.name}</span>
                </li>`;
              }
            });
            suggestionsBox.innerHTML = html;
            suggestionsBox.style.display = data.length ? 'block' : 'none';
          });
      } else {
        // On vide et on cache la liste si le champ est vide ou trop court
        suggestionsBox.innerHTML = '';
        suggestionsBox.style.display = 'none';
      }
    });

    // Remplir le champ quand on clique sur une suggestion
    suggestionsBox.addEventListener('click', function(e) {
      // Trouve le <li> parent même si on clique sur l'image ou le span
      let li = e.target.closest('.suggestion-item');
      if (li) {
        // Récupère le texte du <span> à l'intérieur du <li>
        const value = li.getAttribute('data-value') ;
        searchInput.value = value;
        suggestionsBox.innerHTML = '';
        suggestionsBox.style.display = 'none';
      }
    });

    // Cacher la box si on clique ailleurs
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.style.display = 'none';
      }
    });
  }
});

