<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Trajets • Safari</title>
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
          <h1>Gestion des Trajets</h1>
          <p>Créer et gérer les trajets, arrêts et points de chifte</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" id="btnNouveauTrajet">
            <i data-feather="plus"></i> Nouveau Trajet
          </button>
        </div>
      </header>

      <!-- Filter bar -->
      <section class="filters card">
        <div class="filters__title">
          <i data-feather="filter"></i>
          Filtres
        </div>
        <div class="filters__controls">
          <select id="filterStatutTrajet">
            <option value="">Tous les statuts</option>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
          </select>
          <input type="text" id="searchTrajet" placeholder="Rechercher un trajet...">
          <!-- <button class="btn btn--primary" id="btnFiltrerTrajet">Filtrer</button> -->
        </div>
      </section>

      <!-- Trajets Table -->
      <section class="bus-table card">
          <div style="overflow-x: auto;">
              <table class="table" style="white-space: nowrap;">
                  <thead>
                      <tr>
                          <th>Nom du trajet</th>
                          <th>Distance totale</th>
                          <th>Nombre d'arrêts</th>
                          <th>Points de chifte</th>
                          <th>Statut</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($trajets)) : ?>
                          <?php foreach ($trajets as $trajet) : ?>
                              <tr>
                                  <td><?= htmlspecialchars($trajet['nom']) ?></td>
                                  <td><?= htmlspecialchars($trajet['distance_totale']) ?> km</td>
                                  <td>
                                      <?= isset($arretsCount[$trajet['id']]) ? $arretsCount[$trajet['id']] : 0 ?>
                                  </td>
                                  <td>
                                      <?= isset($pointsChifteCount[$trajet['id']]) ? $pointsChifteCount[$trajet['id']] : 0 ?>
                                  </td>
                                  <td>
                                      <span class="status-badge <?= $trajet['statut'] === 'actif' ? 'status--active' : 'status--inactive' ?>">
                                          <?= ucfirst($trajet['statut']) ?>
                                      </span>
                                  </td>
                                  <td>
                                      <div class="action-buttons">
                                          <button class="btn-icon btn-icon--edit" 
                                                  onclick="voirTrajet(<?= $trajet['id'] ?>)" 
                                                  title="Voir le trajet">
                                              <i data-feather="eye"></i>
                                          </button>
                                          <button class="btn-icon btn-icon--assign" 
                                                  onclick="modifierTrajet(<?= $trajet['id'] ?>)" 
                                                  title="Modifier">
                                              <i data-feather="edit-2"></i>
                                          </button>
                                          <button class="btn-icon btn-icon--delete" 
                                                  onclick="supprimerTrajet(<?= $trajet['id'] ?>)" 
                                                  title="Supprimer">
                                              <i data-feather="trash-2"></i>
                                          </button>
                                      </div>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="6" class="text-center">Aucun trajet disponible</td>
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>

          <!-- Pagination -->
          <div class="pagination">
              <div class="pagination__info">
                  Affichage de <strong><?= $paginationStart ?></strong> à <strong><?= $paginationEnd ?></strong> sur <strong><?= $totalTrajets ?></strong> trajets
              </div>
              <div class="pagination__controls">
                  <button class="pagination__btn" <?= $currentPage == 1 ? 'disabled' : '' ?> 
                          onclick="goToPageTrajet(<?= $currentPage - 1 ?>)">
                      <i data-feather="chevron-left"></i> Précédent
                  </button>
                  <div class="pagination__pages">
                      <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                          <button class="pagination__btn <?= $currentPage == $i ? 'active' : '' ?>" 
                                  onclick="goToPageTrajet(<?= $i ?>)">
                              <?= $i ?>
                          </button>
                      <?php endfor; ?>
                  </div>
                  <button class="pagination__btn" <?= $currentPage == $totalPages ? 'disabled' : '' ?> 
                          onclick="goToPageTrajet(<?= $currentPage + 1 ?>)">
                      Suivant <i data-feather="chevron-right"></i>
                  </button>
              </div>
          </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal pour voir les détails d'un trajet -->
  <div class="modal" id="modalDetailsTrajet">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2 id="detailsTrajetTitle">Détails du Trajet</h2>
        <button class="modal__close" id="btnCloseModalDetails">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div class="profil-section">
          <h3 class="profil-section__title">Informations générales</h3>
          <div class="profil-info">
            <div class="profil-info__item">
              <span class="profil-info__label">Nom du trajet</span>
              <span class="profil-info__value" id="detailsNom">-</span>
            </div>
            <div class="profil-info__item">
              <span class="profil-info__label">Distance totale</span>
              <span class="profil-info__value" id="detailsDistance">-</span>
            </div>
            <div class="profil-info__item">
              <span class="profil-info__label">Statut</span>
              <span id="detailsStatut">-</span>
            </div>
          </div>
        </div>

        <div class="profil-section">
          <h3 class="profil-section__title">Arrêts du trajet</h3>
          <div class="trajet-liste" id="detailsArrets">
            <!-- Arrêts générés par JS -->
          </div>
        </div>

        <div class="profil-section">
          <h3 class="profil-section__title">Points de chifte</h3>
          <div class="trajet-liste" id="detailsChiftes">
            <!-- Points de chifte générés par JS -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal pour créer/modifier un trajet - SYSTÈME EN ÉTAPES -->
  <div class="modal" id="modalTrajet">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 900px;">
      <div class="modal__header">
        <h2 id="modalTrajetTitle">Nouveau Trajet</h2>
        <button class="modal__close" id="btnCloseModalTrajet">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <!-- Indicateur d'étapes -->
      <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
          <div id="stepIndicator1" class="step-indicator active" style="text-align: center; flex: 1;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #1B4B7F; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">1</div>
            <div style="font-size: 10px; color: #6b7280;">Infos de base</div>
          </div>
          <div id="stepIndicator2" class="step-indicator" style="text-align: center; flex: 1;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">2</div>
            <div style="font-size: 10px; color: #6b7280;">Arrêts</div>
          </div>
          <div id="stepIndicator3" class="step-indicator" style="text-align: center; flex: 1;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">3</div>
            <div style="font-size: 10px; color: #6b7280;">Shifts</div>
          </div>
          <div id="stepIndicator4" class="step-indicator" style="text-align: center; flex: 1;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">4</div>
            <div style="font-size: 10px; color: #6b7280;">Résumé</div>
          </div>
        </div>
      </div>

      <form class="modal__body" id="formTrajet" style="padding: 24px;" novalidate>
        <input type="hidden" id="trajetId" name="trajetId">
        
        <!-- ÉTAPE 1: Informations de base + Coordonnées -->
        <div id="step1" class="trajet-step">
          <h3 style="margin-bottom: 20px; color: #1B4B7F; display: flex; align-items: center; gap: 8px;"><i data-feather="map-pin" style="width: 20px; height: 20px;"></i> Informations de base et coordonnées</h3>
          
          <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div class="form-group">
              <label for="trajetNom">Nom du trajet *</label>
              <input type="text" id="trajetNom" name="trajetNom" required placeholder="Ex: Kinshasa → Matadi" class="form-control">
            </div>

            <div class="form-group">
              <label for="trajetStatut">Statut *</label>
              <select id="trajetStatut" name="trajetStatut" required class="form-control">
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
              </select>
            </div>
          </div>

          <h4 style="margin: 20px 0 12px; color: #374151; display: flex; align-items: center; gap: 8px;"><i data-feather="navigation" style="width: 16px; height: 16px;"></i> Point de départ</h4>
          <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div class="form-group">
              <label for="latDepart">Latitude départ *</label>
              <input type="number" id="latDepart" step="0.000001" min="-90" max="90" required placeholder="-4.3217" class="form-control">
            </div>
            <div class="form-group">
              <label for="lonDepart">Longitude départ *</label>
              <input type="number" id="lonDepart" step="0.000001" min="-180" max="180" required placeholder="15.3125" class="form-control">
            </div>
          </div>

          <h4 style="margin: 20px 0 12px; color: #374151; display: flex; align-items: center; gap: 8px;"><i data-feather="flag" style="width: 16px; height: 16px;"></i> Point d'arrivée</h4>
          <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div class="form-group">
              <label for="latArrivee">Latitude arrivée *</label>
              <input type="number" id="latArrivee" step="0.000001" min="-90" max="90" required placeholder="-5.8167" class="form-control">
            </div>
            <div class="form-group">
              <label for="lonArrivee">Longitude arrivée *</label>
              <input type="number" id="lonArrivee" step="0.000001" min="-180" max="180" required placeholder="13.4583" class="form-control">
            </div>
          </div>

          <div style="background: #f0f9ff; border: 1px solid #0284c7; border-radius: 8px; padding: 16px; margin-top: 20px;">
            <h4 style="margin: 0 0 12px; color: #0369a1; display: flex; align-items: center; gap: 8px;"><i data-feather="activity" style="width: 18px; height: 18px;"></i> Calculs automatiques</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div>
                <label style="font-size: 12px; color: #6b7280;">Distance totale</label>
                <input type="text" id="distanceCalculee" readonly class="form-control" style="background: #f9fafb; font-weight: 700; color: #1B4B7F;" value="-- km">
              </div>
              <div>
                <label style="font-size: 12px; color: #6b7280;">Temps estimé (40 km/h)</label>
                <input type="text" id="tempsCalcule" readonly class="form-control" style="background: #f9fafb; font-weight: 700; color: #1B4B7F;" value="-- min">
              </div>
            </div>
          </div>
        </div>

        <!-- ÉTAPE 2: Gestion des arrêts -->
        <div id="step2" class="trajet-step" style="display: none;">
          <h3 style="margin-bottom: 20px; color: #1B4B7F; display: flex; align-items: center; gap: 8px;"><i data-feather="map-pin" style="width: 20px; height: 20px;"></i> Gestion des arrêts</h3>
          <button type="button" class="btn btn--secondary btn--sm" id="btnAjouterArret" style="margin-bottom: 16px;">
            <i data-feather="plus"></i> Ajouter un arrêt
          </button>
          <div id="arretsContainer">
            <!-- Arrêts dynamiques -->
          </div>
        </div>

        <!-- ÉTAPE 3: Gestion des shifts -->
        <div id="step3" class="trajet-step" style="display: none;">
          <h3 style="margin-bottom: 20px; color: #1B4B7F; display: flex; align-items: center; gap: 8px;"><i data-feather="refresh-cw" style="width: 20px; height: 20px;"></i> Gestion des shifts</h3>
          <button type="button" class="btn btn--secondary btn--sm" id="btnAjouterShift" style="margin-bottom: 16px;">
            <i data-feather="plus"></i> Ajouter un shift
          </button>
          <div id="shiftsContainer">
            <!-- Shifts dynamiques -->
          </div>
        </div>

        <!-- ÉTAPE 4: Résumé -->
        <div id="step4" class="trajet-step" style="display: none;">
          <h3 style="margin-bottom: 20px; color: #1B4B7F; display: flex; align-items: center; gap: 8px;"><i data-feather="check-circle" style="width: 20px; height: 20px;"></i> Résumé et confirmation</h3>
          <div id="resumeTrajet" style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <!-- Résumé généré dynamiquement -->
          </div>
        </div>

        <!-- Navigation entre étapes -->
        <div class="modal__footer" style="display: flex; justify-content: space-between; padding-top: 20px; border-top: 1px solid #e5e7eb; margin-top: 20px;">
          <button type="button" class="btn btn--secondary" id="btnPrecedent" style="display: none; align-items: center; gap: 8px;">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Précédent
          </button>
          <div style="flex: 1;"></div>
          <button type="button" class="btn btn--secondary" id="btnAnnulerTrajet">Annuler</button>
          <button type="button" class="btn btn--primary" id="btnSuivant" style="display: inline-flex; align-items: center; gap: 8px;">
            Suivant <i data-feather="arrow-right" style="width: 16px; height: 16px;"></i>
          </button>
          <button type="submit" class="btn btn--primary" id="btnEnregistrer" style="display: none; align-items: center; gap: 8px;">
            <i data-feather="save" style="width: 16px; height: 16px;"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  
  <script>
    let currentTrajetId = null;
    let isEditMode = false;

    // Fonction pour voir un trajet (modal détails) - AJAX
    function voirTrajet(trajetId) {
      // Afficher le modal avec loader
      document.getElementById('modalDetailsTrajet').classList.add('active');
      document.getElementById('detailsTrajetTitle').textContent = 'Chargement...';
      
      // Appel AJAX
      fetch(`/trajets/details?trajet_id=${trajetId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const trajet = data.trajet;
            const arrets = data.arrets;
            const pointsChifte = data.pointsChifte;
            
            // Remplir le modal avec les vraies données
            document.getElementById('detailsTrajetTitle').textContent = 'Détails du Trajet - ' + trajet.nom;
            document.getElementById('detailsNom').textContent = trajet.nom;
            document.getElementById('detailsDistance').textContent = trajet.distance_totale + ' km';
            document.getElementById('detailsStatut').innerHTML = `<span class="status-badge status-badge--${trajet.statut}">${trajet.statut.charAt(0).toUpperCase() + trajet.statut.slice(1)}</span>`;
            
            // Afficher les arrêts
            const arretsContainer = document.getElementById('detailsArrets');
            if (arrets && arrets.length > 0) {
              arretsContainer.innerHTML = arrets.map((arret, index) => `
                <div style="padding: 10px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                  <div>
                    <strong>${index + 1}.</strong> ${arret.nom}
                  </div>
                  <small style="color: #6b7280;">${arret.distance_avec_debut} km</small>
                </div>
              `).join('');
            } else {
              arretsContainer.innerHTML = '<p style="color: #9ca3af; padding: 12px; background: #f9fafb; border-radius: 6px;">Aucun arrêt défini</p>';
            }
            
            // Afficher les points de chifte
            const chiftesContainer = document.getElementById('detailsChiftes');
            if (pointsChifte && pointsChifte.length > 0) {
              chiftesContainer.innerHTML = pointsChifte.map((pc, index) => `
                <div style="padding: 10px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                  <div>
                    <strong>${index + 1}.</strong> ${pc.nom}
                  </div>
                  <small style="color: #6b7280;">${pc.distance_avec_debut} km</small>
                </div>
              `).join('');
            } else {
              chiftesContainer.innerHTML = '<p style="color: #9ca3af; padding: 12px; background: #f9fafb; border-radius: 6px;">Aucun point de chifte défini</p>';
            }
            
            setTimeout(() => feather.replace(), 10);
          } else {
            alert('Erreur : ' + data.message);
            document.getElementById('modalDetailsTrajet').classList.remove('active');
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors du chargement des détails');
          document.getElementById('modalDetailsTrajet').classList.remove('active');
        });
    }
    
    // Fonction pour modifier un trajet - NOUVEAU SYSTÈME EN ÉTAPES
    function modifierTrajet(trajetId) {
      console.log('🔧 MODIFICATION DU TRAJET:', trajetId);
      
      // Afficher le modal avec loader
      document.getElementById('modalTrajet').classList.add('active');
      document.getElementById('modalTrajetTitle').textContent = 'Chargement...';
      
      // Appel AJAX
      fetch(`/trajets/details?trajet_id=${trajetId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const trajet = data.trajet;
            const arrets = data.arrets || [];
            const pointsChifte = data.pointsChifte || [];
            
            console.log('📦 Données reçues:', { trajet, arrets, pointsChifte });
            
            // Remplir l'étape 1 : Informations de base
            document.getElementById('modalTrajetTitle').textContent = 'Modifier le Trajet';
            document.getElementById('trajetId').value = trajet.id;
            document.getElementById('trajetNom').value = trajet.nom;
            document.getElementById('trajetStatut').value = trajet.statut;
            
            // Remplir les coordonnées GPS
            document.getElementById('latDepart').value = trajet.latitude_depart || '';
            document.getElementById('lonDepart').value = trajet.longitude_depart || '';
            document.getElementById('latArrivee').value = trajet.latitude_arrivee || '';
            document.getElementById('lonArrivee').value = trajet.longitude_arrivee || '';
            
            // Calculer automatiquement la distance et le temps
            calculerDistanceTrajet();
            
            // Vider les conteneurs d'arrêts et de shifts
            document.getElementById('arretsContainer').innerHTML = '';
            document.getElementById('shiftsContainer').innerHTML = '';
            arretIndex = 0;
            shiftIndex = 0;
            
            // Charger les arrêts existants
            arrets.forEach(arret => {
              arretIndex++;
              const arretHtml = `
                <div class="arret-item" id="arret${arretIndex}" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e5e7eb;">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h4 style="margin: 0; color: #374151;">Arrêt #${arretIndex}</h4>
                    <button type="button" class="btn-icon btn-icon--delete" onclick="supprimerArret(${arretIndex})">
                      <i data-feather="trash-2"></i>
                    </button>
                  </div>
                  <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div class="form-group" style="margin: 0;">
                      <label>Nom de l'arrêt *</label>
                      <input type="text" class="form-control arret-nom" data-index="${arretIndex}" placeholder="Ex: Gare centrale" value="${arret.nom}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                      <label>Latitude *</label>
                      <input type="number" class="form-control arret-lat" data-index="${arretIndex}" step="0.000001" min="-90" max="90" value="${arret.latitude || ''}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                      <label>Longitude *</label>
                      <input type="number" class="form-control arret-lon" data-index="${arretIndex}" step="0.000001" min="-180" max="180" value="${arret.longitude || ''}">
                    </div>
                  </div>
                  <div style="background: #e0f2fe; padding: 12px; border-radius: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                      <label style="font-size: 11px; color: #6b7280;">Distance depuis départ</label>
                      <input type="text" class="form-control arret-distance" id="arretDistance${arretIndex}" readonly style="background: white; font-weight: 700; color: #0369a1; font-size: 13px;" value="${arret.distance_avec_debut ? parseFloat(arret.distance_avec_debut).toFixed(2) + ' km' : '-- km'}">
                    </div>
                    <div>
                      <label style="font-size: 11px; color: #6b7280;">Temps depuis départ</label>
                      <input type="text" class="form-control arret-temps" id="arretTemps${arretIndex}" readonly style="background: white; font-weight: 700; color: #0369a1; font-size: 13px;" value="-- min">
                    </div>
                  </div>
                </div>
              `;
              document.getElementById('arretsContainer').insertAdjacentHTML('beforeend', arretHtml);
              
              // Ajouter les listeners pour recalculer
              const latInput = document.querySelector(`.arret-lat[data-index="${arretIndex}"]`);
              const lonInput = document.querySelector(`.arret-lon[data-index="${arretIndex}"]`);
              [latInput, lonInput].forEach(input => {
                input.addEventListener('input', () => calculerDistanceArret(arretIndex));
              });
              
              // Calculer la distance initiale
              setTimeout(() => calculerDistanceArret(arretIndex), 100);
            });
            
            // Charger les shifts existants
            pointsChifte.forEach(shift => {
              shiftIndex++;
              const shiftHtml = `
                <div class="shift-item" id="shift${shiftIndex}" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e5e7eb;">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h4 style="margin: 0; color: #374151;">Shift #${shiftIndex}</h4>
                    <button type="button" class="btn-icon btn-icon--delete" onclick="supprimerShift(${shiftIndex})">
                      <i data-feather="trash-2"></i>
                    </button>
                  </div>
                  <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div class="form-group" style="margin: 0;">
                      <label>Nom du shift *</label>
                      <input type="text" class="form-control shift-nom" data-index="${shiftIndex}" placeholder="Ex: Shift matin" value="${shift.nom}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                      <label>Latitude *</label>
                      <input type="number" class="form-control shift-lat" data-index="${shiftIndex}" step="0.000001" min="-90" max="90" value="${shift.latitude || ''}">
                    </div>
                    <div class="form-group" style="margin: 0;">
                      <label>Longitude *</label>
                      <input type="number" class="form-control shift-lon" data-index="${shiftIndex}" step="0.000001" min="-180" max="180" value="${shift.longitude || ''}">
                    </div>
                  </div>
                  <div style="background: #fef3c7; padding: 12px; border-radius: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                      <label style="font-size: 11px; color: #6b7280;">Distance depuis départ</label>
                      <input type="text" class="form-control shift-distance" id="shiftDistance${shiftIndex}" readonly style="background: white; font-weight: 700; color: #92400e; font-size: 13px;" value="${shift.distance_avec_debut ? parseFloat(shift.distance_avec_debut).toFixed(2) + ' km' : '-- km'}">
                    </div>
                    <div>
                      <label style="font-size: 11px; color: #6b7280;">Temps depuis départ</label>
                      <input type="text" class="form-control shift-temps" id="shiftTemps${shiftIndex}" readonly style="background: white; font-weight: 700; color: #92400e; font-size: 13px;" value="-- min">
                    </div>
                  </div>
                </div>
              `;
              document.getElementById('shiftsContainer').insertAdjacentHTML('beforeend', shiftHtml);
              
              // Ajouter les listeners pour recalculer
              const latInput = document.querySelector(`.shift-lat[data-index="${shiftIndex}"]`);
              const lonInput = document.querySelector(`.shift-lon[data-index="${shiftIndex}"]`);
              [latInput, lonInput].forEach(input => {
                input.addEventListener('input', () => calculerDistanceShift(shiftIndex));
              });
              
              // Calculer la distance initiale
              setTimeout(() => calculerDistanceShift(shiftIndex), 100);
            });
            
            // Afficher l'étape 1
            afficherEtape(1);
            
            setTimeout(() => feather.replace(), 200);
          } else {
            alert('Erreur : ' + data.message);
            document.getElementById('modalTrajet').classList.remove('active');
          }
        })
        .catch(error => {
          console.error('Erreur complète:', error);
          alert('Erreur lors du chargement des données. Vérifiez la console (F12) pour plus de détails.');
          document.getElementById('modalTrajet').classList.remove('active');
        });
    }
    
    // Fonction pour supprimer un trajet
    function supprimerTrajet(trajetId) {
      if (!confirm('Voulez-vous vraiment supprimer ce trajet ?')) {
        return;
      }
      
      fetch('/trajets/delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: trajetId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          location.reload();
        } else {
          alert('Erreur : ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression');
      });
    }
    
    // Fonction de pagination
    function goToPageTrajet(page) {
      window.location.href = `/trajets?page=${page}`;
    }
    
    // Bouton Nouveau Trajet
    document.getElementById('btnNouveauTrajet')?.addEventListener('click', function() {
      isEditMode = false;
      currentTrajetId = null;
      
      document.getElementById('modalTrajetTitle').textContent = 'Nouveau Trajet';
      document.getElementById('formTrajet').reset();
      document.getElementById('trajetId').value = '';
      
      // Vider les conteneurs d'arrêts et de chiftes
      document.getElementById('arretsContainer').innerHTML = '';
      document.getElementById('chiftesContainer').innerHTML = '';
      
      // Réinitialiser les compteurs
      arretCounter = 0;
      chifteCounter = 0;
      
      document.getElementById('modalTrajet').classList.add('active');
      setTimeout(() => feather.replace(), 10);
    });
    
    // Fermer modal détails
    document.getElementById('btnCloseModalDetails')?.addEventListener('click', function() {
      document.getElementById('modalDetailsTrajet').classList.remove('active');
    });
    
    document.querySelector('#modalDetailsTrajet .modal__overlay')?.addEventListener('click', function() {
      document.getElementById('modalDetailsTrajet').classList.remove('active');
    });
    
    // Fermer modal trajet
    document.getElementById('btnCloseModalTrajet')?.addEventListener('click', function() {
      document.getElementById('modalTrajet').classList.remove('active');
    });
    
    document.getElementById('btnAnnulerTrajet')?.addEventListener('click', function() {
      document.getElementById('modalTrajet').classList.remove('active');
    });
    
    document.querySelector('#modalTrajet .modal__overlay')?.addEventListener('click', function() {
      document.getElementById('modalTrajet').classList.remove('active');
    });
    
    // ANCIEN CODE SUPPRIMÉ - Utilise maintenant le nouveau système avec étapes
    
    // Recherche et filtrage
    const searchInput = document.getElementById('searchTrajet');
    const filterStatut = document.getElementById('filterStatutTrajet');
    const btnFiltrer = document.getElementById('btnFiltrerTrajet');
    
    function filtrerTrajets() {
      const searchTerm = searchInput.value.toLowerCase();
      const statutFilter = filterStatut.value.toLowerCase();
      const rows = document.querySelectorAll('.table tbody tr');
      
      rows.forEach(row => {
        // Ignorer la ligne "Aucun trajet disponible"
        if (row.cells.length < 6) {
          return;
        }
        
        const nom = row.cells[0].textContent.toLowerCase();
        const statut = row.querySelector('.status-badge')?.textContent.toLowerCase() || '';
        
        const matchSearch = nom.includes(searchTerm);
        const matchStatut = !statutFilter || statut.includes(statutFilter);
        
        if (matchSearch && matchStatut) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
    
    // Événements de recherche
    searchInput?.addEventListener('input', filtrerTrajets);
    filterStatut?.addEventListener('change', filtrerTrajets);
    btnFiltrer?.addEventListener('click', filtrerTrajets);
    
    // ANCIENNES FONCTIONS SUPPRIMÉES - Utilise maintenant le nouveau système avec étapes et Haversine

    // ANCIEN CODE SUPPRIMÉ - Utilise maintenant le nouveau système avec Haversine

    // ========================================
    // SYSTÈME DE GESTION DU MODAL EN ÉTAPES
    // ========================================
    
    let currentStep = 1;
    const totalSteps = 4;
    let arretsData = [];
    let shiftsData = [];

    // Formule de Haversine pour calculer la distance entre deux points GPS
    function calculerDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Rayon de la Terre en km
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      return R * c; // Distance en km
    }

    // Calculer le temps estimé (distance / vitesse * 60 pour avoir en minutes)
    function calculerTemps(distance) {
      const vitesse = 40; // km/h
      return (distance / vitesse) * 60; // en minutes
    }

    // Calculer automatiquement la distance et le temps dès que les coordonnées changent
    function calculerDistanceTrajet() {
      const latDepart = parseFloat(document.getElementById('latDepart').value);
      const lonDepart = parseFloat(document.getElementById('lonDepart').value);
      const latArrivee = parseFloat(document.getElementById('latArrivee').value);
      const lonArrivee = parseFloat(document.getElementById('lonArrivee').value);

      if (latDepart && lonDepart && latArrivee && lonArrivee) {
        const distance = calculerDistance(latDepart, lonDepart, latArrivee, lonArrivee);
        const temps = calculerTemps(distance);
        
        document.getElementById('distanceCalculee').value = distance.toFixed(2) + ' km';
        document.getElementById('tempsCalcule').value = Math.round(temps) + ' min';
      }
    }

    // Écouter les changements sur les champs de coordonnées
    ['latDepart', 'lonDepart', 'latArrivee', 'lonArrivee'].forEach(id => {
      const element = document.getElementById(id);
      if (element) {
        element.addEventListener('input', calculerDistanceTrajet);
      }
    });

    // Navigation entre les étapes
    function afficherEtape(etape) {
      // Cacher toutes les étapes
      for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('step' + i).style.display = 'none';
        const indicator = document.getElementById('stepIndicator' + i);
        const circle = indicator.querySelector('div:first-child');
        if (i <= etape) {
          circle.style.background = '#1B4B7F';
          circle.style.color = 'white';
        } else {
          circle.style.background = '#e5e7eb';
          circle.style.color = '#6b7280';
        }
      }
      
      // Afficher l'étape courante
      document.getElementById('step' + etape).style.display = 'block';
      currentStep = etape;

      // Gérer l'affichage des boutons
      document.getElementById('btnPrecedent').style.display = etape > 1 ? 'inline-flex' : 'none';
      document.getElementById('btnSuivant').style.display = etape < totalSteps ? 'inline-flex' : 'none';
      document.getElementById('btnEnregistrer').style.display = etape === totalSteps ? 'inline-flex' : 'none';

      // Rafraîchir les icônes
      feather.replace();
    }

    // Bouton Suivant
    const btnSuivant = document.getElementById('btnSuivant');
    if (btnSuivant) {
    btnSuivant.addEventListener('click', () => {
      if (currentStep === 1) {
        // Valider l'étape 1
        const nom = document.getElementById('trajetNom').value;
        const latD = document.getElementById('latDepart').value;
        const lonD = document.getElementById('lonDepart').value;
        const latA = document.getElementById('latArrivee').value;
        const lonA = document.getElementById('lonArrivee').value;

        if (!nom || !latD || !lonD || !latA || !lonA) {
          alert('Veuillez remplir tous les champs obligatoires');
          return;
        }
      }

      if (currentStep < totalSteps) {
        if (currentStep === 3) {
          // Avant d'aller au résumé, générer le résumé
          genererResume();
        }
        afficherEtape(currentStep + 1);
      }
    });
    }

    // Bouton Précédent
    const btnPrecedent = document.getElementById('btnPrecedent');
    if (btnPrecedent) {
    btnPrecedent.addEventListener('click', () => {
      if (currentStep > 1) {
        afficherEtape(currentStep - 1);
      }
    });
    }

    // Ajouter un arrêt
    let arretIndex = 0;
    const btnAjouterArret = document.getElementById('btnAjouterArret');
    if (btnAjouterArret) {
    btnAjouterArret.addEventListener('click', () => {
      arretIndex++;
      const arretHtml = `
        <div class="arret-item" id="arret${arretIndex}" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e5e7eb;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="margin: 0; color: #374151;">Arrêt #${arretIndex}</h4>
            <button type="button" class="btn-icon btn-icon--delete" onclick="supprimerArret(${arretIndex})">
              <i data-feather="trash-2"></i>
            </button>
          </div>
          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
            <div class="form-group" style="margin: 0;">
              <label>Nom de l'arrêt *</label>
              <input type="text" class="form-control arret-nom" data-index="${arretIndex}" placeholder="Ex: Gare centrale">
            </div>
            <div class="form-group" style="margin: 0;">
              <label>Latitude *</label>
              <input type="number" class="form-control arret-lat" data-index="${arretIndex}" step="0.000001" min="-90" max="90">
            </div>
            <div class="form-group" style="margin: 0;">
              <label>Longitude *</label>
              <input type="number" class="form-control arret-lon" data-index="${arretIndex}" step="0.000001" min="-180" max="180">
            </div>
          </div>
          <div style="background: #e0f2fe; padding: 12px; border-radius: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <label style="font-size: 11px; color: #6b7280;">Distance depuis départ</label>
              <input type="text" class="form-control arret-distance" id="arretDistance${arretIndex}" readonly style="background: white; font-weight: 700; color: #0369a1; font-size: 13px;" value="-- km">
            </div>
            <div>
              <label style="font-size: 11px; color: #6b7280;">Temps depuis départ</label>
              <input type="text" class="form-control arret-temps" id="arretTemps${arretIndex}" readonly style="background: white; font-weight: 700; color: #0369a1; font-size: 13px;" value="-- min">
            </div>
          </div>
        </div>
      `;
      document.getElementById('arretsContainer').insertAdjacentHTML('beforeend', arretHtml);
      
      // Ajouter les listeners pour calculer automatiquement
      const latInput = document.querySelector(`.arret-lat[data-index="${arretIndex}"]`);
      const lonInput = document.querySelector(`.arret-lon[data-index="${arretIndex}"]`);
      
      [latInput, lonInput].forEach(input => {
        input.addEventListener('input', () => calculerDistanceArret(arretIndex));
      });
      
      feather.replace();
    });
    }

    // Calculer la distance d'un arrêt depuis le point de départ
    function calculerDistanceArret(index) {
      const latDepart = parseFloat(document.getElementById('latDepart').value);
      const lonDepart = parseFloat(document.getElementById('lonDepart').value);
      const latArret = parseFloat(document.querySelector(`.arret-lat[data-index="${index}"]`).value);
      const lonArret = parseFloat(document.querySelector(`.arret-lon[data-index="${index}"]`).value);

      if (latDepart && lonDepart && latArret && lonArret) {
        const distance = calculerDistance(latDepart, lonDepart, latArret, lonArret);
        const temps = calculerTemps(distance);
        
        document.getElementById('arretDistance' + index).value = distance.toFixed(2) + ' km';
        document.getElementById('arretTemps' + index).value = Math.round(temps) + ' min';
      }
    }

    // Supprimer un arrêt
    function supprimerArret(index) {
      document.getElementById('arret' + index).remove();
    }

    // Ajouter un shift
    let shiftIndex = 0;
    const btnAjouterShift = document.getElementById('btnAjouterShift');
    if (btnAjouterShift) {
    btnAjouterShift.addEventListener('click', () => {
      shiftIndex++;
      const shiftHtml = `
        <div class="shift-item" id="shift${shiftIndex}" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e5e7eb;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="margin: 0; color: #374151;">Shift #${shiftIndex}</h4>
            <button type="button" class="btn-icon btn-icon--delete" onclick="supprimerShift(${shiftIndex})">
              <i data-feather="trash-2"></i>
            </button>
          </div>
          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
            <div class="form-group" style="margin: 0;">
              <label>Nom du shift *</label>
              <input type="text" class="form-control shift-nom" data-index="${shiftIndex}" placeholder="Ex: Shift matin">
            </div>
            <div class="form-group" style="margin: 0;">
              <label>Latitude *</label>
              <input type="number" class="form-control shift-lat" data-index="${shiftIndex}" step="0.000001" min="-90" max="90">
            </div>
            <div class="form-group" style="margin: 0;">
              <label>Longitude *</label>
              <input type="number" class="form-control shift-lon" data-index="${shiftIndex}" step="0.000001" min="-180" max="180">
            </div>
          </div>
          <div style="background: #fef3c7; padding: 12px; border-radius: 6px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <label style="font-size: 11px; color: #6b7280;">Distance depuis départ</label>
              <input type="text" class="form-control shift-distance" id="shiftDistance${shiftIndex}" readonly style="background: white; font-weight: 700; color: #92400e; font-size: 13px;" value="-- km">
            </div>
            <div>
              <label style="font-size: 11px; color: #6b7280;">Temps depuis départ</label>
              <input type="text" class="form-control shift-temps" id="shiftTemps${shiftIndex}" readonly style="background: white; font-weight: 700; color: #92400e; font-size: 13px;" value="-- min">
            </div>
          </div>
        </div>
      `;
      document.getElementById('shiftsContainer').insertAdjacentHTML('beforeend', shiftHtml);
      
      // Ajouter les listeners pour calculer automatiquement
      const latInput = document.querySelector(`.shift-lat[data-index="${shiftIndex}"]`);
      const lonInput = document.querySelector(`.shift-lon[data-index="${shiftIndex}"]`);
      
      [latInput, lonInput].forEach(input => {
        input.addEventListener('input', () => calculerDistanceShift(shiftIndex));
      });
      
      feather.replace();
    });
    }

    // Calculer la distance d'un shift depuis le point de départ
    function calculerDistanceShift(index) {
      const latDepart = parseFloat(document.getElementById('latDepart').value);
      const lonDepart = parseFloat(document.getElementById('lonDepart').value);
      const latShift = parseFloat(document.querySelector(`.shift-lat[data-index="${index}"]`).value);
      const lonShift = parseFloat(document.querySelector(`.shift-lon[data-index="${index}"]`).value);

      if (latDepart && lonDepart && latShift && lonShift) {
        const distance = calculerDistance(latDepart, lonDepart, latShift, lonShift);
        const temps = calculerTemps(distance);
        
        document.getElementById('shiftDistance' + index).value = distance.toFixed(2) + ' km';
        document.getElementById('shiftTemps' + index).value = Math.round(temps) + ' min';
      }
    }

    // Supprimer un shift
    function supprimerShift(index) {
      document.getElementById('shift' + index).remove();
    }

    // Générer le résumé
    function genererResume() {
      const nom = document.getElementById('trajetNom').value;
      const statut = document.getElementById('trajetStatut').value;
      const distance = document.getElementById('distanceCalculee').value;
      const temps = document.getElementById('tempsCalcule').value;

      // Collecter les arrêts
      const arrets = [];
      document.querySelectorAll('.arret-item').forEach((item, index) => {
        const nomArret = item.querySelector('.arret-nom').value;
        const distanceArret = item.querySelector('.arret-distance').value;
        const tempsArret = item.querySelector('.arret-temps').value;
        if (nomArret) {
          arrets.push({ nom: nomArret, distance: distanceArret, temps: tempsArret });
        }
      });

      // Collecter les shifts
      const shifts = [];
      document.querySelectorAll('.shift-item').forEach((item, index) => {
        const nomShift = item.querySelector('.shift-nom').value;
        const distanceShift = item.querySelector('.shift-distance').value;
        const tempsShift = item.querySelector('.shift-temps').value;
        if (nomShift) {
          shifts.push({ nom: nomShift, distance: distanceShift, temps: tempsShift });
        }
      });

      // Générer le HTML du résumé
      let resumeHtml = `
        <div style="margin-bottom: 20px;">
          <h4 style="color: #1B4B7F; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;"><i data-feather="map-pin" style="width: 18px; height: 18px;"></i> Informations du trajet</h4>
          <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div><strong>Nom :</strong> ${nom}</div>
              <div><strong>Statut :</strong> <span class="status-badge status-badge--${statut}">${statut.charAt(0).toUpperCase() + statut.slice(1)}</span></div>
              <div><strong>Distance totale :</strong> ${distance}</div>
              <div><strong>Temps estimé :</strong> ${temps}</div>
            </div>
          </div>
        </div>
      `;

      if (arrets.length > 0) {
        resumeHtml += `
          <div style="margin-bottom: 20px;">
            <h4 style="color: #1B4B7F; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;"><i data-feather="map-pin" style="width: 18px; height: 18px;"></i> Arrêts (${arrets.length})</h4>
            <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
              ${arrets.map((a, i) => `
                <div style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                  <div><strong>${i + 1}.</strong> ${a.nom}</div>
                  <div style="color: #6b7280; font-size: 13px;">${a.distance} • ${a.temps}</div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
      }

      if (shifts.length > 0) {
        resumeHtml += `
          <div style="margin-bottom: 20px;">
            <h4 style="color: #1B4B7F; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;"><i data-feather="refresh-cw" style="width: 18px; height: 18px;"></i> Shifts (${shifts.length})</h4>
            <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
              ${shifts.map((s, i) => `
                <div style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                  <div><strong>${i + 1}.</strong> ${s.nom}</div>
                  <div style="color: #6b7280; font-size: 13px;">${s.distance} • ${s.temps}</div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
      }

      document.getElementById('resumeTrajet').innerHTML = resumeHtml;
      
      // Rafraîchir les icônes Feather dans le résumé
      setTimeout(() => feather.replace(), 10);
    }

    // Ouvrir le modal pour nouveau trajet
    document.getElementById('btnNouveauTrajet').addEventListener('click', () => {
      document.getElementById('modalTrajet').classList.add('active');
      document.getElementById('modalTrajetTitle').textContent = 'Nouveau Trajet';
      document.getElementById('formTrajet').reset();
      document.getElementById('trajetId').value = '';
      document.getElementById('arretsContainer').innerHTML = '';
      document.getElementById('shiftsContainer').innerHTML = '';
      arretIndex = 0;
      shiftIndex = 0;
      afficherEtape(1);
      feather.replace();
    });

    // Fermer le modal
    document.getElementById('btnCloseModalTrajet').addEventListener('click', () => {
      document.getElementById('modalTrajet').classList.remove('active');
    });

    document.getElementById('btnAnnulerTrajet').addEventListener('click', () => {
      document.getElementById('modalTrajet').classList.remove('active');
    });

    // Debug: Log sur le bouton Enregistrer
    const btnEnregistrer = document.getElementById('btnEnregistrer');
    if (btnEnregistrer) {
      btnEnregistrer.addEventListener('click', () => {
        console.log('🔘 BOUTON ENREGISTRER CLIQUÉ !');
        console.log('📋 Étape actuelle:', currentStep);
        console.log('🎯 Type du bouton:', btnEnregistrer.type);
      });
    }

    // Soumettre le formulaire
    const formTrajet = document.getElementById('formTrajet');
    if (formTrajet) {
    formTrajet.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      console.log('🚀 DÉBUT DE LA SOUMISSION DU FORMULAIRE');
      console.log('📋 Étape actuelle:', currentStep);
      
      // Collecter toutes les données
      const trajetData = {
        id: document.getElementById('trajetId').value,
        nom: document.getElementById('trajetNom').value,
        statut: document.getElementById('trajetStatut').value,
        lat_depart: document.getElementById('latDepart').value,
        lon_depart: document.getElementById('lonDepart').value,
        lat_arrivee: document.getElementById('latArrivee').value,
        lon_arrivee: document.getElementById('lonArrivee').value,
        distance_totale: parseFloat(document.getElementById('distanceCalculee').value),
        arrets: [],
        shifts: []
      };
      
      console.log('📦 Données de base collectées:', trajetData);

      // Collecter les arrêts
      const arretsItems = document.querySelectorAll('.arret-item');
      console.log('🚏 Nombre d\'arrêts trouvés:', arretsItems.length);
      
      arretsItems.forEach((item, index) => {
        const nom = item.querySelector('.arret-nom').value;
        const lat = item.querySelector('.arret-lat').value;
        const lon = item.querySelector('.arret-lon').value;
        const distance = parseFloat(item.querySelector('.arret-distance').value);
        console.log(`  Arrêt ${index + 1}:`, { nom, lat, lon, distance });
        if (nom && lat && lon) {
          trajetData.arrets.push({ nom, latitude: lat, longitude: lon, distance_avec_debut: distance });
        }
      });

      // Collecter les shifts
      const shiftsItems = document.querySelectorAll('.shift-item');
      console.log('🔄 Nombre de shifts trouvés:', shiftsItems.length);
      
      shiftsItems.forEach((item, index) => {
        const nom = item.querySelector('.shift-nom').value;
        const lat = item.querySelector('.shift-lat').value;
        const lon = item.querySelector('.shift-lon').value;
        const distance = parseFloat(item.querySelector('.shift-distance').value);
        const temps = parseFloat(item.querySelector('.shift-temps').value);
        console.log(`  Shift ${index + 1}:`, { nom, lat, lon, distance, temps });
        if (nom && lat && lon) {
          trajetData.shifts.push({ 
            nom, 
            latitude: lat, 
            longitude: lon, 
            distance_avec_debut: distance,
            temp_parcour: temps
          });
        }
      });

      console.log('📤 Données COMPLÈTES à enregistrer:', trajetData);

      // Envoyer les données au serveur
      console.log('🌐 Envoi de la requête vers /trajets/save...');
      
      try {
        const response = await fetch('/trajets/save', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(trajetData)
        });

        console.log('📡 Réponse reçue, status:', response.status);
        
        const result = await response.json();
        console.log('📥 Résultat:', result);

        if (result.success) {
          console.log('✅ Succès !');
          alert('✓ Trajet enregistré avec succès !');
          document.getElementById('modalTrajet').classList.remove('active');
          window.location.reload();
        } else {
          console.error('❌ Échec:', result.message);
          alert('Erreur : ' + result.message);
        }
      } catch (error) {
        console.error('💥 ERREUR CRITIQUE:', error);
        alert('Erreur lors de l\'enregistrement');
      }
    });
    }
    
    // Initialisation des icônes Feather
    setTimeout(() => feather.replace(), 10);
  </script>
</body>
</html>
