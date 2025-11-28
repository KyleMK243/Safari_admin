<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Trajets & arrêts du jour • Planification</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php'; ?>

    <main class="main">
      <header class="header">
        <div>
          <h1>Trajets & arrêts du jour</h1>
          <p>Adapter l'offre de service du jour : suspension de trajets, réorganisation des arrêts.</p>
        </div>
        <div class="header__actions">
          <input type="date" id="dateTrajetsJour" value="<?php echo date('Y-m-d'); ?>" class="form-control" />
        </div>
      </header>

      <section class="card">
        <div class="card__header card__header--reverse">
          <div>
            <h2>Trajets du réseau</h2>
            <p style="font-size:13px;color:#6b7280;">Suspendre ou réactiver les trajets pour la journée, selon la circulation et les événements.</p>
          </div>
        </div>
        <div class="card__body">
          <div style="overflow-x:auto;">
            <table class="table" style="white-space:nowrap;">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Nom du trajet</th>
                  <th>Distance</th>
                  <th>Arrêts</th>
                  <th>Points de chifte</th>
                  <th>Bus affectés</th>
                  <th>Statut permanent</th>
                  <th>Statut du jour</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($trajets)) : ?>
                  <?php foreach ($trajets as $trajet) : ?>
                    <tr>
                      <td>
                        <span style="background: <?= htmlspecialchars($trajet['couleur'] ?? '#3b82f6') ?>; color:#fff; padding:4px 8px; border-radius:4px; font-weight:700; font-size:12px;">
                          <?= htmlspecialchars($trajet['code'] ?? '-') ?>
                        </span>
                      </td>
                      <td><?= htmlspecialchars($trajet['nom']) ?></td>
                      <td><?= htmlspecialchars($trajet['distance_totale']) ?> km</td>
                      <td><?= isset($arretsCount[$trajet['id']]) ? $arretsCount[$trajet['id']] : 0 ?></td>
                      <td><?= isset($pointsChifteCount[$trajet['id']]) ? $pointsChifteCount[$trajet['id']] : 0 ?></td>
                      <td><?= isset($busCount[$trajet['id']]) ? $busCount[$trajet['id']] : 0 ?></td>
                      <td>
                        <span class="badge <?= $trajet['statut'] === 'actif' ? 'badge--green' : 'badge--danger' ?>">
                          <?= ucfirst($trajet['statut']) ?>
                        </span>
                      </td>
                      <td>
                        <!-- Maquette : à brancher plus tard sur un statut journalier -->
                        <span class="status-badge status-badge--actif">Actif aujourd'hui</span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon btn-icon--delete" title="Suspendre pour aujourd'hui">
                            <i data-feather="pause-circle"></i>
                          </button>
                          <button class="btn-icon btn-icon--success" title="Réactiver pour aujourd'hui">
                            <i data-feather="play-circle"></i>
                          </button>
                          <button class="btn-icon" title="Voir / réorganiser les arrêts">
                            <i data-feather="map-pin"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" style="text-align:center;padding:32px;color:#6b7280;">
                      Aucun trajet trouvé.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php'; ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    });
  </script>
</body>
</html>
