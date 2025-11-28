<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Dashboard • Bureau de planification</title>
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
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Dashboard • Bureau de planification</h1>
          <p>Suivre les événements en ville et leurs impacts sur le réseau</p>
        </div>
      </header>

      <!-- Statistiques rapides du jour -->
      <section class="stats-grid">
        <div class="card stat-card">
          <div class="stat-card__content">
            <div class="stat-card__icon stat-card__icon--primary">
              <i data-feather="user-plus"></i>
            </div>
            <div class="stat-card__info">
              <div class="stat-card__value">-</div>
              <div class="stat-card__label">Roulements à confirmer</div>
            </div>
          </div>
        </div>
        <div class="card stat-card">
          <div class="stat-card__content">
            <div class="stat-card__icon stat-card__icon--success">
              <i data-feather="check"></i>
            </div>
            <div class="stat-card__info">
              <div class="stat-card__value">-</div>
              <div class="stat-card__label">Roulements confirmés</div>
            </div>
          </div>
        </div>
        <div class="card stat-card">
          <div class="stat-card__content">
            <div class="stat-card__icon stat-card__icon--warning">
              <i data-feather="shuffle"></i>
            </div>
            <div class="stat-card__info">
              <div class="stat-card__value">-</div>
              <div class="stat-card__label">Réaffectations du jour</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Bloc : Événements en ville & trajets impactés -->
      <section class="card" style="margin-top:24px;">
        <div class="card__header card__header--reverse">
          <div>
            <h2>Événements en ville & trajets impactés</h2>
            <p style="font-size:13px;color:#6b7280;">Liste des événements (manifestations, travaux, accidents, météo) et des trajets/bus à réaffecter.</p>
          </div>
          <div class="header__actions">
            <button class="btn btn--primary" id="btnAjouterEvenement" style="display:inline-flex;align-items:center;gap:6px;">
              <i data-feather="plus"></i>
              <span>Ajouter un événement</span>
            </button>
          </div>
        </div>
        <div class="card__body">
          <div style="overflow-x:auto;">
            <table class="table" style="white-space:nowrap;">
              <thead>
                <tr>
                  <th>Heure</th>
                  <th>Type d'événement</th>
                  <th>Localisation</th>
                  <th>Trajets impactés</th>
                  <th>Bus concernés</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- Maquette : quelques événements exemples -->
                <tr>
                  <td>06:30</td>
                  <td><span class="badge badge--danger">Accident</span></td>
                  <td>Boulevard du 30 Juin • Rond-point Victoire</td>
                  <td>
                    <span class="badge badge--primary">L101</span>
                    <span class="badge badge--primary">L102</span>
                  </td>
                  <td>
                    Bus #421, Bus #315
                  </td>
                  <td>
                    <button class="btn btn--sm btn--secondary" style="display:inline-flex;align-items:center;gap:6px;">
                      <i data-feather="shuffle"></i>
                      <span>Réaffecter les bus</span>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>08:15</td>
                  <td><span class="badge badge--warning">Travaux</span></td>
                  <td>Avenue de l'Université • UP Campus</td>
                  <td>
                    <span class="badge badge--primary">L102</span>
                  </td>
                  <td>
                    Bus #238
                  </td>
                  <td>
                    <button class="btn btn--sm btn--secondary" style="display:inline-flex;align-items:center;gap:6px;">
                      <i data-feather="shuffle"></i>
                      <span>Réaffecter les bus</span>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>10:00</td>
                  <td><span class="badge badge--info">Manifestation</span></td>
                  <td>Place des Évolués</td>
                  <td>
                    <span class="badge badge--primary">L103</span>
                  </td>
                  <td>
                    Bus #512, Bus #642
                  </td>
                  <td>
                    <button class="btn btn--sm btn--secondary" style="display:inline-flex;align-items:center;gap:6px;">
                      <i data-feather="shuffle"></i>
                      <span>Réaffecter les bus</span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      
      <!-- Modal : Ajouter un événement en ville -->
      <div class="modal" id="modalEvenement">
        <div class="modal__overlay"></div>
        <div class="modal__content" style="max-width: 640px;">
          <div class="modal__header">
            <h2>Nouvel événement en ville</h2>
            <button class="modal__close" id="btnCloseModalEvenement">
              <i data-feather="x"></i>
            </button>
          </div>
          <div class="modal__body">
            <div class="form-group">
              <label for="evenementNom">Nom de l'événement</label>
              <input type="text" id="evenementNom" class="form-control" placeholder="Ex: Manifestation, travaux, accident...">
            </div>

            <div class="form-group">
              <label for="evenementType">Type d'événement</label>
              <select id="evenementType" class="form-control">
                <option value="manifestation">Manifestation</option>
                <option value="travaux">Travaux</option>
                <option value="accident">Accident</option>
                <option value="meteo">Météo</option>
                <option value="autre">Autre</option>
              </select>
            </div>

            <div class="form-group">
              <label for="evenementDescription">Description</label>
              <textarea id="evenementDescription" class="form-control" rows="3" placeholder="Détaillez l'impact sur la circulation..."></textarea>
            </div>

            <div class="form-group">
              <label>Localisation (coordonnées)</label>
              <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:flex-end;">
                <div style="flex:1;min-width:140px;">
                  <input type="text" id="evenementLat" class="form-control" placeholder="Latitude" readonly>
                </div>
                <div style="flex:1;min-width:140px;">
                  <input type="text" id="evenementLon" class="form-control" placeholder="Longitude" readonly>
                </div>
                <button type="button" class="btn btn--secondary" id="btnOuvrirCarteEvenement" style="display:inline-flex;align-items:center;gap:6px;">
                  <i data-feather="map-pin"></i>
                  <span>Choisir sur la carte</span>
                </button>
              </div>
            </div>
          </div>
          <div class="modal__footer">
            <button type="button" class="btn btn--secondary" id="btnAnnulerEvenement">Annuler</button>
            <button type="button" class="btn btn--primary" id="btnEnregistrerEvenement">
              <i data-feather="save"></i>
              <span>Enregistrer (maquette)</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Modal : Sélection de la position sur la carte (maquette) -->
      <div class="modal" id="modalCarteEvenement">
        <div class="modal__overlay"></div>
        <div class="modal__content" style="max-width: 720px;">
          <div class="modal__header">
            <h2>Sélectionner une position sur la carte</h2>
            <button class="modal__close" id="btnCloseModalCarteEvenement">
              <i data-feather="x"></i>
            </button>
          </div>
          <div class="modal__body" style="padding: 20px;">
            <p style="margin-bottom: 12px; color: #6b7280; font-size: 14px;">
              <i data-feather="info" style="width: 16px; height: 16px; display: inline; vertical-align: middle;"></i>
              Maquette : la carte sera branchée plus tard comme dans les trajets. Pour l'instant, une position fictive est utilisée.
            </p>
            <div id="mapEvenement" class="map-container" style="width:100%;height:320px;background:#e5e7eb;border-radius:8px;border:1px dashed #d1d5db;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:14px;">
              Carte Google Maps (maquette)
            </div>
          </div>
          <div class="modal__footer" style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:13px;color:#6b7280;">
              Position simulée : <strong>Lat</strong> <span id="latSimulee">-4.3276</span> / <strong>Lon</strong> <span id="lonSimulee">15.3136</span>
            </div>
            <div>
              <button type="button" class="btn btn--secondary" id="btnAnnulerCarteEvenement">Annuler</button>
              <button type="button" class="btn btn--primary" id="btnConfirmerCarteEvenement">
                <i data-feather="check"></i>
                <span>Utiliser cette position</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <?php require_once 'includes/footer.php'; ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof feather !== 'undefined') {
        feather.replace();
      }

      const modalEvenement = document.getElementById('modalEvenement');
      const modalCarte = document.getElementById('modalCarteEvenement');

      const btnAjouterEvenement = document.getElementById('btnAjouterEvenement');
      const btnCloseModalEvenement = document.getElementById('btnCloseModalEvenement');
      const btnAnnulerEvenement = document.getElementById('btnAnnulerEvenement');
      const btnEnregistrerEvenement = document.getElementById('btnEnregistrerEvenement');

      const btnOuvrirCarteEvenement = document.getElementById('btnOuvrirCarteEvenement');
      const btnCloseModalCarteEvenement = document.getElementById('btnCloseModalCarteEvenement');
      const btnAnnulerCarteEvenement = document.getElementById('btnAnnulerCarteEvenement');
      const btnConfirmerCarteEvenement = document.getElementById('btnConfirmerCarteEvenement');

      const latInput = document.getElementById('evenementLat');
      const lonInput = document.getElementById('evenementLon');
      const latSimulee = document.getElementById('latSimulee');
      const lonSimulee = document.getElementById('lonSimulee');

      function openModal(modal) {
        if (modal) {
          modal.classList.add('active');
          if (typeof feather !== 'undefined') {
            feather.replace();
          }
        }
      }

      function closeModal(modal) {
        if (modal) {
          modal.classList.remove('active');
        }
      }

      if (btnAjouterEvenement) {
        btnAjouterEvenement.addEventListener('click', function () {
          openModal(modalEvenement);
        });
      }

      if (btnCloseModalEvenement) {
        btnCloseModalEvenement.addEventListener('click', function () {
          closeModal(modalEvenement);
        });
      }

      if (btnAnnulerEvenement) {
        btnAnnulerEvenement.addEventListener('click', function () {
          closeModal(modalEvenement);
        });
      }

      if (modalEvenement) {
        const overlay = modalEvenement.querySelector('.modal__overlay');
        if (overlay) {
          overlay.addEventListener('click', function () {
            closeModal(modalEvenement);
          });
        }
      }

      if (btnEnregistrerEvenement) {
        btnEnregistrerEvenement.addEventListener('click', function () {
          alert('Maquette : les événements seront enregistrés côté serveur plus tard.');
          closeModal(modalEvenement);
        });
      }

      if (btnOuvrirCarteEvenement) {
        btnOuvrirCarteEvenement.addEventListener('click', function () {
          openModal(modalCarte);
        });
      }

      if (btnCloseModalCarteEvenement) {
        btnCloseModalCarteEvenement.addEventListener('click', function () {
          closeModal(modalCarte);
        });
      }

      if (btnAnnulerCarteEvenement) {
        btnAnnulerCarteEvenement.addEventListener('click', function () {
          closeModal(modalCarte);
        });
      }

      if (modalCarte) {
        const overlayCarte = modalCarte.querySelector('.modal__overlay');
        if (overlayCarte) {
          overlayCarte.addEventListener('click', function () {
            closeModal(modalCarte);
          });
        }
      }

      if (btnConfirmerCarteEvenement) {
        btnConfirmerCarteEvenement.addEventListener('click', function () {
          if (latInput && lonInput && latSimulee && lonSimulee) {
            latInput.value = latSimulee.textContent;
            lonInput.value = lonSimulee.textContent;
          }
          closeModal(modalCarte);
        });
      }
    });
  </script>
</body>
</html>
