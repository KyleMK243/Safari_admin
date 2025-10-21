<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Alertes & Notifications • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    /* Styles pour les modals */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    
    .modal--active {
      display: flex !important;
    }
    
    .modal__overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }
    
    .modal__content {
      position: relative;
      background: white;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 90%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
      z-index: 10000;
    }
    
    .modal__header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .modal__title {
      font-size: 20px;
      font-weight: 700;
      color: #111827;
      margin: 0;
    }
    
    .modal__close {
      background: none;
      border: none;
      cursor: pointer;
      padding: 8px;
      color: #6b7280;
      transition: color 0.2s;
    }
    
    .modal__close:hover {
      color: #111827;
    }
    
    .modal__body {
      padding: 24px;
    }
    
    .modal__footer {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      padding: 20px 24px;
      border-top: 1px solid #e5e7eb;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #374151;
    }
    
    .form-control {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 14px;
      transition: border-color 0.2s;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn--danger {
      background: #ef4444;
      color: white;
    }
    
    .btn--danger:hover {
      background: #dc2626;
    }
  </style>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Alertes & Notifications</h1>
          <p>Suivi des événements et alertes système</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" id="btnMarquerToutLu">
            <i data-feather="check-circle"></i> Tout marquer comme lu
          </button>
        </div>
      </header>

      <!-- Stats rapides -->
      <section class="alert-stats">
        <div class="alert-stat-card alert-stat-card--critical">
          <div class="alert-stat-card__icon">
            <i data-feather="alert-triangle"></i>
          </div>
          <div class="alert-stat-card__content">
            <div class="alert-stat-card__value" id="statCritiques"><?= $stats['critiques'] ?? 0 ?></div>
            <div class="alert-stat-card__label">Critiques</div>
          </div>
        </div>

        <div class="alert-stat-card alert-stat-card--warning">
          <div class="alert-stat-card__icon">
            <i data-feather="alert-circle"></i>
          </div>
          <div class="alert-stat-card__content">
            <div class="alert-stat-card__value" id="statAvertissements"><?= $stats['avertissements'] ?? 0 ?></div>
            <div class="alert-stat-card__label">Avertissements</div>
          </div>
        </div>

        <div class="alert-stat-card alert-stat-card--info">
          <div class="alert-stat-card__icon">
            <i data-feather="info"></i>
          </div>
          <div class="alert-stat-card__content">
            <div class="alert-stat-card__value" id="statInformations"><?= $stats['informations'] ?? 0 ?></div>
            <div class="alert-stat-card__label">Informations</div>
          </div>
        </div>

        <div class="alert-stat-card alert-stat-card--success">
          <div class="alert-stat-card__icon">
            <i data-feather="check-circle"></i>
          </div>
          <div class="alert-stat-card__content">
            <div class="alert-stat-card__value" id="statResolues"><?= $stats['resolus'] ?? 0 ?></div>
            <div class="alert-stat-card__label">Résolues</div>
          </div>
        </div>
      </section>

      <!-- Filtres -->
      <section class="filters card">
        <div class="filters__title">
          <i data-feather="filter"></i>
          Filtres
        </div>
        <div class="filters__controls">
          <select id="filterType">
            <option value="">Tous les types</option>
            <option value="critical">Critique</option>
            <option value="warning">Avertissement</option>
            <option value="info">Information</option>
            <option value="success">Succès</option>
          </select>
          <select id="filterStatut">
            <option value="">Tous les statuts</option>
            <option value="nouveau">Nouveau</option>
            <option value="en_cours">En cours</option>
            <option value="resolu">Résolu</option>
          </select>
          <select id="filterPriorite">
            <option value="">Toutes les priorités</option>
            <option value="haute">Haute</option>
            <option value="moyenne">Moyenne</option>
            <option value="basse">Basse</option>
          </select>
          <input type="text" id="searchAlert" placeholder="Rechercher...">
          <button class="btn btn--primary" id="btnFiltrer">Filtrer</button>
        </div>
      </section>

      <!-- Liste des alertes -->
      <section class="card">
        <div class="alerts-list" id="alertsList">
          <?php if (!empty($alertes)) : ?>
            <?php foreach ($alertes as $alerte) : 
              $typeClass = match($alerte['type_alerte']) {
                'critical' => 'critical',
                'warning' => 'warning',
                'info' => 'info',
                'success' => 'success',
                default => 'info'
              };
              
              $typeLabel = match($alerte['type_alerte']) {
                'critical' => 'Critique',
                'warning' => 'Avertissement',
                'info' => 'Information',
                'success' => 'Succès',
                default => 'Info'
              };
              
              $statutClass = match($alerte['statut']) {
                'nouveau' => 'nouveau',
                'en_cours' => 'en-cours',
                'resolu' => 'resolu',
                default => 'nouveau'
              };
              
              // Calculer le temps écoulé
              $dateAlerte = new DateTime($alerte['date_alerte']);
              $maintenant = new DateTime();
              $diff = $maintenant->diff($dateAlerte);
              
              if ($diff->days > 0) {
                $tempsEcoule = "Il y a " . $diff->days . " jour" . ($diff->days > 1 ? 's' : '');
              } elseif ($diff->h > 0) {
                $tempsEcoule = "Il y a " . $diff->h . " heure" . ($diff->h > 1 ? 's' : '');
              } elseif ($diff->i > 0) {
                $tempsEcoule = "Il y a " . $diff->i . " minute" . ($diff->i > 1 ? 's' : '');
              } else {
                $tempsEcoule = "À l'instant";
              }
            ?>
          <div class="alert-item alert-item--<?= $typeClass ?>" data-alerte-id="<?= $alerte['id'] ?>" data-statut="<?= $alerte['statut'] ?>">
            <div class="alert-item__icon">
              <i data-feather="<?= $alerte['type_alerte'] === 'critical' ? 'alert-triangle' : ($alerte['type_alerte'] === 'warning' ? 'alert-circle' : ($alerte['type_alerte'] === 'success' ? 'check-circle' : 'info')) ?>"></i>
            </div>
            <div class="alert-item__content">
              <div class="alert-item__header">
                <h3><?= htmlspecialchars($alerte['titre']) ?></h3>
                <span class="alert-badge alert-badge--<?= $typeClass ?>"><?= $typeLabel ?></span>
              </div>
              <p class="alert-item__message"><?= htmlspecialchars($alerte['message']) ?></p>
              <div class="alert-item__meta">
                <span><i data-feather="clock"></i> <?= $tempsEcoule ?></span>
                <?php if ($alerte['localisation']) : ?>
                  <span><i data-feather="map-pin"></i> <?= htmlspecialchars($alerte['localisation']) ?></span>
                <?php endif; ?>
                <?php if ($alerte['bus_numero']) : ?>
                  <span><i data-feather="truck"></i> Bus #<?= htmlspecialchars($alerte['bus_numero']) ?></span>
                <?php endif; ?>
                <?php if ($alerte['membre_nom']) : ?>
                  <span><i data-feather="user"></i> <?= htmlspecialchars($alerte['membre_nom']) ?></span>
                <?php endif; ?>
                <span class="alert-item__priorite" style="background: <?= $alerte['priorite'] === 'haute' ? '#ef4444' : ($alerte['priorite'] === 'moyenne' ? '#f59e0b' : '#6b7280') ?>; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                  <?= ucfirst($alerte['priorite']) ?>
                </span>
              </div>
            </div>
            <div class="alert-item__actions">
              <?php if ($alerte['statut'] === 'nouveau') : ?>
                <button class="btn btn--sm btn--primary" onclick="ouvrirModalTraitement(<?= $alerte['id'] ?>, '<?= htmlspecialchars($alerte['titre'], ENT_QUOTES) ?>')">
                  <i data-feather="play-circle"></i> Traiter
                </button>
                <button class="btn btn--sm btn--secondary" onclick="ouvrirModalIgnorer(<?= $alerte['id'] ?>, '<?= htmlspecialchars($alerte['titre'], ENT_QUOTES) ?>')">
                  <i data-feather="x"></i> Ignorer
                </button>
              <?php elseif ($alerte['statut'] === 'en_cours') : ?>
                <button class="btn btn--sm btn--success" onclick="ouvrirModalResoudre(<?= $alerte['id'] ?>, '<?= htmlspecialchars($alerte['titre'], ENT_QUOTES) ?>')">
                  <i data-feather="check-circle"></i> Résoudre
                </button>
              <?php else : ?>
                <span class="alert-badge alert-badge--success">
                  <i data-feather="check"></i> Résolu
                </span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
          <?php else : ?>
            <div style="text-align: center; padding: 40px; color: #6b7280;">
              <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
              <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">Aucune alerte</p>
              <p>Toutes les alertes ont été traitées ou aucune alerte n'a été générée.</p>
            </div>
          <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1) : ?>
        <div class="pagination">
          <div class="pagination__info">
            Affichage de <?= $paginationStart ?> à <?= $paginationEnd ?> sur <?= $totalAlertes ?> alertes
          </div>
          <div class="pagination__controls">
            <?php if ($currentPage > 1) : ?>
              <a href="?page=<?= $currentPage - 1 ?><?= !empty($_GET['type_alerte']) ? '&type_alerte=' . $_GET['type_alerte'] : '' ?><?= !empty($_GET['statut']) ? '&statut=' . $_GET['statut'] : '' ?><?= !empty($_GET['priorite']) ? '&priorite=' . $_GET['priorite'] : '' ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="pagination__btn">
                <i data-feather="chevron-left"></i> Précédent
              </a>
            <?php endif; ?>
            
            <span class="pagination__current">Page <?= $currentPage ?> sur <?= $totalPages ?></span>
            
            <?php if ($currentPage < $totalPages) : ?>
              <a href="?page=<?= $currentPage + 1 ?><?= !empty($_GET['type_alerte']) ? '&type_alerte=' . $_GET['type_alerte'] : '' ?><?= !empty($_GET['statut']) ? '&statut=' . $_GET['statut'] : '' ?><?= !empty($_GET['priorite']) ? '&priorite=' . $_GET['priorite'] : '' ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="pagination__btn">
                Suivant <i data-feather="chevron-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Traitement -->
  <div class="modal" id="modalTraitement">
    <div class="modal__overlay" onclick="fermerModal('modalTraitement')"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2 class="modal__title">Traiter l'alerte</h2>
        <button class="modal__close" onclick="fermerModal('modalTraitement')">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <p id="modalTraitementTitre" style="margin-bottom: 20px; font-weight: 600;"></p>
        
        <div class="form-group">
          <label for="typeTraitement">Type de traitement</label>
          <select id="typeTraitement" class="form-control">
            <option value="">-- Sélectionner le type --</option>
            <option value="intervention_technique">Intervention technique</option>
            <option value="reparation">Réparation</option>
            <option value="maintenance">Maintenance</option>
            <option value="remplacement">Remplacement</option>
            <option value="affectation_personnel">Affectation personnel</option>
            <option value="renouvellement_document">Renouvellement document</option>
            <option value="autre">Autre</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="commentaireTraitement">Commentaire (optionnel)</label>
          <textarea id="commentaireTraitement" class="form-control" rows="3" placeholder="Détails du traitement..."></textarea>
        </div>
      </div>
      
      <div class="modal__footer">
        <button class="btn btn--secondary" onclick="fermerModal('modalTraitement')">
          <i data-feather="x"></i> Annuler
        </button>
        <button class="btn btn--primary" onclick="confirmerTraitement()">
          <i data-feather="check"></i> Confirmer le traitement
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Résoudre -->
  <div class="modal" id="modalResoudre">
    <div class="modal__overlay" onclick="fermerModal('modalResoudre')"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2 class="modal__title">Résoudre l'alerte</h2>
        <button class="modal__close" onclick="fermerModal('modalResoudre')">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <p id="modalResoudreTitre" style="margin-bottom: 20px; font-weight: 600;"></p>
        
        <div class="form-group">
          <label for="solutionAppliquee">Solution appliquée</label>
          <select id="solutionAppliquee" class="form-control">
            <option value="">-- Sélectionner la solution --</option>
            <option value="reparation_effectuee">Réparation effectuée</option>
            <option value="remplacement_effectue">Remplacement effectué</option>
            <option value="maintenance_effectuee">Maintenance effectuée</option>
            <option value="document_renouvele">Document renouvelé</option>
            <option value="personnel_affecte">Personnel affecté</option>
            <option value="probleme_resolu">Problème résolu</option>
            <option value="autre">Autre</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="commentaireResolution">Commentaire de résolution</label>
          <textarea id="commentaireResolution" class="form-control" rows="3" placeholder="Détails de la résolution..." required></textarea>
        </div>
      </div>
      
      <div class="modal__footer">
        <button class="btn btn--secondary" onclick="fermerModal('modalResoudre')">
          <i data-feather="x"></i> Annuler
        </button>
        <button class="btn btn--success" onclick="confirmerResolution()">
          <i data-feather="check-circle"></i> Confirmer la résolution
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Ignorer -->
  <div class="modal" id="modalIgnorer">
    <div class="modal__overlay" onclick="fermerModal('modalIgnorer')"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2 class="modal__title">Ignorer l'alerte</h2>
        <button class="modal__close" onclick="fermerModal('modalIgnorer')">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <p id="modalIgnorerTitre" style="margin-bottom: 20px; font-weight: 600;"></p>
        
        <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
            Attention : Cette action marquera l'alerte comme résolue sans traitement
          </p>
        </div>
        
        <div class="form-group">
          <label for="raisonIgnorer">Raison de l'ignorance *</label>
          <select id="raisonIgnorer" class="form-control" required>
            <option value="">-- Sélectionner la raison --</option>
            <option value="fausse_alerte">Fausse alerte</option>
            <option value="deja_traite">Déjà traité</option>
            <option value="non_pertinent">Non pertinent</option>
            <option value="doublon">Doublon</option>
            <option value="autre">Autre</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="commentaireIgnorer">Commentaire *</label>
          <textarea id="commentaireIgnorer" class="form-control" rows="3" placeholder="Expliquez pourquoi vous ignorez cette alerte..." required></textarea>
        </div>
      </div>
      
      <div class="modal__footer">
        <button class="btn btn--secondary" onclick="fermerModal('modalIgnorer')">
          <i data-feather="x"></i> Annuler
        </button>
        <button class="btn btn--danger" onclick="confirmerIgnorer()">
          <i data-feather="x-circle"></i> Confirmer l'ignorance
        </button>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    // Variables globales pour stocker l'ID de l'alerte en cours
    let alerteEnCoursId = null;
    
    // Fonctions pour ouvrir les modals
    function ouvrirModalTraitement(alerteId, titre) {
      alerteEnCoursId = alerteId;
      document.getElementById('modalTraitementTitre').textContent = titre;
      document.getElementById('typeTraitement').value = '';
      document.getElementById('commentaireTraitement').value = '';
      document.getElementById('modalTraitement').classList.add('modal--active');
      feather.replace();
    }
    
    function ouvrirModalResoudre(alerteId, titre) {
      alerteEnCoursId = alerteId;
      document.getElementById('modalResoudreTitre').textContent = titre;
      document.getElementById('solutionAppliquee').value = '';
      document.getElementById('commentaireResolution').value = '';
      document.getElementById('modalResoudre').classList.add('modal--active');
      feather.replace();
    }
    
    function ouvrirModalIgnorer(alerteId, titre) {
      alerteEnCoursId = alerteId;
      document.getElementById('modalIgnorerTitre').textContent = titre;
      document.getElementById('raisonIgnorer').value = '';
      document.getElementById('commentaireIgnorer').value = '';
      document.getElementById('modalIgnorer').classList.add('modal--active');
      feather.replace();
    }
    
    // Fonction pour fermer un modal
    function fermerModal(modalId) {
      document.getElementById(modalId).classList.remove('modal--active');
      alerteEnCoursId = null;
    }
    
    // Confirmer le traitement
    function confirmerTraitement() {
      const typeTraitement = document.getElementById('typeTraitement').value;
      const commentaire = document.getElementById('commentaireTraitement').value;
      
      if (!typeTraitement) {
        alert('Veuillez sélectionner un type de traitement');
        return;
      }
      
      traiterAlerte(alerteEnCoursId, 'traiter', {
        type_traitement: typeTraitement,
        commentaire: commentaire
      });
      
      fermerModal('modalTraitement');
    }
    
    // Confirmer la résolution
    function confirmerResolution() {
      const solution = document.getElementById('solutionAppliquee').value;
      const commentaire = document.getElementById('commentaireResolution').value;
      
      if (!solution) {
        alert('Veuillez sélectionner une solution appliquée');
        return;
      }
      
      if (!commentaire.trim()) {
        alert('Veuillez ajouter un commentaire de résolution');
        return;
      }
      
      traiterAlerte(alerteEnCoursId, 'resoudre', {
        solution: solution,
        commentaire: commentaire
      });
      
      fermerModal('modalResoudre');
    }
    
    // Confirmer l'ignorance
    function confirmerIgnorer() {
      const raison = document.getElementById('raisonIgnorer').value;
      const commentaire = document.getElementById('commentaireIgnorer').value;
      
      if (!raison) {
        alert('Veuillez sélectionner une raison');
        return;
      }
      
      if (!commentaire.trim()) {
        alert('Veuillez ajouter un commentaire');
        return;
      }
      
      traiterAlerte(alerteEnCoursId, 'ignorer', {
        raison: raison,
        commentaire: commentaire
      });
      
      fermerModal('modalIgnorer');
    }
    
    // Fonction pour traiter une alerte
    function traiterAlerte(alerteId, action, details = {}) {
      fetch('/alertes/traiter', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id: alerteId,
          action: action,
          details: details
        })
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
        alert('Erreur lors du traitement de l\'alerte');
      });
    }
    
    // Fonction pour rafraîchir les alertes
    function rafraichirAlertes() {
      const filterType = document.getElementById('filterType').value;
      const filterStatut = document.getElementById('filterStatut').value;
      const filterPriorite = document.getElementById('filterPriorite').value;
      const searchAlert = document.getElementById('searchAlert').value;
      
      let url = '/alertes/getAlertes?limit=20&offset=0';
      if (filterType) url += '&type_alerte=' + filterType;
      if (filterStatut) url += '&statut=' + filterStatut;
      if (filterPriorite) url += '&priorite=' + filterPriorite;
      if (searchAlert) url += '&search=' + encodeURIComponent(searchAlert);
      
      fetch(url)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Mettre à jour les statistiques
            document.getElementById('statCritiques').textContent = data.stats.critiques;
            document.getElementById('statAvertissements').textContent = data.stats.avertissements;
            document.getElementById('statInformations').textContent = data.stats.informations;
            document.getElementById('statResolues').textContent = data.stats.resolus;
            
            console.log('Alertes rafraîchies automatiquement');
          }
        })
        .catch(error => console.error('Erreur lors du rafraîchissement:', error));
    }
    
    // Marquer toutes les alertes comme lues
    document.getElementById('btnMarquerToutLu')?.addEventListener('click', function() {
      if (!confirm('Voulez-vous marquer toutes les alertes comme lues ?')) {
        return;
      }
      
      fetch('/alertes/marquerToutesLues', {
        method: 'POST'
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
        alert('Erreur lors du traitement');
      });
    });
    
    // Filtrage
    document.getElementById('btnFiltrer')?.addEventListener('click', function() {
      const filterType = document.getElementById('filterType').value;
      const filterStatut = document.getElementById('filterStatut').value;
      const filterPriorite = document.getElementById('filterPriorite').value;
      const searchAlert = document.getElementById('searchAlert').value;
      
      let url = window.location.pathname + '?';
      const params = [];
      
      if (filterType) params.push('type_alerte=' + filterType);
      if (filterStatut) params.push('statut=' + filterStatut);
      if (filterPriorite) params.push('priorite=' + filterPriorite);
      if (searchAlert) params.push('search=' + encodeURIComponent(searchAlert));
      
      url += params.join('&');
      window.location.href = url;
    });
    
    // Rafraîchissement automatique toutes les 60 secondes
    setInterval(rafraichirAlertes, 60000);
    
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
      
      // Pré-remplir les filtres si présents dans l'URL
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('type_alerte')) {
        document.getElementById('filterType').value = urlParams.get('type_alerte');
      }
      if (urlParams.get('statut')) {
        document.getElementById('filterStatut').value = urlParams.get('statut');
      }
      if (urlParams.get('priorite')) {
        document.getElementById('filterPriorite').value = urlParams.get('priorite');
      }
      if (urlParams.get('search')) {
        document.getElementById('searchAlert').value = urlParams.get('search');
      }
    });
  </script>
</body>
</html>
