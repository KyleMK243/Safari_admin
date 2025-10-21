<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Historique des Ventes • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <?php
    // Charger les billets depuis la BD
    require_once ROOT_PATH . '/Model/Billets.php';
    require_once ROOT_PATH . '/Model/Trajets.php';
    
    $billetModel = new Billets();
    $trajetModel = new Trajets();
    
    // Récupérer les filtres
    $filters = [];
    $filters['date_debut'] = $_GET['date_debut'] ?? date('Y-m-01'); // Premier jour du mois
    $filters['date_fin'] = $_GET['date_fin'] ?? date('Y-m-d'); // Aujourd'hui
    $filters['trajet_id'] = $_GET['trajet_id'] ?? '';
    $filters['mode_paiement'] = $_GET['mode_paiement'] ?? '';
    
    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;
    
    // Récupérer les données
    $billets = $billetModel->getBilletsRecents($limit, $offset, $filters);
    $totalBillets = $billetModel->compterBillets($filters);
    $totalPages = ceil($totalBillets / $limit);
    
    // Statistiques
    $stats = $billetModel->getStatistiquesHistorique($filters);
    
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
          <h1>Historique des Ventes</h1>
          <p>Consultation de toutes les ventes de billets</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary">
            <i data-feather="download"></i> Exporter
          </button>
        </div>
      </header>

      <!-- Filtres -->
      <section class="card" style="margin-bottom: 20px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date début</label>
              <input type="date" class="form-control" id="dateDebut" name="date_debut" value="<?= htmlspecialchars($filters['date_debut']) ?>">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date fin</label>
              <input type="date" class="form-control" id="dateFin" name="date_fin" value="<?= htmlspecialchars($filters['date_fin']) ?>">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Trajet</label>
              <select class="form-control" id="trajetId" name="trajet_id">
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
              <select class="form-control" id="modePaiement" name="mode_paiement">
                <option value="">Tous</option>
                <option value="especes" <?= $filters['mode_paiement'] == 'especes' ? 'selected' : '' ?>>Espèces</option>
                <option value="mobile_money" <?= $filters['mode_paiement'] == 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                <option value="carte_bancaire" <?= $filters['mode_paiement'] == 'carte_bancaire' ? 'selected' : '' ?>>Carte bancaire</option>
                <option value="carte_prepayee" <?= $filters['mode_paiement'] == 'carte_prepayee' ? 'selected' : '' ?>>Carte prépayée</option>
              </select>
            </div>
          </div>
          <div style="margin-top: 16px; display: flex; gap: 12px;">
            <button class="btn btn--primary" id="btnRechercher">
              <i data-feather="search"></i> Rechercher
            </button>
            <button class="btn btn--secondary" id="btnReinitialiser">
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
              <th>Date voyage</th>
              <th>Siège</th>
              <th>Montant</th>
              <th>Paiement</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($billets)) : ?>
              <?php foreach ($billets as $billet) : 
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
              <td><?= date('d/m/Y', strtotime($billet['date_voyage'])) ?> - <?= $billet['heure_depart'] ?? 'N/A' ?></td>
              <td><span class="badge badge--info"><?= $billet['siege_numero'] ?? 'N/A' ?></span></td>
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
            <?php else : ?>
            <tr>
              <td colspan="10" style="text-align: center; padding: 40px; color: #6b7280;">
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
              <a href="?page=<?= $page - 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">
                <i data-feather="chevron-left"></i> Précédent
              </a>
            <?php else: ?>
              <button class="btn btn--secondary btn--sm" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
            <?php endif; ?>

            <?php
            // Afficher les numéros de page
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1): ?>
              <a href="?page=1&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">1</a>
              <?php if ($startPage > 2): ?>
                <span style="padding: 0 8px; color: #6b7280;">...</span>
              <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
              <?php if ($i == $page): ?>
                <button class="btn btn--secondary btn--sm active" style="background: #1B4B7F; color: white;"><?= $i ?></button>
              <?php else: ?>
                <a href="?page=<?= $i ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm"><?= $i ?></a>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
              <?php if ($endPage < $totalPages - 1): ?>
                <span style="padding: 0 8px; color: #6b7280;">...</span>
              <?php endif; ?>
              <a href="?page=<?= $totalPages ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm"><?= $totalPages ?></a>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?>&date_debut=<?= urlencode($filters['date_debut']) ?>&date_fin=<?= urlencode($filters['date_fin']) ?>&trajet_id=<?= urlencode($filters['trajet_id']) ?>&mode_paiement=<?= urlencode($filters['mode_paiement']) ?>" class="btn btn--secondary btn--sm">
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

        // Construire l'URL avec les paramètres
        const params = new URLSearchParams();
        if (dateDebut) params.append('date_debut', dateDebut);
        if (dateFin) params.append('date_fin', dateFin);
        if (trajetId) params.append('trajet_id', trajetId);
        if (modePaiement) params.append('mode_paiement', modePaiement);
        params.append('page', '1'); // Réinitialiser à la page 1

        // Rediriger avec les nouveaux paramètres
        window.location.href = '?' + params.toString();
      }

      // Bouton Rechercher
      document.getElementById('btnRechercher').addEventListener('click', function() {
        appliquerFiltres();
      });

      // Bouton Réinitialiser
      document.getElementById('btnReinitialiser').addEventListener('click', function() {
        window.location.href = window.location.pathname;
      });

      // Appliquer les filtres avec Enter
      ['dateDebut', 'dateFin', 'trajetId', 'modePaiement'].forEach(id => {
        document.getElementById(id).addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            appliquerFiltres();
          }
        });
      });
    });
  </script>
</body>
</html>
