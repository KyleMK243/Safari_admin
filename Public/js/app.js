// ========================================
// SAFARI SMART MOBILITY - Application JS
// ========================================

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
  initDashboard();
  initGestionBus();
  replaceFeatherIcons();
});

// Initialiser les icônes Feather (fonction globale)
function replaceFeatherIcons() {
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
}

// Alias pour compatibilité
window.initFeatherIcons = replaceFeatherIcons;

// ========================================
// DASHBOARD - Carte et marqueurs
// ========================================
function initDashboard() {
  const map = document.getElementById('mapCanvas');
  if (!map) return;

  const buses = [
    { id: 421, x: 22, y: 36, status: 'actif' },
    { id: 105, x: 68, y: 18, status: 'actif' },
    { id: 202, x: 54, y: 42, status: 'maintenance' },
    { id: 512, x: 14, y: 78, status: 'panne' },
    { id: 238, x: 62, y: 72, status: 'actif' },
    { id: 310, x: 30, y: 20, status: 'actif' },
  ];

  function createMarker(bus) {
    const wrap = document.createElement('div');
    wrap.className = 'marker';
    wrap.dataset.status = bus.status;
    wrap.style.left = bus.x + '%';
    wrap.style.top = bus.y + '%';

    const label = document.createElement('div');
    label.className = 'marker__label';
    label.textContent = `#${bus.id}`;

    const pin = document.createElement('div');
    pin.className = `marker__pin marker__pin--${bus.status}`;

    wrap.appendChild(label);
    wrap.appendChild(pin);
    return wrap;
  }

  function renderMarkers(filter) {
    map.innerHTML = '';
    buses
      .filter(b => !filter || b.status === filter)
      .forEach(b => map.appendChild(createMarker(b)));
  }

  // Chips filtering
  const chips = document.querySelectorAll('.chip');
  let activeFilter = 'actif';
  
  function setActiveChip(val) {
    chips.forEach(c => c.classList.toggle('active', c.dataset.status === val));
  }
  
  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      activeFilter = chip.dataset.status;
      setActiveChip(activeFilter);
      renderMarkers(activeFilter);
    });
  });

  // Initialize
  setActiveChip(activeFilter);
  renderMarkers(activeFilter);

  // Fuel meter
  document.querySelectorAll('.meter').forEach(el => {
    const val = Number(el.dataset.value || '0');
    el.style.setProperty('--value', String(val));
  });
}

// ========================================
// GESTION BUS - CRUD Operations
// ========================================

// Variables globales (initialisées vides, seront remplies par PHP)
window.busData = window.busData || [];
window.editingBusId = null;

function initGestionBus() {
  const modal = document.getElementById('modalBus');
  if (!modal) {
    console.log('Modal non trouvé - pas sur la page de gestion');
    return;
  }

  const btnNouveauBus = document.getElementById('btnNouveauBus');
  const btnCloseModal = document.getElementById('btnCloseModal');
  const btnAnnuler = document.getElementById('btnAnnuler');
  const formBus = document.getElementById('formBus');
  const busTableBody = document.getElementById('busTableBody');
  const modalTitle = document.getElementById('modalTitle');

  console.log('✓ Gestion Bus initialisée');
  console.log('✓ Données chargées:', window.busData.length, 'bus');
  console.log('✓ Modal trouvé:', modal);
  console.log('✓ Bouton Nouveau Bus:', btnNouveauBus);

  // Configuration des écouteurs d'événements
  btnNouveauBus.addEventListener('click', () => {
    console.log('Clic sur Nouveau Bus');
    openModalForNew();
  });
  btnCloseModal.addEventListener('click', closeModal);
  btnAnnuler.addEventListener('click', closeModal);
  formBus.addEventListener('submit', handleFormSubmit);
  
  // Fermer le modal en cliquant sur l'overlay
  modal.querySelector('.modal__overlay').addEventListener('click', closeModal);

  // Filtres désactivés - gérés par PHP dans gestion-bus.php
  // Les filtres utilisent maintenant un formulaire GET

  // Ouvrir le modal pour un nouveau bus
  function openModalForNew() {
    window.editingBusId = null;
    modalTitle.textContent = 'Nouveau Bus';
    formBus.reset();
    modal.classList.add('active');
    console.log('Modal ouvert pour nouveau bus');
    initFeatherIcons();
  }

  // Ouvrir le modal pour éditer un bus
  window.openModalForEdit = function(busId) {
    window.editingBusId = busId;
    const bus = window.busData.find(b => b.id === busId);
    console.log('Modal ouvert pour éditer bus', busId);
    
    if (!bus) return;

    modalTitle.textContent = 'Modifier le Bus #' + bus.numero;
    
    // Remplir le formulaire
    document.getElementById('numeroBus').value = bus.numero;
    document.getElementById('immatriculation').value = bus.immatriculation;
    document.getElementById('marque').value = bus.marque || '';
    document.getElementById('modele').value = bus.modele || '';
    document.getElementById('annee').value = bus.annee || '';
    document.getElementById('capacite').value = bus.capacite || '';
    document.getElementById('ligneAffectee').value = bus.ligne || '';
    document.getElementById('statut').value = bus.statut;
    document.getElementById('notes').value = bus.notes || '';

    // Cocher les modules
    const moduleCheckboxes = document.querySelectorAll('input[name="modules"]');
    moduleCheckboxes.forEach(checkbox => {
      checkbox.checked = bus.modules.includes(checkbox.value);
    });

    modal.classList.add('active');
    initFeatherIcons();
  };

  // Fermer le modal
  function closeModal() {
    modal.classList.remove('active');
    formBus.reset();
    window.editingBusId = null;
  }

  // Gérer la soumission du formulaire
  function handleFormSubmit(e) {
    e.preventDefault();

    const formData = new FormData(formBus);
    const modules = Array.from(formData.getAll('modules'));

    const busInfo = {
      numero: formData.get('numeroBus'),
      immatriculation: formData.get('immatriculation'),
      marque: formData.get('marque'),
      modele: formData.get('modele'),
      annee: formData.get('annee'),
      capacite: formData.get('capacite'),
      ligne: formData.get('ligneAffectee'),
      statut: formData.get('statut'),
      modules: modules,
      notes: formData.get('notes'),
      derniereActivite: new Date().toISOString().slice(0, 16).replace('T', ' ')
    };

    if (window.editingBusId) {
      // Modification
      const index = window.busData.findIndex(b => b.id === window.editingBusId);
      if (index !== -1) {
        window.busData[index] = { ...window.busData[index], ...busInfo };
      }
    } else {
      // Création
      const newBus = {
        id: Date.now(),
        ...busInfo,
        chauffeur: '-'
      };
      window.busData.unshift(newBus);
    }

    closeModal();
    renderBusTable();
  }

  // Supprimer un bus
  window.deleteBus = function(busId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce bus ?')) {
      window.busData = window.busData.filter(b => b.id !== busId);
      renderBusTable();
    }
  };

  // Désactiver/Activer un bus
  window.toggleBusStatus = function(busId) {
    const bus = window.busData.find(b => b.id === busId);
    if (bus) {
      bus.statut = bus.statut === 'inactif' ? 'actif' : 'inactif';
      renderBusTable();
    }
  };

  // Obtenir le nom de la ligne
  function getLigneName(ligneId) {
    const lignes = {
      '1': 'Ligne 1',
      '2': 'Ligne 2',
      '3': 'Ligne 3'
    };
    return lignes[ligneId] || 'Non affecté';
  }

  // Appliquer les filtres
  function applyFilters() {
    const statutFilterEl = document.getElementById('filterStatut');
    const ligneFilterEl = document.getElementById('filterLigne') || document.getElementById('filterTrajet');
    const searchBusEl = document.getElementById('searchBus');
    
    // Vérifier que les éléments existent
    if (!statutFilterEl || !searchBusEl) {
      console.log('Éléments de filtre non trouvés - pas sur la page de gestion');
      return;
    }
    
    const statutFilter = statutFilterEl.value;
    const ligneFilter = ligneFilterEl ? ligneFilterEl.value : '';
    const searchTerm = searchBusEl.value.toLowerCase();

    let filteredData = window.busData;

    if (statutFilter) {
      filteredData = filteredData.filter(bus => bus.statut === statutFilter);
    }

    if (ligneFilter) {
      filteredData = filteredData.filter(bus => bus.ligne === ligneFilter);
    }

    if (searchTerm) {
      filteredData = filteredData.filter(bus => 
        bus.numero.toLowerCase().includes(searchTerm) ||
        bus.immatriculation.toLowerCase().includes(searchTerm) ||
        bus.chauffeur.toLowerCase().includes(searchTerm)
      );
    }

    renderBusTable(filteredData);
  }

  // Afficher le tableau des bus
  function renderBusTable(data = window.busData) {
    if (data.length === 0) {
      busTableBody.innerHTML = `
        <tr>
          <td colspan="7" class="empty-state">
            <i data-feather="inbox"></i>
            <h3>Aucun bus trouvé</h3>
            <p>Essayez de modifier vos filtres ou ajoutez un nouveau bus</p>
          </td>
        </tr>
      `;
      initFeatherIcons();
      return;
    }

    busTableBody.innerHTML = data.map(bus => `
      <tr>
        <td><strong>#${bus.numero}</strong></td>
        <td>${bus.immatriculation}</td>
        <td>${getLigneName(bus.ligne)}</td>
        <td>
          <span class="status-badge status-badge--${bus.statut}">
            ${bus.statut.charAt(0).toUpperCase() + bus.statut.slice(1)}
          </span>
        </td>
        <td>${bus.chauffeur}</td>
        <td>${bus.derniereActivite}</td>
        <td>
          <div class="action-buttons">
            <button class="btn-icon btn-icon--edit" onclick="openModalForEdit(${bus.id})" title="Modifier">
              <i data-feather="edit-2"></i>
            </button>
            <button class="btn-icon btn-icon--assign" onclick="toggleBusStatus(${bus.id})" title="${bus.statut === 'inactif' ? 'Activer' : 'Désactiver'}">
              <i data-feather="${bus.statut === 'inactif' ? 'check-circle' : 'x-circle'}"></i>
            </button>
            <button class="btn-icon btn-icon--delete" onclick="deleteBus(${bus.id})" title="Supprimer">
              <i data-feather="trash-2"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join('');

    initFeatherIcons();
  }

  // Initialiser le tableau
  renderBusTable();
}
