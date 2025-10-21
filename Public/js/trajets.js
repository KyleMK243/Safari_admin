// ========================================
// GESTION DES TRAJETS
// ========================================

document.addEventListener('DOMContentLoaded', function() {
  console.log('Page Trajets chargée');
  
  // Attendre que les données soient chargées
  setTimeout(() => {
    console.log('Données trajets:', window.trajetsData ? window.trajetsData.length : 0, 'trajets');
    
    if (!window.trajetsData || window.trajetsData.length === 0) {
      console.error('Aucune donnée trajet chargée !');
    }
    
    // Afficher les trajets
    afficherTrajets();
  }, 200);
  
  // Variables
  let currentPage = 1;
  const itemsPerPage = 5;
  let trajetEnEdition = null;
  let arretsCount = 0;
  let chiftesCount = 0;
  
  // Éléments du DOM
  const modalTrajet = document.getElementById('modalTrajet');
  const modalDetails = document.getElementById('modalDetailsTrajet');
  const modalTitle = document.getElementById('modalTrajetTitle');
  const btnNouveauTrajet = document.getElementById('btnNouveauTrajet');
  const btnCloseModalTrajet = document.getElementById('btnCloseModalTrajet');
  const btnCloseModalDetails = document.getElementById('btnCloseModalDetails');
  const btnAnnulerTrajet = document.getElementById('btnAnnulerTrajet');
  const trajetsTableBody = document.getElementById('trajetsTableBody');
  const formTrajet = document.getElementById('formTrajet');
  
  // Pagination
  const btnPrevPage = document.getElementById('btnPrevPageTrajet');
  const btnNextPage = document.getElementById('btnNextPageTrajet');
  const paginationPages = document.getElementById('paginationPagesTrajet');
  const paginationStart = document.getElementById('paginationStartTrajet');
  const paginationEnd = document.getElementById('paginationEndTrajet');
  const paginationTotal = document.getElementById('paginationTotalTrajet');
  
  // Ouvrir modal pour nouveau trajet
  function ouvrirModalTrajet() {
    trajetEnEdition = null;
    modalTitle.textContent = 'Nouveau Trajet';
    formTrajet.reset();
    
    // Réinitialiser les conteneurs
    document.getElementById('arretsContainer').innerHTML = '';
    document.getElementById('chiftesContainer').innerHTML = '';
    arretsCount = 0;
    chiftesCount = 0;
    
    // Ajouter un arrêt par défaut
    ajouterArret();
    ajouterArret();
    
    modalTrajet.classList.add('active');
    feather.replace();
  }
  
  // Ajouter un arrêt
  function ajouterArret() {
    arretsCount++;
    const container = document.getElementById('arretsContainer');
    const arretDiv = document.createElement('div');
    arretDiv.className = 'form-row';
    arretDiv.dataset.arretId = arretsCount;
    arretDiv.innerHTML = `
      <div class="form-row__content">
        <div class="form-group">
          <label>Nom de l'arrêt *</label>
          <input type="text" name="arret_nom_${arretsCount}" required placeholder="Ex: Gare Centrale">
        </div>
        <div class="form-group">
          <label>Distance depuis le départ (km) *</label>
          <input type="number" name="arret_distance_${arretsCount}" step="0.1" required placeholder="Ex: 5.2">
        </div>
      </div>
      <button type="button" class="btn-remove" onclick="supprimerArret(${arretsCount})">
        <i data-feather="x"></i>
      </button>
    `;
    container.appendChild(arretDiv);
    feather.replace();
  }
  
  // Supprimer un arrêt
  window.supprimerArret = function(id) {
    const arret = document.querySelector(`[data-arret-id="${id}"]`);
    if (arret) {
      arret.remove();
    }
  };
  
  // Ajouter un point de chifte
  function ajouterChifte() {
    chiftesCount++;
    const container = document.getElementById('chiftesContainer');
    const chifteDiv = document.createElement('div');
    chifteDiv.className = 'form-row';
    chifteDiv.dataset.chifteId = chiftesCount;
    chifteDiv.innerHTML = `
      <div class="form-row__content">
        <div class="form-group">
          <label>Nom du point de chifte *</label>
          <input type="text" name="chifte_nom_${chiftesCount}" required placeholder="Ex: Point de relève Lemba">
        </div>
        <div class="form-group">
          <label>Distance depuis le départ (km) *</label>
          <input type="number" name="chifte_distance_${chiftesCount}" step="0.1" required placeholder="Ex: 12.5">
        </div>
      </div>
      <button type="button" class="btn-remove" onclick="supprimerChifte(${chiftesCount})">
        <i data-feather="x"></i>
      </button>
    `;
    container.appendChild(chifteDiv);
    feather.replace();
  }
  
  // Supprimer un point de chifte
  window.supprimerChifte = function(id) {
    const chifte = document.querySelector(`[data-chifte-id="${id}"]`);
    if (chifte) {
      chifte.remove();
    }
  };
  
  // Voir les détails d'un trajet
  window.voirDetailsTrajet = function(trajetId) {
    const trajet = window.trajetsData.find(t => t.id === trajetId);
    if (!trajet) return;
    
    document.getElementById('detailsTrajetTitle').textContent = trajet.nom;
    document.getElementById('detailsNom').textContent = trajet.nom;
    document.getElementById('detailsDistance').textContent = trajet.distanceTotale + ' km';
    document.getElementById('detailsStatut').innerHTML = `
      <span class="status-badge status-badge--${trajet.statut}">
        ${trajet.statut.charAt(0).toUpperCase() + trajet.statut.slice(1)}
      </span>
    `;
    
    // Afficher les arrêts
    const arretsHtml = trajet.arrets.map((arret, index) => `
      <div class="trajet-item">
        <div class="trajet-item__icon">
          <i data-feather="map-pin"></i>
        </div>
        <div class="trajet-item__content">
          <span class="trajet-item__nom">${arret.nom}</span>
          <span class="trajet-item__distance">${arret.distance} km depuis le départ</span>
        </div>
      </div>
    `).join('');
    document.getElementById('detailsArrets').innerHTML = arretsHtml;
    
    // Afficher les points de chifte
    const chiftesHtml = trajet.pointsChifte.map((chifte, index) => `
      <div class="trajet-item trajet-item--chifte">
        <div class="trajet-item__icon">
          <i data-feather="refresh-cw"></i>
        </div>
        <div class="trajet-item__content">
          <span class="trajet-item__nom">${chifte.nom}</span>
          <span class="trajet-item__distance">${chifte.distance} km depuis le départ</span>
        </div>
      </div>
    `).join('');
    document.getElementById('detailsChiftes').innerHTML = chiftesHtml;
    
    modalDetails.classList.add('active');
    feather.replace();
  };
  
  // Modifier un trajet
  window.modifierTrajet = function(trajetId) {
    trajetEnEdition = window.trajetsData.find(t => t.id === trajetId);
    if (!trajetEnEdition) return;
    
    modalTitle.textContent = 'Modifier le Trajet';
    
    // Remplir les champs de base
    document.getElementById('trajetNom').value = trajetEnEdition.nom;
    document.getElementById('trajetDistance').value = trajetEnEdition.distanceTotale;
    document.getElementById('trajetStatut').value = trajetEnEdition.statut;
    
    // Réinitialiser les conteneurs
    document.getElementById('arretsContainer').innerHTML = '';
    document.getElementById('chiftesContainer').innerHTML = '';
    arretsCount = 0;
    chiftesCount = 0;
    
    // Ajouter les arrêts existants
    trajetEnEdition.arrets.forEach(arret => {
      arretsCount++;
      const container = document.getElementById('arretsContainer');
      const arretDiv = document.createElement('div');
      arretDiv.className = 'form-row';
      arretDiv.dataset.arretId = arretsCount;
      arretDiv.innerHTML = `
        <div class="form-row__content">
          <div class="form-group">
            <label>Nom de l'arrêt *</label>
            <input type="text" name="arret_nom_${arretsCount}" required value="${arret.nom}">
          </div>
          <div class="form-group">
            <label>Distance depuis le départ (km) *</label>
            <input type="number" name="arret_distance_${arretsCount}" step="0.1" required value="${arret.distance}">
          </div>
        </div>
        <button type="button" class="btn-remove" onclick="supprimerArret(${arretsCount})">
          <i data-feather="x"></i>
        </button>
      `;
      container.appendChild(arretDiv);
    });
    
    // Ajouter les points de chifte existants
    trajetEnEdition.pointsChifte.forEach(chifte => {
      chiftesCount++;
      const container = document.getElementById('chiftesContainer');
      const chifteDiv = document.createElement('div');
      chifteDiv.className = 'form-row';
      chifteDiv.dataset.chifteId = chiftesCount;
      chifteDiv.innerHTML = `
        <div class="form-row__content">
          <div class="form-group">
            <label>Nom du point de chifte *</label>
            <input type="text" name="chifte_nom_${chiftesCount}" required value="${chifte.nom}">
          </div>
          <div class="form-group">
            <label>Distance depuis le départ (km) *</label>
            <input type="number" name="chifte_distance_${chiftesCount}" step="0.1" required value="${chifte.distance}">
          </div>
        </div>
        <button type="button" class="btn-remove" onclick="supprimerChifte(${chiftesCount})">
          <i data-feather="x"></i>
        </button>
      `;
      container.appendChild(chifteDiv);
    });
    
    modalTrajet.classList.add('active');
    feather.replace();
  };
  
  // Supprimer un trajet
  window.supprimerTrajet = function(trajetId) {
    const trajet = window.trajetsData.find(t => t.id === trajetId);
    if (trajet && confirm(`Voulez-vous vraiment supprimer le trajet "${trajet.nom}" ?`)) {
      window.trajetsData = window.trajetsData.filter(t => t.id !== trajetId);
      afficherTrajets();
    }
  };
  
  // Fermer les modals
  function fermerModalTrajet() {
    modalTrajet.classList.remove('active');
    formTrajet.reset();
    trajetEnEdition = null;
  }
  
  function fermerModalDetails() {
    modalDetails.classList.remove('active');
  }
  
  // Événements
  btnNouveauTrajet.addEventListener('click', ouvrirModalTrajet);
  btnCloseModalTrajet.addEventListener('click', fermerModalTrajet);
  btnCloseModalDetails.addEventListener('click', fermerModalDetails);
  btnAnnulerTrajet.addEventListener('click', fermerModalTrajet);
  modalTrajet.querySelector('.modal__overlay').addEventListener('click', fermerModalTrajet);
  modalDetails.querySelector('.modal__overlay').addEventListener('click', fermerModalDetails);
  
  document.getElementById('btnAjouterArret').addEventListener('click', ajouterArret);
  document.getElementById('btnAjouterChifte').addEventListener('click', ajouterChifte);
  
  // Soumission du formulaire
  formTrajet.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(formTrajet);
    
    // Récupérer les arrêts
    const arrets = [];
    for (let i = 1; i <= arretsCount; i++) {
      const nom = formData.get(`arret_nom_${i}`);
      const distance = formData.get(`arret_distance_${i}`);
      if (nom && distance) {
        arrets.push({ nom, distance: parseFloat(distance) });
      }
    }
    
    // Récupérer les points de chifte
    const pointsChifte = [];
    for (let i = 1; i <= chiftesCount; i++) {
      const nom = formData.get(`chifte_nom_${i}`);
      const distance = formData.get(`chifte_distance_${i}`);
      if (nom && distance) {
        pointsChifte.push({ nom, distance: parseFloat(distance) });
      }
    }
    
    // Trier par distance
    arrets.sort((a, b) => a.distance - b.distance);
    pointsChifte.sort((a, b) => a.distance - b.distance);
    
    const trajetInfo = {
      nom: formData.get('trajetNom'),
      distanceTotale: parseFloat(formData.get('trajetDistance')),
      statut: formData.get('trajetStatut'),
      arrets: arrets,
      pointsChifte: pointsChifte
    };
    
    if (trajetEnEdition) {
      // Modification
      Object.assign(trajetEnEdition, trajetInfo);
      console.log('Trajet modifié:', trajetEnEdition);
    } else {
      // Création
      const newTrajet = {
        id: Date.now(),
        ...trajetInfo
      };
      window.trajetsData.unshift(newTrajet);
      console.log('Nouveau trajet créé:', newTrajet);
    }
    
    fermerModalTrajet();
    afficherTrajets();
  });
  
  // Afficher les trajets
  function afficherTrajets(data = window.trajetsData) {
    if (!data || data.length === 0) {
      trajetsTableBody.innerHTML = `
        <tr>
          <td colspan="6" style="text-align:center; padding:40px;">
            <p style="color:#6b7280;">Aucun trajet disponible.</p>
          </td>
        </tr>
      `;
      return;
    }
    
    const totalItems = data.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    const currentData = data.slice(startIndex, endIndex);
    
    trajetsTableBody.innerHTML = currentData.map(trajet => `
      <tr>
        <td><strong>${trajet.nom}</strong></td>
        <td>${trajet.distanceTotale} km</td>
        <td>${trajet.arrets.length} arrêts</td>
        <td>${trajet.pointsChifte.length} points</td>
        <td>
          <span class="status-badge status-badge--${trajet.statut}">
            ${trajet.statut.charAt(0).toUpperCase() + trajet.statut.slice(1)}
          </span>
        </td>
        <td>
          <div class="action-buttons">
            <button class="btn-icon btn-icon--edit" onclick="voirDetailsTrajet(${trajet.id})" title="Voir détails">
              <i data-feather="eye"></i>
            </button>
            <button class="btn-icon btn-icon--assign" onclick="modifierTrajet(${trajet.id})" title="Modifier">
              <i data-feather="edit-2"></i>
            </button>
            <button class="btn-icon btn-icon--delete" onclick="supprimerTrajet(${trajet.id})" title="Supprimer">
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
        afficherTrajets(data);
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
      afficherTrajets();
    }
  });
  
  btnNextPage.addEventListener('click', () => {
    const totalPages = Math.ceil(window.trajetsData.length / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      afficherTrajets();
    }
  });
  
  // Filtres
  document.getElementById('btnFiltrerTrajet').addEventListener('click', function() {
    const statutFilter = document.getElementById('filterStatutTrajet').value;
    const searchTerm = document.getElementById('searchTrajet').value.toLowerCase();
    
    let filteredData = window.trajetsData;
    
    if (statutFilter) {
      filteredData = filteredData.filter(t => t.statut === statutFilter);
    }
    
    if (searchTerm) {
      filteredData = filteredData.filter(t => 
        t.nom.toLowerCase().includes(searchTerm)
      );
    }
    
    currentPage = 1;
    afficherTrajets(filteredData);
  });
  
  // Initialisation
  feather.replace();
});
