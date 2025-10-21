<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Historique des Locations • Safari</title>
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
          <h1>Historique des Locations</h1>
          <p>Toutes les locations terminées</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/locations'">
            <i data-feather="arrow-left"></i> Retour
          </button>
          <select class="form-control" style="width: 200px;">
            <option value="month" selected>Ce mois</option>
            <option value="3months">3 derniers mois</option>
            <option value="6months">6 derniers mois</option>
            <option value="year">Cette année</option>
            <option value="all">Tout l'historique</option>
          </select>
        </div>
      </header>

      <!-- Statistiques de la période -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total locations</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">147</div>
          <div style="font-size: 12px; color: #10b981; margin-top: 4px;">+18% vs période précédente</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus totaux</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">18.5M CDF</div>
          <div style="font-size: 12px; color: #10b981; margin-top: 4px;">+12% vs période précédente</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Durée moyenne</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">3.2 jours</div>
          <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Par location</div>
        </div>
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
              <input type="text" class="form-control" placeholder="N° location, client..." id="searchHistorique">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type</label>
              <select class="form-control">
                <option value="">Tous</option>
                <option value="kilometre">Par kilomètre</option>
                <option value="duree">Par durée</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Véhicule</label>
              <select class="form-control">
                <option value="">Tous</option>
                <option value="bus">Bus</option>
                <option value="minibus">Minibus</option>
                <option value="van">Van</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut final</label>
              <select class="form-control">
                <option value="">Tous</option>
                <option value="termine">Terminé</option>
                <option value="annule">Annulé</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Tableau historique -->
      <section class="card">
        <div class="card__header">
          <h3>Historique complet</h3>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table">
            <thead>
              <tr>
                <th>N° Location</th>
                <th>Client</th>
                <th>Type</th>
                <th>Véhicule</th>
                <th>Période</th>
                <th>Durée réelle</th>
                <th>Distance/Jours</th>
                <th>Montant final</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>LOC-2024-245</strong></td>
                <td>
                  <div style="font-weight: 600;">Sophie Mbuyi</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><span class="badge badge--info">Par kilomètre</span></td>
                <td>
                  <div style="font-weight: 600;">Bus #421</div>
                  <div style="font-size: 12px; color: #6b7280;">45 places</div>
                </td>
                <td>
                  <div>01 Oct - 04 Oct 2025</div>
                  <div style="font-size: 12px; color: #6b7280;">3 jours</div>
                </td>
                <td><strong>3 jours</strong></td>
                <td>
                  <div style="font-weight: 600;">580 km</div>
                  <div style="font-size: 12px; color: #6b7280;">2,500 CDF/km</div>
                </td>
                <td><strong style="color: #10b981;">1,450,000 CDF</strong></td>
                <td><span class="status-badge status-badge--termine">Terminé</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--view" title="Détails" onclick="voirDetailsHistorique('LOC-2024-245')">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--primary" title="Facture" onclick="telechargerFacture('LOC-2024-245')">
                      <i data-feather="download"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>LOC-2024-244</strong></td>
                <td>
                  <div style="font-weight: 600;">David Mwamba</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><span class="badge badge--warning">Par durée</span></td>
                <td>
                  <div style="font-weight: 600;">Minibus #215</div>
                  <div style="font-size: 12px; color: #6b7280;">20 places</div>
                </td>
                <td>
                  <div>28 Sep - 02 Oct 2025</div>
                  <div style="font-size: 12px; color: #6b7280;">5 jours</div>
                </td>
                <td><strong>5 jours</strong></td>
                <td>
                  <div style="font-weight: 600;">5 jours</div>
                  <div style="font-size: 12px; color: #6b7280;">150,000 CDF/jour</div>
                </td>
                <td><strong style="color: #10b981;">750,000 CDF</strong></td>
                <td><span class="status-badge status-badge--termine">Terminé</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--view" title="Détails" onclick="voirDetailsHistorique('LOC-2024-244')">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--primary" title="Facture" onclick="telechargerFacture('LOC-2024-244')">
                      <i data-feather="download"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>LOC-2024-243</strong></td>
                <td>
                  <div style="font-weight: 600;">Joseph Kabila</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><span class="badge badge--info">Par kilomètre</span></td>
                <td>
                  <div style="font-weight: 600;">Van #108</div>
                  <div style="font-size: 12px; color: #6b7280;">12 places</div>
                </td>
                <td>
                  <div>25 Sep - 27 Sep 2025</div>
                  <div style="font-size: 12px; color: #6b7280;">2 jours</div>
                </td>
                <td><strong>2 jours</strong></td>
                <td>
                  <div style="font-weight: 600;">320 km</div>
                  <div style="font-size: 12px; color: #6b7280;">1,800 CDF/km</div>
                </td>
                <td><strong style="color: #10b981;">576,000 CDF</strong></td>
                <td><span class="status-badge status-badge--termine">Terminé</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--view" title="Détails" onclick="voirDetailsHistorique('LOC-2024-243')">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--primary" title="Facture" onclick="telechargerFacture('LOC-2024-243')">
                      <i data-feather="download"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>LOC-2024-242</strong></td>
                <td>
                  <div style="font-weight: 600;">Grace Lumbu</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><span class="badge badge--warning">Par durée</span></td>
                <td>
                  <div style="font-weight: 600;">Bus #512</div>
                  <div style="font-size: 12px; color: #6b7280;">50 places</div>
                </td>
                <td>
                  <div>20 Sep - 27 Sep 2025</div>
                  <div style="font-size: 12px; color: #6b7280;">7 jours</div>
                </td>
                <td><strong>8 jours</strong> <span style="color: #ef4444; font-size: 11px;">(+1 jour)</span></td>
                <td>
                  <div style="font-weight: 600;">7 jours</div>
                  <div style="font-size: 12px; color: #6b7280;">200,000 CDF/jour</div>
                </td>
                <td><strong style="color: #10b981;">1,600,000 CDF</strong></td>
                <td><span class="status-badge status-badge--termine">Terminé</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--view" title="Détails" onclick="voirDetailsHistorique('LOC-2024-242')">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--primary" title="Facture" onclick="telechargerFacture('LOC-2024-242')">
                      <i data-feather="download"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr style="opacity: 0.6;">
                <td><strong>LOC-2024-241</strong></td>
                <td>
                  <div style="font-weight: 600;">Paul Nsimba</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><span class="badge badge--info">Par kilomètre</span></td>
                <td>
                  <div style="font-weight: 600;">Bus #421</div>
                  <div style="font-size: 12px; color: #6b7280;">45 places</div>
                </td>
                <td>
                  <div>18 Sep 2025</div>
                  <div style="font-size: 12px; color: #6b7280;">Annulé</div>
                </td>
                <td><strong>-</strong></td>
                <td>
                  <div style="font-weight: 600;">-</div>
                  <div style="font-size: 12px; color: #6b7280;">-</div>
                </td>
                <td><strong style="color: #6b7280;">0 CDF</strong></td>
                <td><span class="status-badge status-badge--annule">Annulé</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--view" title="Détails" onclick="voirDetailsHistorique('LOC-2024-241')">
                      <i data-feather="eye"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails -->
  <div class="modal" id="modalDetails">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2 id="modalDetailsTitle">Détails de la location</h2>
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

    // Fonction pour voir les détails
    function voirDetailsHistorique(locationId) {
      document.getElementById('detailsContent').innerHTML = `
        <div style="display: grid; gap: 20px;">
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">N° Location</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${locationId}</div>
            </div>
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Statut</div>
              <div><span class="status-badge status-badge--termine">Terminé</span></div>
            </div>
          </div>

          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations client</h4>
            <div style="display: grid; gap: 8px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Nom</span>
                <span style="font-weight: 600;">Sophie Mbuyi</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Téléphone</span>
                <span style="font-weight: 600;">+243 XXX XXX XXX</span>
              </div>
            </div>
          </div>

          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Détails de la location</h4>
            <div style="display: grid; gap: 8px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Type</span>
                <span class="badge badge--info">Par kilomètre</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Véhicule</span>
                <span style="font-weight: 600;">Bus #421</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Distance parcourue</span>
                <span style="font-weight: 600;">580 km</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Tarif</span>
                <span style="font-weight: 600;">2,500 CDF/km</span>
              </div>
            </div>
          </div>

          <div style="background: #dcfce7; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-weight: 700; font-size: 18px; color: #065f46;">Montant total</span>
              <span style="font-weight: 800; font-size: 28px; color: #10b981;">1,450,000 CDF</span>
            </div>
          </div>
        </div>
      `;
      document.getElementById('modalDetails').classList.add('active');
      feather.replace();
    }

    // Fonction pour télécharger la facture
    function telechargerFacture(locationId) {
      alert(`Téléchargement de la facture pour ${locationId}...`);
      // Ici, on déclencherait le téléchargement du PDF
    }
  </script>
</body>
</html>
