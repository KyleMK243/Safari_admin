<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Locations • Safari</title>
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
          <h1>Gestion des Locations</h1>
          <p>Location de véhicules par kilomètre ou par durée</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/historique-locations'">
            <i data-feather="clock"></i> Historique
          </button>
          <button class="btn btn--primary" id="btnNouvelleLocation">
            <i data-feather="plus"></i> Nouvelle location
          </button>
        </div>
      </header>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- Formulaire de location -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Locations actives</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">24</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Par kilomètre</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">15</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Par durée</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">9</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus (mois)</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">8.5M CDF</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">En retard</div>
          <div style="font-size: 32px; font-weight: 800; color: #ef4444;">3</div>
        </div>
      </div>

      <!-- Onglets -->
      <div class="tabs">
        <button class="tab-btn active" data-tab="actives">
          <i data-feather="play-circle"></i> Locations actives (24)
        </button>
        <button class="tab-btn" data-tab="reservations">
          <i data-feather="calendar"></i> Réservations (12)
        </button>
        <button class="tab-btn" data-tab="retard">
          <i data-feather="alert-circle"></i> En retard (3)
        </button>
      </div>

      <!-- Filtres -->
      <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Rechercher</label>
              <input type="text" class="form-control" placeholder="N° location, client..." id="searchLocation">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type</label>
              <select class="form-control" id="filterType">
                <option value="">Tous</option>
                <option value="kilometre">Par kilomètre</option>
                <option value="duree">Par durée</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Véhicule</label>
              <select class="form-control" id="filterVehicule">
                <option value="">Tous</option>
                <option value="bus">Bus</option>
                <option value="minibus">Minibus</option>
                <option value="van">Van</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control" id="filterStatut">
                <option value="">Tous</option>
                <option value="en_cours">En cours</option>
                <option value="reservation">Réservation</option>
                <option value="retard">En retard</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu Locations actives -->
      <div class="tab-content active" id="tab-actives">
        <section class="card">
          <div class="card__header">
            <h3>Locations en cours</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table">
              <thead>
                <tr>
                  <th>N° Location</th>
                  <th>Client</th>
                  <th>Type</th>
                  <th>Véhicule</th>
                  <th>Début</th>
                  <th>Fin prévue</th>
                  <th>Tarif</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>LOC-2025-001</strong></td>
                  <td>
                    <div style="font-weight: 600;">Jean Mukendi</div>
                    <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                  </td>
                  <td><span class="badge badge--info">Par kilomètre</span></td>
                  <td>
                    <div style="font-weight: 600;">Bus #421</div>
                    <div style="font-size: 12px; color: #6b7280;">45 places</div>
                  </td>
                  <td>05 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">08:00</span></td>
                  <td>08 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">18:00</span></td>
                  <td>
                    <div style="font-weight: 600;">2,500 CDF/km</div>
                    <div style="font-size: 12px; color: #6b7280;">350 km parcourus</div>
                  </td>
                  <td><strong style="color: #10b981;">875,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--en-cours">En cours</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirLocation('LOC-2025-001')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--success" title="Clôturer" onclick="cloturerLocation('LOC-2025-001')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>LOC-2025-002</strong></td>
                  <td>
                    <div style="font-weight: 600;">Marie Tshala</div>
                    <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                  </td>
                  <td><span class="badge badge--warning">Par durée</span></td>
                  <td>
                    <div style="font-weight: 600;">Minibus #215</div>
                    <div style="font-size: 12px; color: #6b7280;">20 places</div>
                  </td>
                  <td>06 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">09:00</span></td>
                  <td>09 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">09:00</span></td>
                  <td>
                    <div style="font-weight: 600;">150,000 CDF/jour</div>
                    <div style="font-size: 12px; color: #6b7280;">3 jours</div>
                  </td>
                  <td><strong style="color: #10b981;">450,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--en-cours">En cours</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirLocation('LOC-2025-002')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--success" title="Clôturer" onclick="cloturerLocation('LOC-2025-002')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>LOC-2025-003</strong></td>
                  <td>
                    <div style="font-weight: 600;">Paul Nsimba</div>
                    <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                  </td>
                  <td><span class="badge badge--info">Par kilomètre</span></td>
                  <td>
                    <div style="font-weight: 600;">Van #108</div>
                    <div style="font-size: 12px; color: #6b7280;">12 places</div>
                  </td>
                  <td>07 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">10:30</span></td>
                  <td>08 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">16:00</span></td>
                  <td>
                    <div style="font-weight: 600;">1,800 CDF/km</div>
                    <div style="font-size: 12px; color: #6b7280;">120 km parcourus</div>
                  </td>
                  <td><strong style="color: #10b981;">216,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--en-cours">En cours</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirLocation('LOC-2025-003')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--success" title="Clôturer" onclick="cloturerLocation('LOC-2025-003')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr style="background: #fef3c7;">
                  <td><strong>LOC-2025-004</strong></td>
                  <td>
                    <div style="font-weight: 600;">Grace Lumbu</div>
                    <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                  </td>
                  <td><span class="badge badge--warning">Par durée</span></td>
                  <td>
                    <div style="font-weight: 600;">Bus #512</div>
                    <div style="font-size: 12px; color: #6b7280;">50 places</div>
                  </td>
                  <td>03 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">07:00</span></td>
                  <td>07 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">19:00</span></td>
                  <td>
                    <div style="font-weight: 600;">200,000 CDF/jour</div>
                    <div style="font-size: 12px; color: #6b7280;">5 jours</div>
                  </td>
                  <td><strong style="color: #10b981;">1,000,000 CDF</strong></td>
                  <td><span class="status-badge status-badge--en-retard">En retard</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirLocation('LOC-2025-004')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--success" title="Clôturer" onclick="cloturerLocation('LOC-2025-004')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <!-- Contenu Réservations -->
      <div class="tab-content" id="tab-reservations">
        <section class="card">
          <div class="card__header">
            <h3>Réservations à venir</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Réservations futures - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <!-- Contenu En retard -->
      <div class="tab-content" id="tab-retard">
        <section class="card">
          <div class="card__header">
            <h3>Locations en retard</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Locations dépassant la date de fin prévue
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Nouvelle Location -->
  <div class="modal" id="modalNouvelleLocation">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 700px;">
      <div class="modal__header">
        <h2>Nouvelle location</h2>
        <button class="modal__close" id="closeModalLocation">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formNouvelleLocation">
          <!-- Type de location -->
          <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 12px; color: #374151;">Type de location *</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <label style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="type-location-option">
                <input type="radio" name="typeLocation" value="kilometre" required style="margin-right: 8px;">
                <strong>Par kilomètre</strong>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Tarif selon la distance parcourue</div>
              </label>
              <label style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="type-location-option">
                <input type="radio" name="typeLocation" value="duree" required style="margin-right: 8px;">
                <strong>Par durée</strong>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Tarif selon la durée (jour/semaine)</div>
              </label>
            </div>
          </div>

          <!-- Client -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nom du client *</label>
              <input type="text" class="form-control" placeholder="Ex: Jean Mukendi" required>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Téléphone *</label>
              <input type="tel" class="form-control" placeholder="+243 XXX XXX XXX" required>
            </div>
          </div>

          <!-- Véhicule -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de véhicule *</label>
              <select class="form-control" required>
                <option value="">Sélectionner...</option>
                <option value="bus">Bus (40-50 places)</option>
                <option value="minibus">Minibus (15-25 places)</option>
                <option value="van">Van (8-12 places)</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Véhicule *</label>
              <select class="form-control" required>
                <option value="">Sélectionner...</option>
                <option value="421">Bus #421 - Disponible</option>
                <option value="512">Bus #512 - Disponible</option>
                <option value="215">Minibus #215 - Disponible</option>
              </select>
            </div>
          </div>

          <!-- Dates -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date de début *</label>
              <input type="datetime-local" class="form-control" required>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date de fin prévue *</label>
              <input type="datetime-local" class="form-control" required>
            </div>
          </div>

          <!-- Tarif -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Tarif unitaire *</label>
              <input type="number" class="form-control" placeholder="Ex: 2500" min="0" required>
              <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">CDF/km ou CDF/jour selon le type</div>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Caution *</label>
              <input type="number" class="form-control" placeholder="Ex: 500000" min="0" required>
              <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Montant de la caution</div>
            </div>
          </div>

          <!-- Observations -->
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Observations</label>
            <textarea class="form-control" rows="3" placeholder="Notes supplémentaires..."></textarea>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelLocation">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="check"></i> Créer la location
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Détails Location -->
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

      // Modal Nouvelle Location
      const btnNouvelleLocation = document.getElementById('btnNouvelleLocation');
      const modalNouvelleLocation = document.getElementById('modalNouvelleLocation');
      const closeModalLocation = document.getElementById('closeModalLocation');
      const cancelLocation = document.getElementById('cancelLocation');

      btnNouvelleLocation?.addEventListener('click', () => {
        modalNouvelleLocation.classList.add('active');
        feather.replace();
      });

      closeModalLocation?.addEventListener('click', () => {
        modalNouvelleLocation.classList.remove('active');
      });

      cancelLocation?.addEventListener('click', () => {
        modalNouvelleLocation.classList.remove('active');
      });

      // Modal Détails
      const closeModalDetails = document.getElementById('closeModalDetails');
      closeModalDetails?.addEventListener('click', () => {
        document.getElementById('modalDetails').classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });

      // Style des options de type de location
      document.querySelectorAll('.type-location-option').forEach(option => {
        option.addEventListener('click', function() {
          document.querySelectorAll('.type-location-option').forEach(opt => {
            opt.style.borderColor = '#e5e7eb';
            opt.style.background = 'white';
          });
          this.style.borderColor = '#1B4B7F';
          this.style.background = '#dbeafe';
        });
      });

      // Formulaire
      document.getElementById('formNouvelleLocation')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Location créée avec succès !');
        modalNouvelleLocation.classList.remove('active');
      });
    });

    // Fonction pour voir les détails
    function voirLocation(locationId) {
      document.getElementById('detailsContent').innerHTML = `
        <div style="display: grid; gap: 20px;">
          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations générales</h4>
            <div style="display: grid; gap: 8px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">N° Location</span>
                <span style="font-weight: 600;">${locationId}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Client</span>
                <span style="font-weight: 600;">Jean Mukendi</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Type</span>
                <span class="badge badge--info">Par kilomètre</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Véhicule</span>
                <span style="font-weight: 600;">Bus #421</span>
              </div>
            </div>
          </div>
          <div style="background: #dbeafe; padding: 16px; border-radius: 8px;">
            <strong>Montant total : 875,000 CDF</strong>
          </div>
        </div>
      `;
      document.getElementById('modalDetails').classList.add('active');
      feather.replace();
    }

    // Fonction pour clôturer
    function cloturerLocation(locationId) {
      if(confirm('Voulez-vous clôturer cette location ?')) {
        alert('Location clôturée avec succès !');
        location.reload();
      }
    }
  </script>
</body>
</html>
