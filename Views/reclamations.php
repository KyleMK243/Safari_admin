<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Réclamations • Safari</title>
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
          <h1>Gestion des Réclamations</h1>
          <p>Suivi et traitement des réclamations</p>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total réclamations</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">156</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">En attente</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">42</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">En cours</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;">38</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Résolues</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">68</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Rejetées</div>
          <div style="font-size: 32px; font-weight: 800; color: #ef4444;">8</div>
        </div>
      </div>

      <!-- Onglets par type -->
      <div class="tabs">
        <button class="tab-btn active" data-tab="toutes">
          <i data-feather="list"></i> Toutes (156)
        </button>
        <button class="tab-btn" data-tab="clients">
          <i data-feather="users"></i> Clients (85)
        </button>
        <button class="tab-btn" data-tab="chauffeurs">
          <i data-feather="truck"></i> Chauffeurs (32)
        </button>
        <button class="tab-btn" data-tab="controleurs">
          <i data-feather="clipboard"></i> Contrôleurs (24)
        </button>
        <button class="tab-btn" data-tab="receveurs">
          <i data-feather="dollar-sign"></i> Receveurs (15)
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
              <input type="text" class="form-control" placeholder="N° réclamation, nom..." id="searchReclamation">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control" id="filterStatut">
                <option value="">Tous</option>
                <option value="en_attente">En attente</option>
                <option value="en_cours">En cours</option>
                <option value="resolue">Résolue</option>
                <option value="rejetee">Rejetée</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Priorité</label>
              <select class="form-control" id="filterPriorite">
                <option value="">Toutes</option>
                <option value="haute">Haute</option>
                <option value="moyenne">Moyenne</option>
                <option value="basse">Basse</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Catégorie</label>
              <select class="form-control" id="filterCategorie">
                <option value="">Toutes</option>
                <option value="service">Service</option>
                <option value="retard">Retard</option>
                <option value="paiement">Paiement</option>
                <option value="personnel">Personnel</option>
                <option value="vehicule">Véhicule</option>
                <option value="autre">Autre</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu Toutes -->
      <div class="tab-content active" id="tab-toutes">
        <section class="card">
          <div class="card__header">
            <h3>Toutes les réclamations</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table">
              <thead>
                <tr>
                  <th>N° Réclamation</th>
                  <th>Auteur</th>
                  <th>Type</th>
                  <th>Catégorie</th>
                  <th>Objet</th>
                  <th>Priorité</th>
                  <th>Date</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>REC-2025-001</strong></td>
                  <td>
                    <div style="font-weight: 600;">Jean Mukendi</div>
                    <div style="font-size: 12px; color: #6b7280;">Client</div>
                  </td>
                  <td><span class="badge badge--info">Client</span></td>
                  <td><span class="badge badge--primary">Retard</span></td>
                  <td>Bus en retard de 2h</td>
                  <td><span class="badge badge--danger">Haute</span></td>
                  <td>08 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">09:30</span></td>
                  <td><span class="status-badge status-badge--en-attente">En attente</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-001')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-001')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>REC-2025-002</strong></td>
                  <td>
                    <div style="font-weight: 600;">Pierre Kalala</div>
                    <div style="font-size: 12px; color: #6b7280;">Chauffeur</div>
                  </td>
                  <td><span class="badge badge--warning">Chauffeur</span></td>
                  <td><span class="badge badge--success">Véhicule</span></td>
                  <td>Problème de climatisation Bus #421</td>
                  <td><span class="badge badge--warning">Moyenne</span></td>
                  <td>07 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">14:15</span></td>
                  <td><span class="status-badge status-badge--en-cours">En cours</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-002')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-002')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>REC-2025-003</strong></td>
                  <td>
                    <div style="font-weight: 600;">Marie Tshala</div>
                    <div style="font-size: 12px; color: #6b7280;">Client</div>
                  </td>
                  <td><span class="badge badge--info">Client</span></td>
                  <td><span class="badge badge--primary">Service</span></td>
                  <td>Mauvais accueil au guichet</td>
                  <td><span class="badge badge--secondary">Basse</span></td>
                  <td>07 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">11:20</span></td>
                  <td><span class="status-badge status-badge--resolue">Résolue</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-003')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-003')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>REC-2025-004</strong></td>
                  <td>
                    <div style="font-weight: 600;">Joseph Mbala</div>
                    <div style="font-size: 12px; color: #6b7280;">Contrôleur</div>
                  </td>
                  <td><span class="badge badge--success">Contrôleur</span></td>
                  <td><span class="badge badge--danger">Personnel</span></td>
                  <td>Conflit avec un passager</td>
                  <td><span class="badge badge--danger">Haute</span></td>
                  <td>06 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">16:45</span></td>
                  <td><span class="status-badge status-badge--en-cours">En cours</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-004')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-004')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>REC-2025-005</strong></td>
                  <td>
                    <div style="font-weight: 600;">Grace Lumbu</div>
                    <div style="font-size: 12px; color: #6b7280;">Receveur</div>
                  </td>
                  <td><span class="badge badge--primary">Receveur</span></td>
                  <td><span class="badge badge--warning">Paiement</span></td>
                  <td>Écart de caisse non justifié</td>
                  <td><span class="badge badge--warning">Moyenne</span></td>
                  <td>06 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">10:00</span></td>
                  <td><span class="status-badge status-badge--en-attente">En attente</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-005')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-005')">
                        <i data-feather="check-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong>REC-2025-006</strong></td>
                  <td>
                    <div style="font-weight: 600;">Paul Nsimba</div>
                    <div style="font-size: 12px; color: #6b7280;">Client</div>
                  </td>
                  <td><span class="badge badge--info">Client</span></td>
                  <td><span class="badge badge--warning">Paiement</span></td>
                  <td>Remboursement non reçu</td>
                  <td><span class="badge badge--danger">Haute</span></td>
                  <td>05 Oct 2025<br><span style="font-size: 12px; color: #6b7280;">15:30</span></td>
                  <td><span class="status-badge status-badge--rejetee">Rejetée</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="btn-icon btn-icon--view" title="Détails" onclick="voirReclamation('REC-2025-006')">
                        <i data-feather="eye"></i>
                      </button>
                      <button class="btn-icon btn-icon--edit" title="Traiter" onclick="traiterReclamation('REC-2025-006')">
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

      <!-- Contenu Clients -->
      <div class="tab-content" id="tab-clients">
        <section class="card">
          <div class="card__header">
            <h3>Réclamations des clients</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par type "Clients" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <!-- Contenu Chauffeurs -->
      <div class="tab-content" id="tab-chauffeurs">
        <section class="card">
          <div class="card__header">
            <h3>Réclamations des chauffeurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par type "Chauffeurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <!-- Contenu Contrôleurs -->
      <div class="tab-content" id="tab-controleurs">
        <section class="card">
          <div class="card__header">
            <h3>Réclamations des contrôleurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par type "Contrôleurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <!-- Contenu Receveurs -->
      <div class="tab-content" id="tab-receveurs">
        <section class="card">
          <div class="card__header">
            <h3>Réclamations des receveurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par type "Receveurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails Réclamation -->
  <div class="modal" id="modalDetails">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2 id="modalDetailsTitle">Détails de la réclamation</h2>
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

  <!-- Modal Traiter Réclamation -->
  <div class="modal" id="modalTraiter">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Traiter la réclamation</h2>
        <button class="modal__close" id="closeModalTraiter">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formTraiterReclamation">
          <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Réclamation</div>
            <div id="reclamationInfo" style="font-weight: 600; font-size: 16px; color: #1B4B7F;">REC-2025-001</div>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nouveau statut *</label>
            <select class="form-control" id="nouveauStatut" required>
              <option value="">Sélectionner...</option>
              <option value="en_cours">En cours de traitement</option>
              <option value="resolue">Résolue</option>
              <option value="rejetee">Rejetée</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Réponse / Action prise *</label>
            <textarea class="form-control" rows="5" placeholder="Décrivez les actions prises ou la réponse apportée..." required></textarea>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Compensation (si applicable)</label>
            <select class="form-control">
              <option value="">Aucune</option>
              <option value="remboursement">Remboursement total</option>
              <option value="remboursement_partiel">Remboursement partiel</option>
              <option value="bon">Bon de réduction</option>
              <option value="voyage_gratuit">Voyage gratuit</option>
              <option value="points">Points fidélité</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox">
              <span style="font-size: 14px; font-weight: 600;">Envoyer une notification au réclamant</span>
            </label>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" onclick="document.getElementById('modalTraiter').classList.remove('active')">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="check"></i> Valider le traitement
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

      // Fermer les modals
      const closeModalDetails = document.getElementById('closeModalDetails');
      const closeModalTraiter = document.getElementById('closeModalTraiter');

      closeModalDetails?.addEventListener('click', () => {
        document.getElementById('modalDetails').classList.remove('active');
      });

      closeModalTraiter?.addEventListener('click', () => {
        document.getElementById('modalTraiter').classList.remove('active');
      });

      // Fermer en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });

      // Formulaire traiter réclamation
      document.getElementById('formTraiterReclamation')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Réclamation traitée avec succès !');
        document.getElementById('modalTraiter').classList.remove('active');
        location.reload();
      });
    });

    // Fonction pour voir les détails
    function voirReclamation(reclamationId) {
      const reclamations = {
        'REC-2025-001': {
          numero: 'REC-2025-001',
          auteur: 'Jean Mukendi',
          type: 'Client',
          categorie: 'Retard',
          objet: 'Bus en retard de 2h',
          priorite: 'Haute',
          date: '08 Oct 2025 09:30',
          statut: 'En attente',
          description: 'Le bus prévu pour 08h00 est arrivé à 10h00. J\'ai raté mon rendez-vous important. Je demande un remboursement.',
          trajet: 'Kinshasa → Matadi',
          bus: 'Bus #421',
          contact: '+243 XXX XXX XXX'
        }
      };

      const rec = reclamations[reclamationId] || reclamations['REC-2025-001'];
      
      document.getElementById('detailsContent').innerHTML = `
        <div style="display: grid; gap: 20px;">
          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">N° Réclamation</div>
              <div style="font-weight: 700; font-size: 18px; color: #1B4B7F;">${rec.numero}</div>
            </div>
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Date</div>
              <div style="font-weight: 600;">${rec.date}</div>
            </div>
          </div>

          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations du réclamant</h4>
            <div style="display: grid; gap: 8px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Nom</span>
                <span style="font-weight: 600;">${rec.auteur}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Type</span>
                <span class="badge badge--info">${rec.type}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Contact</span>
                <span style="font-weight: 600;">${rec.contact}</span>
              </div>
            </div>
          </div>

          <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Détails de la réclamation</h4>
            <div style="display: grid; gap: 8px; margin-bottom: 16px;">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Catégorie</span>
                <span class="badge badge--primary">${rec.categorie}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Priorité</span>
                <span class="badge badge--danger">${rec.priorite}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Trajet</span>
                <span style="font-weight: 600;">${rec.trajet}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280;">Bus</span>
                <span style="font-weight: 600;">${rec.bus}</span>
              </div>
            </div>
            <div>
              <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px; font-weight: 600;">Objet</div>
              <div style="font-weight: 600; margin-bottom: 12px;">${rec.objet}</div>
            </div>
            <div>
              <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px; font-weight: 600;">Description</div>
              <div style="line-height: 1.6;">${rec.description}</div>
            </div>
          </div>

          <div style="background: ${rec.statut === 'En attente' ? '#fef3c7' : rec.statut === 'En cours' ? '#dbeafe' : rec.statut === 'Résolue' ? '#dcfce7' : '#fee2e2'}; padding: 16px; border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
              <strong>Statut actuel : ${rec.statut}</strong>
            </div>
          </div>
        </div>
      `;

      document.getElementById('modalDetails').classList.add('active');
      feather.replace();
    }

    // Fonction pour traiter une réclamation
    function traiterReclamation(reclamationId) {
      document.getElementById('reclamationInfo').textContent = reclamationId;
      document.getElementById('modalTraiter').classList.add('active');
      feather.replace();
    }
  </script>
</body>
</html>
