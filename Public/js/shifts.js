// ========================================
// GESTION DES SHIFTS
// ========================================

document.addEventListener('DOMContentLoaded', function() {
  console.log('Page Shifts chargée');
  
  // Attendre que les données soient chargées
  setTimeout(() => {
    console.log('Données shifts:', window.shiftsData ? window.shiftsData.length : 0, 'shifts');
    
    // Initialiser les données si vides
    if (!window.shiftsData) {
      window.shiftsData = [];
    }
    
    // Afficher les shifts
    afficherShifts();
    mettreAJourStats();
  }, 200);
  
  // Variables
  let currentPage = 1;
  const itemsPerPage = 10;
  
  // Éléments du DOM
  const modalDetails = document.getElementById('modalDetailsShift');
  const modalSuggestions = document.getElementById('modalSuggestions');
  const shiftsTableBody = document.getElementById('shiftsTableBody');
  
  // Pagination
  const btnPrevPage = document.getElementById('btnPrevPageShift');
  const btnNextPage = document.getElementById('btnNextPageShift');
  const paginationPages = document.getElementById('paginationPagesShift');
  const paginationStart = document.getElementById('paginationStartShift');
  const paginationEnd = document.getElementById('paginationEndShift');
  const paginationTotal = document.getElementById('paginationTotalShift');
  
  // Mettre à jour les statistiques
  function mettreAJourStats() {
    const total = window.shiftsData.length;
    const actifs = window.shiftsData.filter(s => s.statut === 'actif').length;
    const planifies = window.shiftsData.filter(s => s.statut === 'planifie').length;
    const termines = window.shiftsData.filter(s => s.statut === 'termine').length;
    
    document.getElementById('totalShifts').textContent = total;
    document.getElementById('shiftsActifs').textContent = actifs;
    document.getElementById('shiftsPlanifies').textContent = planifies;
    document.getElementById('shiftsTermines').textContent = termines;
  }
  
  // Voir les détails d'un shift
  window.voirDetailsShift = function(shiftId) {
    const shift = window.shiftsData.find(s => s.id === shiftId);
    if (!shift) return;
    
    document.getElementById('detailsDate').textContent = new Date(shift.date).toLocaleDateString('fr-FR');
    document.getElementById('detailsHoraire').textContent = `${shift.heureDebut} - ${shift.heureFin}`;
    document.getElementById('detailsBus').textContent = `Bus #${shift.busNumero}`;
    document.getElementById('detailsStatut').innerHTML = `
      <span class="status-badge status-badge--${shift.statut}">
        ${shift.statut.charAt(0).toUpperCase() + shift.statut.slice(1)}
      </span>
    `;
    
    document.getElementById('detailsChauffeur').textContent = shift.chauffeur.nom;
    document.getElementById('detailsControleur').textContent = shift.controleur.nom;
    document.getElementById('detailsReceveur').textContent = shift.receveur.nom;
    
    // Gérer le bouton annuler
    const btnAnnuler = document.getElementById('btnAnnulerShift');
    btnAnnuler.onclick = function() {
      if (confirm('Voulez-vous vraiment annuler ce shift ?')) {
        shift.statut = 'annule';
        modalDetails.classList.remove('active');
        afficherShifts();
        mettreAJourStats();
      }
    };
    
    // Gérer le bouton envoyer notification
    const btnEnvoyer = document.getElementById('btnEnvoyerNotif');
    btnEnvoyer.onclick = function() {
      envoyerNotification(shift);
    };
    
    modalDetails.classList.add('active');
    feather.replace();
  };
  
  // Envoyer une notification
  function envoyerNotification(shift) {
    const message = `
📅 NOUVELLE AFFECTATION

Bus: #${shift.busNumero}
Date: ${new Date(shift.date).toLocaleDateString('fr-FR')}
Horaire: ${shift.heureDebut} - ${shift.heureFin}

👥 Équipe:
🚗 Chauffeur: ${shift.chauffeur.nom}
📋 Contrôleur: ${shift.controleur.nom}
💰 Receveur: ${shift.receveur.nom}

Merci de confirmer votre présence.
    `;
    
    alert(`Notification envoyée avec succès !\n\n${message}`);
    modalDetails.classList.remove('active');
  }
  
  // Suggérer des shifts
  document.getElementById('btnSuggererShifts').addEventListener('click', function() {
    genererSuggestions();
    modalSuggestions.classList.add('active');
    feather.replace();
  });
  
  // Générer des suggestions de shifts
  function genererSuggestions() {
    const container = document.getElementById('suggestionsContainer');
    const suggestions = [];
    
    // Générer des suggestions pour les 7 prochains jours
    const today = new Date();
    for (let i = 1; i <= 7; i++) {
      const date = new Date(today);
      date.setDate(date.getDate() + i);
      const dateStr = date.toISOString().split('T')[0];
      
      // Vérifier si des shifts existent déjà pour cette date
      const shiftsExistants = window.shiftsData.filter(s => s.date === dateStr);
      
      if (shiftsExistants.length === 0) {
        // Suggérer des shifts pour les bus actifs
        if (window.busData) {
          window.busData.filter(b => b.statut === 'actif').slice(0, 3).forEach(bus => {
            suggestions.push({
              date: dateStr,
              dateFormatted: date.toLocaleDateString('fr-FR'),
              bus: bus,
              heureDebut: '06:00',
              heureFin: '14:00'
            });
          });
        }
      }
    }
    
    if (suggestions.length === 0) {
      container.innerHTML = '<p style="text-align:center; color:var(--muted); padding:40px;">Aucune suggestion disponible. Tous les shifts sont déjà planifiés.</p>';
      return;
    }
    
    container.innerHTML = suggestions.map((sugg, index) => `
      <div class="suggestion-item">
        <div class="suggestion-item__header">
          <div>
            <strong>${sugg.dateFormatted}</strong>
            <span class="suggestion-item__time">${sugg.heureDebut} - ${sugg.heureFin}</span>
          </div>
          <span class="suggestion-item__bus">Bus #${sugg.bus.numero}</span>
        </div>
        <div class="suggestion-item__info">
          <i data-feather="map-pin"></i>
          <span>${sugg.bus.ligne ? 'Ligne ' + sugg.bus.ligne : 'Non affecté'}</span>
        </div>
      </div>
    `).join('');
    
    feather.replace();
  }
  
  // Appliquer les suggestions
  document.getElementById('btnAppliquerSuggestions').addEventListener('click', function() {
    alert('Fonctionnalité en cours de développement.\nLes suggestions seront converties en shifts planifiés.');
    modalSuggestions.classList.remove('active');
  });
  
  // Supprimer un shift
  window.supprimerShift = function(shiftId) {
    if (confirm('Voulez-vous vraiment supprimer ce shift ?')) {
      window.shiftsData = window.shiftsData.filter(s => s.id !== shiftId);
      afficherShifts();
      mettreAJourStats();
    }
  };
  
  // Afficher les shifts
  function afficherShifts(data = window.shiftsData) {
    if (!data || data.length === 0) {
      shiftsTableBody.innerHTML = `
        <tr>
          <td colspan="8" style="text-align:center; padding:40px;">
            <p style="color:#6b7280;">Aucun shift enregistré. Créez votre premier shift depuis la page Équipe de bord.</p>
          </td>
        </tr>
      `;
      return;
    }
    
    // Trier par date décroissante
    data.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    const totalItems = data.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    const currentData = data.slice(startIndex, endIndex);
    
    shiftsTableBody.innerHTML = currentData.map(shift => `
      <tr>
        <td><strong>${new Date(shift.date).toLocaleDateString('fr-FR')}</strong></td>
        <td>${shift.heureDebut} - ${shift.heureFin}</td>
        <td>Bus #${shift.busNumero}</td>
        <td>${shift.chauffeur.nom}</td>
        <td>${shift.controleur.nom}</td>
        <td>${shift.receveur.nom}</td>
        <td>
          <span class="status-badge status-badge--${shift.statut}">
            ${shift.statut.charAt(0).toUpperCase() + shift.statut.slice(1)}
          </span>
        </td>
        <td>
          <div class="action-buttons">
            <button class="btn-icon btn-icon--edit" onclick="voirDetailsShift(${shift.id})" title="Voir détails">
              <i data-feather="eye"></i>
            </button>
            <button class="btn-icon btn-icon--delete" onclick="supprimerShift(${shift.id})" title="Supprimer">
              <i data-feather="trash-2"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join('');
    
    paginationStart.textContent = startIndex + 1;
    paginationEnd.textContent = endIndex;
    paginationTotal.textContent = totalItems;
    
    paginationPages.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
      const pageBtn = document.createElement('button');
      pageBtn.className = 'page-number' + (i === currentPage ? ' active' : '');
      pageBtn.textContent = i;
      pageBtn.addEventListener('click', () => {
        currentPage = i;
        afficherShifts(data);
      });
      paginationPages.appendChild(pageBtn);
    }
    
    btnPrevPage.disabled = currentPage === 1;
    btnNextPage.disabled = currentPage === totalPages;
    
    feather.replace();
  }
  
  // Pagination
  btnPrevPage.addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      afficherShifts();
    }
  });
  
  btnNextPage.addEventListener('click', () => {
    const totalPages = Math.ceil(window.shiftsData.length / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      afficherShifts();
    }
  });
  
  // Filtres
  document.getElementById('btnFiltrerShift').addEventListener('click', function() {
    const statutFilter = document.getElementById('filterStatutShift').value;
    const dateFilter = document.getElementById('filterDate').value;
    const searchTerm = document.getElementById('searchShift').value.toLowerCase();
    
    let filteredData = window.shiftsData;
    
    if (statutFilter) {
      filteredData = filteredData.filter(s => s.statut === statutFilter);
    }
    
    if (dateFilter) {
      filteredData = filteredData.filter(s => s.date === dateFilter);
    }
    
    if (searchTerm) {
      filteredData = filteredData.filter(s => 
        s.busNumero.toString().includes(searchTerm) ||
        s.chauffeur.nom.toLowerCase().includes(searchTerm) ||
        s.controleur.nom.toLowerCase().includes(searchTerm) ||
        s.receveur.nom.toLowerCase().includes(searchTerm)
      );
    }
    
    currentPage = 1;
    afficherShifts(filteredData);
  });
  
  // Fermer les modals
  document.getElementById('btnCloseModalDetails').addEventListener('click', function() {
    modalDetails.classList.remove('active');
  });
  
  document.getElementById('btnCloseModalSuggestions').addEventListener('click', function() {
    modalSuggestions.classList.remove('active');
  });
  
  document.getElementById('btnFermerSuggestions').addEventListener('click', function() {
    modalSuggestions.classList.remove('active');
  });
  
  modalDetails.querySelector('.modal__overlay').addEventListener('click', function() {
    modalDetails.classList.remove('active');
  });
  
  modalSuggestions.querySelector('.modal__overlay').addEventListener('click', function() {
    modalSuggestions.classList.remove('active');
  });
  
  // Initialisation
  feather.replace();
});
