<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Clients • Safari</title>
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
          <h1>Gestion des Clients</h1>
          <p>Suivi et gestion des comptes clients</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" id="btnEnvoyerMessage">
            <i data-feather="send"></i> Message groupé
          </button>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total clients</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;"><?= number_format($stats['total_clients'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Clients actifs</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= number_format($stats['clients_actifs'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Nouveaux (mois)</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;"><?= number_format($stats['nouveaux_mois'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">VIP</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= number_format($stats['clients_vip'] ?? 0, 0, ',', ' ') ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Promo éligibles</div>
          <div style="font-size: 32px; font-weight: 800; color: #ef4444;"><?= number_format($stats['promo_eligibles'] ?? 0, 0, ',', ' ') ?></div>
        </div>
      </div>


      <!-- Filtres -->
      <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Rechercher</label>
              <input type="text" class="form-control" placeholder="Nom, téléphone, email..." id="searchClient">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control" id="filterStatut">
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type</label>
              <select class="form-control" id="filterType">
                <option value="">Tous</option>
                <option value="standard">Standard</option>
                <option value="vip">VIP</option>
                <option value="etudiant">Étudiant</option>
                <option value="entreprise">Entreprise</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Promotion</label>
              <select class="form-control" id="filterPromo">
                <option value="">Tous</option>
                <option value="eligible">Éligible</option>
                <option value="non-eligible">Non éligible</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Liste des clients -->
      <section class="card">
        <div class="card__header">
          <h3>Liste des clients</h3>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Client</th>
                <th>Contact</th>
                <th>Type</th>
                <th>Points fidélité</th>
                <th>Voyages</th>
                <th>Dépenses totales</th>
                <th>Promotion</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="clientsTableBody">
              <?php if (empty($clients)): ?>
                <tr>
                  <td colspan="9" style="text-align: center; padding: 40px; color: #9ca3af;">
                    <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 12px;"></i>
                    <p style="margin: 0;">Aucun client enregistré</p>
                  </td>
                </tr>
              <?php else: ?>
                <?php 
                $gradients = [
                  'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                  'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                  'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                  'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                  'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
                  'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
                ];
                $badgeColors = [
                  'VIP' => 'warning',
                  'Standard' => 'success',
                  'Nouveau' => 'info',
                  'Étudiant' => 'info',
                  'Entreprise' => 'primary'
                ];
                ?>
                <?php foreach ($clients as $index => $client): ?>
                  <tr>
                    <td>
                      <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: <?= $gradients[$index % count($gradients)] ?>; display: grid; place-items: center; color: white; font-weight: 700;">
                          <?= htmlspecialchars($client['initiales']) ?>
                        </div>
                        <div>
                          <div style="font-weight: 600;"><?= htmlspecialchars($client['nom_complet']) ?></div>
                          <div style="font-size: 12px; color: #6b7280;">ID: <?= $client['id'] ?></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div><?= htmlspecialchars($client['telephone'] ?? '-') ?></div>
                      <div style="font-size: 12px; color: #6b7280;"><?= htmlspecialchars($client['email'] ?? '-') ?></div>
                    </td>
                    <td><span class="badge badge--<?= $badgeColors[$client['type_compte']] ?? 'success' ?>"><?= htmlspecialchars($client['type_compte']) ?></span></td>
                    <td>
                      <div style="font-weight: 700; color: <?= $client['niveau'] === 'Or' ? '#f59e0b' : ($client['niveau'] === 'Argent' ? '#3b82f6' : '#6b7280') ?>;">
                        <?= number_format($client['points_fidelite'], 0, ',', ' ') ?> pts
                      </div>
                      <div style="font-size: 11px; color: #6b7280;">Niveau <?= htmlspecialchars($client['niveau']) ?></div>
                    </td>
                    <td><strong><?= $client['nombre_voyages'] ?></strong></td>
                    <td><strong style="color: #10b981;"><?= number_format($client['depenses_totales'], 0, ',', ' ') ?> CDF</strong></td>
                    <td>
                      <?php if ($client['promo_eligible']): ?>
                        <span class="status-badge status-badge--actif">Éligible</span>
                        <div style="font-size: 11px; color: #10b981; margin-top: 2px;">-<?= $client['reduction'] ?>% voyage</div>
                      <?php else: ?>
                        <span class="status-badge status-badge--inactif">Non éligible</span>
                        <div style="font-size: 11px; color: #6b7280; margin-top: 2px;"><?= 1000 - $client['points_fidelite'] ?> pts requis</div>
                      <?php endif; ?>
                    </td>
                    <td><span class="status-badge status-badge--<?= $client['statut'] ?>"><?= ucfirst($client['statut']) ?></span></td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn-icon btn-icon--view btn-voir-details" title="Détails" data-client-id="<?= $client['id'] ?>">
                          <i data-feather="eye"></i>
                        </button>
                        <button class="btn-icon btn-icon--edit btn-modifier-client" title="Modifier" data-client-id="<?= $client['id'] ?>">
                          <i data-feather="edit-2"></i>
                        </button>
                        <button class="btn-icon btn-icon--primary btn-envoyer-message" title="Envoyer message" data-client-id="<?= $client['id'] ?>" data-client-nom="<?= htmlspecialchars($client['nom_complet']) ?>" data-client-tel="<?= htmlspecialchars($client['telephone']) ?>">
                          <i data-feather="message-circle"></i>
                        </button>
                        <button class="btn-icon btn-icon--delete btn-toggle-statut" title="Désactiver" data-client-id="<?= $client['id'] ?>" data-statut="<?= $client['statut'] ?>">
                          <i data-feather="power"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails Client -->
  <div class="modal" id="modalDetails">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 900px;">
      <div class="modal__header">
        <h2 id="modalDetailsTitle">Détails du client</h2>
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

  <!-- Modal Modifier Client -->
  <div class="modal" id="modalModifier">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Modifier le client</h2>
        <button class="modal__close" id="closeModalModifier">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formModifierClient">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nom complet *</label>
              <input type="text" class="form-control" id="editNom" required>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Téléphone *</label>
              <input type="tel" class="form-control" id="editTel" required>
            </div>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
            <input type="email" class="form-control" id="editEmail">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de compte</label>
              <select class="form-control" id="editType">
                <option value="standard">Standard</option>
                <option value="vip">VIP</option>
                <option value="etudiant">Étudiant</option>
                <option value="entreprise">Entreprise</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Points fidélité</label>
              <input type="number" class="form-control" id="editPoints" min="0">
            </div>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" onclick="document.getElementById('modalModifier').classList.remove('active')">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="save"></i> Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Envoyer Message -->
  <div class="modal" id="modalMessage">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2 id="modalMessageTitle">Envoyer un message</h2>
        <button class="modal__close" id="closeModalMessage">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formEnvoyerMessage">
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Destinataire(s)</label>
            <input type="text" class="form-control" id="messageDestinataire" readonly style="background: #f3f4f6;">
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de message</label>
            <select class="form-control" id="messageType">
              <option value="sms">SMS</option>
              <option value="email">Email</option>
              <option value="notification">Notification push</option>
              <option value="tous">Tous les canaux</option>
            </select>
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Objet (pour email)</label>
            <input type="text" class="form-control" placeholder="Ex: Promotion spéciale">
          </div>

          <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Message *</label>
            <textarea class="form-control" rows="5" placeholder="Votre message..." required></textarea>
            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">160 caractères max pour SMS</div>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" onclick="document.getElementById('modalMessage').classList.remove('active')">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="send"></i> Envoyer
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

      // Fermer les modals
      const modals = ['modalDetails', 'modalModifier', 'modalMessage'];
      modals.forEach(modalId => {
        const closeBtn = document.getElementById('close' + modalId.charAt(5).toUpperCase() + modalId.slice(6));
        closeBtn?.addEventListener('click', () => {
          document.getElementById(modalId).classList.remove('active');
        });
      });

      // Fermer en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });

      // Message groupé
      document.getElementById('btnEnvoyerMessage')?.addEventListener('click', () => {
        document.getElementById('messageDestinataire').value = 'Tous les clients actifs (2,385)';
        document.getElementById('modalMessageTitle').textContent = 'Message groupé';
        document.getElementById('modalMessage').classList.add('active');
        feather.replace();
      });

      // Event delegation pour les boutons d'action
      document.addEventListener('click', function(e) {
        // Bouton Voir détails
        const btnDetails = e.target.closest('.btn-voir-details');
        if (btnDetails) {
          e.preventDefault();
          const clientId = btnDetails.dataset.clientId;
          voirDetails(clientId);
        }

        // Bouton Modifier
        const btnModifier = e.target.closest('.btn-modifier-client');
        if (btnModifier) {
          e.preventDefault();
          const clientId = btnModifier.dataset.clientId;
          modifierClient(clientId);
        }

        // Bouton Envoyer message
        const btnMessage = e.target.closest('.btn-envoyer-message');
        if (btnMessage) {
          e.preventDefault();
          const clientId = btnMessage.dataset.clientId;
          const clientNom = btnMessage.dataset.clientNom;
          const clientTel = btnMessage.dataset.clientTel;
          envoyerMessage(clientId, clientNom, clientTel);
        }

        // Bouton Toggle statut
        const btnToggle = e.target.closest('.btn-toggle-statut');
        if (btnToggle) {
          e.preventDefault();
          const clientId = btnToggle.dataset.clientId;
          const statut = btnToggle.dataset.statut;
          toggleStatut(clientId, statut);
        }
      });

      // Formulaires
      document.getElementById('formModifierClient')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Client modifié avec succès !');
        document.getElementById('modalModifier').classList.remove('active');
      });

      document.getElementById('formEnvoyerMessage')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Message envoyé avec succès !');
        document.getElementById('modalMessage').classList.remove('active');
      });
    });

    // Fonction pour voir les détails (AJAX)
    function voirDetails(clientId) {
      fetch(`/clients/details?id=${clientId}`)
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            alert('Erreur: ' + data.message);
            return;
          }

          const client = data.client;
          
          document.getElementById('detailsContent').innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
              <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center; color: white; font-weight: 700; font-size: 48px; margin: 0 auto 16px;">
                  ${client.initiales}
                </div>
                <h3 style="margin: 0 0 8px 0;">${client.nom_complet}</h3>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">ID: ${client.id}</div>
                <span class="badge badge--warning" style="font-size: 14px;">${client.type_compte}</span>
              </div>

              <div>
                <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Informations personnelles</h4>
                <div style="display: grid; gap: 12px; margin-bottom: 24px;">
                  <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <span style="color: #6b7280;">Téléphone</span>
                    <span style="font-weight: 600;">${client.telephone || '-'}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <span style="color: #6b7280;">Email</span>
                    <span style="font-weight: 600;">${client.email || '-'}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <span style="color: #6b7280;">Inscription</span>
                    <span style="font-weight: 600;">${new Date(client.date_creation).toLocaleDateString('fr-FR')}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <span style="color: #6b7280;">Dernier voyage</span>
                    <span style="font-weight: 600;">${client.dernier_voyage ? new Date(client.dernier_voyage).toLocaleDateString('fr-FR') : 'Aucun'}</span>
                  </div>
                </div>

                <h4 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Programme fidélité</h4>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px;">
                  <div style="padding: 16px; background: #fef3c7; border-radius: 8px; text-align: center;">
                    <div style="font-size: 28px; font-weight: 800; color: #f59e0b;">${client.points_fidelite.toLocaleString()}</div>
                    <div style="font-size: 12px; color: #92400e; margin-top: 4px;">Points fidélité</div>
                    <div style="font-size: 11px; color: #92400e; margin-top: 2px;">Niveau ${client.niveau}</div>
                  </div>
                  <div style="padding: 16px; background: #dbeafe; border-radius: 8px; text-align: center;">
                    <div style="font-size: 28px; font-weight: 800; color: #1B4B7F;">${client.nombre_voyages}</div>
                    <div style="font-size: 12px; color: #1e40af; margin-top: 4px;">Voyages effectués</div>
                    <div style="font-size: 11px; color: #1e40af; margin-top: 2px;">${parseFloat(client.depenses_totales).toLocaleString()} CDF dépensés</div>
                  </div>
                </div>

                ${client.promo_eligible ? `
                  <div style="background: #dcfce7; padding: 16px; border-radius: 8px; border-left: 4px solid #10b981;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                      <i data-feather="gift" style="width: 20px; height: 20px; color: #059669;"></i>
                      <strong style="color: #059669;">Promotion active</strong>
                    </div>
                    <div style="color: #059669; font-size: 14px;">Éligible - ${client.reduction}% de réduction</div>
                  </div>
                ` : ''}
              </div>
            </div>
          `;

          document.getElementById('modalDetails').classList.add('active');
          feather.replace();
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors du chargement des détails');
        });
    }

    // Fonction pour modifier un client
    function modifierClient(clientId) {
      // Charger les données du client via AJAX
      fetch(`/clients/details?id=${clientId}`)
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            alert('Erreur: ' + data.message);
            return;
          }

          const client = data.client;
          document.getElementById('editNom').value = client.nom || '';
          document.getElementById('editTel').value = client.telephone || '';
          document.getElementById('editEmail').value = client.email || '';
          
          document.getElementById('modalModifier').classList.add('active');
          feather.replace();
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors du chargement des données');
        });
    }

    // Fonction pour envoyer un message
    function envoyerMessage(clientId, clientNom, clientTel) {
      document.getElementById('messageDestinataire').value = `${clientNom} (${clientTel})`;
      document.getElementById('modalMessageTitle').textContent = 'Envoyer un message';
      document.getElementById('modalMessage').classList.add('active');
      feather.replace();
    }

    // Fonction pour activer/désactiver un compte
    function toggleStatut(clientId, statutActuel) {
      const action = statutActuel === 'actif' ? 'désactiver' : 'activer';
      if(confirm(`Voulez-vous vraiment ${action} ce compte ?`)) {
        alert(`Fonctionnalité en cours de développement`);
        // TODO: Implémenter l'API pour changer le statut
        // fetch('/clients/toggle-statut', {...})
      }
    }
  </script>
</body>
</html>
