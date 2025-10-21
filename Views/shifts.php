<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Shifts • Safari</title>
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
          <h1>Historique des Shifts</h1>
          <p>Consulter l'historique et obtenir des suggestions</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" id="btnSuggererShifts">
            <i data-feather="zap"></i> Voir les suggestions
          </button>
        </div>
      </header>

      <!-- Filter bar -->
      <section class="filters card">
        <div class="filters__title">
          <i data-feather="filter"></i>
          Filtres
        </div>
        <form method="GET" action="<?php echo BASE_URL; ?>/shifts" class="filters__controls">
          <select name="statut" id="filterStatut">
            <option value="">Tous les statuts</option>
            <option value="planifie" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'planifie') ? 'selected' : ''; ?>>Planifié</option>
            <option value="actif" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'actif') ? 'selected' : ''; ?>>Actif</option>
            <option value="termine" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'termine') ? 'selected' : ''; ?>>Terminé</option>
            <option value="annule" <?php echo (isset($_GET['statut']) && $_GET['statut'] == 'annule') ? 'selected' : ''; ?>>Annulé</option>
          </select>
          <input type="date" name="date" id="filterDate" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
          <select name="bus" id="filterBus">
            <option value="">Tous les bus</option>
            <?php if (isset($busList) && is_array($busList)): ?>
              <?php foreach ($busList as $bus): ?>
                <option value="<?php echo htmlspecialchars($bus['numero']); ?>" 
                  <?php echo (isset($_GET['bus']) && $_GET['bus'] == $bus['numero']) ? 'selected' : ''; ?>>
                  Bus #<?php echo htmlspecialchars($bus['numero']); ?> - <?php echo htmlspecialchars($bus['immatriculation']); ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <button type="submit" class="btn btn--primary" id="btnFiltrer">Filtrer</button>
          <?php if (isset($_GET['statut']) || isset($_GET['date']) || isset($_GET['bus'])): ?>
            <a href="<?php echo BASE_URL; ?>/shifts" class="btn btn--secondary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Réinitialiser</a>
          <?php endif; ?>
        </form>
      </section>

      <!-- Shifts Table -->
      <section class="bus-table card">
        <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>Date</th>
              <th>Horaire</th>
              <th>Bus</th>
              <th>Chauffeur</th>
              <th>Contrôleur</th>
              <th>Receveur</th>
              <th>Statut</th>
              <th style="width: 180px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($shifts) && count($shifts) > 0): ?>
              <?php foreach ($shifts as $shift): ?>
                <tr>
                  <td>
                    <strong><?php echo date('d/m/Y', strtotime($shift['date_prevue'])); ?></strong>
                  </td>
                  <td><?php echo substr($shift['heure_debut'], 0, 5); ?> - <?php echo substr($shift['heure_fin'], 0, 5); ?></td>
                  <td>
                    <div class="bus-info">
                      <strong>Bus #<?php echo htmlspecialchars($shift['bus_numero']); ?></strong>
                      <small><?php echo htmlspecialchars($shift['bus_immatriculation'] ?? '-'); ?></small>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($shift['chauffeur_nom'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($shift['controleur_nom'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($shift['receveur_nom'] ?? '-'); ?></td>
                  <td>
                    <?php
                    $statutClass = '';
                    $statutLabel = '';
                    switch ($shift['statut']) {
                      case 'planifie':
                        $statutClass = 'status-badge--actif';
                        $statutLabel = 'Planifié';
                        break;
                      case 'actif':
                        $statutClass = 'status-badge--maintenance';
                        $statutLabel = 'En cours';
                        break;
                      case 'termine':
                        $statutClass = 'status-badge--inactif';
                        $statutLabel = 'Terminé';
                        break;
                      case 'annule':
                        $statutClass = 'status-badge--panne';
                        $statutLabel = 'Annulé';
                        break;
                      default:
                        $statutClass = '';
                        $statutLabel = $shift['statut'];
                    }
                    ?>
                    <span class="status-badge <?php echo $statutClass; ?>">
                      <?php echo $statutLabel; ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon" onclick="voirDetailsShift(<?php echo $shift['id']; ?>)" title="Voir détails">
                        <i data-feather="eye"></i>
                      </button>
                      <?php if ($shift['statut'] == 'planifie'): ?>
                        <button class="btn-icon btn-icon--assign" onclick="envoyerNotification(<?php echo $shift['id']; ?>, '<?php echo htmlspecialchars($shift['bus_numero']); ?>', '<?php echo date('d/m/Y', strtotime($shift['date_prevue'])); ?>', '<?php echo substr($shift['heure_debut'], 0, 5); ?>', '<?php echo substr($shift['heure_fin'], 0, 5); ?>')" title="Envoyer notification">
                          <i data-feather="send"></i>
                        </button>
                        <button class="btn-icon btn-icon--edit" onclick="changerStatutShift(<?php echo $shift['id']; ?>, 'actif')" title="Démarrer">
                          <i data-feather="play"></i>
                        </button>
                        <button class="btn-icon btn-icon--delete" onclick="annulerShift(<?php echo $shift['id']; ?>)" title="Annuler">
                          <i data-feather="x-circle"></i>
                        </button>
                      <?php elseif ($shift['statut'] == 'actif'): ?>
                        <button class="btn-icon btn-icon--edit" onclick="changerStatutShift(<?php echo $shift['id']; ?>, 'termine')" title="Terminer">
                          <i data-feather="check"></i>
                        </button>
                        <button class="btn-icon btn-icon--delete" onclick="annulerShift(<?php echo $shift['id']; ?>)" title="Annuler">
                          <i data-feather="x-circle"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                  <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                    <i data-feather="calendar" style="width: 48px; height: 48px; color: #d1d5db;"></i>
                    <p style="color: #6b7280; margin: 0; font-weight: 500;">Aucun shift trouvé</p>
                    <?php if (isset($_GET['statut']) || isset($_GET['date']) || isset($_GET['bus'])): ?>
                      <p style="color: #9ca3af; margin: 0; font-size: 14px;">Essayez de modifier vos filtres</p>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
          <div class="pagination__info">
            Affichage de <strong id="paginationStartShift">0</strong> à <strong id="paginationEndShift">0</strong> sur <strong id="paginationTotalShift">0</strong> shifts
          </div>
          <div class="pagination__controls">
            <button class="pagination__btn" id="btnPrevPageShift" disabled>
              <i data-feather="chevron-left"></i> Précédent
            </button>
            <div class="pagination__pages" id="paginationPagesShift">
              <!-- Pages générées par JS -->
            </div>
            <button class="pagination__btn" id="btnNextPageShift">
              Suivant <i data-feather="chevron-right"></i>
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal pour voir les détails d'un shift -->
  <div class="modal" id="modalDetailsShift">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 700px;">
      <div class="modal__header">
        <h2 id="shiftDetailsTitle">Détails du Shift</h2>
        <button class="modal__close" id="btnCloseModalDetailsShift">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div class="profil-grid">
          <div class="profil-section">
            <h3 class="profil-section__title">Informations du shift</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">Date</span>
                <span class="profil-info__value" id="shiftDate">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Horaire</span>
                <span class="profil-info__value" id="shiftHoraire">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Bus</span>
                <span class="profil-info__value" id="shiftBus">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Immatriculation</span>
                <span class="profil-info__value" id="shiftImmat">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Trajet</span>
                <span class="profil-info__value" id="shiftTrajet">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">Statut</span>
                <span id="shiftStatut">-</span>
              </div>
            </div>
          </div>

          <div class="profil-section">
            <h3 class="profil-section__title">Équipe de bord</h3>
            <div class="profil-info">
              <div class="profil-info__item">
                <span class="profil-info__label">
                  <i data-feather="user" style="width: 16px; height: 16px;"></i> Chauffeur
                </span>
                <span class="profil-info__value" id="shiftChauffeur">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">
                  <i data-feather="shield" style="width: 16px; height: 16px;"></i> Contrôleur
                </span>
                <span class="profil-info__value" id="shiftControleur">-</span>
              </div>
              <div class="profil-info__item">
                <span class="profil-info__label">
                  <i data-feather="dollar-sign" style="width: 16px; height: 16px;"></i> Receveur
                </span>
                <span class="profil-info__value" id="shiftReceveur">-</span>
              </div>
            </div>
          </div>
        </div>

        <div class="profil-section" id="shiftNotesSection" style="display: none;">
          <h3 class="profil-section__title">Notes</h3>
          <p id="shiftNotes" style="color: #6b7280; line-height: 1.6;">-</p>
        </div>
      </div>
      
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnFermerDetailsShift">Fermer</button>
      </div>
    </div>
  </div>

  <!-- Modal pour envoyer une notification -->
  <div class="modal" id="modalEnvoyerNotification">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Envoyer une notification</h2>
        <button class="modal__close" id="btnCloseModalNotification">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #1e40af; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <i data-feather="info" style="width: 20px; height: 20px;"></i>
            Notification à l'équipe du shift
          </p>
        </div>
        
        <div class="profil-section">
          <h3 class="profil-section__title">Informations du shift</h3>
          <div class="profil-info">
            <div class="profil-info__item">
              <span class="profil-info__label">Bus</span>
              <span class="profil-info__value" id="notifBus">-</span>
            </div>
            <div class="profil-info__item">
              <span class="profil-info__label">Date</span>
              <span class="profil-info__value" id="notifDate">-</span>
            </div>
            <div class="profil-info__item">
              <span class="profil-info__label">Horaire</span>
              <span class="profil-info__value" id="notifHoraire">-</span>
            </div>
          </div>
        </div>
        
        <div class="profil-section">
          <h3 class="profil-section__title">Destinataires</h3>
          <div id="notifDestinataires" style="display: flex; flex-direction: column; gap: 8px;">
            <!-- Généré dynamiquement -->
          </div>
        </div>
        
        <div class="form-group">
          <label for="notifMessage">Message personnalisé (optionnel)</label>
          <textarea id="notifMessage" rows="4" placeholder="Ajoutez un message personnalisé..."></textarea>
        </div>
      </div>
      
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnAnnulerNotification">Annuler</button>
        <button type="button" class="btn btn--primary" id="btnConfirmerNotification">
          <i data-feather="send"></i> Envoyer
        </button>
      </div>
    </div>
  </div>

  <!-- Modal pour changer le statut -->
  <div class="modal" id="modalChangerStatut">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2 id="modalStatutTitle">Changer le statut</h2>
        <button class="modal__close" id="btnCloseModalStatut">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #92400e; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
            <span id="modalStatutMessage">Êtes-vous sûr de vouloir changer le statut ?</span>
          </p>
        </div>
        
        <div class="profil-section">
          <div class="profil-info">
            <div class="profil-info__item">
              <span class="profil-info__label">Shift</span>
              <span class="profil-info__value" id="statutShiftInfo">-</span>
            </div>
            <div class="profil-info__item">
              <span class="profil-info__label">Nouveau statut</span>
              <span id="statutNouveau">-</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnAnnulerStatut">Annuler</button>
        <button type="button" class="btn btn--primary" id="btnConfirmerStatut">
          <i data-feather="check"></i> Confirmer
        </button>
      </div>
    </div>
  </div>

  <!-- Modal pour annuler un shift -->
  <div class="modal" id="modalAnnulerShift">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2>Annuler le shift</h2>
        <button class="modal__close" id="btnCloseModalAnnuler">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #991b1b; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
            Cette action annulera définitivement le shift
          </p>
        </div>
        
        <div class="profil-section">
          <div class="profil-info">
            <div class="profil-info__item">
              <span class="profil-info__label">Shift</span>
              <span class="profil-info__value" id="annulerShiftInfo">-</span>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label for="annulerMotif">Motif de l'annulation <span style="color: #ef4444;">*</span></label>
          <textarea id="annulerMotif" rows="3" placeholder="Expliquez la raison de l'annulation..." required></textarea>
        </div>
      </div>
      
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnAnnulerAnnulation">Annuler</button>
        <button type="button" class="btn btn--primary" style="background: #ef4444;" id="btnConfirmerAnnulation">
          <i data-feather="x-circle"></i> Confirmer l'annulation
        </button>
      </div>
    </div>
  </div>

  <!-- Modal pour les suggestions de shifts -->
  <div class="modal" id="modalSuggestions">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2>Suggestions de Shifts</h2>
        <button class="modal__close" id="btnCloseModalSuggestions">
          <i data-feather="x"></i>
        </button>
      </div>
      
      <div class="modal__body">
        <div class="suggestions-info">
          <i data-feather="info"></i>
          <p>Suggestions basées sur l'historique des 30 derniers jours.</p>
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
          <label for="suggestionDate">Date pour les suggestions</label>
          <input type="date" id="suggestionDate" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
          <button type="button" class="btn btn--primary" id="btnChargerSuggestions" style="margin-top: 8px;">
            <i data-feather="refresh-cw"></i> Charger les suggestions
          </button>
        </div>
        
        <div id="suggestionLoader" style="display: none; text-align: center; padding: 40px;">
          <p style="color: #6b7280;">Chargement des suggestions...</p>
        </div>
        
        <div id="suggestionContent" style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
            <thead>
              <tr>
                <th>Bus</th>
                <th>Horaire</th>
                <th>Trajet</th>
                <th>Équipe suggérée</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="suggestionsTableBody">
              <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #9ca3af;">
                  Cliquez sur "Charger les suggestions" pour voir les recommandations
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal__footer" style="justify-content: flex-end;">
        <button type="button" class="btn btn--secondary" id="btnFermerSuggestions">Fermer</button>
      </div>
    </div>
  </div>
  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    // Script pour la page shifts
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Page Shifts chargée');
      
      // Mettre à jour la pagination
      const totalShifts = <?php echo isset($shifts) ? count($shifts) : 0; ?>;
      document.getElementById('paginationTotalShift').textContent = totalShifts;
      document.getElementById('paginationStartShift').textContent = totalShifts > 0 ? '1' : '0';
      document.getElementById('paginationEndShift').textContent = totalShifts;
      
      // Ouvrir modal suggestions
      document.getElementById('btnSuggererShifts').addEventListener('click', function() {
        document.getElementById('modalSuggestions').classList.add('active');
        feather.replace();
      });
      
      // Charger les suggestions
      document.getElementById('btnChargerSuggestions').addEventListener('click', function() {
        const date = document.getElementById('suggestionDate').value;
        const loader = document.getElementById('suggestionLoader');
        const content = document.getElementById('suggestionContent');
        const tbody = document.getElementById('suggestionsTableBody');
        
        // Afficher le loader
        loader.style.display = 'block';
        content.style.display = 'none';
        
        fetch('<?php echo BASE_URL; ?>/shifts/suggestions?date=' + date)
          .then(response => response.json())
          .then(data => {
            loader.style.display = 'none';
            content.style.display = 'block';
            
            if (data.success && data.suggestions.length > 0) {
              tbody.innerHTML = data.suggestions.map(suggestion => `
                <tr>
                  <td>
                    <strong>Bus #${suggestion.bus_numero}</strong><br>
                    <small style="color: #6b7280;">${suggestion.bus_immatriculation || '-'}</small>
                    ${suggestion.source === 'aleatoire' ? '<br><span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 11px;">Suggestion aléatoire</span>' : ''}
                    ${suggestion.source === 'historique' ? '<br><span style="background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px; font-size: 11px;">Basé sur historique</span>' : ''}
                  </td>
                  <td>${suggestion.heure_debut.substring(0, 5)} - ${suggestion.heure_fin.substring(0, 5)}</td>
                  <td>${suggestion.trajet_nom || 'Non affecté'}</td>
                  <td>
                    <div style="display: flex; flex-direction: column; gap: 4px; font-size: 13px;">
                      ${suggestion.chauffeur_nom ? `<div><i data-feather="user" style="width: 14px; height: 14px;"></i> ${suggestion.chauffeur_nom}</div>` : '<div style="color: #9ca3af;">Aucun chauffeur disponible</div>'}
                      ${suggestion.controleur_nom ? `<div><i data-feather="shield" style="width: 14px; height: 14px;"></i> ${suggestion.controleur_nom}</div>` : '<div style="color: #9ca3af;">Aucun contrôleur disponible</div>'}
                      ${suggestion.receveur_nom ? `<div><i data-feather="dollar-sign" style="width: 14px; height: 14px;"></i> ${suggestion.receveur_nom}</div>` : '<div style="color: #9ca3af;">Aucun receveur disponible</div>'}
                    </div>
                  </td>
                  <td>
                    <button class="btn btn--sm btn--primary" onclick="appliquerSuggestion(${suggestion.bus_numero}, '${date}', '${suggestion.heure_debut}', '${suggestion.heure_fin}', ${suggestion.chauffeur_suggere_id || 0}, ${suggestion.controleur_suggere_id || 0}, ${suggestion.receveur_suggere_id || 0}, ${suggestion.trajet_id || 0})">
                      <i data-feather="check"></i> Appliquer
                    </button>
                  </td>
                </tr>
              `).join('');
              feather.replace();
            } else {
              tbody.innerHTML = `
                <tr>
                  <td colspan="5" style="text-align: center; padding: 40px; color: #9ca3af;">
                    Aucune suggestion trouvée pour cette date
                  </td>
                </tr>
              `;
            }
          })
          .catch(error => {
            console.error('Erreur:', error);
            loader.style.display = 'none';
            content.style.display = 'block';
            tbody.innerHTML = `
              <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #ef4444;">
                  Erreur lors du chargement des suggestions
                </td>
              </tr>
            `;
          });
      });
      
      // Fermer modal
      document.getElementById('btnCloseModalSuggestions').addEventListener('click', function() {
        document.getElementById('modalSuggestions').classList.remove('active');
      });
      
      document.getElementById('btnFermerSuggestions').addEventListener('click', function() {
        document.getElementById('modalSuggestions').classList.remove('active');
      });
      
      document.querySelector('#modalSuggestions .modal__overlay').addEventListener('click', function() {
        document.getElementById('modalSuggestions').classList.remove('active');
      });
      
      // Fonction pour appliquer une suggestion
      window.appliquerSuggestion = function(busNumero, date, heureDebut, heureFin, chauffeurId, controleurId, receveurId, trajetId) {
        if (!confirm('Voulez-vous créer ce shift avec l\'équipe suggérée ?')) {
          return;
        }
        
        // Rediriger vers la page equipe-bord avec les paramètres pré-remplis
        window.location.href = `<?php echo BASE_URL; ?>/equipe-bord?bus=${busNumero}&date=${date}&debut=${heureDebut}&fin=${heureFin}&chauffeur=${chauffeurId}&controleur=${controleurId}&receveur=${receveurId}&trajet=${trajetId}&auto_suggest=1`;
      };
      
      feather.replace();
    });
    
    // Fonction pour voir les détails d'un shift
    function voirDetailsShift(shiftId) {
      const modal = document.getElementById('modalDetailsShift');
      
      // Afficher le modal avec loader
      modal.classList.add('active');
      document.getElementById('shiftDetailsTitle').textContent = 'Chargement...';
      
      fetch('<?php echo BASE_URL; ?>/shifts/details?shift_id=' + shiftId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const shift = data.shift;
            
            // Titre
            document.getElementById('shiftDetailsTitle').textContent = `Shift #${shift.id}`;
            
            // Informations du shift
            document.getElementById('shiftDate').textContent = new Date(shift.date_prevue).toLocaleDateString('fr-FR');
            document.getElementById('shiftHoraire').textContent = `${shift.heure_debut.substring(0, 5)} - ${shift.heure_fin.substring(0, 5)}`;
            document.getElementById('shiftBus').textContent = `Bus #${shift.bus_numero}`;
            document.getElementById('shiftImmat').textContent = shift.bus_immatriculation || '-';
            document.getElementById('shiftTrajet').textContent = shift.trajet_nom || 'Non affecté';
            
            // Badge statut
            const statutLabels = {
              'planifie': 'Planifié',
              'actif': 'En cours',
              'termine': 'Terminé',
              'annule': 'Annulé'
            };
            const statutClasses = {
              'planifie': 'status-badge--actif',
              'actif': 'status-badge--maintenance',
              'termine': 'status-badge--inactif',
              'annule': 'status-badge--panne'
            };
            document.getElementById('shiftStatut').innerHTML = `<span class="status-badge ${statutClasses[shift.statut]}">${statutLabels[shift.statut]}</span>`;
            
            // Équipe
            document.getElementById('shiftChauffeur').textContent = shift.chauffeur_nom || 'Non affecté';
            document.getElementById('shiftControleur').textContent = shift.controleur_nom || 'Non affecté';
            document.getElementById('shiftReceveur').textContent = shift.receveur_nom || 'Non affecté';
            
            // Notes (si présentes)
            if (shift.notes && shift.notes.trim() !== '') {
              document.getElementById('shiftNotes').textContent = shift.notes;
              document.getElementById('shiftNotesSection').style.display = 'block';
            } else {
              document.getElementById('shiftNotesSection').style.display = 'none';
            }
            
            // Remplacer les icônes Feather
            feather.replace();
          } else {
            alert('Erreur: ' + data.message);
            modal.classList.remove('active');
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de la récupération des détails');
          modal.classList.remove('active');
        });
    }
    
    // Fermer le modal de détails
    document.getElementById('btnCloseModalDetailsShift').addEventListener('click', function() {
      document.getElementById('modalDetailsShift').classList.remove('active');
    });
    
    document.getElementById('btnFermerDetailsShift').addEventListener('click', function() {
      document.getElementById('modalDetailsShift').classList.remove('active');
    });
    
    document.querySelector('#modalDetailsShift .modal__overlay').addEventListener('click', function() {
      document.getElementById('modalDetailsShift').classList.remove('active');
    });
    
    // Fonction pour changer le statut d'un shift
    function changerStatutShift(shiftId, nouveauStatut) {
      const modal = document.getElementById('modalChangerStatut');
      currentShiftId = shiftId;
      
      // Récupérer les infos du shift
      fetch('<?php echo BASE_URL; ?>/shifts/details?shift_id=' + shiftId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const shift = data.shift;
            
            // Configurer le modal selon le nouveau statut
            const statutLabels = {
              'actif': 'En cours',
              'termine': 'Terminé'
            };
            const statutClasses = {
              'actif': 'status-badge--maintenance',
              'termine': 'status-badge--inactif'
            };
            
            if (nouveauStatut === 'actif') {
              document.getElementById('modalStatutTitle').textContent = 'Démarrer le shift';
              document.getElementById('modalStatutMessage').textContent = 'Le shift va passer en statut "En cours"';
            } else if (nouveauStatut === 'termine') {
              document.getElementById('modalStatutTitle').textContent = 'Terminer le shift';
              document.getElementById('modalStatutMessage').textContent = 'Le shift va passer en statut "Terminé"';
            }
            
            document.getElementById('statutShiftInfo').textContent = `Bus #${shift.bus_numero} - ${new Date(shift.date_prevue).toLocaleDateString('fr-FR')}`;
            document.getElementById('statutNouveau').innerHTML = `<span class="status-badge ${statutClasses[nouveauStatut]}">${statutLabels[nouveauStatut]}</span>`;
            
            // Stocker le nouveau statut
            modal.dataset.nouveauStatut = nouveauStatut;
            
            // Afficher le modal
            modal.classList.add('active');
            feather.replace();
          } else {
            alert('Erreur: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de la récupération des détails');
        });
    }
    
    // Confirmer le changement de statut
    document.getElementById('btnConfirmerStatut').addEventListener('click', function() {
      const modal = document.getElementById('modalChangerStatut');
      const nouveauStatut = modal.dataset.nouveauStatut;
      
      fetch('<?php echo BASE_URL; ?>/shifts/change-status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          shift_id: currentShiftId,
          statut: nouveauStatut
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('✅ ' + data.message);
          modal.classList.remove('active');
          location.reload();
        } else {
          alert('Erreur: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du changement de statut');
      });
    });
    
    // Fermer modal statut
    document.getElementById('btnCloseModalStatut').addEventListener('click', function() {
      document.getElementById('modalChangerStatut').classList.remove('active');
    });
    
    document.getElementById('btnAnnulerStatut').addEventListener('click', function() {
      document.getElementById('modalChangerStatut').classList.remove('active');
    });
    
    document.querySelector('#modalChangerStatut .modal__overlay').addEventListener('click', function() {
      document.getElementById('modalChangerStatut').classList.remove('active');
    });
    
    // Fonction pour annuler un shift
    function annulerShift(shiftId) {
      const modal = document.getElementById('modalAnnulerShift');
      currentShiftId = shiftId;
      
      // Récupérer les infos du shift
      fetch('<?php echo BASE_URL; ?>/shifts/details?shift_id=' + shiftId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const shift = data.shift;
            
            document.getElementById('annulerShiftInfo').textContent = `Bus #${shift.bus_numero} - ${new Date(shift.date_prevue).toLocaleDateString('fr-FR')} (${shift.heure_debut.substring(0, 5)} - ${shift.heure_fin.substring(0, 5)})`;
            document.getElementById('annulerMotif').value = '';
            
            // Afficher le modal
            modal.classList.add('active');
            feather.replace();
          } else {
            alert('Erreur: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de la récupération des détails');
        });
    }
    
    // Confirmer l'annulation
    document.getElementById('btnConfirmerAnnulation').addEventListener('click', function() {
      const motif = document.getElementById('annulerMotif').value.trim();
      
      if (!motif) {
        alert('⚠️ Veuillez indiquer un motif d\'annulation');
        return;
      }
      
      fetch('<?php echo BASE_URL; ?>/shifts/annuler', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          shift_id: currentShiftId,
          motif: motif
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('✅ ' + data.message);
          document.getElementById('modalAnnulerShift').classList.remove('active');
          location.reload();
        } else {
          alert('Erreur: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'annulation');
      });
    });
    
    // Fermer modal annulation
    document.getElementById('btnCloseModalAnnuler').addEventListener('click', function() {
      document.getElementById('modalAnnulerShift').classList.remove('active');
    });
    
    document.getElementById('btnAnnulerAnnulation').addEventListener('click', function() {
      document.getElementById('modalAnnulerShift').classList.remove('active');
    });
    
    document.querySelector('#modalAnnulerShift .modal__overlay').addEventListener('click', function() {
      document.getElementById('modalAnnulerShift').classList.remove('active');
    });
    
    // Variables globales pour stocker les données temporaires
    let currentShiftId = null;
    let currentShiftData = null;
    
    // Fonction pour envoyer une notification à l'équipe
    function envoyerNotification(shiftId, busNumero, date, heureDebut, heureFin) {
      const modal = document.getElementById('modalEnvoyerNotification');
      currentShiftId = shiftId;
      
      // Remplir les infos du shift
      document.getElementById('notifBus').textContent = `Bus #${busNumero}`;
      document.getElementById('notifDate').textContent = date;
      document.getElementById('notifHoraire').textContent = `${heureDebut} - ${heureFin}`;
      
      // Récupérer les détails du shift pour avoir les noms des membres
      fetch('<?php echo BASE_URL; ?>/shifts/details?shift_id=' + shiftId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const shift = data.shift;
            currentShiftData = shift;
            let destinataires = [];
            
            if (shift.chauffeur_nom) destinataires.push(`<div style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f3f4f6; border-radius: 6px;"><i data-feather="user" style="width: 16px; height: 16px;"></i><strong>${shift.chauffeur_nom}</strong> <span style="color: #6b7280;">(Chauffeur)</span></div>`);
            if (shift.controleur_nom) destinataires.push(`<div style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f3f4f6; border-radius: 6px;"><i data-feather="shield" style="width: 16px; height: 16px;"></i><strong>${shift.controleur_nom}</strong> <span style="color: #6b7280;">(Contrôleur)</span></div>`);
            if (shift.receveur_nom) destinataires.push(`<div style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f3f4f6; border-radius: 6px;"><i data-feather="dollar-sign" style="width: 16px; height: 16px;"></i><strong>${shift.receveur_nom}</strong> <span style="color: #6b7280;">(Receveur)</span></div>`);
            
            document.getElementById('notifDestinataires').innerHTML = destinataires.length > 0 ? destinataires.join('') : '<p style="color: #9ca3af;">Aucun membre affecté</p>';
            
            // Afficher le modal
            modal.classList.add('active');
            feather.replace();
          } else {
            alert('Erreur: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de la récupération des détails');
        });
    }
    
    // Confirmer l'envoi de notification
    document.getElementById('btnConfirmerNotification').addEventListener('click', function() {
      const messagePerso = document.getElementById('notifMessage').value;
      
      // TODO: Implémenter l'envoi réel de SMS/Email via API
      
      alert('✅ Notification envoyée avec succès à l\'équipe !');
      document.getElementById('modalEnvoyerNotification').classList.remove('active');
      document.getElementById('notifMessage').value = '';
    });
    
    // Fermer modal notification
    document.getElementById('btnCloseModalNotification').addEventListener('click', function() {
      document.getElementById('modalEnvoyerNotification').classList.remove('active');
    });
    
    document.getElementById('btnAnnulerNotification').addEventListener('click', function() {
      document.getElementById('modalEnvoyerNotification').classList.remove('active');
    });
    
    document.querySelector('#modalEnvoyerNotification .modal__overlay').addEventListener('click', function() {
      document.getElementById('modalEnvoyerNotification').classList.remove('active');
    });
  </script>
</body>
</html>
