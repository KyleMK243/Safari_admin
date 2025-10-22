<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Équipe de bord • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Amélioration du design Select2 */
    .select2-container--default .select2-selection--single {
      height: 44px !important;
      border: 1px solid #e5e7eb !important;
      border-radius: 8px !important;
      padding: 8px 12px !important;
      font-size: 14px !important;
      transition: all 0.2s ease !important;
    }
    
    .select2-container--default .select2-selection--single:hover {
      border-color: #3b82f6 !important;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 28px !important;
      padding-left: 0 !important;
      color: #1f2937 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
      color: #9ca3af !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 42px !important;
      right: 8px !important;
    }
    
    .select2-dropdown {
      border: 1px solid #e5e7eb !important;
      border-radius: 8px !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    
    .select2-container--default .select2-results__option {
      padding: 10px 12px !important;
      font-size: 14px !important;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #eff6ff !important;
      color: #1e40af !important;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: #3b82f6 !important;
      color: white !important;
    }
    
    .select2-search--dropdown .select2-search__field {
      border: 1px solid #e5e7eb !important;
      border-radius: 6px !important;
      padding: 8px 12px !important;
      font-size: 14px !important;
    }
    
    .select2-search--dropdown .select2-search__field:focus {
      border-color: #3b82f6 !important;
      outline: none !important;
    }
    
    /* Espacement dans le modal d'affectation */
    #modalAffectation .form-group {
      margin-bottom: 20px !important;
    }
    
    #modalAffectation .form-grid {
      gap: 16px !important;
    }
    
    #modalAffectation .divider {
      margin: 32px 0 !important;
      border-top: 1px solid #e5e7eb;
    }
    
    #modalAffectation h3 {
      margin-bottom: 20px !important;
    }
  </style>
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
          <h1>Équipe de bord</h1>
          <p>Gérer les équipes et les affecter aux bus</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" id="btnNouvelleAffectation">
            <i data-feather="plus"></i> Nouvelle Affectation
          </button>
        </div>
      </header>

      <!-- Filter bar -->
      <section class="filters card">
        <div class="filters__title">
          <i data-feather="filter"></i>
          Filtres
        </div>
        <form method="GET" action="<?php echo BASE_URL; ?>/equipe-bord" class="filters__controls" id="formFiltre">
          <select name="poste" id="filterPoste">
            <option value="">Tous les postes</option>
            <option value="chauffeur" <?php echo (isset($_GET['poste']) && $_GET['poste'] == 'chauffeur') ? 'selected' : ''; ?>>Chauffeur</option>
            <option value="controleur" <?php echo (isset($_GET['poste']) && $_GET['poste'] == 'controleur') ? 'selected' : ''; ?>>Contrôleur</option>
            <option value="receveur" <?php echo (isset($_GET['poste']) && $_GET['poste'] == 'receveur') ? 'selected' : ''; ?>>Receveur</option>
          </select>
          <select name="statut" id="filterStatutEquipe">
            <option value="">Tous les statuts</option>
            <option value="actif" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'actif') ? 'selected' : ''; ?>>Actif</option>
            <option value="conge" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'conge') ? 'selected' : ''; ?>>En congé</option>
            <option value="inactif" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'inactif') ? 'selected' : ''; ?>>Inactif</option>
          </select>
          <input type="text" name="search" id="searchEquipe" placeholder="Rechercher un membre..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="min-width: 200px;">
          <button type="submit" class="btn btn--primary" id="btnFiltrer">Filtrer</button>
          <?php if (isset($_GET['poste']) || isset($_GET['statut']) || (isset($_GET['search']) && $_GET['search'] !== '')): ?>
            <a href="<?php echo BASE_URL; ?>/equipe-bord" class="btn btn--secondary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Réinitialiser</a>
          <?php endif; ?>
        </form>
        
        <script>
        // Nettoyer les paramètres vides avant la soumission du formulaire
        document.getElementById('formFiltre').addEventListener('submit', function(e) {
          e.preventDefault();
          
          const form = this;
          const formData = new FormData(form);
          const params = new URLSearchParams();
          
          // Ajouter uniquement les paramètres non vides
          for (let [key, value] of formData.entries()) {
            if (value && value.trim() !== '') {
              params.append(key, value);
            }
          }
          
          // Construire l'URL finale
          const baseUrl = '<?php echo BASE_URL; ?>/equipe-bord';
          const queryString = params.toString();
          const finalUrl = queryString ? baseUrl + '?' + queryString : baseUrl;
          
          // Rediriger
          window.location.href = finalUrl;
        });
        </script>
      </section>

      <!-- Équipes Table -->
      <section class="bus-table card">
        <div style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>Nom complet</th>
              <th>Poste</th>
              <th>Téléphone</th>
              <th>Bus affecté</th>
              <th>Statut</th>
              <th>Date d'embauche</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="equipeTableBody">
            <?php if (isset($membres) && count($membres) > 0): ?>
              <?php foreach ($membres as $membre): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($membre['nom']); ?></strong></td>
                  <td>
                    <?php
                    $posteBadges = [
                      'chauffeur' => 'status-badge--actif',
                      'controleur' => 'status-badge--maintenance',
                      'receveur' => 'status-badge--panne'
                    ];
                    $badgeClass = $posteBadges[$membre['poste']] ?? 'status-badge--actif';
                    ?>
                    <span class="status-badge <?php echo $badgeClass; ?>">
                      <?php echo ucfirst($membre['poste']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($membre['telephone'] ?? '-'); ?></td>
                  <td>
                    <?php 
                    if (!empty($membre['bus_affecte'])) {
                      echo 'Bus #' . htmlspecialchars($membre['bus_affecte']);
                    } else {
                      echo 'Non affecté';
                    }
                    ?>
                  </td>
                  <td>
                    <span class="status-badge status-badge--<?php echo $membre['statut']; ?>">
                      <?php echo ucfirst($membre['statut']); ?>
                    </span>
                  </td>
                  <td><?php echo $membre['date_embauche'] ? date('d/m/Y', strtotime($membre['date_embauche'])) : '-'; ?></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--edit" onclick="voirProfilMembre(<?php echo $membre['id']; ?>)" title="Voir le profil">
                        <i data-feather="eye"></i>
                      </button>
                      <?php if (!empty($membre['bus_affecte'])): ?>
                        <button class="btn-icon btn-icon--delete" onclick="annulerAffectation(<?php echo $membre['id']; ?>)" title="Désaffecter du bus">
                          <i data-feather="x-circle"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" style="text-align:center; padding:40px;">
                  <div style="color:#6b7280;">
                    <i data-feather="inbox" style="width:48px;height:48px;margin-bottom:12px;"></i>
                    <p style="font-size:16px;font-weight:600;margin:12px 0 8px;">Aucun membre enregistré</p>
                    <p style="font-size:14px;margin:0;">Cliquez sur "Nouvelle Affectation" pour ajouter un membre</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
          <div class="pagination__info">
            <?php
            $totalMembres = isset($totalMembres) ? $totalMembres : 0;
            $membresAffiches = isset($membres) ? count($membres) : 0;
            $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $itemsPerPage = 10;
            $totalPages = $totalMembres > 0 ? ceil($totalMembres / $itemsPerPage) : 1;
            $startItem = ($currentPage - 1) * $itemsPerPage + 1;
            $endItem = min($currentPage * $itemsPerPage, $totalMembres);
            ?>
            Affichage de <strong><?php echo $startItem; ?></strong> à <strong><?php echo $endItem; ?></strong> sur <strong><?php echo $totalMembres; ?></strong> membres
          </div>
          <div class="pagination__controls">
            <?php if ($currentPage > 1): ?>
              <a href="?page=equipe-bord&p=<?php echo $currentPage - 1; ?>" class="pagination__btn">
                <i data-feather="chevron-left"></i> Précédent
              </a>
            <?php else: ?>
              <button class="pagination__btn" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
            <?php endif; ?>
            
            <div class="pagination__pages">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                  <button class="pagination__page active"><?php echo $i; ?></button>
                <?php else: ?>
                  <a href="?page=equipe-bord&p=<?php echo $i; ?>" class="pagination__page"><?php echo $i; ?></a>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
            
            <?php if ($currentPage < $totalPages): ?>
              <a href="?page=equipe-bord&p=<?php echo $currentPage + 1; ?>" class="pagination__btn">
                Suivant <i data-feather="chevron-right"></i>
              </a>
            <?php else: ?>
              <button class="pagination__btn" disabled>
                Suivant <i data-feather="chevron-right"></i>
              </button>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal pour voir le profil d'un membre -->
  <div class="modal" id="modalProfilMembre">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2 id="membreProfilTitle">Profil du membre</h2>
        <button class="modal__close" id="btnCloseModalProfilMembre">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div class="profil-grid">
          <div class="profil-section">
            <h3 class="profil-section__title">Informations personnelles</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">Nom complet</span>
                <span class="profil-info__value" id="membreNom">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Poste</span>
                <span id="membrePoste">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Téléphone</span>
                <span class="profil-info__value" id="membreTelephone">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Email</span>
                <span class="profil-info__value" id="membreEmail">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Adresse</span>
                <span class="profil-info__value" id="membreAdresse">-</span>
              </div>
            </div>
          </div>

          <div class="profil-section">
            <h3 class="profil-section__title">Affectation</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">Bus affecté</span>
                <span class="profil-info__value" id="membreBus">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Statut</span>
                <span id="membreStatut">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Date d'embauche</span>
                <span class="profil-info__value" id="membreDateEmbauche">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Ancienneté</span>
                <span class="profil-info__value" id="membreAnciennete">-</span>
              </div>
            </div>
          </div>
        </div>

        <div class="profil-section" id="membreNotesSection">
          <h3 class="profil-section__title">Notes</h3>
          <p class="profil-notes" id="membreNotes">-</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal pour affecter une équipe à un bus -->
  <div class="modal" id="modalAffectation">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2>Nouvelle Affectation d'Équipe</h2>
        <button class="modal__close" id="btnCloseModalAffectation">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <form class="modal__body" id="formAffectation">
        <!-- Sélection du bus -->
        <div class="form-group">
          <label for="busSelect">Sélectionner un bus *</label>
          <select id="busSelect" name="busSelect" class="select2" required>
            <option value="">-- Choisir un bus --</option>
            <?php if (isset($busList) && count($busList) > 0): ?>
              <?php foreach ($busList as $bus): ?>
                <option value="<?php echo $bus['id']; ?>" 
                        data-ligne="<?php echo htmlspecialchars($bus['trajet_id'] ?? ''); ?>" 
                        data-immat="<?php echo htmlspecialchars($bus['immatriculation']); ?>">
                  Bus #<?php echo htmlspecialchars($bus['numero']); ?> - <?php echo htmlspecialchars($bus['immatriculation']); ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <!-- Info ligne du bus -->
        <div class="affectation-info" id="busInfo" style="display:none;">
          <div class="affectation-info__item">
            <i data-feather="map-pin"></i>
            <div>
              <span class="affectation-info__label">Ligne affectée</span>
              <span class="affectation-info__value" id="busLigneInfo">-</span>
            </div>
          </div>
          <div class="affectation-info__item">
            <i data-feather="info"></i>
            <div>
              <span class="affectation-info__label">Immatriculation</span>
              <span class="affectation-info__value" id="busImmatInfo">-</span>
            </div>
          </div>
        </div>

        <div class="divider" style="margin: 24px 0;"></div>

        <!-- Affectation de l'équipe -->
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text); display: flex; align-items: center; gap: 8px;">
          <i data-feather="users" style="width: 20px; height: 20px;"></i> Composer l'équipe
        </h3>
        <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
          <i data-feather="info" style="width: 16px; height: 16px; display: inline; vertical-align: middle;"></i>
          Vous pouvez affecter un ou plusieurs membres (au moins un requis)
        </p>

        <div class="form-group">
          <label for="chauffeurSelect">Chauffeur</label>
          <select id="chauffeurSelect" name="chauffeurSelect" class="select2">
            <option value="">-- Sélectionner un chauffeur --</option>
            <?php if (isset($chauffeurs) && count($chauffeurs) > 0): ?>
              <?php foreach ($chauffeurs as $chauffeur): ?>
                <option value="<?php echo $chauffeur['id']; ?>">
                  <?php echo htmlspecialchars($chauffeur['nom']); ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>Aucun chauffeur disponible</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="controleurSelect">Contrôleur</label>
          <select id="controleurSelect" name="controleurSelect" class="select2">
            <option value="">-- Sélectionner un contrôleur --</option>
            <?php if (isset($controleurs) && count($controleurs) > 0): ?>
              <?php foreach ($controleurs as $controleur): ?>
                <option value="<?php echo $controleur['id']; ?>">
                  <?php echo htmlspecialchars($controleur['nom']); ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>Aucun contrôleur disponible</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="receveurSelect">Receveur</label>
          <select id="receveurSelect" name="receveurSelect" class="select2">
            <option value="">-- Sélectionner un receveur --</option>
            <?php if (isset($receveurs) && count($receveurs) > 0): ?>
              <?php foreach ($receveurs as $receveur): ?>
                <option value="<?php echo $receveur['id']; ?>">
                  <?php echo htmlspecialchars($receveur['nom']); ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="" disabled>Aucun receveur disponible</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="divider" style="margin: 24px 0;"></div>

        <!-- Horaires d'affectation -->
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text); display: flex; align-items: center; gap: 8px;">
          <i data-feather="clock" style="width: 20px; height: 20px;"></i> Horaires du shift
        </h3>

        <div class="form-grid">
          <div class="form-group">
            <label for="dateAffectation">Date *</label>
            <input type="date" id="dateAffectation" name="dateAffectation" required>
          </div>

          <div class="form-group">
            <label for="heureDebut">Heure de début *</label>
            <input type="time" id="heureDebut" name="heureDebut" required>
          </div>

          <div class="form-group">
            <label for="heureFin">Heure de fin *</label>
            <input type="time" id="heureFin" name="heureFin" required>
          </div>
        </div>

        <div class="modal__footer">
          <button type="button" class="btn btn--secondary" id="btnAnnulerAffectation">Annuler</button>
          <button type="submit" class="btn btn--primary">
            <i data-feather="check"></i> Affecter l'équipe
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- jQuery et Select2 (charger AVANT app.js) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    // Fonction pour voir le profil d'un membre (AJAX)
    function voirProfilMembre(membreId) {
      // Vérifier que jQuery est chargé
      if (typeof jQuery === 'undefined') {
        console.error('jQuery n\'est pas chargé !');
        alert('Erreur : jQuery n\'est pas chargé');
        return;
      }
      
      const modalProfil = $('#modalProfilMembre');
      
      // Afficher le modal avec loader
      modalProfil.addClass('active');
      $('#membreProfilTitle').text('Chargement...');
      
      // Récupérer les détails du membre via AJAX
      $.ajax({
        url: '<?php echo BASE_URL; ?>/equipe-bord/details',
        method: 'GET',
        data: { membre_id: membreId },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            const membre = response.membre;
            
            // Remplir le modal
            $('#membreProfilTitle').text('Profil de ' + membre.nom);
            $('#membreNom').text(membre.nom);
            
            // Badge poste
            const posteBadges = {
              'chauffeur': 'status-badge--actif',
              'controleur': 'status-badge--maintenance',
              'receveur': 'status-badge--panne'
            };
            $('#membrePoste').html('<span class="status-badge ' + posteBadges[membre.poste] + '">' + 
              membre.poste.charAt(0).toUpperCase() + membre.poste.slice(1) + '</span>');
            
            $('#membreTelephone').text(membre.telephone || 'Non renseigné');
            $('#membreEmail').text(membre.email || 'Non renseigné');
            $('#membreAdresse').text(membre.adresse || 'Non renseignée');
            $('#membreBus').text(membre.bus_affecte ? 'Bus #' + membre.bus_affecte : 'Non affecté');
            
            // Badge statut
            $('#membreStatut').html('<span class="status-badge status-badge--' + membre.statut + '">' + 
              membre.statut.charAt(0).toUpperCase() + membre.statut.slice(1) + '</span>');
            
            $('#membreDateEmbauche').text(membre.date_embauche || '-');
            
            // Calculer ancienneté
            if (membre.date_embauche) {
              const debut = new Date(membre.date_embauche);
              const maintenant = new Date();
              const diff = maintenant - debut;
              const annees = Math.floor(diff / (365 * 24 * 60 * 60 * 1000));
              const mois = Math.floor((diff % (365 * 24 * 60 * 60 * 1000)) / (30 * 24 * 60 * 60 * 1000));
              $('#membreAnciennete').text(annees + ' an(s) ' + mois + ' mois');
            } else {
              $('#membreAnciennete').text('-');
            }
            
            // Notes
            if (membre.notes) {
              $('#membreNotesSection').show();
              $('#membreNotes').text(membre.notes);
            } else {
              $('#membreNotesSection').hide();
            }
            
            setTimeout(() => feather.replace(), 10);
          } else {
            alert('Erreur : ' + response.message);
            modalProfil.removeClass('active');
          }
        },
        error: function() {
          alert('Erreur lors du chargement du profil');
          modalProfil.removeClass('active');
        }
      });
    }
    
    // Fonction pour annuler l'affectation d'un membre (désaffecter)
    function annulerAffectation(membreId) {
      if (confirm('Voulez-vous vraiment désaffecter ce membre de son bus ?')) {
        // Appel AJAX pour désaffecter
        $.ajax({
          url: '<?php echo BASE_URL; ?>/equipe-bord/desaffecter',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ membre_id: membreId }),
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              alert(response.message);
              location.reload(); // Recharger la page
            } else {
              alert('Erreur : ' + response.message);
            }
          },
          error: function(xhr) {
            let errorMsg = 'Erreur lors de la désaffectation';
            try {
              const response = JSON.parse(xhr.responseText);
              errorMsg = response.message || errorMsg;
            } catch(e) {}
            alert(errorMsg);
          }
        });
      }
    }
    
    $(document).ready(function() {
      // Initialiser Select2
      $('.select2').select2({
        placeholder: 'Rechercher...',
        allowClear: true,
        width: '100%'
      });
      
      // Afficher les infos du bus sélectionné
      $('#busSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const ligne = selectedOption.data('ligne');
        const immat = selectedOption.data('immat');
        
        if ($(this).val()) {
          $('#busLigneInfo').text(ligne || 'Non affecté');
          $('#busImmatInfo').text(immat);
          $('#busInfo').show();
        } else {
          $('#busInfo').hide();
        }
      });
      
      // Bouton Nouvelle Affectation
      $('#btnNouvelleAffectation').on('click', function() {
        $('#modalAffectation').addClass('active');
        setTimeout(() => feather.replace(), 10);
      });
      
      // Fermer modal affectation
      $('#btnCloseModalAffectation, #btnAnnulerAffectation').on('click', function() {
        $('#modalAffectation').removeClass('active');
      });
      
      $('#modalAffectation .modal__overlay').on('click', function() {
        $('#modalAffectation').removeClass('active');
      });
      
      // Fermer modal profil
      $('#btnCloseModalProfilMembre').on('click', function() {
        $('#modalProfilMembre').removeClass('active');
      });
      
      $('#modalProfilMembre .modal__overlay').on('click', function() {
        $('#modalProfilMembre').removeClass('active');
      });
      
      // Soumettre le formulaire d'affectation
      $('#formAffectation').on('submit', function(e) {
        e.preventDefault();
        
        const busId = $('#busSelect').val();
        const chauffeurId = $('#chauffeurSelect').val();
        const controleurId = $('#controleurSelect').val();
        const receveurId = $('#receveurSelect').val();
        const datePrevue = $('#dateAffectation').val();
        const heureDebut = $('#heureDebut').val();
        const heureFin = $('#heureFin').val();
        
        // Validation
        if (!busId) {
          alert('Veuillez sélectionner un bus');
          return;
        }
        
        // Au moins un membre doit être sélectionné
        if (!chauffeurId && !controleurId && !receveurId) {
          alert('Veuillez sélectionner au moins un membre d\'équipe');
          return;
        }
        
        // Validation des horaires
        if (!datePrevue || !heureDebut || !heureFin) {
          alert('Veuillez renseigner la date et les horaires du shift');
          return;
        }
        
        // Vérifier que heure_fin > heure_debut
        if (heureFin <= heureDebut) {
          alert('L\'heure de fin doit être après l\'heure de début');
          return;
        }
        
        // Préparer les données
        const payload = {
          bus_id: parseInt(busId),
          chauffeur_id: chauffeurId ? parseInt(chauffeurId) : 0,
          controleur_id: controleurId ? parseInt(controleurId) : 0,
          receveur_id: receveurId ? parseInt(receveurId) : 0,
          date_prevue: datePrevue,
          heure_debut: heureDebut,
          heure_fin: heureFin,
          trajet_id: 0  // À implémenter plus tard si besoin
        };
        
        // Envoyer la requête AJAX
        $.ajax({
          url: '<?php echo BASE_URL; ?>/equipe-bord/affecter',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(payload),
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              alert(response.message);
              location.reload(); // Recharger la page
            } else {
              alert('Erreur : ' + response.message);
            }
          },
          error: function(xhr) {
            let errorMsg = 'Erreur lors de l\'affectation';
            try {
              const response = JSON.parse(xhr.responseText);
              errorMsg = response.message || errorMsg;
            } catch(e) {}
            alert(errorMsg);
          }
        });
      });
      
      // Initialisation des icônes Feather
      setTimeout(() => feather.replace(), 10);
    });
  </script>
</body>
</html>
