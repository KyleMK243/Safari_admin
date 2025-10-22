<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Bus • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Gestion des Bus</h1>
          <p>Créer, modifier et gérer votre flotte de véhicules</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" id="btnNouveauBus">
            <i data-feather="plus"></i> Nouveau Bus
          </button>
        </div>
      </header>

      <!-- Filter bar -->
      <section class="filters card">
        <div class="filters__title">
          <i data-feather="filter"></i>
          Filtres
        </div>
        <form method="GET" action="/gestion-bus" class="filters__controls">
          <select name="statut" id="filterStatut">
            <option value="">Tous les statuts</option>
            <option value="actif" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'actif') ? 'selected' : ''; ?>>Actif</option>
            <option value="maintenance" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
            <option value="panne" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'panne') ? 'selected' : ''; ?>>En panne</option>
            <option value="inactif" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'inactif') ? 'selected' : ''; ?>>Inactif</option>
          </select>
          <select name="trajet" id="filterTrajet">
            <option value="">Tous les trajets</option>
            <?php if (isset($trajets) && is_array($trajets)): ?>
              <?php foreach ($trajets as $trajet): ?>
                <option value="<?php echo htmlspecialchars($trajet['id']); ?>" 
                  <?php echo (isset($_GET['trajet']) && $_GET['trajet'] == $trajet['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($trajet['nom']); ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
            <option value="non_affecte" <?php echo (isset($_GET['trajet']) && $_GET['trajet'] == 'non_affecte') ? 'selected' : ''; ?>>Non affecté</option>
          </select>
          <input type="text" name="search" id="searchBus" placeholder="Rechercher (plaque, marque)..." 
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="min-width: 200px;">
          <button type="submit" class="btn btn--primary" id="btnFiltrer">Filtrer</button>
          <?php if (isset($_GET['statut']) || isset($_GET['trajet']) || (isset($_GET['search']) && $_GET['search'] !== '')): ?>
            <a href="/gestion-bus" class="btn btn--secondary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Réinitialiser</a>
          <?php endif; ?>
        </form>
      </section>

      <!-- Bus Table -->
      <section class="bus-table card">
        <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>N° Bus</th>
              <th>Immatriculation</th>
              <th>Ligne affectée</th>
              <th>Statut</th>
              <th>Chauffeur</th>
              <th>Dernière activité</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="busTableBody">
            <!-- Data will be populated by JS -->
          </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
          <div class="pagination__info">
            Affichage de <strong id="paginationStart">0</strong> à <strong id="paginationEnd">0</strong> sur <strong id="paginationTotal">0</strong> bus
          </div>
          <div class="pagination__controls">
            <button class="pagination__btn" id="btnPrevPage" disabled>
              <i data-feather="chevron-left"></i> Précédent
            </button>
            <div class="pagination__pages" id="paginationPages">
              <!-- Pages générées par JS -->
            </div>
            <button class="pagination__btn" id="btnNextPage">
              Suivant <i data-feather="chevron-right"></i>
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal pour voir le profil d'un bus -->
  <div class="modal" id="modalProfil">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2 id="profilTitle">Profil du Bus</h2>
        <button class="modal__close" id="btnCloseModalProfil">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div class="profil-grid">
          <div class="profil-section">
            <h3 class="profil-section__title">Informations générales</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">Numéro de bus</span>
                <span class="profil-info__value" id="profilNumero">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Immatriculation</span>
                <span class="profil-info__value" id="profilImmatriculation">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Marque & Modèle</span>
                <span class="profil-info__value" id="profilMarque">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Année</span>
                <span class="profil-info__value" id="profilAnnee">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Capacité</span>
                <span class="profil-info__value" id="profilCapacite">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Kilométrage</span>
                <span class="profil-info__value" id="profilKilometrage">-</span>
              </div>
            </div>
          </div>

          <div class="profil-section">
            <h3 class="profil-section__title">Affectation & Équipe</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">Ligne affectée</span>
                <span class="profil-info__value" id="profilLigne">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Statut</span>
                <span id="profilStatut">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Dernière activité</span>
                <span class="profil-info__value" id="profilActivite">-</span>
              </div>
            </div>
            
            <h4 class="profil-subsection__title">Équipe de bord (avec horaires)</h4>
            <div id="profilEquipeHoraires">
              <!-- Équipe avec horaires générée par JS -->
            </div>
          </div>
        </div>

        <div class="profil-section">
          <h3 class="profil-section__title">Modules installés</h3>
          <div class="profil-modules" id="profilModules">
            <!-- Modules générés par JS -->
          </div>
        </div>

        <div class="profil-section">
          <h3 class="profil-section__title">Documents de bord</h3>
          <table class="profil-table">
            <thead>
              <tr>
                <th>Désignation</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody id="profilDocuments">
              <!-- Documents générés par JS -->
            </tbody>
          </table>
        </div>

        <div class="profil-section">
          <h3 class="profil-section__title">Shifts planifiés</h3>
          <div id="profilShifts">
            <!-- Shifts générés par JS -->
          </div>
        </div>

        <div class="profil-section" id="profilNotesSection">
          <h3 class="profil-section__title">Notes</h3>
          <p class="profil-notes" id="profilNotes">-</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal pour créer/modifier un bus -->
  <div class="modal" id="modalBus">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2 id="modalTitle">Nouveau Bus</h2>
        <button class="modal__close" id="btnCloseModal">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <form class="modal__body" id="formBus">
        <div class="form-grid">
          <div class="form-group">
            <label for="numeroBus">Numéro de Bus *</label>
            <input type="text" id="numeroBus" name="numeroBus" required placeholder="Ex: 421">
          </div>

          <div class="form-group">
            <label for="immatriculation">Immatriculation *</label>
            <input type="text" id="immatriculation" name="immatriculation" required placeholder="Ex: KIN-1234-AB">
          </div>

          <div class="form-group">
            <label for="marque">Marque</label>
            <input type="text" id="marque" name="marque" placeholder="Ex: Mercedes">
          </div>

          <div class="form-group">
            <label for="modele">Modèle</label>
            <input type="text" id="modele" name="modele" placeholder="Ex: Sprinter">
          </div>

          <div class="form-group">
            <label for="annee">Année</label>
            <input type="number" id="annee" name="annee" placeholder="2024" min="1990" max="2030">
          </div>

          <div class="form-group">
            <label for="capacite">Capacité (places)</label>
            <input type="number" id="capacite" name="capacite" placeholder="50" min="1">
          </div>
          
          <div class="form-group">
            <label for="kilometrage">Kilométrage</label>
            <input type="number" id="kilometrage" name="kilometrage" placeholder="0" min="0">
          </div>

          <div class="form-group">
            <label for="ligneAffectee">Trajet affecté</label>
            <select id="ligneAffectee" name="ligneAffectee">
              <option value="">Non affecté</option>
              <?php if (isset($trajets) && is_array($trajets)): ?>
                <?php foreach ($trajets as $trajet): ?>
                  <option value="<?php echo htmlspecialchars($trajet['id']); ?>">
                    <?php echo htmlspecialchars($trajet['nom']); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="statut">Statut *</label>
            <select id="statut" name="statut" required>
              <option value="actif">Actif</option>
              <option value="maintenance">Maintenance</option>
              <option value="panne">En panne</option>
              <option value="inactif">Inactif</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="modules">Modules installés</label>
          <div class="checkbox-group">
            <label class="checkbox-label">
              <input type="checkbox" name="modules[]" value="datcha"> Datcha
            </label>
            <label class="checkbox-label">
              <input type="checkbox" name="modules[]" value="wifi"> WiFi
            </label>
            <label class="checkbox-label">
              <input type="checkbox" name="modules[]" value="pos"> POS
            </label>
            <label class="checkbox-label">
              <input type="checkbox" name="modules[]" value="gps"> GPS
            </label>
            <label class="checkbox-label">
              <input type="checkbox" name="modules[]" value="camera"> Caméra
            </label>
          </div>
        </div>

        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea id="notes" name="notes" rows="3" placeholder="Informations complémentaires..."></textarea>
        </div>

        <div class="modal__footer">
          <button type="button" class="btn btn--secondary" id="btnAnnuler">Annuler</button>
          <button type="submit" class="btn btn--primary">
            <i data-feather="save"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>

  <!-- Script pour l'ajout et la modification des bus -->
    <script>
    (() => {
      const BASE_URL = "<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>";
      const formBus = document.getElementById("formBus");
      const modal = document.getElementById("modalBus");
      const modalTitle = document.getElementById("modalTitle");
      const btnNouveauBus = document.getElementById("btnNouveauBus");
      const btnCloseModal = document.getElementById("btnCloseModal");
      const btnAnnuler = document.getElementById("btnAnnuler");

      let busEnEdition = null;

      // ========== Fonctions utilitaires ==========
      const $ = (id) => document.getElementById(id);

      function ouvrirModalNouveau() {
        busEnEdition = null;
        formBus.reset();
        modalTitle.textContent = "Nouveau Bus";
        modal.classList.add("active");
      }

      async function ouvrirModalEdition(busId) {
        try {
          const resp = await fetch(`${BASE_URL}/bus/details?bus_id=${encodeURIComponent(busId)}`);
          if (!resp.ok) throw new Error(`Erreur serveur (${resp.status})`);
          const data = await resp.json();
          if (!data.success) throw new Error(data.message || "Erreur lors du chargement du bus");
          const bus = data.bus;

          busEnEdition = bus;
          modalTitle.textContent = `Modifier le Bus #${bus.numero || bus.id || ""}`;
          $("numeroBus").value = bus.numero ?? "";
          $("immatriculation").value = bus.immatriculation ?? "";
          $("marque").value = bus.marque ?? "";
          $("modele").value = bus.modele ?? "";
          $("annee").value = bus.annee ?? "";
          $("capacite").value = bus.capacite ?? "";
          $("kilometrage").value = bus.kilometrage ?? "";
          $("ligneAffectee").value = bus.trajet_id ?? bus.ligne ?? "";
          $("statut").value = bus.statut ?? "disponible";
          $("notes").value = bus.notes ?? "";

          const modulesArray = (bus.modules && typeof bus.modules === "string")
            ? bus.modules.split(",").map((m) => m.trim())
            : (Array.isArray(bus.modules) ? bus.modules : []);

          // ✅ correction ici : on sélectionne les bonnes cases à cocher
          document.querySelectorAll('input[name="modules[]"]').forEach(cb => {
            cb.checked = modulesArray.includes(cb.value);
          });

          modal.classList.add("active");
        } catch (err) {
          alert(err.message);
        }
      }

      function fermerModal() {
        modal.classList.remove("active");
        formBus.reset();
        busEnEdition = null;
      }

      // ========== Soumission du formulaire ==========
      formBus.addEventListener("submit", async (e) => {
        e.preventDefault();

        const numero = $("numeroBus")?.value.trim() || "";
        const immatriculation = $("immatriculation")?.value.trim() || "";

        if (!numero || !immatriculation) {
          alert("Le numéro et l'immatriculation sont obligatoires.");
          return;
        }

        // ✅ correction ici : bon nom de champ
        const modulesChecked = Array.from(document.querySelectorAll('input[name="modules[]"]:checked')).map(cb => cb.value);

        const payload = {
          numero,
          immatriculation,
          marque: $("marque")?.value.trim() || "",
          modele: $("modele")?.value.trim() || "",
          annee: $("annee")?.value || 0,
          capacite: $("capacite")?.value || 0,
          kilometrage: $("kilometrage")?.value || 0,
          trajet_id: $("ligneAffectee")?.value || null,
          statut: $("statut")?.value || "disponible",
          modules: modulesChecked.join(","), // ✅ envoyé sous forme de chaîne CSV
          notes: $("notes")?.value.trim() || ""
        };

        let url = `${BASE_URL}/bus/ajouter`;
        if (busEnEdition && busEnEdition.id) {
          url = `${BASE_URL}/bus/modifier`;
          payload.id = busEnEdition.id;
        }

        try {
          const resp = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
          });
          if (!resp.ok) throw new Error(`Erreur serveur (${resp.status})`);
          const data = await resp.json();

          if (data.status === "success" || data.success) {
            alert(data.message || "Opération réussie !");
            location.reload(); // ✅ recharge la page après ajout ou modification
          } else {
            throw new Error(data.message || "Erreur lors de l'enregistrement");
          }
        } catch (err) {
          console.error("Erreur:", err);
          alert("Erreur : " + err.message);
        }
      });

      // ========== Événements ==========
      if (btnNouveauBus) btnNouveauBus.addEventListener("click", (e) => {
        e.preventDefault();
        ouvrirModalNouveau();
      });
      if (btnCloseModal) btnCloseModal.addEventListener("click", fermerModal);
      if (btnAnnuler) btnAnnuler.addEventListener("click", (e) => {
        e.preventDefault();
        fermerModal();
      });

      // Clique sur overlay pour fermer
      const overlay = modal.querySelector(".modal__overlay");
      if (overlay) overlay.addEventListener("click", fermerModal);

      // Expose la fonction de modification pour les boutons
      window.modifierBus = ouvrirModalEdition;
    })();
  </script>

  <!-- Script pour affichage, pagination, profil et filtrage -->
  <script>
  // Injection des données PHP
  <?php
  $busesJSON = [];
  if (isset($buses) && is_array($buses)) {
      foreach ($buses as $bus) {
          $busesJSON[] = [
              'id' => (int) $bus['id'],
              'numero' => $bus['numero'],
              'immatriculation' => $bus['immatriculation'],
              'marque' => $bus['marque'] ?? '',
              'modele' => $bus['modele'] ?? '',
              'annee' => (int) ($bus['annee'] ?? 0),
              'capacite' => (int) ($bus['capacite'] ?? 0),
              'kilometrage' => (int) ($bus['kilometrage'] ?? 0),
              'trajet_id' => $bus['trajet_id'] ?? '',
              'trajet_nom' => $bus['trajet_nom'] ?? 'Non affecté',
              'statut' => $bus['statut'] ?? 'disponible',
              'chauffeur' => $bus['chauffeur'] ?? '-',
              'derniere_activite' => $bus['derniere_activite'] ?? '-',
              'derniereActivite' => $bus['derniere_activite'] ?? '-',
              'modules' => !empty($bus['modules']) ? $bus['modules'] : '',
              'notes' => $bus['notes'] ?? ''
          ];
      }
  }
  ?>
  window.busData = <?php echo json_encode($busesJSON, JSON_HEX_TAG | JSON_HEX_AMP); ?>;
  
  (() => {
    // ===========================
    // Utils
    // ===========================
    const BASE_URL = "<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>";
    const log = (...args) => console.debug('[BusManager]', ...args);

    // Échappe le texte pour insertion sûre dans le DOM
    function escapeHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    // Récupère un élément (safe)
    const $ = id => document.getElementById(id);

    // ===========================
    // État
    // ===========================
    let currentPage = 1;
    const itemsPerPage = 5;
    let busEnEdition = null;

    // Données injectées par PHP
    if (!window.busData) window.busData = [];
    if (!Array.isArray(window.busData)) window.busData = [];

    // ===========================
    // DOM
    // ===========================
    const modal = $('modalBus');
    const modalTitle = $('modalTitle');
    const btnNouveauBus = $('btnNouveauBus');
    const btnCloseModal = $('btnCloseModal');
    const btnAnnuler = $('btnAnnuler');
    const busTableBody = $('busTableBody');
    const formBus = $('formBus');

    const btnPrevPage = $('btnPrevPage');
    const btnNextPage = $('btnNextPage');
    const paginationPages = $('paginationPages');
    const paginationStart = $('paginationStart');
    const paginationEnd = $('paginationEnd');
    const paginationTotal = $('paginationTotal');

    // Modal profil
    const modalProfil = $('modalProfil');

    // sécurité : éléments requis
    if (!busTableBody || !formBus) {
      console.error('Éléments clés manquants dans le DOM (busTableBody ou formBus).');
      return;
    }

    // ===========================
    // Voir profil (AJAX + affichage)
    // ===========================
    async function voirProfilBus(busId) {
      if (!modalProfil) {
        alert('Fenêtre de profil non disponible.');
        return;
      }
      modalProfil.classList.add('active');
      const titreEl = $('profilTitle');
      if (titreEl) titreEl.textContent = 'Chargement...';

      try {
        const resp = await fetch(`${BASE_URL}/bus/details?bus_id=${encodeURIComponent(busId)}`);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const text = await resp.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (err) {
          console.error('Response non JSON:', text);
          throw new Error('Réponse invalide du serveur');
        }
        if (!data.success) throw new Error(data.message || 'Erreur serveur');
        afficherProfilBus(data.bus);
      } catch (err) {
        console.error('Erreur voirProfilBus:', err);
        alert('Erreur lors du chargement du profil: ' + (err.message || err));
        modalProfil.classList.remove('active');
      }
    }

    function afficherProfilBus(bus) {
      $('profilTitle').textContent = `Profil du Bus #${escapeHtml(bus.numero ?? bus.id ?? '')}`;
      $('profilNumero').textContent = '#' + (bus.numero ?? bus.id ?? '-');
      $('profilImmatriculation').textContent = bus.immatriculation ?? '-';
      $('profilMarque').textContent = `${bus.marque ?? ''} ${bus.modele ?? ''}`.trim() || '-';
      $('profilAnnee').textContent = bus.annee ?? '-';
      $('profilCapacite').textContent = bus.capacite ? `${bus.capacite} places` : '-';
      $('profilKilometrage').textContent = (bus.kilometrage ?? 0) + ' km';
      $('profilLigne').textContent = bus.trajet_nom || 'Non affecté';

      $('profilStatut').innerHTML = `
        <span class="status-badge status-badge--${escapeHtml(bus.statut ?? 'disponible')}">
          ${escapeHtml((bus.statut ?? 'disponible').charAt(0).toUpperCase() + (bus.statut ?? 'disponible').slice(1))}
        </span>
      `;

      $('profilActivite').textContent = bus.derniere_activite ?? bus.derniereActivite ?? '-';

      // équipe
      const equipeEl = $('profilEquipeHoraires');
      if (equipeEl) {
        const equipe = Array.isArray(bus.equipe) ? bus.equipe : [];
        if (equipe.length === 0) {
          equipeEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucune équipe affectée actuellement.</p>';
        } else {
          const group = {};
          equipe.forEach(m => {
            const poste = m.poste || 'autre';
            group[poste] = group[poste] || [];
            group[poste].push(m);
          });
          let html = '';
          for (const [poste, membres] of Object.entries(group)) {
            html += `<div class="equipe-role-section"><div class="equipe-role-title"><i data-feather="user"></i> ${escapeHtml(poste.charAt(0).toUpperCase() + poste.slice(1))}</div>`;
            membres.forEach(m => {
              html += `<div class="equipe-membre-horaire"><span class="equipe-membre-horaire__nom">${escapeHtml(m.nom)}</span><span class="equipe-membre-horaire__time"><i data-feather="phone"></i> ${escapeHtml(m.telephone || 'N/A')}</span></div>`;
            });
            html += '</div>';
          }
          equipeEl.innerHTML = html;
        }
      }

      // modules
      const modulesEl = $('profilModules');
      if (modulesEl) {
        const modulesArray = (bus.modules && typeof bus.modules === 'string')
          ? bus.modules.split(',').map(s => s.trim()).filter(Boolean)
          : (Array.isArray(bus.modules) ? bus.modules : []);
        if (modulesArray.length === 0) {
          modulesEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucun module installé</p>';
        } else {
          const icons = { datcha: 'credit-card', wifi: 'wifi', pos: 'shopping-cart', gps: 'map-pin', camera: 'camera' };
          const labels = { datcha: 'Datcha', wifi: 'WiFi', pos: 'POS', gps: 'GPS', camera: 'Caméra' };
          modulesEl.innerHTML = modulesArray.map(m => `<span class="profil-module"><i data-feather="${icons[m] || 'box'}"></i>${escapeHtml(labels[m] || m)}</span>`).join('');
        }
      }

      // documents
      const docsEl = $('profilDocuments');
      if (docsEl) {
        const docs = Array.isArray(bus.documents) ? bus.documents : [];
        if (docs.length === 0) {
          docsEl.innerHTML = '<tr><td colspan="2" style="text-align:center; color:var(--muted);">Aucun document enregistré</td></tr>';
        } else {
          const icons = { valide: 'check-circle', expire: 'x-circle', bientot: 'alert-circle' };
          const labels = { valide: 'Valide', expire: 'Expiré', bientot: 'Expire bientôt' };
          docsEl.innerHTML = docs.map(doc => `<tr><td>${escapeHtml(doc.designation)}</td><td><span class="doc-status doc-status--${escapeHtml(doc.statut)}"><i data-feather="${icons[doc.statut] || 'file'}"></i>${escapeHtml(labels[doc.statut] || doc.statut)}${doc.date_expiration ? `<br><small>Exp: ${escapeHtml(doc.date_expiration)}</small>` : ''}</span></td></tr>`).join('');
        }
      }

      // shifts
      const shiftsEl = $('profilShifts');
      if (shiftsEl) {
        const shifts = Array.isArray(window.shiftsData) ? window.shiftsData.filter(s => Number(s.busId) === Number(bus.id) && s.statut !== 'termine') : [];
        if (shifts.length === 0) {
          shiftsEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucun shift planifié pour ce bus.</p>';
        } else {
          shiftsEl.innerHTML = shifts.map(shift => `<div class="shift-card"><div class="shift-card__header"><div class="shift-card__date"><i data-feather="calendar"></i><strong>${escapeHtml(new Date(shift.date).toLocaleDateString('fr-FR'))}</strong></div><span class="status-badge status-badge--${escapeHtml(shift.statut)}">${escapeHtml(shift.statut.charAt(0).toUpperCase() + shift.statut.slice(1))}</span></div><div class="shift-card__time"><i data-feather="clock"></i>${escapeHtml(shift.heureDebut)} - ${escapeHtml(shift.heureFin)}</div><div class="shift-card__equipe"><div class="shift-card__membre"><i data-feather="user"></i><span><strong>Chauffeur:</strong> ${escapeHtml((shift.chauffeur && shift.chauffeur.nom) || 'N/A')}</span></div></div></div>`).join('');
        }
      }

      // notes
      const notesSec = $('profilNotesSection');
      if (notesSec) {
        if (bus.notes) {
          notesSec.style.display = 'block';
          $('profilNotes').textContent = bus.notes;
        } else {
          notesSec.style.display = 'none';
        }
      }

      setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
    }

    // fermer modal profil
    const btnCloseModalProfil = $('btnCloseModalProfil');
    if (btnCloseModalProfil) btnCloseModalProfil.addEventListener('click', () => modalProfil.classList.remove('active'));
    if (modalProfil) {
      const overlay = modalProfil.querySelector('.modal__overlay');
      if (overlay) overlay.addEventListener('click', () => modalProfil.classList.remove('active'));
    }

    // ===========================
    // Supprimer
    // ===========================
    async function supprimerBus(busId) {
      if (!confirm('Voulez-vous vraiment supprimer ce bus ? Cette action est irréversible.')) return;
      try {
        alert('Fonction de suppression à implémenter côté serveur.');
      } catch (err) {
        console.error('Erreur suppression:', err);
        alert('Impossible de supprimer le bus.');
      }
    }

    // Rendre disponible globalement
    window.voirProfilBus = voirProfilBus;
    window.supprimerBus = supprimerBus;

    // ===========================
    // Rendu tableau & pagination
    // ===========================
    function renderRow(bus) {
      return `
        <tr>
          <td><strong>#${escapeHtml(bus.numero ?? bus.id ?? '')}</strong></td>
          <td>${escapeHtml(bus.immatriculation ?? '')}</td>
          <td>${escapeHtml(bus.trajet_nom ?? 'Non affecté')}</td>
          <td>
            <span class="status-badge status-badge--${escapeHtml(bus.statut ?? 'disponible')}">
              ${escapeHtml((bus.statut ?? 'disponible').charAt(0).toUpperCase() + (bus.statut ?? 'disponible').slice(1))}
            </span>
          </td>
          <td>${escapeHtml(bus.chauffeur ?? '-')}</td>
          <td>${escapeHtml(bus.derniereActivite ?? bus.derniere_activite ?? '-')}</td>
          <td>
            <div class="action-buttons">
              <button class="btn-icon btn-icon--edit" onclick="voirProfilBus(${JSON.stringify(bus.id)})" title="Voir le profil">
                <i data-feather="eye"></i>
              </button>
              <button class="btn-icon btn-icon--assign" onclick="modifierBus(${JSON.stringify(bus.id)})" title="Modifier">
                <i data-feather="edit-2"></i>
              </button>
              <button class="btn-icon btn-icon--delete" onclick="supprimerBus(${JSON.stringify(bus.id)})" title="Supprimer">
                <i data-feather="trash-2"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
    }

    function afficherBus() {
      log('afficherBus - total', window.busData.length);
      if (!window.busData || window.busData.length === 0) {
        busTableBody.innerHTML = `
          <tr>
            <td colspan="7" style="text-align:center; padding:40px;">
              <div style="color:#6b7280;">
                <i data-feather="inbox" style="width:48px;height:48px;margin-bottom:12px;"></i>
                <p style="font-size:16px;font-weight:600;margin:12px 0 8px;">Aucun bus enregistré</p>
                <p style="font-size:14px;margin:0;">Cliquez sur "Nouveau Bus" pour ajouter votre premier véhicule</p>
              </div>
            </td>
          </tr>
        `;
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
        if (paginationStart) paginationStart.textContent = 0;
        if (paginationEnd) paginationEnd.textContent = 0;
        if (paginationTotal) paginationTotal.textContent = 0;
        if (paginationPages) paginationPages.innerHTML = '';
        if (btnPrevPage) btnPrevPage.disabled = true;
        if (btnNextPage) btnNextPage.disabled = true;
        return;
      }

      const totalItems = window.busData.length;
      const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
      if (currentPage > totalPages) currentPage = totalPages;
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
      const currentData = window.busData.slice(startIndex, endIndex);

      busTableBody.innerHTML = currentData.map(renderRow).join('');
      if (paginationStart) paginationStart.textContent = startIndex + 1;
      if (paginationEnd) paginationEnd.textContent = endIndex;
      if (paginationTotal) paginationTotal.textContent = totalItems;

      // pages
      if (paginationPages) {
        paginationPages.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
          const btn = document.createElement('button');
          btn.className = 'pagination__page' + (i === currentPage ? ' active' : '');
          btn.textContent = i;
          btn.addEventListener('click', () => { currentPage = i; afficherBus(); });
          paginationPages.appendChild(btn);
        }
      }

      if (btnPrevPage) btnPrevPage.disabled = (currentPage === 1);
      if (btnNextPage) btnNextPage.disabled = (currentPage === totalPages);

      setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
    }

    // ===========================
    // Pagination buttons
    // ===========================
    if (btnPrevPage) btnPrevPage.addEventListener('click', () => {
      if (currentPage > 1) { currentPage--; afficherBus(); }
    });
    if (btnNextPage) btnNextPage.addEventListener('click', () => {
      const totalPages = Math.ceil(window.busData.length / itemsPerPage);
      if (currentPage < totalPages) { currentPage++; afficherBus(); }
    });

    // Initial rendering
    document.addEventListener('DOMContentLoaded', () => {
      log('DOMContentLoaded - initialisation');
      afficherBus();
      setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
    });

    // Exports
    window.BusManager = {
      afficherBus,
      getData: () => window.busData
    };

  })();
  </script>

</body>
</html>
