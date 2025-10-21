<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Historique • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <?php
    // Charger les models
    require_once ROOT_PATH . '/Model/Billets.php';
    require_once ROOT_PATH . '/Model/Reservations.php';
    require_once ROOT_PATH . '/Model/Trajets.php';
    
    $billetModel = new Billets();
    $reservationModel = new Reservations();
    $trajetModel = new Trajets();
    
    // Déterminer l'onglet actif
    $ongletActif = $_GET['onglet'] ?? 'ventes';
    
    // Récupérer les filtres
    $filters = [];
    $filters['date_debut'] = $_GET['date_debut'] ?? date('Y-m-01');
    $filters['date_fin'] = $_GET['date_fin'] ?? date('Y-m-d');
    $filters['trajet_id'] = $_GET['trajet_id'] ?? '';
    $filters['mode_paiement'] = $_GET['mode_paiement'] ?? '';
    $filters['statut'] = $_GET['statut'] ?? '';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;
    
    if ($ongletActif === 'ventes') {
      // Données pour les ventes
      $billets = $billetModel->getBilletsRecents($limit, $offset, $filters);
      $totalBillets = $billetModel->compterBillets($filters);
      $totalPages = ceil($totalBillets / $limit);
      $stats = $billetModel->getStatistiquesHistorique($filters);
    } else {
      // Données pour les réservations
      $reservations = $reservationModel->getReservationsRecentes($limit, $offset, $filters);
      $totalReservations = $reservationModel->compterReservations($filters);
      $totalPages = ceil($totalReservations / $limit);
      $stats = $reservationModel->getStatistiquesHistorique($filters);
    }
    
    // Liste des trajets pour le filtre
    $trajets = $trajetModel->getTousLesTrajets();
  ?>
  <div class="app">
    <?php require_once 'includes/menu_BT.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Historique des transactions</h1>
          <p>Consultation de toutes les ventes et réservations</p>
        </div>
        <div class="header__actions">
            <i data-feather="download"></i> Exporter
          </button>
        </div>
      </header>

      <!-- Filtres -->
      <div class="tabs">
        <a href="?onglet=ventes" class="tab-btn <?= $ongletActif === 'ventes' ? 'active' : '' ?>">
          <i data-feather="shopping-cart"></i> Ventes de billets
        </a>
        <a href="?onglet=reservations" class="tab-btn <?= $ongletActif === 'reservations' ? 'active' : '' ?>">
          <i data-feather="calendar"></i> Réservations
        </a>
      </div>

      <!-- Contenu Onglet Ventes -->
      <div class="tab-content <?= $ongletActif === 'ventes' ? 'active' : '' ?>" id="tab-ventes" style="<?= $ongletActif !== 'ventes' ? 'display: none;' : '' ?>">
        <!-- Filtres -->
        <section class="card" style="margin-bottom: 20px;">
          <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date début</label>
                <input type="date" class="form-control" id="dateDebut" value="<?= htmlspecialchars($filters['date_debut']) ?>">
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date fin</label>
                <input type="date" class="form-control" id="dateFin" value="<?= htmlspecialchars($filters['date_fin']) ?>">
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Trajet</label>
                <select class="form-control" id="trajetId">
                  <option value="">Tous les trajets</option>
                  <?php foreach ($trajets as $trajet): ?>
                    <option value="<?= $trajet['id'] ?>" <?= $filters['trajet_id'] == $trajet['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($trajet['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Mode paiement</label>
                <select class="form-control" id="modePaiement">
                  <option value="">Tous</option>
                  <option value="especes" <?= $filters['mode_paiement'] == 'especes' ? 'selected' : '' ?>>Espèces</option>
                  <option value="mobile_money" <?= $filters['mode_paiement'] == 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                  <option value="carte_bancaire" <?= $filters['mode_paiement'] == 'carte_bancaire' ? 'selected' : '' ?>>Carte bancaire</option>
                  <option value="carte_prepayee" <?= $filters['mode_paiement'] == 'carte_prepayee' ? 'selected' : '' ?>>Carte prépayée</option>
                </select>
              </div>
            </div>
            <div style="margin-top: 16px; display: flex; gap: 12px;">
              <button class="btn btn--primary">
                <i data-feather="search"></i> Rechercher
              </button>
              <button class="btn btn--secondary">
                <i data-feather="refresh-cw"></i> Réinitialiser
              </button>
            </div>
          </div>
        </section>

        <!-- Statistiques rapides -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total billets</div>
            <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;"><?= number_format($stats['total_billets'], 0, ',', ' ') ?></div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus totaux</div>
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= number_format($stats['revenus_totaux'], 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Prix moyen</div>
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= number_format($stats['prix_moyen'], 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Taux d'occupation</div>
            <div style="font-size: 32px; font-weight: 800; color: #3b82f6;"><?= $stats['taux_occupation'] ?>%</div>
          </div>
        </div>

        <!-- Tableau des ventes -->
        <section class="card">
          <div class="card__header">
            <h3>Liste des ventes (<?= number_format($totalBillets, 0, ',', ' ') ?> résultats)</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table" style="white-space: nowrap;">
            <thead>
              <tr>
                <th>N° Billet</th>
                <th>Date/Heure</th>
                <th>Client</th>
                <th>Trajet</th>
                <th>Validité</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($billets)): ?>
                <?php foreach ($billets as $billet): 
                  $statutBadge = match($billet['statut_billet']) {
                    'paye' => 'actif',
                    'reserve' => 'warning',
                    'annule' => 'inactif',
                    default => 'warning'
                  };
                  
                  $modePaiementBadge = match($billet['mode_paiement']) {
                    'mobile_money' => 'success',
                    'carte_bancaire' => 'info',
                    'especes' => 'primary',
                    default => 'secondary'
                  };
                ?>
                <tr>
                  <td><strong><?= htmlspecialchars($billet['numero_billet']) ?></strong></td>
                  <td><?= date('d/m/Y', strtotime($billet['date_achat'])) ?><br><small style="color: #6b7280;"><?= date('H:i', strtotime($billet['date_achat'])) ?></small></td>
                  <td>
                    <?php if (!empty($billet['client_nom']) || !empty($billet['client_prenom'])): ?>
                      <?= htmlspecialchars($billet['client_prenom'] ?? '') ?> <?= htmlspecialchars($billet['client_nom'] ?? '') ?>
                    <?php else: ?>
                      <span style="color: #6b7280; font-style: italic;">Client sans compte</span>
                    <?php endif; ?>
                    <br><small style="color: #6b7280;"><?= htmlspecialchars($billet['client_telephone'] ?? 'N/A') ?></small>
                  </td>
                  <td><?= htmlspecialchars($billet['arret_depart']) ?> → <?= htmlspecialchars($billet['arret_arrivee']) ?></td>
                  <td><span class="badge badge--success">Valide 30j</span></td>
                  <td><strong><?= number_format($billet['prix_paye'], 0, ',', ' ') ?> <?= $billet['devise'] ?></strong></td>
                  <td><span class="badge badge--<?= $modePaiementBadge ?>"><?= ucfirst(str_replace('_', ' ', $billet['mode_paiement'])) ?></span></td>
                  <td><span class="status-badge status-badge--<?= $statutBadge ?>"><?= ucfirst($billet['statut_billet']) ?></span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Voir">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--print" title="Imprimer">
                        <i data-feather="printer"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">
                    <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
                    <p>Aucune vente enregistrée</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          </div>

          <!-- Pagination -->
          <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb;">
            <div style="color: #6b7280; font-size: 14px;">
              Affichage de <strong><?= $offset + 1 ?></strong> à <strong><?= min($offset + $limit, $totalBillets) ?></strong> sur <strong><?= number_format($totalBillets, 0, ',', ' ') ?></strong> résultats
            </div>
            <div style="display: flex; gap: 8px;">
              <?php if ($page > 1): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $page - 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">
                  <i data-feather="chevron-left"></i> Précédent
                </a>
              <?php else: ?>
                <button class="btn btn--secondary btn--sm" disabled>
                  <i data-feather="chevron-left"></i> Précédent
                </button>
              <?php endif; ?>

              <?php
              $startPage = max(1, $page - 2);
              $endPage = min($totalPages, $page + 2);
              
              if ($startPage > 1): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=1&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">1</a>
                <?php if ($startPage > 2): ?>
                  <span style="padding: 0 8px; color: #6b7280;">...</span>
                <?php endif; ?>
              <?php endif; ?>

              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php if ($i == $page): ?>
                  <button class="btn btn--secondary btn--sm active" style="background: #1B4B7F; color: white;"><?= $i ?></button>
                <?php else: ?>
                  <a href="?onglet=<?= $ongletActif ?>&page=<?= $i ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm"><?= $i ?></a>
                <?php endif; ?>
              <?php endfor; ?>

              <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                  <span style="padding: 0 8px; color: #6b7280;">...</span>
                <?php endif; ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $totalPages ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm"><?= $totalPages ?></a>
              <?php endif; ?>

              <?php if ($page < $totalPages): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $page + 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">
                  Suivant <i data-feather="chevron-right"></i>
                </a>
              <?php else: ?>
                <button class="btn btn--secondary btn--sm" disabled>
                  Suivant <i data-feather="chevron-right"></i>
                </button>
              <?php endif; ?>
            </div>
          </div>
        </section>
      </div>

      <!-- Contenu Onglet Réservations -->
      <div class="tab-content <?= $ongletActif === 'reservations' ? 'active' : '' ?>" id="tab-reservations" style="<?= $ongletActif !== 'reservations' ? 'display: none;' : '' ?>">
        <!-- Filtres -->
        <section class="card" style="margin-bottom: 20px;">
          <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date début</label>
                <input type="date" class="form-control" id="dateDebutRes" value="<?= htmlspecialchars($filters['date_debut']) ?>">
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date fin</label>
                <input type="date" class="form-control" id="dateFinRes" value="<?= htmlspecialchars($filters['date_fin']) ?>">
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
                <select class="form-control" id="statutRes">
                  <option value="">Tous les statuts</option>
                  <option value="en_attente" <?= $filters['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                  <option value="confirmee" <?= $filters['statut'] == 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                  <option value="payee" <?= $filters['statut'] == 'payee' ? 'selected' : '' ?>>Payée</option>
                  <option value="annulee" <?= $filters['statut'] == 'annulee' ? 'selected' : '' ?>>Annulée</option>
                  <option value="expiree" <?= $filters['statut'] == 'expiree' ? 'selected' : '' ?>>Expirée</option>
                </select>
              </div>
              <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Trajet</label>
                <select class="form-control" id="trajetIdRes">
                  <option value="">Tous les trajets</option>
                  <?php foreach ($trajets as $trajet): ?>
                    <option value="<?= $trajet['id'] ?>" <?= $filters['trajet_id'] == $trajet['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($trajet['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div style="margin-top: 16px; display: flex; gap: 12px;">
              <button class="btn btn--primary">
                <i data-feather="search"></i> Rechercher
              </button>
              <button class="btn btn--secondary">
                <i data-feather="refresh-cw"></i> Réinitialiser
              </button>
            </div>
          </div>
        </section>

        <!-- Statistiques rapides -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total réservations</div>
            <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;"><?= number_format($stats['total_reservations'], 0, ',', ' ') ?></div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Montant total</div>
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= number_format($stats['montant_total'], 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Montant moyen</div>
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= number_format($stats['montant_moyen'], 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total places</div>
            <div style="font-size: 32px; font-weight: 800; color: #3b82f6;"><?= number_format($stats['total_places'], 0, ',', ' ') ?></div>
          </div>
        </div>

        <!-- Tableau des réservations -->
        <section class="card">
          <div class="card__header">
            <h3>Liste des réservations (<?= number_format($totalReservations, 0, ',', ' ') ?> résultats)</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table" style="white-space: nowrap;">
            <thead>
              <tr>
                <th>N° Réservation</th>
                <th>Date création</th>
                <th>Client</th>
                <th>Trajet</th>
                <th>Date voyage</th>
                <th>Heure</th>
                <th>Places</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $reservation): 
                  $statutBadge = match($reservation['statut_reservation']) {
                    'en_attente' => 'warning',
                    'confirmee' => 'info',
                    'payee' => 'actif',
                    'annulee' => 'inactif',
                    'expiree' => 'inactif',
                    default => 'warning'
                  };
                ?>
                <tr>
                  <td><strong><?= htmlspecialchars($reservation['numero_reservation']) ?></strong></td>
                  <td><?= date('d/m/Y', strtotime($reservation['date_creation'])) ?><br><small style="color: #6b7280;"><?= date('H:i', strtotime($reservation['date_creation'])) ?></small></td>
                  <td>
                    <?php if (!empty($reservation['client_nom']) || !empty($reservation['client_prenom'])): ?>
                      <?= htmlspecialchars($reservation['client_prenom'] ?? '') ?> <?= htmlspecialchars($reservation['client_nom'] ?? '') ?>
                    <?php else: ?>
                      <span style="color: #6b7280; font-style: italic;">Client sans compte</span>
                    <?php endif; ?>
                    <br><small style="color: #6b7280;"><?= htmlspecialchars($reservation['client_telephone'] ?? 'N/A') ?></small>
                  </td>
                  <td><?= htmlspecialchars($reservation['arret_depart']) ?> → <?= htmlspecialchars($reservation['arret_arrivee']) ?></td>
                  <td><?= date('d/m/Y', strtotime($reservation['date_voyage'])) ?></td>
                  <td><?= $reservation['heure_depart'] ? date('H:i', strtotime($reservation['heure_depart'])) : 'N/A' ?></td>
                  <td><span class="badge badge--info"><?= $reservation['nombre_places'] ?> place<?= $reservation['nombre_places'] > 1 ? 's' : '' ?></span></td>
                  <td><strong><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> CDF</strong></td>
                  <td><span class="status-badge status-badge--<?= $statutBadge ?>"><?= ucfirst(str_replace('_', ' ', $reservation['statut_reservation'])) ?></span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Voir">
                        <i data-feather="eye"></i>
                      </button>
                      <?php if ($reservation['statut_reservation'] == 'en_attente'): ?>
                        <button class="btn-icon btn-icon--edit" title="Confirmer">
                          <i data-feather="check"></i>
                        </button>
                        <button class="btn-icon btn-icon--delete" title="Annuler">
                          <i data-feather="x"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10" style="text-align: center; padding: 40px; color: #6b7280;">
                    <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
                    <p>Aucune réservation enregistrée</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          </div>

          <!-- Pagination -->
          <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb;">
            <div style="color: #6b7280; font-size: 14px;">
              Affichage de <strong><?= $offset + 1 ?></strong> à <strong><?= min($offset + $limit, $totalReservations) ?></strong> sur <strong><?= number_format($totalReservations, 0, ',', ' ') ?></strong> résultats
            </div>
            <div style="display: flex; gap: 8px;">
              <?php if ($page > 1): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $page - 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&statut=<?= urlencode($filters['statut']) ?>" class="btn btn--secondary btn--sm">
                  <i data-feather="chevron-left"></i> Précédent
                </a>
              <?php else: ?>
                <button class="btn btn--secondary btn--sm" disabled>
                  <i data-feather="chevron-left"></i> Précédent
                </button>
              <?php endif; ?>

              <?php
              $startPage = max(1, $page - 2);
              $endPage = min($totalPages, $page + 2);
              
              if ($startPage > 1): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=1&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&statut=<?= urlencode($filters['statut']) ?>" class="btn btn--secondary btn--sm">1</a>
                <?php if ($startPage > 2): ?>
                  <span style="padding: 0 8px; color: #6b7280;">...</span>
                <?php endif; ?>
              <?php endif; ?>

              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php if ($i == $page): ?>
                  <button class="btn btn--secondary btn--sm active" style="background: #1B4B7F; color: white;"><?= $i ?></button>
                <?php else: ?>
                  <a href="?onglet=<?= $ongletActif ?>&page=<?= $i ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&statut=<?= urlencode($filters['statut']) ?>" class="btn btn--secondary btn--sm"><?= $i ?></a>
                <?php endif; ?>
              <?php endfor; ?>

              <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                  <span style="padding: 0 8px; color: #6b7280;">...</span>
                <?php endif; ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $totalPages ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&statut=<?= urlencode($filters['statut']) ?>" class="btn btn--secondary btn--sm"><?= $totalPages ?></a>
              <?php endif; ?>

              <?php if ($page < $totalPages): ?>
                <a href="?onglet=<?= $ongletActif ?>&page=<?= $page + 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&statut=<?= urlencode($filters['statut']) ?>" class="btn btn--secondary btn--sm">
                  Suivant <i data-feather="chevron-right"></i>
                </a>
              <?php else: ?>
                <button class="btn btn--secondary btn--sm" disabled>
                  Suivant <i data-feather="chevron-right"></i>
                </button>
              <?php endif; ?>
            </div>
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      // Fonction pour appliquer les filtres
      function appliquerFiltres() {
        const dateDebut = document.getElementById('dateDebut').value;
        const dateFin = document.getElementById('dateFin').value;
        const trajetId = document.getElementById('trajetId').value;
        const modePaiement = document.getElementById('modePaiement').value;
        const onglet = '<?= $ongletActif ?>';

        // Construire l'URL avec les paramètres
        const params = new URLSearchParams();
        params.append('onglet', onglet);
        if (dateDebut) params.append('date_debut', dateDebut);
        if (dateFin) params.append('date_fin', dateFin);
        if (trajetId) params.append('trajet_id', trajetId);
        if (modePaiement) params.append('mode_paiement', modePaiement);
        params.append('page', '1');

        window.location.href = '?' + params.toString();
      }

      // Bouton Rechercher
      const btnRechercher = document.getElementById('btnRechercher');
      if (btnRechercher) {
        btnRechercher.addEventListener('click', appliquerFiltres);
      }

      // Bouton Réinitialiser
      const btnReinitialiser = document.getElementById('btnReinitialiser');
      if (btnReinitialiser) {
        btnReinitialiser.addEventListener('click', function() {
          window.location.href = '?onglet=<?= $ongletActif ?>';
        });
      }

      // Fonction pour appliquer les filtres des réservations
      function appliquerFiltresRes() {
        const dateDebut = document.getElementById('dateDebutRes').value;
        const dateFin = document.getElementById('dateFinRes').value;
        const trajetId = document.getElementById('trajetIdRes').value;
        const statut = document.getElementById('statutRes').value;

        const params = new URLSearchParams();
        params.append('onglet', 'reservations');
        if (dateDebut) params.append('date_debut', dateDebut);
        if (dateFin) params.append('date_fin', dateFin);
        if (trajetId) params.append('trajet_id', trajetId);
        if (statut) params.append('statut', statut);
        params.append('page', '1');

        window.location.href = '?' + params.toString();
      }

      // Bouton Rechercher Réservations
      const btnRechercherRes = document.getElementById('btnRechercherRes');
      if (btnRechercherRes) {
        btnRechercherRes.addEventListener('click', appliquerFiltresRes);
      }

      // Bouton Réinitialiser Réservations
      const btnReinitialiserRes = document.getElementById('btnReinitialiserRes');
      if (btnReinitialiserRes) {
        btnReinitialiserRes.addEventListener('click', function() {
          window.location.href = '?onglet=reservations';
        });
      }

      // Gestion des onglets (déjà géré par les liens)
      const tabBtns = document.querySelectorAll('.tab-btn');
      const tabContents = document.querySelectorAll('.tab-content');

      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const tabId = btn.getAttribute('data-tab');
          
          // Retirer active de tous les boutons et contenus
          tabBtns.forEach(b => b.classList.remove('active'));
          tabContents.forEach(c => c.classList.remove('active'));
          
          // Ajouter active au bouton cliqué et au contenu correspondant
          btn.classList.add('active');
          document.getElementById(`tab-${tabId}`).classList.add('active');
          
          // Rafraîchir les icônes
          feather.replace();
        });
      });
    });
  </script>
</body>
</html>
