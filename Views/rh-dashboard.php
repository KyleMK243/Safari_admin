<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Tableau de bord RH • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_RH.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Tableau de bord RH</h1>
          <p>Vue d'ensemble des ressources humaines</p>
        </div>
      </header>

      <!-- KPIs principaux -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; display: grid; place-items: center;">
              <i data-feather="users" style="width: 24px; height: 24px; color: #1B4B7F;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Total employés</div>
              <div style="font-size: 28px; font-weight: 800; color: #1B4B7F;" id="kpiTotal">0</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #10b981;" id="kpiTotalVariation">Chargement...</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: grid; place-items: center;">
              <i data-feather="check-circle" style="width: 24px; height: 24px; color: #10b981;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Actifs</div>
              <div style="font-size: 28px; font-weight: 800; color: #10b981;" id="kpiActifs">0</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #6b7280;" id="kpiActifsPourcent">0% du personnel</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: grid; place-items: center;">
              <i data-feather="file-text" style="width: 24px; height: 24px; color: #f59e0b;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">Contrats CDI</div>
              <div style="font-size: 28px; font-weight: 800; color: #f59e0b;" id="kpiCDI">0</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #6b7280;" id="kpiCDD">0 CDD</div>
        </div>

        <div class="card" style="padding: 20px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fee2e2; display: grid; place-items: center;">
              <i data-feather="alert-circle" style="width: 24px; height: 24px; color: #ef4444;"></i>
            </div>
            <div style="flex: 1;">
              <div style="font-size: 13px; color: #6b7280;">En congé</div>
              <div style="font-size: 28px; font-weight: 800; color: #ef4444;" id="kpiConge">0</div>
            </div>
          </div>
          <div style="font-size: 12px; color: #6b7280;" id="kpiCongePourcent">0% du personnel</div>
        </div>
      </div>

      <!-- Répartition par fonction -->
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div class="card">
          <div class="card__header">
            <h3>Répartition par fonction</h3>
          </div>
          <div style="padding: 24px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i data-feather="truck" style="width: 16px; height: 16px; color: #1B4B7F;"></i>
                    <span style="font-weight: 600;">Chauffeurs</span>
                  </div>
                  <span style="font-weight: 700; color: #1B4B7F;" id="repChauffeurs">0 (0%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div id="repChauffeursBar" style="width: 0%; height: 100%; background: #1B4B7F;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i data-feather="dollar-sign" style="width: 16px; height: 16px; color: #10b981;"></i>
                    <span style="font-weight: 600;">Receveurs</span>
                  </div>
                  <span style="font-weight: 700; color: #10b981;" id="repReceveurs">0 (0%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div id="repReceveursBar" style="width: 0%; height: 100%; background: #10b981;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i data-feather="clipboard" style="width: 16px; height: 16px; color: #3b82f6;"></i>
                    <span style="font-weight: 600;">Contrôleurs</span>
                  </div>
                  <span style="font-weight: 700; color: #3b82f6;" id="repControleurs">0 (0%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div id="repControleursBar" style="width: 0%; height: 100%; background: #3b82f6;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i data-feather="tool" style="width: 16px; height: 16px; color: #f59e0b;"></i>
                    <span style="font-weight: 600;">Mécaniciens</span>
                  </div>
                  <span style="font-weight: 700; color: #f59e0b;" id="repMecaniciens">0 (0%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div id="repMecaniciensBar" style="width: 0%; height: 100%; background: #f59e0b;"></div>
                </div>
              </div>

              <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i data-feather="briefcase" style="width: 16px; height: 16px; color: #8b5cf6;"></i>
                    <span style="font-weight: 600;">Administratif</span>
                  </div>
                  <span style="font-weight: 700; color: #8b5cf6;" id="repAdministratif">0 (0%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                  <div id="repAdministratifBar" style="width: 0%; height: 100%; background: #8b5cf6;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
          <div class="card__header">
            <h3>Actions rapides</h3>
          </div>
          <div style="padding: 20px;">
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <button class="btn btn--primary" style="width: 100%; justify-content: center;" onclick="window.location.href='<?php echo BASE_URL; ?>/nouveau-agent'">
                <i data-feather="user-plus"></i> Ajouter un agent
              </button>
              <button class="btn btn--secondary" style="width: 100%; justify-content: center;" onclick="window.location.href='<?php echo BASE_URL; ?>/personnel'">
                <i data-feather="users"></i> Voir le personnel
              </button>
              <button class="btn btn--secondary" style="width: 100%; justify-content: center;" onclick="window.location.href='<?php echo BASE_URL; ?>/contrats'">
                <i data-feather="file-text"></i> Gérer les contrats
              </button>
            </div>

            <div style="margin-top: 24px; padding: 16px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i data-feather="alert-triangle" style="width: 16px; height: 16px; color: #f59e0b;"></i>
                <strong style="color: #92400e; font-size: 14px;">Alertes</strong>
              </div>
              <div style="font-size: 13px; color: #92400e; line-height: 1.6;">
                • - contrats à renouveler<br>
                • - documents manquants<br>
                • - formations à planifier
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Derniers ajouts -->
      <div class="card">
        <div class="card__header">
          <h3>Derniers agents ajoutés</h3>
        </div>
        <div style="overflow-x: auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Agent</th>
                <th>Fonction</th>
                <th>Date d'embauche</th>
                <th>Type de contrat</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody id="derniersAgentsBody">
              <!-- Données chargées dynamiquement -->
            </tbody>
          </table>
        </div>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
      chargerDonneesDashboard();
    });

    function chargerDonneesDashboard() {
      // Charger les statistiques
      fetch('<?php echo BASE_URL; ?>/personnel/get?limit=5')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.stats) {
            updateKPIs(data.stats);
            updateRepartition(data.stats);
            afficherDerniersAgents(data.agents);
          }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function updateKPIs(stats) {
      const total = stats.total || 0;
      const actifs = stats.statut_actif || 0;
      const conge = stats.statut_conge || 0;
      const cdi = stats.contrat_cdi || 0;
      const cdd = stats.contrat_cdd || 0;

      document.getElementById('kpiTotal').textContent = total;
      document.getElementById('kpiActifs').textContent = actifs;
      document.getElementById('kpiConge').textContent = conge;
      document.getElementById('kpiCDI').textContent = cdi;

      // Calcul des pourcentages
      const actifsPourcent = total > 0 ? Math.round((actifs / total) * 100) : 0;
      const congePourcent = total > 0 ? Math.round((conge / total) * 100) : 0;

      document.getElementById('kpiActifsPourcent').textContent = `${actifsPourcent}% du personnel`;
      document.getElementById('kpiCongePourcent').textContent = `${congePourcent}% du personnel`;
      document.getElementById('kpiCDD').textContent = `${cdd} CDD`;
      document.getElementById('kpiTotalVariation').textContent = `${total} agents`;
    }

    function updateRepartition(stats) {
      const total = stats.total || 1; // Éviter division par 0
      
      const postes = [
        { id: 'Chauffeurs', count: stats.poste_chauffeur || 0 },
        { id: 'Receveurs', count: stats.poste_receveur || 0 },
        { id: 'Controleurs', count: stats.poste_controleur || 0 },
        { id: 'Mecaniciens', count: stats.poste_mecanicien || 0 },
        { id: 'Administratif', count: stats.poste_administratif || 0 }
      ];

      postes.forEach(poste => {
        const pourcent = Math.round((poste.count / total) * 100);
        document.getElementById(`rep${poste.id}`).textContent = `${poste.count} (${pourcent}%)`;
        document.getElementById(`rep${poste.id}Bar`).style.width = `${pourcent}%`;
      });
    }

    function afficherDerniersAgents(agents) {
      const tbody = document.getElementById('derniersAgentsBody');
      
      if (!agents || agents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #9ca3af;">Aucun agent trouvé</td></tr>';
        return;
      }

      const posteColors = {
        'chauffeur': 'badge--info',
        'receveur': 'badge--success',
        'controleur': 'badge--primary',
        'mecanicien': 'badge--warning',
        'administratif': 'badge--secondary'
      };

      const statutColors = {
        'actif': 'status-badge--actif',
        'conge': 'status-badge--conge',
        'suspendu': 'status-badge--panne',
        'inactif': 'status-badge--inactif'
      };

      const gradients = [
        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
        'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
      ];

      tbody.innerHTML = agents.slice(0, 5).map((agent, index) => {
        const nomParts = agent.nom.split(' ');
        const initiales = nomParts.length > 1 
          ? (nomParts[0].charAt(0) + nomParts[nomParts.length - 1].charAt(0)).toUpperCase()
          : agent.nom.substring(0, 2).toUpperCase();

        return `
          <tr>
            <td>
              <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: ${gradients[index % gradients.length]}; display: grid; place-items: center; color: white; font-weight: 700;">${initiales}</div>
                <div>
                  <div style="font-weight: 600;">${agent.nom}</div>
                  <div style="font-size: 12px; color: #6b7280;">ID: ${agent.matricule || 'N/A'}</div>
                </div>
              </div>
            </td>
            <td><span class="badge ${posteColors[agent.poste] || 'badge--secondary'}">${agent.poste.charAt(0).toUpperCase() + agent.poste.slice(1)}</span></td>
            <td>${agent.date_embauche ? new Date(agent.date_embauche).toLocaleDateString('fr-FR') : '-'}</td>
            <td>${agent.type_contrat ? agent.type_contrat.toUpperCase() : '-'}</td>
            <td><span class="status-badge ${statutColors[agent.statut]}">${agent.statut.charAt(0).toUpperCase() + agent.statut.slice(1)}</span></td>
          </tr>
        `;
      }).join('');

      feather.replace();
    }
  </script>
</body>
</html>
