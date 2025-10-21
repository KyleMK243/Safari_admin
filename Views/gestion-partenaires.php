<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Partenaires • Safari</title>
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
          <h1>Gestion des Partenaires</h1>
          <p>Liste et configuration des revendeurs agréés</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/canaux-vente'">
            <i data-feather="arrow-left"></i> Retour
          </button>
          <button class="btn btn--primary" id="btnNouveauPartenaire">
            <i data-feather="plus"></i> Nouveau partenaire
          </button>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total partenaires</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">12</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Partenaires actifs</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">10</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Ventes ce mois</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">470</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Commission à verser</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">235,000 CDF</div>
        </div>
      </div>

      <!-- Liste des partenaires -->
      <section class="card">
        <div class="card__header">
          <h3>Liste des partenaires (12)</h3>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Nom du partenaire</th>
                <th>Type</th>
                <th>Localisation</th>
                <th>Contact</th>
                <th>Commission</th>
                <th>Ventes (mois)</th>
                <th>Revenus (mois)</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>ABC Voyages</strong></td>
                <td><span class="badge badge--info">Agence</span></td>
                <td>Kinshasa, Gombe</td>
                <td>
                  <div>Jean Mukendi</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>10%</strong></td>
                <td><strong>125</strong></td>
                <td><strong style="color: #10b981;">625,000 CDF</strong></td>
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
                <td><strong>Express Travel</strong></td>
                <td><span class="badge badge--info">Agence</span></td>
                <td>Matadi, Centre</td>
                <td>
                  <div>Marie Tshala</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>12%</strong></td>
                <td><strong>95</strong></td>
                <td><strong style="color: #10b981;">475,000 CDF</strong></td>
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
                <td><strong>Kiosque Lemba</strong></td>
                <td><span class="badge badge--warning">Kiosque</span></td>
                <td>Kinshasa, Lemba</td>
                <td>
                  <div>Paul Nsimba</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>8%</strong></td>
                <td><strong>65</strong></td>
                <td><strong style="color: #10b981;">325,000 CDF</strong></td>
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
                <td><strong>Hôtel Grand Karavia</strong></td>
                <td><span class="badge badge--success">Hôtel</span></td>
                <td>Lubumbashi, Centre</td>
                <td>
                  <div>Grace Lumbu</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>15%</strong></td>
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
              <tr>
                <td><strong>Boutique Voyages Plus</strong></td>
                <td><span class="badge badge--info">Agence</span></td>
                <td>Kikwit, Terminal</td>
                <td>
                  <div>Joseph Kabila</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>10%</strong></td>
                <td><strong>45</strong></td>
                <td><strong style="color: #10b981;">225,000 CDF</strong></td>
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
                <td><strong>Supermarché City Market</strong></td>
                <td><span class="badge badge--success">Supermarché</span></td>
                <td>Kinshasa, Gombe</td>
                <td>
                  <div>Sophie Mbuyi</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>5%</strong></td>
                <td><strong>30</strong></td>
                <td><strong style="color: #10b981;">150,000 CDF</strong></td>
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
                <td><span class="badge badge--warning">Kiosque</span></td>
                <td>Kinshasa, Université</td>
                <td>
                  <div>David Mwamba</div>
                  <div style="font-size: 12px; color: #6b7280;">+243 XXX XXX XXX</div>
                </td>
                <td><strong>8%</strong></td>
                <td><strong>25</strong></td>
                <td><strong style="color: #10b981;">125,000 CDF</strong></td>
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
            </tbody>
          </table>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Nouveau Partenaire -->
  <div class="modal" id="modalNouveauPartenaire">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Nouveau partenaire</h2>
        <button class="modal__close" id="closeModalPartenaire">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formNouveauPartenaire">
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nom du partenaire *</label>
            <input type="text" class="form-control" placeholder="Ex: ABC Voyages" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type *</label>
              <select class="form-control" required>
                <option value="">Sélectionner...</option>
                <option>Agence de voyage</option>
                <option>Kiosque</option>
                <option>Hôtel</option>
                <option>Supermarché</option>
                <option>Autre</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Commission (%) *</label>
              <input type="number" class="form-control" placeholder="Ex: 10" min="0" max="50" step="0.5" required>
            </div>
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
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Adresse complète</label>
            <textarea class="form-control" rows="2" placeholder="Ex: Avenue du Commerce, N°45"></textarea>
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
            <input type="email" class="form-control" placeholder="partenaire@email.cd">
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelPartenaire">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="check"></i> Créer le partenaire
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

      const btnNouveauPartenaire = document.getElementById('btnNouveauPartenaire');
      const modalNouveauPartenaire = document.getElementById('modalNouveauPartenaire');
      const closeModalPartenaire = document.getElementById('closeModalPartenaire');
      const cancelPartenaire = document.getElementById('cancelPartenaire');

      btnNouveauPartenaire?.addEventListener('click', () => {
        modalNouveauPartenaire.classList.add('active');
        feather.replace();
      });

      closeModalPartenaire?.addEventListener('click', () => {
        modalNouveauPartenaire.classList.remove('active');
      });

      cancelPartenaire?.addEventListener('click', () => {
        modalNouveauPartenaire.classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelector('.modal__overlay')?.addEventListener('click', () => {
        modalNouveauPartenaire.classList.remove('active');
      });

      // Formulaire
      document.getElementById('formNouveauPartenaire')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Partenaire créé avec succès !');
        modalNouveauPartenaire.classList.remove('active');
      });
    });
  </script>
</body>
</html>
