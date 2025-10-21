<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Canaux de Vente • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BT.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Canaux de Vente</h1>
          <p>Gestion et suivi des différents points de vente</p>
        </div>
      </header>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- Statistiques globales -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total ventes</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">8,547</div>
          <div style="font-size: 12px; color: #10b981; margin-top: 4px;">+12% ce mois</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus totaux</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">42,735,000 CDF</div>
          <div style="font-size: 12px; color: #10b981; margin-top: 4px;">+8% ce mois</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Canaux actifs</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">4</div>
          <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Sur 4 canaux</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Ticket moyen</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">5,000 CDF</div>
          <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Par transaction</div>
        </div>
      </div>

      <!-- Canaux de vente -->
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
        <!-- Vente en ligne -->
        <div class="card">
          <div style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center;">
                  <i data-feather="globe" style="width: 28px; height: 28px; color: white;"></i>
                </div>
                <div>
                  <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Vente en ligne</h3>
                  <p style="margin: 4px 0 0; font-size: 13px; color: #6b7280;">Site web & Plateforme</p>
                </div>
              </div>
              <span class="status-badge status-badge--actif">Actif</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Ventes</div>
                <div style="font-weight: 700; font-size: 20px; color: #1B4B7F;">3,245</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Revenus</div>
                <div style="font-weight: 700; font-size: 20px; color: #10b981;">16,225,000 CDF</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Part</div>
                <div style="font-weight: 700; font-size: 20px; color: #f59e0b;">38%</div>
              </div>
            </div>

            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px; color: #6b7280;">Performance</span>
                <span style="font-size: 13px; font-weight: 600; color: #10b981;">+15%</span>
              </div>
              <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                <div style="width: 38%; height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
              </div>
            </div>

            <div style="display: flex; gap: 8px;">
              <button class="btn btn--primary" style="flex: 1;" onclick="ouvrirStats('en-ligne')">
                <i data-feather="bar-chart-2"></i> Voir stats
              </button>
              <button class="btn btn--secondary" onclick="ouvrirConfig('en-ligne')">
                <i data-feather="settings"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Application mobile -->
        <div class="card">
          <div style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: grid; place-items: center;">
                  <i data-feather="smartphone" style="width: 28px; height: 28px; color: white;"></i>
                </div>
                <div>
                  <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Application mobile</h3>
                  <p style="margin: 4px 0 0; font-size: 13px; color: #6b7280;">iOS & Android</p>
                </div>
              </div>
              <span class="status-badge status-badge--actif">Actif</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Ventes</div>
                <div style="font-weight: 700; font-size: 20px; color: #1B4B7F;">2,847</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Revenus</div>
                <div style="font-weight: 700; font-size: 20px; color: #10b981;">14,235,000 CDF</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Part</div>
                <div style="font-weight: 700; font-size: 20px; color: #f59e0b;">33%</div>
              </div>
            </div>

            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px; color: #6b7280;">Performance</span>
                <span style="font-size: 13px; font-weight: 600; color: #10b981;">+22%</span>
              </div>
              <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                <div style="width: 33%; height: 100%; background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);"></div>
              </div>
            </div>

            <div style="display: flex; gap: 8px;">
              <button class="btn btn--primary" style="flex: 1;" onclick="ouvrirStats('mobile')">
                <i data-feather="bar-chart-2"></i> Voir stats
              </button>
              <button class="btn btn--secondary" onclick="ouvrirConfig('mobile')">
                <i data-feather="settings"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Guichets physiques -->
        <div class="card">
          <div style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: grid; place-items: center;">
                  <i data-feather="home" style="width: 28px; height: 28px; color: white;"></i>
                </div>
                <div>
                  <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Guichets physiques</h3>
                  <p style="margin: 4px 0 0; font-size: 13px; color: #6b7280;">Gares & Agences</p>
                </div>
              </div>
              <span class="status-badge status-badge--actif">Actif</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Ventes</div>
                <div style="font-weight: 700; font-size: 20px; color: #1B4B7F;">1,985</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Revenus</div>
                <div style="font-weight: 700; font-size: 20px; color: #10b981;">9,925,000 CDF</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Part</div>
                <div style="font-weight: 700; font-size: 20px; color: #f59e0b;">23%</div>
              </div>
            </div>

            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px; color: #6b7280;">Performance</span>
                <span style="font-size: 13px; font-weight: 600; color: #10b981;">+5%</span>
              </div>
              <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                <div style="width: 23%; height: 100%; background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);"></div>
              </div>
            </div>

            <div style="display: flex; gap: 8px;">
              <button class="btn btn--primary" style="flex: 1;" onclick="window.location.href='<?php echo BASE_URL; ?>/gestion-guichets'">
                <i data-feather="map-pin"></i> Gérer les guichets
              </button>
              <button class="btn btn--secondary" onclick="ouvrirStats('guichets')" title="Statistiques">
                <i data-feather="bar-chart-2"></i>
              </button>
              <button class="btn btn--secondary" onclick="ouvrirConfig('guichets')" title="Configuration">
                <i data-feather="settings"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Partenaires & Revendeurs -->
        <div class="card">
          <div style="padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: grid; place-items: center;">
                  <i data-feather="users" style="width: 28px; height: 28px; color: white;"></i>
                </div>
                <div>
                  <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Partenaires</h3>
                  <p style="margin: 4px 0 0; font-size: 13px; color: #6b7280;">Revendeurs agréés</p>
                </div>
              </div>
              <span class="status-badge status-badge--actif">Actif</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Ventes</div>
                <div style="font-weight: 700; font-size: 20px; color: #1B4B7F;">470</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Revenus</div>
                <div style="font-weight: 700; font-size: 20px; color: #10b981;">2,350,000 CDF</div>
              </div>
              <div style="background: #f9fafb; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Part</div>
                <div style="font-weight: 700; font-size: 20px; color: #f59e0b;">6%</div>
              </div>
            </div>

            <div style="margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px; color: #6b7280;">Performance</span>
                <span style="font-size: 13px; font-weight: 600; color: #10b981;">+18%</span>
              </div>
              <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                <div style="width: 6%; height: 100%; background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);"></div>
              </div>
            </div>

            <div style="display: flex; gap: 8px;">
              <button class="btn btn--primary" style="flex: 1;" onclick="window.location.href='<?php echo BASE_URL; ?>/gestion-partenaires'">
                <i data-feather="users"></i> Gérer les partenaires
              </button>
              <button class="btn btn--secondary" onclick="ouvrirStats('partenaires')" title="Statistiques">
                <i data-feather="bar-chart-2"></i>
              </button>
              <button class="btn btn--secondary" onclick="ouvrirConfig('partenaires')" title="Configuration">
                <i data-feather="settings"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Graphique de répartition -->
      <div class="card">
        <div class="card__header">
          <h3>Répartition des ventes par canal</h3>
        </div>
        <div style="padding: 24px;">
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Graphique (placeholder) -->
            <div>
              <div style="background: #f9fafb; border-radius: 12px; padding: 40px; text-align: center;">
                <div style="width: 300px; height: 300px; margin: 0 auto; border-radius: 50%; background: conic-gradient(
                  from 0deg,
                  #667eea 0deg 136.8deg,
                  #f093fb 136.8deg 255.6deg,
                  #4facfe 255.6deg 338.4deg,
                  #fa709a 338.4deg 360deg
                ); position: relative;">
                  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 180px; height: 180px; background: white; border-radius: 50%; display: grid; place-items: center;">
                    <div>
                      <div style="font-size: 14px; color: #6b7280;">Total</div>
                      <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">8,547</div>
                      <div style="font-size: 12px; color: #6b7280;">ventes</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Légende -->
            <div>
              <h4 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 700;">Détails par canal</h4>
              
              <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 12px; height: 12px; border-radius: 3px; background: #667eea;"></div>
                    <span style="font-weight: 600;">Vente en ligne</span>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-weight: 700; color: #1B4B7F;">38%</div>
                    <div style="font-size: 12px; color: #6b7280;">3,245 ventes</div>
                  </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 12px; height: 12px; border-radius: 3px; background: #f093fb;"></div>
                    <span style="font-weight: 600;">Application mobile</span>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-weight: 700; color: #1B4B7F;">33%</div>
                    <div style="font-size: 12px; color: #6b7280;">2,847 ventes</div>
                  </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 12px; height: 12px; border-radius: 3px; background: #4facfe;"></div>
                    <span style="font-weight: 600;">Guichets</span>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-weight: 700; color: #1B4B7F;">23%</div>
                    <div style="font-size: 12px; color: #6b7280;">1,985 ventes</div>
                  </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 12px; height: 12px; border-radius: 3px; background: #fa709a;"></div>
                    <span style="font-weight: 600;">Partenaires</span>
                  </div>
                  <div style="text-align: right;">
                    <div style="font-weight: 700; color: #1B4B7F;">6%</div>
                    <div style="font-size: 12px; color: #6b7280;">470 ventes</div>
                  </div>
                </div>
              </div>

              <div style="margin-top: 24px; padding: 16px; background: #dbeafe; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                  <i data-feather="trending-up" style="width: 16px; height: 16px; color: #1e40af;"></i>
                  <strong style="color: #1e40af; font-size: 14px;">Tendance</strong>
                </div>
                <p style="margin: 0; font-size: 13px; color: #1e40af; line-height: 1.5;">
                  Les canaux digitaux (en ligne + mobile) représentent <strong>71%</strong> des ventes totales avec une croissance de <strong>+18%</strong> ce mois.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Statistiques -->
  <div class="modal" id="modalStats">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 900px;">
      <div class="modal__header">
        <h2 id="modalStatsTitle">Statistiques détaillées</h2>
        <button class="modal__close" id="closeModalStats">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <div id="statsContent">
          <!-- Contenu dynamique -->
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Configuration -->
  <div class="modal" id="modalConfig">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 700px;">
      <div class="modal__header">
        <h2 id="modalConfigTitle">Configuration du canal</h2>
        <button class="modal__close" id="closeModalConfig">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <div id="configContent">
          <!-- Contenu dynamique -->
        </div>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      const modalStats = document.getElementById('modalStats');
      const modalConfig = document.getElementById('modalConfig');
      const closeModalStats = document.getElementById('closeModalStats');
      const closeModalConfig = document.getElementById('closeModalConfig');

      // Fermer les modals
      closeModalStats?.addEventListener('click', () => {
        modalStats.classList.remove('active');
      });

      closeModalConfig?.addEventListener('click', () => {
        modalConfig.classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });
    });

    // Fonction pour ouvrir les statistiques
    function ouvrirStats(canal) {
      const modalStats = document.getElementById('modalStats');
      const modalStatsTitle = document.getElementById('modalStatsTitle');
      const statsContent = document.getElementById('statsContent');

      const canaux = {
        'en-ligne': {
          titre: 'Vente en ligne - Statistiques détaillées',
          ventes: 3245,
          revenus: '16,225,000 CDF',
          part: '38%',
          croissance: '+15%',
          couleur: '#667eea'
        },
        'mobile': {
          titre: 'Application mobile - Statistiques détaillées',
          ventes: 2847,
          revenus: '14,235,000 CDF',
          part: '33%',
          croissance: '+22%',
          couleur: '#f093fb'
        },
        'guichets': {
          titre: 'Guichets physiques - Statistiques détaillées',
          ventes: 1985,
          revenus: '9,925,000 CDF',
          part: '23%',
          croissance: '+5%',
          couleur: '#4facfe'
        },
        'partenaires': {
          titre: 'Partenaires - Statistiques détaillées',
          ventes: 470,
          revenus: '2,350,000 CDF',
          part: '6%',
          croissance: '+18%',
          couleur: '#fa709a'
        }
      };

      const data = canaux[canal];
      modalStatsTitle.textContent = data.titre;

      statsContent.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
          <div style="background: #f9fafb; padding: 20px; border-radius: 12px; border-left: 4px solid ${data.couleur};">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total des ventes</div>
            <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">${data.ventes.toLocaleString()}</div>
            <div style="font-size: 13px; color: #10b981; margin-top: 4px;">Croissance: ${data.croissance}</div>
          </div>
          <div style="background: #f9fafb; padding: 20px; border-radius: 12px; border-left: 4px solid ${data.couleur};">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus générés</div>
            <div style="font-size: 32px; font-weight: 800; color: #10b981;">${data.revenus}</div>
            <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">Part totale: ${data.part}</div>
          </div>
        </div>

        <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
          <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Ventes par période</h4>
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
            <div style="text-align: center; padding: 12px; background: white; border-radius: 8px;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Aujourd'hui</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${Math.floor(data.ventes / 30)}</div>
            </div>
            <div style="text-align: center; padding: 12px; background: white; border-radius: 8px;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Cette semaine</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${Math.floor(data.ventes / 4)}</div>
            </div>
            <div style="text-align: center; padding: 12px; background: white; border-radius: 8px;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Ce mois</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${data.ventes}</div>
            </div>
            <div style="text-align: center; padding: 12px; background: white; border-radius: 8px;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Cette année</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${data.ventes * 12}</div>
            </div>
          </div>
        </div>

        <div style="background: #f9fafb; padding: 20px; border-radius: 12px;">
          <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Top 5 des trajets</h4>
          <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
              <span style="font-weight: 600;">Kinshasa → Matadi</span>
              <span style="font-weight: 700; color: #1B4B7F;">${Math.floor(data.ventes * 0.35)} ventes</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
              <span style="font-weight: 600;">Kinshasa → Lubumbashi</span>
              <span style="font-weight: 700; color: #1B4B7F;">${Math.floor(data.ventes * 0.25)} ventes</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
              <span style="font-weight: 600;">Kinshasa → Kikwit</span>
              <span style="font-weight: 700; color: #1B4B7F;">${Math.floor(data.ventes * 0.20)} ventes</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
              <span style="font-weight: 600;">Matadi → Kinshasa</span>
              <span style="font-weight: 700; color: #1B4B7F;">${Math.floor(data.ventes * 0.12)} ventes</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px;">
              <span style="font-weight: 600;">Kinshasa Gare → Lemba</span>
              <span style="font-weight: 700; color: #1B4B7F;">${Math.floor(data.ventes * 0.08)} ventes</span>
            </div>
          </div>
        </div>
      `;

      modalStats.classList.add('active');
      feather.replace();
    }

    // Fonction pour ouvrir la configuration
    function ouvrirConfig(canal) {
      const modalConfig = document.getElementById('modalConfig');
      const modalConfigTitle = document.getElementById('modalConfigTitle');
      const configContent = document.getElementById('configContent');

      const canaux = {
        'en-ligne': 'Vente en ligne',
        'mobile': 'Application mobile',
        'guichets': 'Guichets physiques',
        'partenaires': 'Partenaires'
      };

      modalConfigTitle.textContent = 'Configuration - ' + canaux[canal];

      configContent.innerHTML = `
        <form id="formConfig">
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut du canal</label>
            <select class="form-control">
              <option value="actif" selected>Actif</option>
              <option value="inactif">Inactif</option>
              <option value="maintenance">En maintenance</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Commission (%)</label>
            <input type="number" class="form-control" value="5" min="0" max="100" step="0.5">
            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Pourcentage de commission sur chaque vente</div>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Limite de transactions par jour</label>
            <input type="number" class="form-control" value="1000" min="0">
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Email de notification</label>
            <input type="email" class="form-control" placeholder="notifications@safari.cd">
          </div>

          <div style="margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" checked>
              <span style="font-size: 14px; font-weight: 600;">Activer les notifications en temps réel</span>
            </label>
          </div>

          <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <button type="button" class="btn btn--secondary" onclick="document.getElementById('modalConfig').classList.remove('active')">
              Annuler
            </button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="save"></i> Enregistrer
            </button>
          </div>
        </form>
      `;

      // Gérer la soumission du formulaire
      document.getElementById('formConfig').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Configuration enregistrée avec succès !');
        modalConfig.classList.remove('active');
      });

      modalConfig.classList.add('active');
      feather.replace();
    }
  </script>
</body>
</html>
