<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des utilisateurs • Bureau de conception</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BC.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Gestion des utilisateurs • Bureau de conception</h1>
          <p>Créer, modifier, activer/désactiver les comptes et gérer leurs permissions pour le BC</p>
        </div>
      </header>

      <!-- Contenu : Gestion des utilisateurs BC -->
      <section>
        <div class="card">
          <div class="card__header card__header--reverse">
            <button class="btn btn--primary" id="btnNouvelUtilisateur">
              <i data-feather="user-plus"></i> Nouvel utilisateur
            </button>
            <h3>Utilisateurs du Bureau de conception</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table" style="white-space: nowrap;">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Type de compte</th>
                  <th>Statut</th>
                  <th>Dernière connexion</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="utilisateursTableBody">
                <?php if (empty($utilisateurs)): ?>
                  <?php
                  // Utilisateurs de test (maquette BC) : 3 physiciens
                  $utilisateursDemo = [
                    [
                      'nom' => 'Albert Einstein',
                      'email' => 'einstein@safari.cd',
                      'role' => 'admin',
                      'statut' => 'actif',
                      'initiales' => 'AE',
                      'derniere_connexion' => null,
                    ],
                    [
                      'nom' => 'Marie Curie',
                      'email' => 'curie@safari.cd',
                      'role' => 'supervisor',
                      'statut' => 'actif',
                      'initiales' => 'MC',
                      'derniere_connexion' => null,
                    ],
                    [
                      'nom' => 'Isaac Newton',
                      'email' => 'newton@safari.cd',
                      'role' => 'operator',
                      'statut' => 'inactif',
                      'initiales' => 'IN',
                      'derniere_connexion' => null,
                    ],
                  ];
                  ?>
                  <?php foreach ($utilisateursDemo as $user): ?>
                    <tr class="user-demo-row">
                      <td>
                        <div class="user-cell">
                          <div class="user-cell__avatar"><?= htmlspecialchars($user['initiales']) ?></div>
                          <strong><?= htmlspecialchars($user['nom']) ?> <span style="font-size:11px; color:#9ca3af;"></span></strong>
                        </div>
                      </td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                      <td>
                        <?php
                          $roleLabels = [
                            'admin' => 'Administrateur',
                            'supervisor' => 'Superviseur',
                            'operator' => 'Opérateur',
                            'viewer' => 'Lecteur'
                          ];
                          $roleLabel = $roleLabels[$user['role']] ?? $user['role'];
                        ?>
                        <span class="role-badge role-badge--<?= $user['role'] ?>"><?= $roleLabel ?></span>
                      </td>
                      <td>
                        <span class="status-badge status-badge--<?= $user['statut'] ?>">
                          <?= $user['statut'] === 'actif' ? 'Actif' : 'Inactif' ?>
                        </span>
                      </td>
                      <td>Jamais</td>
                      <td>
                        <div class="action-buttons">
                          <button type="button" class="btn-icon btn-icon--edit" 
                            onclick="ouvrirUtilisateurDemo('<?= htmlspecialchars($user['nom']) ?>','<?= htmlspecialchars($user['email']) ?>','<?= $user['role'] ?>','<?= $user['statut'] ?>')" 
                            title="Voir / modifier">
                            <i data-feather="edit-2"></i>
                          </button>
                          <button type="button" class="btn-icon btn-icon--assign" 
                            onclick="ouvrirPermissionsDemo('<?= $user['role'] ?>','<?= htmlspecialchars($user['nom']) ?>')" 
                            title="Gérer les permissions">
                            <i data-feather="shield"></i>
                          </button>
                          <button type="button" class="btn-icon btn-icon--delete demo-toggle-status" 
                            onclick="toggleStatutDemoUser('<?= htmlspecialchars($user['email']) ?>')" 
                            data-email="<?= htmlspecialchars($user['email']) ?>"
                            data-statut="<?= $user['statut'] ?>"
                            title="Activer / désactiver">
                            <i data-feather="user-x"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                      <td>
                        <div class="user-cell">
                          <div class="user-cell__avatar"><?= htmlspecialchars($user['initiales']) ?></div>
                          <strong><?= htmlspecialchars($user['nom']) ?></strong>
                        </div>
                      </td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                      <td>
                        <?php
                          $roleLabels = [
                            'admin' => 'Administrateur',
                            'supervisor' => 'Superviseur',
                            'operator' => 'Opérateur',
                            'viewer' => 'Lecteur'
                          ];
                          $roleLabel = $roleLabels[$user['role']] ?? $user['role'];
                        ?>
                        <span class="role-badge role-badge--<?= $user['role'] ?>"><?= $roleLabel ?></span>
                      </td>
                      <td>
                        <span class="status-badge status-badge--<?= $user['statut'] ?>">
                          <?= $user['statut'] === 'actif' ? 'Actif' : 'Inactif' ?>
                        </span>
                      </td>
                      <td>
                        <?php
                          if ($user['derniere_connexion']) {
                            $date = new DateTime($user['derniere_connexion']);
                            $now = new DateTime();
                            $diff = $now->diff($date);
                            if ($diff->days == 0) {
                              if ($diff->h == 0) {
                                echo "Il y a " . $diff->i . " minute" . ($diff->i > 1 ? 's' : '');
                              } else {
                                echo "Il y a " . $diff->h . " heure" . ($diff->h > 1 ? 's' : '');
                              }
                            } elseif ($diff->days == 1) {
                              echo "Il y a 1 jour";
                            } else {
                              echo "Il y a " . $diff->days . " jours";
                            }
                          } else {
                            echo "Jamais";
                          }
                        ?>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <!-- Modifier l'utilisateur -->
                          <button class="btn-icon btn-icon--edit btn-edit-user" 
                            data-id="<?= $user['id'] ?>"
                            data-nom="<?= htmlspecialchars($user['nom']) ?>"
                            title="Modifier l'utilisateur">
                            <i data-feather="edit-2"></i>
                          </button>
                          <!-- Gérer les permissions (par rôle) -->
                          <button class="btn-icon btn-icon--assign btn-permissions-user"
                            data-id="<?= $user['id'] ?>"
                            data-nom="<?= htmlspecialchars($user['nom']) ?>"
                            data-role="<?= htmlspecialchars($user['role']) ?>"
                            title="Gérer les permissions">
                            <i data-feather="shield"></i>
                          </button>
                          <!-- Activer / Désactiver -->
                          <button class="btn-icon btn-icon--<?= $user['statut'] === 'actif' ? 'delete' : 'success' ?> btn-toggle-status" 
                            data-id="<?= $user['id'] ?>"
                            data-statut="<?= $user['statut'] ?>"
                            title="<?= $user['statut'] === 'actif' ? 'Désactiver' : 'Activer' ?>">
                            <i data-feather="<?= $user['statut'] === 'actif' ? 'user-x' : 'user-check' ?>"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Nouvel Utilisateur (même logique que PL) -->
  <div class="modal" id="modalUtilisateur">
    <div class="modal__overlay"></div>
    <div class="modal__content">
      <div class="modal__header">
        <h2 id="modalUtilisateurTitle">Nouvel utilisateur</h2>
        <button class="modal__close" id="btnCloseModal">
          <i data-feather="x"></i>
        </button>
      </div>
      <form class="modal__body" id="formUtilisateur">
        <input type="hidden" id="utilisateurId" name="id">
        <div class="form-grid">
          <div class="form-group">
            <label>Nom complet *</label>
            <input type="text" id="utilisateurNom" name="nom" required placeholder="Ex: Jean Dupont">
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" id="utilisateurEmail" name="email" required placeholder="email@safari.cd">
          </div>
          <div class="form-group">
            <label>Type de compte *</label>
            <select id="utilisateurRole" name="role" required>
              <option value="">Sélectionner un rôle</option>
              <option value="admin">Administrateur</option>
              <option value="supervisor">Superviseur</option>
              <option value="operator">Opérateur</option>
              <option value="viewer">Lecteur</option>
            </select>
          </div>
          <div class="form-group">
            <label>Statut *</label>
            <select id="utilisateurStatut" name="statut" required>
              <option value="actif">Actif</option>
              <option value="inactif">Inactif</option>
              <option value="suspendu">Suspendu</option>
            </select>
          </div>
          <div class="form-group">
            <label>Mot de passe <span id="passwordOptional" style="display:none;">(optionnel)</span></label>
            <input type="password" id="utilisateurPassword" name="password" placeholder="••••••••">
          </div>
        </div>
        <div class="modal__footer">
          <button type="button" class="btn btn--secondary" id="btnAnnuler">Annuler</button>
          <button type="submit" class="btn btn--primary">
            <i data-feather="user-plus"></i> <span id="btnSubmitText">Créer l'utilisateur</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Permissions par utilisateur (affiche les permissions de son rôle) -->
  <div class="modal" id="modalPermissionsUtilisateur">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 900px;">
      <div class="modal__header">
        <h2 id="permissionsModalTitle">Permissions de l'utilisateur</h2>
        <button class="modal__close" id="btnClosePermissionsModal">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <p style="font-size:14px; color:#6b7280; margin-bottom:16px;">Cochez les pages auxquelles cet utilisateur aura accès :</p>
        <div id="permissionsUtilisateurContainer" style="display:flex; flex-direction:column; gap:12px;">
          <!-- Liste de checkboxes chargée dynamiquement -->
        </div>
      </div>
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnFermerPermissions">Fermer</button>
      </div>
    </div>
  </div>

  <script src="Public/js/app.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Modal utilisateur (création / modification)
      const modal = document.getElementById('modalUtilisateur');
      const modalTitle = modal.querySelector('.modal__header h2');
      const modalForm = modal.querySelector('form');
      const btnNouvel = document.getElementById('btnNouvelUtilisateur');
      const btnClose = document.getElementById('btnCloseModal');
      const btnAnnuler = document.getElementById('btnAnnuler');

      btnNouvel.addEventListener('click', function() {
        modalTitle.textContent = 'Nouvel utilisateur';
        document.getElementById('btnSubmitText').textContent = 'Créer l\'utilisateur';
        document.getElementById('utilisateurId').value = '';
        document.getElementById('utilisateurPassword').required = true;
        document.getElementById('passwordOptional').style.display = 'none';
        modalForm.reset();
        modal.classList.add('active');
        feather.replace();
      });

      btnClose.addEventListener('click', () => modal.classList.remove('active'));
      btnAnnuler.addEventListener('click', () => modal.classList.remove('active'));
      modal.querySelector('.modal__overlay').addEventListener('click', () => modal.classList.remove('active'));

      // Fonctions pour les utilisateurs BC de démo (Einstein, Curie, Newton)
      window.ouvrirUtilisateurDemo = function(nom, email, role, statut) {
        modalTitle.textContent = "Modifier l'utilisateur";
        document.getElementById('btnSubmitText').textContent = "Enregistrer";
        document.getElementById('utilisateurId').value = '';
        document.getElementById('utilisateurNom').value = nom;
        document.getElementById('utilisateurEmail').value = email;
        document.getElementById('utilisateurRole').value = role;
        document.getElementById('utilisateurStatut').value = statut;
        document.getElementById('utilisateurPassword').value = '';
        document.getElementById('utilisateurPassword').required = false;
        document.getElementById('passwordOptional').style.display = 'inline';
        modal.classList.add('active');
        feather.replace();
      };

      window.ouvrirPermissionsDemo = function(role, nomUser) {
        // Réutilise la logique de rôle (BC) pour afficher les cartes de permissions dans le modal
        ouvrirPermissionsPourRole(role, nomUser);
      };

      window.toggleStatutDemoUser = function(email) {
        const rows = document.querySelectorAll('.user-demo-row');
        rows.forEach(row => {
          const emailCell = row.cells[1];
          if (!emailCell) return;
          if (emailCell.textContent.trim() !== email) return;

          const badge = row.querySelector('.status-badge');
          if (!badge) return;

          const isActif = badge.textContent.toLowerCase().includes('actif');
          const nouveauStatut = isActif ? 'inactif' : 'actif';

          // Mettre à jour le badge
          badge.textContent = (nouveauStatut === 'actif' ? 'Actif' : 'Inactif');
          badge.classList.remove('status-badge--actif', 'status-badge--inactif');
          badge.classList.add('status-badge--' + nouveauStatut);

          // Mettre à jour l'attribut data-statut du bouton correspondant
          const btnToggle = row.querySelector('.demo-toggle-status');
          if (btnToggle) {
            btnToggle.dataset.statut = nouveauStatut;
            btnToggle.title = nouveauStatut === 'actif' ? 'Désactiver' : 'Activer';
          }
        });

        if (typeof feather !== 'undefined') {
          feather.replace();
        }
      };

      // Modal Permissions utilisateur
      const permissionsModal = document.getElementById('modalPermissionsUtilisateur');
      const permissionsTitle = document.getElementById('permissionsModalTitle');
      const permissionsContainer = document.getElementById('permissionsUtilisateurContainer');
      const btnClosePerm = document.getElementById('btnClosePermissionsModal');
      const btnFermerPerm = document.getElementById('btnFermerPermissions');

      function ouvrirPermissionsPourRole(role, nomUser) {
        // MODE MAQUETTE : pas d'appel backend, simple liste de pages du menu BC
        permissionsTitle.textContent = 'Permissions de ' + nomUser;

        const pagesBC = [
          { id: 'dashboard_BC', nom: 'Dashboard • Bureau de conception' },
          { id: 'trajets', nom: 'Lignes / trajets' },
          { id: 'roulements-bc', nom: 'Roulements • Contrôleurs' },
          { id: 'parametres-bc', nom: 'Paramètres • Bureau de conception' }
        ];

        let html = '';
        pagesBC.forEach(page => {
          const disabled = role === 'admin' ? 'disabled' : '';
          const checked = role === 'admin' ? 'checked' : '';
          html += `
            <label style="display:flex; align-items:center; gap:8px; padding:12px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
              <input type="checkbox" 
                     class="permission-checkbox" 
                     data-page-id="${page.id}"
                     data-role="${role}"
                     ${checked}
                     ${disabled}
                     style="width:18px; height:18px; cursor:pointer;">
              <span style="font-size:14px; color:#111827;">${page.nom}</span>
            </label>
          `;
        });

        if (role === 'admin') {
          html += '<div style="margin-top:16px; padding:12px; background:#fef3c7; border-radius:8px; font-size:13px; color:#92400e;">✓ Administrateur : accès complet à toutes les pages</div>';
        }

        permissionsContainer.innerHTML = html;
        permissionsModal.classList.add('active');
        feather.replace();
      }

      btnClosePerm.addEventListener('click', () => permissionsModal.classList.remove('active'));
      btnFermerPerm.addEventListener('click', () => permissionsModal.classList.remove('active'));
      permissionsModal.querySelector('.modal__overlay').addEventListener('click', () => permissionsModal.classList.remove('active'));

      // Gestion des actions utilisateurs (Modifier / Permissions / Statut)
      document.addEventListener('click', async function(e) {
        // Modifier un utilisateur
        if (e.target.closest('.btn-edit-user')) {
          const btn = e.target.closest('.btn-edit-user');
          const userId = btn.dataset.id;
          try {
            const response = await fetch(`/parametres/get-utilisateur?id=${userId}`);
            const result = await response.json();
            if (result.success) {
              const user = result.utilisateur;
              modalTitle.textContent = 'Modifier l\'utilisateur';
              document.getElementById('btnSubmitText').textContent = 'Enregistrer';
              document.getElementById('utilisateurId').value = user.id;
              document.getElementById('utilisateurNom').value = user.nom;
              document.getElementById('utilisateurEmail').value = user.email;
              document.getElementById('utilisateurRole').value = user.role;
              document.getElementById('utilisateurStatut').value = user.statut;
              document.getElementById('utilisateurPassword').value = '';
              document.getElementById('utilisateurPassword').required = false;
              document.getElementById('passwordOptional').style.display = 'inline';
              modal.classList.add('active');
              feather.replace();
            } else {
              alert('Erreur: ' + result.message);
            }
          } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données');
          }
        }

        // Gérer les permissions (par rôle)
        if (e.target.closest('.btn-permissions-user')) {
          const btn = e.target.closest('.btn-permissions-user');
          const role = btn.dataset.role;
          const nomUser = btn.dataset.nom;
          ouvrirPermissionsPourRole(role, nomUser);
        }

        // Changer le statut
        if (e.target.closest('.btn-toggle-status')) {
          const btn = e.target.closest('.btn-toggle-status');
          const userId = btn.dataset.id;
          const currentStatut = btn.dataset.statut;
          const newStatut = currentStatut === 'actif' ? 'inactif' : 'actif';
          if (!confirm(`Voulez-vous vraiment ${newStatut === 'actif' ? 'activer' : 'désactiver'} cet utilisateur ?`)) {
            return;
          }
          try {
            const formData = new FormData();
            formData.append('id', userId);
            formData.append('statut', newStatut);
            const response = await fetch('/parametres/changer-statut', {
              method: 'POST',
              body: formData
            });
            const result = await response.json();
            if (result.success) {
              alert(result.message);
              window.location.reload();
            } else {
              alert('Erreur: ' + result.message);
            }
          } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors du changement de statut');
          }
        }
      });

      // Soumission du formulaire utilisateur (création/modification)
      const form = document.getElementById('formUtilisateur');
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const userId = document.getElementById('utilisateurId').value;
        const url = userId ? '/parametres/modifier-utilisateur' : '/parametres/creer-utilisateur';
        try {
          const response = await fetch(url, {
            method: 'POST',
            body: formData
          });
          const result = await response.json();
          if (result.success) {
            alert(result.message);
            window.location.reload();
          } else {
            alert('Erreur: ' + result.message);
          }
        } catch (error) {
          console.error('Erreur:', error);
          alert('Erreur lors de l\'enregistrement');
        }
      });

      feather.replace();
    });

    // Animation spin
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>
