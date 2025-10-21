<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Business Intelligence • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Business Intelligence</h1>
          <p>Statistiques et analyses de performance</p>
        </div>
        <div class="header__actions">
          <select class="select-period" id="selectPeriode" onchange="changerPeriode(this.value)">
            <option value="7" <?= isset($periode) && $periode == 7 ? 'selected' : '' ?>>7 derniers jours</option>
            <option value="30" <?= !isset($periode) || $periode == 30 ? 'selected' : '' ?>>30 derniers jours</option>
            <option value="90" <?= isset($periode) && $periode == 90 ? 'selected' : '' ?>>90 derniers jours</option>
            <option value="365" <?= isset($periode) && $periode == 365 ? 'selected' : '' ?>>1 an</option>
          </select>
        </div>
      </header>

      <!-- KPIs -->
      <section class="bi-stats">
        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--blue">
            <i data-feather="truck"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Bus actifs</div>
            <div class="bi-stat-card__value"><?= $kpis['bus_actifs'] ?></div>
            <div class="bi-stat-card__trend">
              <i data-feather="truck"></i> Flotte totale
            </div>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--green">
            <i data-feather="map-pin"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Trajets effectués</div>
            <div class="bi-stat-card__value"><?= number_format($kpis['trajets_effectues']) ?></div>
            <?php if ($kpis['tendance_trajets'] != 0) : ?>
            <div class="bi-stat-card__trend bi-stat-card__trend--<?= $kpis['tendance_trajets'] > 0 ? 'up' : 'down' ?>">
              <i data-feather="arrow-<?= $kpis['tendance_trajets'] > 0 ? 'up' : 'down' ?>"></i> <?= abs($kpis['tendance_trajets']) ?>%
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--yellow">
            <i data-feather="users"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Passagers</div>
            <div class="bi-stat-card__value"><?= number_format($kpis['passagers']) ?></div>
            <?php if ($kpis['tendance_passagers'] != 0) : ?>
            <div class="bi-stat-card__trend bi-stat-card__trend--<?= $kpis['tendance_passagers'] > 0 ? 'up' : 'down' ?>">
              <i data-feather="arrow-<?= $kpis['tendance_passagers'] > 0 ? 'up' : 'down' ?>"></i> <?= abs($kpis['tendance_passagers']) ?>%
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--red">
            <i data-feather="dollar-sign"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Revenus (<?= $periode ?>j)</div>
            <div class="bi-stat-card__value"><?= number_format($kpis['revenus'], 0, ',', ' ') ?> CDF</div>
            <?php if ($kpis['tendance_revenus'] != 0) : ?>
            <div class="bi-stat-card__trend bi-stat-card__trend--<?= $kpis['tendance_revenus'] > 0 ? 'up' : 'down' ?>">
              <i data-feather="arrow-<?= $kpis['tendance_revenus'] > 0 ? 'up' : 'down' ?>"></i> <?= abs($kpis['tendance_revenus']) ?>%
            </div>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- Charts Row 1 -->
      <section class="charts-grid">
        <div class="card chart-card">
          <div class="chart-card__header">
            <h3>Trajets par jour</h3>
            <span class="chart-card__period">30 derniers jours</span>
          </div>
          <div class="chart-card__body">
            <canvas id="chartTrajets"></canvas>
          </div>
        </div>

        <div class="card chart-card">
          <div class="chart-card__header">
            <h3>Répartition par ligne</h3>
            <span class="chart-card__period">Ce mois</span>
          </div>
          <div class="chart-card__body">
            <canvas id="chartLignes"></canvas>
          </div>
        </div>
      </section>

      <!-- Charts Row 2 -->
      <section class="charts-grid">
        <div class="card chart-card">
          <div class="chart-card__header">
            <h3>Performance des bus</h3>
            <span class="chart-card__period">Top 5</span>
          </div>
          <div class="chart-card__body">
            <canvas id="chartPerformance"></canvas>
          </div>
        </div>

        <div class="card chart-card">
          <div class="chart-card__header">
            <h3>Revenus mensuels</h3>
            <span class="chart-card__period">6 derniers mois</span>
          </div>
          <div class="chart-card__body">
            <canvas id="chartRevenus"></canvas>
          </div>
        </div>
      </section>

      <!-- Table des performances -->
      <section class="card">
        <div class="card__header">
          <h3>Performance détaillée par bus</h3>
        </div>
        <div style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>Bus</th>
              <th>Trajets</th>
              <th>Passagers</th>
              <th>Revenus</th>
              <th>Taux de remplissage</th>
              <th>Performance</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($statsDetailleesParBus)) : ?>
              <?php foreach ($statsDetailleesParBus as $stat) : 
                $performance = '';
                $performanceBadge = '';
                $tauxRemplissage = (float)$stat['taux_remplissage'];
                
                if ($tauxRemplissage >= 80) {
                  $performance = 'Excellent';
                  $performanceBadge = 'status-badge--actif';
                } elseif ($tauxRemplissage >= 60) {
                  $performance = 'Bon';
                  $performanceBadge = 'status-badge--actif';
                } elseif ($tauxRemplissage >= 40) {
                  $performance = 'Moyen';
                  $performanceBadge = 'status-badge--maintenance';
                } else {
                  $performance = 'Faible';
                  $performanceBadge = 'status-badge--panne';
                }
              ?>
            <tr>
              <td><strong>Bus #<?= htmlspecialchars($stat['numero']) ?></strong></td>
              <td><?= number_format($stat['total_trajets']) ?></td>
              <td><?= number_format($stat['total_passagers']) ?></td>
              <td><?= number_format($stat['revenus'], 0, ',', ' ') ?> CDF</td>
              <td>
                <div class="progress-bar">
                  <div class="progress-bar__fill" style="width: <?= $tauxRemplissage ?>%"></div>
                  <span class="progress-bar__label"><?= $tauxRemplissage ?>%</span>
                </div>
              </td>
              <td><span class="status-badge <?= $performanceBadge ?>"><?= $performance ?></span></td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
              <td colspan="6" style="text-align: center; padding: 40px; color: #6b7280;">
                <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
                <p>Aucune donnée disponible</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1) : ?>
        <div class="pagination">
          <div class="pagination__info">
            <?php 
              $start = ($page - 1) * 10 + 1;
              $end = min($page * 10, $totalBus);
            ?>
            Affichage de <strong><?= $start ?></strong> à <strong><?= $end ?></strong> sur <strong><?= $totalBus ?></strong> bus
          </div>
          <div class="pagination__controls">
            <?php if ($page > 1) : ?>
              <a href="?periode=<?= $periode ?>&page=<?= $page - 1 ?>" class="pagination__btn">
                <i data-feather="chevron-left"></i> Précédent
              </a>
            <?php else : ?>
              <button class="pagination__btn" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
            <?php endif; ?>

            <div class="pagination__pages">
              <?php 
              // Limiter l'affichage des numéros de pages (max 7 pages visibles)
              $maxPages = 7;
              $startPage = max(1, $page - floor($maxPages / 2));
              $endPage = min($totalPages, $startPage + $maxPages - 1);
              
              if ($endPage - $startPage < $maxPages - 1) {
                $startPage = max(1, $endPage - $maxPages + 1);
              }
              
              for ($i = $startPage; $i <= $endPage; $i++) : 
              ?>
                <?php if ($i == $page) : ?>
                  <button class="pagination__page active"><?= $i ?></button>
                <?php else : ?>
                  <a href="?periode=<?= $periode ?>&page=<?= $i ?>" class="pagination__page"><?= $i ?></a>
                <?php endif; ?>
              <?php endfor; ?>
            </div>

            <?php if ($page < $totalPages) : ?>
              <a href="?periode=<?= $periode ?>&page=<?= $page + 1 ?>" class="pagination__btn">
                Suivant <i data-feather="chevron-right"></i>
              </a>
            <?php else : ?>
              <button class="pagination__btn" disabled>
                Suivant <i data-feather="chevron-right"></i>
              </button>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    // Fonction pour changer la période
    function changerPeriode(periode) {
      window.location.href = '?periode=' + periode;
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Données PHP converties en JavaScript
      const trajetsParJour = <?= json_encode($trajetsParJour) ?>;
      const repartitionLignes = <?= json_encode($repartitionLignes) ?>;
      const top5Bus = <?= json_encode($top5Bus) ?>;
      const revenusMensuels = <?= json_encode($revenusMensuels) ?>;

      // Chart 1: Trajets par jour (Line Chart)
      const ctxTrajets = document.getElementById('chartTrajets').getContext('2d');
      new Chart(ctxTrajets, {
        type: 'line',
        data: {
          labels: trajetsParJour.map(t => {
            const date = new Date(t.date);
            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
          }),
          datasets: [{
            label: 'Trajets',
            data: trajetsParJour.map(t => t.total),
            borderColor: '#0066CC',
            backgroundColor: 'rgba(0, 102, 204, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      // Chart 2: Répartition par ligne (Doughnut Chart)
      const ctxLignes = document.getElementById('chartLignes').getContext('2d');
      new Chart(ctxLignes, {
        type: 'doughnut',
        data: {
          labels: repartitionLignes.map(l => l.ligne || 'Ligne ' + l.ligne_numero),
          datasets: [{
            data: repartitionLignes.map(l => l.total_trajets),
            backgroundColor: ['#0066CC', '#FDB913', '#E63946', '#10B981', '#8B5CF6']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom' }
          }
        }
      });

      // Chart 3: Performance des bus (Bar Chart)
      const ctxPerformance = document.getElementById('chartPerformance').getContext('2d');
      new Chart(ctxPerformance, {
        type: 'bar',
        data: {
          labels: top5Bus.map(b => 'Bus #' + b.numero),
          datasets: [{
            label: 'Trajets',
            data: top5Bus.map(b => b.total_trajets),
            backgroundColor: '#0066CC'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      // Chart 4: Revenus mensuels (Line Chart)
      const ctxRevenus = document.getElementById('chartRevenus').getContext('2d');
      new Chart(ctxRevenus, {
        type: 'line',
        data: {
          labels: revenusMensuels.map(r => {
            const [annee, mois] = r.mois.split('-');
            const date = new Date(annee, mois - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
          }),
          datasets: [{
            label: 'Revenus (CDF)',
            data: revenusMensuels.map(r => r.revenus),
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { 
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return value.toLocaleString('fr-FR') + ' CDF';
                }
              }
            }
          }
        }
      });

      feather.replace();
    });
  </script>
</body>
</html>
