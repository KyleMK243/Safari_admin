<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Contrats • Safari</title>
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
          <h1>Gestion des Contrats</h1>
          <p>Suivi et gestion des contrats de travail</p>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total contrats</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">247</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">CDI</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">185</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">CDD</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">50</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">À renouveler</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">12</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Expirés</div>
          <div style="font-size: 32px; font-weight: 800; color: #ef4444;">5</div>
        </div>
      </div>

      <!-- Onglets -->
      <div class="tabs">
        <button class="tab-btn active" data-tab="actifs">
          <i data-feather="check-circle"></i> Actifs (235)
        </button>
        <button class="tab-btn" data-tab="renouveler">
          <i data-feather="alert-circle"></i> À renouveler (12)
        </button>
        <button class="tab-btn" data-tab="expires">
          <i data-feather="x-circle"></i> Expirés (5)
        </button>
      </div>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- Filtres -->
      <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Rechercher</label>
              <input type="text" class="form-control" placeholder="Nom, matricule..." id="searchContrat">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de contrat</label>
              <select class="form-control" id="filterType">
                <option value="">Tous</option>
                <option value="cdi">CDI</option>
                <option value="cdd">CDD</option>
                <option value="stage">Stage</option>
                <option value="interim">Intérim</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Fonction</label>
              <select class="form-control" id="filterFonction">
                <option value="">Toutes</option>
                <option value="chauffeur">Chauffeur</option>
                <option value="receveur">Receveur</option>
                <option value="controleur">Contrôleur</option>
                <option value="mecanicien">Mécanicien</option>
                <option value="administratif">Administratif</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control" id="filterStatut">
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="renouveler">À renouveler</option>
                <option value="expire">Expiré</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu Actifs -->
      <div class="tab-content active" id="tab-actifs">
        <section class="card">
          <div class="card__header">
            <h3>Contrats actifs</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table">
              <thead>
                <tr>
                  <th>Agent</th>
                  <th>Fonction</th>
                  <th>Type contrat</th>
                  <th>Date début</th>
                  <th>Date fin</th>
                  <th>Durée restante</th>
                  <th>Salaire</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                      <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center; color: white; font-weight: 700;">JM</div>
                      <div>
                        <div style="font-weight: 600;">Jean Mukendi</div>
                        <div style="font-size: 12px; color: #6b7280;">EMP-2025-001</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge badge--info">Chauffeur</span></td>
                  <td><strong>CDI</strong></td>
                  <td>15 Jan 2024</td>
                  <td><span style="color: #6b7280;">Indéterminée</span></td>
                  <td><span style="color: #10b981; font-weight: 600;">∞</span></td>
                  <td><strong style="color: #10b981;">450,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--actif">Actif</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Voir contrat" onclick="voirContrat('EMP-2025-001')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--primary" title="Télécharger" onclick="telechargerContrat('EMP-2025-001')">
                        <i data-feather="download"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Modifier" onclick="modifierContrat('EMP-2025-001')">
                        <i data-feather="edit-2"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                      <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: grid; place-items: center; color: white; font-weight: 700;">MT</div>
                      <div>
                        <div style="font-weight: 600;">Marie Tshala</div>
                        <div style="font-size: 12px; color: #6b7280;">EMP-2025-002</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge badge--success">Receveur</span></td>
                  <td><strong>CDI</strong></td>
                  <td>20 Jan 2024</td>
                  <td><span style="color: #6b7280;">Indéterminée</span></td>
                  <td><span style="color: #10b981; font-weight: 600;">∞</span></td>
                  <td><strong style="color: #10b981;">380,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--actif">Actif</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Voir contrat" onclick="voirContrat('EMP-2025-002')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--primary" title="Télécharger" onclick="telechargerContrat('EMP-2025-002')">
                        <i data-feather="download"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Modifier" onclick="modifierContrat('EMP-2025-002')">
                        <i data-feather="edit-2"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                      <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: grid; place-items: center; color: white; font-weight: 700;">PN</div>
                      <div>
                        <div style="font-weight: 600;">Paul Nsimba</div>
                        <div style="font-size: 12px; color: #6b7280;">EMP-2025-003</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge badge--primary">Contrôleur</span></td>
                  <td><strong>CDD</strong></td>
                  <td>05 Fév 2024</td>
                  <td>05 Fév 2026</td>
                  <td><span style="color: #3b82f6; font-weight: 600;">4 mois</span></td>
                  <td><strong style="color: #10b981;">350,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--actif">Actif</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Voir contrat" onclick="voirContrat('EMP-2025-003')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--primary" title="Télécharger" onclick="telechargerContrat('EMP-2025-003')">
                        <i data-feather="download"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Modifier" onclick="modifierContrat('EMP-2025-003')">
                        <i data-feather="edit-2"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <div class="pagination__info">
              Affichage de <strong>1</strong> à <strong>10</strong> sur <strong>32</strong> contrats
            </div>
            <div class="pagination__controls">
              <button class="pagination__btn" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
              <div class="pagination__pages">
                <button class="pagination__page active">1</button>
                <button class="pagination__page">2</button>
                <button class="pagination__page">3</button>
                <span class="pagination__dots">...</span>
                <button class="pagination__page">10</button>
              </div>
              <button class="pagination__btn">
                Suivant <i data-feather="chevron-right"></i>
              </button>
            </div>
          </div>
        </section>
      </div>

      <!-- Contenu À renouveler -->
      <div class="tab-content" id="tab-renouveler">
        <section class="card">
          <div class="card__header">
            <h3>Contrats à renouveler</h3>
          </div>
          <div style="padding: 24px;">
            <div style="background: #fef3c7; padding: 16px; border-radius: 8px; border-left: 4px solid #f59e0b; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i data-feather="alert-triangle" style="width: 20px; height: 20px; color: #f59e0b;"></i>
                <strong style="color: #92400e;">12 contrats arrivent à échéance dans les 60 prochains jours</strong>
              </div>
            </div>
            <div style="text-align: center; color: #6b7280;">
              Contrats CDD arrivant à échéance - Même structure que le tableau principal
            </div>
          </div>
        </section>
      </div>

      <!-- Contenu Expirés -->
      <div class="tab-content" id="tab-expires">
        <section class="card">
          <div class="card__header">
            <h3>Contrats expirés</h3>
          </div>
          <div style="padding: 24px;">
            <div style="background: #fee2e2; padding: 16px; border-radius: 8px; border-left: 4px solid #ef4444; margin-bottom: 20px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i data-feather="x-circle" style="width: 20px; height: 20px; color: #ef4444;"></i>
                <strong style="color: #991b1b;">5 contrats ont expiré et nécessitent une action</strong>
              </div>
            </div>
            <div style="text-align: center; color: #6b7280;">
              Contrats expirés nécessitant renouvellement ou clôture
            </div>
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails Contrat -->
  <div class="modal" id="modalDetails">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2 id="modalDetailsTitle">Détails du contrat</h2>
        <button class="modal__close" id="closeModalDetails">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <div id="detailsContent">
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

      // Gestion des onglets
      const tabBtns = document.querySelectorAll('.tab-btn');
      const tabContents = document.querySelectorAll('.tab-content');

      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const tabId = btn.getAttribute('data-tab');
          
          tabBtns.forEach(b => b.classList.remove('active'));
          tabContents.forEach(c => c.classList.remove('active'));
          
          btn.classList.add('active');
          document.getElementById(`tab-${tabId}`).classList.add('active');
          
          feather.replace();
        });
      });

      // Modal Détails
      const closeModalDetails = document.getElementById('closeModalDetails');
      closeModalDetails?.addEventListener('click', () => {
        document.getElementById('modalDetails').classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelector('.modal__overlay')?.addEventListener('click', () => {
        document.getElementById('modalDetails').classList.remove('active');
      });
    });

    // Fonction pour voir le contrat
    function voirContrat(agentId) {
      document.getElementById('detailsContent').innerHTML = `
        <div style="display: grid; gap: 20px;">
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Agent</div>
              <div style="font-weight: 700; font-size: 16px; color: #1B4B7F;">Jean Mukendi</div>
              <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">${agentId}</div>
            </div>
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Fonction</div>
              <div><span class="badge badge--info">Chauffeur</span></div>
            </div>
          </div>

          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations du contrat</h4>
            <div style="display: grid; gap: 8px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Type de contrat</span>
                <span style="font-weight: 600;">CDI</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Date de début</span>
                <span style="font-weight: 600;">15 Jan 2024</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Date de fin</span>
                <span style="font-weight: 600; color: #10b981;">Indéterminée</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Salaire mensuel</span>
                <span style="font-weight: 600; color: #10b981;">450,000 CDF</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Statut</span>
                <span class="status-badge status-badge--actif">Actif</span>
              </div>
            </div>
          </div>

          <div style="display: flex; gap: 12px;">
            <button class="btn btn--primary" style="flex: 1;" onclick="telechargerContrat('${agentId}')">
              <i data-feather="download"></i> Télécharger le contrat
            </button>
            <button class="btn btn--secondary" onclick="modifierContrat('${agentId}')">
              <i data-feather="edit-2"></i> Modifier
            </button>
          </div>
        </div>
      `;
      document.getElementById('modalDetails').classList.add('active');
      feather.replace();
    }

    // Fonction pour télécharger le contrat
    function telechargerContrat(agentId) {
      alert(`Téléchargement du contrat pour ${agentId}...`);
      // Ici, on déclencherait le téléchargement du PDF
    }

    // Fonction pour modifier le contrat
    function modifierContrat(agentId) {
      alert('Redirection vers la page de modification du contrat...');
    }
  </script>
</body>
</html>
