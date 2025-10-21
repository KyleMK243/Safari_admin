<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Guichets • Safari</title>
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
          <h1>Gestion des Guichets</h1>
          <p>Liste et configuration des points de vente physiques</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/canaux-vente'">
            <i data-feather="arrow-left"></i> Retour
          </button>
          <button class="btn btn--primary" id="btnNouveauGuichet">
            <i data-feather="plus"></i> Nouveau guichet
          </button>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total guichets</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">8</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Guichets actifs</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">7</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Ventes aujourd'hui</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">124</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Revenus du jour</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">620,000 CDF</div>
        </div>
      </div>

      <!-- Liste des guichets -->
      <section class="card">
        <div class="card__header">
          <h3>Liste des guichets (8)</h3>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Nom du guichet</th>
                <th>Localisation</th>
                <th>Responsable</th>
                <th>Téléphone</th>
                <th>Ventes (mois)</th>
                <th>Revenus (mois)</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Gare Centrale Kinshasa</strong></td>
                <td>Kinshasa, Gombe</td>
                <td>Jean Mukendi</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>485</strong></td>
                <td><strong style="color: #10b981;">2,425,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Gare Matadi</strong></td>
                <td>Matadi, Centre-ville</td>
                <td>Marie Tshala</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>320</strong></td>
                <td><strong style="color: #10b981;">1,600,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Agence Lemba</strong></td>
                <td>Kinshasa, Lemba</td>
                <td>Paul Nsimba</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>275</strong></td>
                <td><strong style="color: #10b981;">1,375,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Terminal Kikwit</strong></td>
                <td>Kikwit, Terminal</td>
                <td>Grace Lumbu</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>245</strong></td>
                <td><strong style="color: #10b981;">1,225,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Gare Lubumbashi</strong></td>
                <td>Lubumbashi, Centre</td>
                <td>Joseph Kabila</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>380</strong></td>
                <td><strong style="color: #10b981;">1,900,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Agence Kananga</strong></td>
                <td>Kananga, Gare</td>
                <td>Sophie Mbuyi</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>180</strong></td>
                <td><strong style="color: #10b981;">900,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>Kiosque Université</strong></td>
                <td>Kinshasa, Université</td>
                <td>David Mwamba</td>
                <td>+243 XXX XXX XXX</td>
                <td><strong>85</strong></td>
                <td><strong style="color: #10b981;">425,000 CDF</strong></td>
                <td><span class="status-badge status-badge--actif">Actif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Désactiver">
                      <i data-feather="power"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr style="opacity: 0.6;">
                <td><strong>Agence Kintambo</strong></td>
                <td>Kinshasa, Kintambo</td>
                <td>-</td>
                <td>-</td>
                <td><strong>0</strong></td>
                <td><strong style="color: #6b7280;">0 CDF</strong></td>
                <td><span class="status-badge status-badge--inactif">Inactif</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-icon btn-icon--edit" title="Modifier">
                      <i data-feather="edit-2"></i>
                    </button>
                    <button class="btn-icon btn-icon--view" title="Détails">
                      <i data-feather="eye"></i>
                    </button>
                    <button class="btn-icon btn-icon--delete" title="Activer">
                      <i data-feather="power"></i>
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

  <!-- Modal Nouveau Guichet -->
  <div class="modal" id="modalNouveauGuichet">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Nouveau guichet</h2>
        <button class="modal__close" id="closeModalGuichet">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formNouveauGuichet">
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nom du guichet *</label>
            <input type="text" class="form-control" placeholder="Ex: Gare Centrale Kinshasa" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Ville *</label>
              <select class="form-control" required>
                <option value="">Sélectionner...</option>
                <option>Kinshasa</option>
                <option>Matadi</option>
                <option>Lubumbashi</option>
                <option>Kikwit</option>
                <option>Kananga</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Quartier *</label>
              <input type="text" class="form-control" placeholder="Ex: Gombe" required>
            </div>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Adresse complète *</label>
            <textarea class="form-control" rows="2" placeholder="Ex: Avenue de la Gare, N°123" required></textarea>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Responsable *</label>
              <input type="text" class="form-control" placeholder="Ex: Jean Mukendi" required>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Téléphone *</label>
              <input type="tel" class="form-control" placeholder="+243 XXX XXX XXX" required>
            </div>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
            <input type="email" class="form-control" placeholder="guichet@safari.cd">
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelGuichet">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="check"></i> Créer le guichet
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      const btnNouveauGuichet = document.getElementById('btnNouveauGuichet');
      const modalNouveauGuichet = document.getElementById('modalNouveauGuichet');
      const closeModalGuichet = document.getElementById('closeModalGuichet');
      const cancelGuichet = document.getElementById('cancelGuichet');

      btnNouveauGuichet?.addEventListener('click', () => {
        modalNouveauGuichet.classList.add('active');
        feather.replace();
      });

      closeModalGuichet?.addEventListener('click', () => {
        modalNouveauGuichet.classList.remove('active');
      });

      cancelGuichet?.addEventListener('click', () => {
        modalNouveauGuichet.classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelector('.modal__overlay')?.addEventListener('click', () => {
        modalNouveauGuichet.classList.remove('active');
      });

      // Formulaire
      document.getElementById('formNouveauGuichet')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Guichet créé avec succès !');
        modalNouveauGuichet.classList.remove('active');
      });
    });
  </script>
</body>
</html>
