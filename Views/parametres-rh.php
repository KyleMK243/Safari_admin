<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Paramètres RH • Safari</title>
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
          <h1>Paramètres - Ressources Humaines</h1>
          <p>Gestion des utilisateurs et permissions du département RH</p>
        </div>
      </header>


      <!-- Tabs -->
      <section class="settings-tabs">
        <button class="settings-tab active" data-tab="users">
          <i data-feather="users"></i> Utilisateurs
        </button>
        <button class="settings-tab" data-tab="permissions">
          <i data-feather="shield"></i> Permissions
        </button>
      </section>

      <!-- Tab Content: Utilisateurs -->
      <section class="tab-content active" id="tab-users">
        <div class="card">
          <div class="card__header card__header--reverse">
            <button class="btn btn--primary" id="btnNouvelUtilisateur">
              <i data-feather="user-plus"></i> Nouvel utilisateur
            </button>
            <h3>Utilisateurs du département RH</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table" style="white-space: nowrap;">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Dernière connexion</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="utilisateursTableBody">
              <?php if (empty($utilisateurs)): ?>
                <tr>
                  <td colspan="6" style="text-align: center; padding: 40px;">
                    <p style="color: #6b7280; margin: 0;">Aucun utilisateur trouvé</p>
                  </td>
                </tr>
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
                        <button class="btn-icon btn-icon--edit btn-edit-user" 
                          data-id="<?= $user['id'] ?>"
                          title="Modifier">
                          <i data-feather="edit-2"></i>
                        </button>
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

      <!-- Tab Content: Permissions -->
      <section class="tab-content" id="tab-permissions">
        <div class="card">
          <div class="card__header">
            <h3>Permissions - Département RH</h3>
          </div>
          
          <div class="permissions-grid" id="permissionsContainer">
            <!-- Les permissions seront chargées dynamiquement ici -->
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- SUPPRIMÉ: Tab Content Général et Notifications - Maintenant dans parametres-general.php -->
      <!-- Tab Content: Général (DÉSACTIVÉ) -->
      <section class="tab-content" id="tab-general">
        <div class="settings-sections">
          <!-- Carte réservée au super admin uniquement -->
          <div class="card" style="display:none;">
            <div class="card__header">
              <h3>Informations de l'entreprise</h3>
            </div>
            <div class="settings-form">
              <div class="form-group">
                <label>Nom de l'entreprise</label>
                <input type="text" value="Safari Transport" class="form-control">
              </div>
              <div class="form-group">
                <label>Email de contact</label>
                <input type="email" value="contact@safari.cd" class="form-control">
              </div>
              <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" value="+243 XXX XXX XXX" class="form-control">
              </div>
              <div class="form-group">
                <label>Adresse</label>
                <textarea class="form-control" rows="2">Kinshasa, République Démocratique du Congo</textarea>
              </div>
              <button class="btn btn--primary">
                <i data-feather="save"></i> Enregistrer
              </button>
            </div>
          </div>

          <div class="card">
            <div class="card__header">
              <h3>Préférences système</h3>
            </div>
            <div class="settings-form">
              <div class="form-group">
                <label>Fuseau horaire</label>
                <select class="form-control">
                  <option>Africa/Kinshasa (UTC+1)</option>
                  <option>Africa/Lubumbashi (UTC+2)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Langue</label>
                <select class="form-control">
                  <option>Français</option>
                  <option>English</option>
                  <option>Lingala</option>
                </select>
              </div>
              <div class="form-group">
                <label>Format de date</label>
                <select class="form-control">
                  <option>DD/MM/YYYY</option>
                  <option>MM/DD/YYYY</option>
                  <option>YYYY-MM-DD</option>
                </select>
              </div>
              <button class="btn btn--primary">
                <i data-feather="save"></i> Enregistrer
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Tab Content: Notifications -->
      <section class="tab-content" id="tab-notifications">
        <div class="card">
          <div class="card__header">
            <h3>Préférences de notifications</h3>
          </div>
          <div class="settings-form">
            <div class="notification-group">
              <h4>Alertes critiques</h4>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Accidents de bus</strong>
                    <p>Recevoir une notification immédiate en cas d'accident</p>
                  </div>
                </label>
              </div>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Pannes mécaniques</strong>
                    <p>Être alerté des pannes nécessitant une intervention</p>
                  </div>
                </label>
              </div>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Documents expirés</strong>
                    <p>Notification quand un document expire</p>
                  </div>
                </label>
              </div>
            </div>

            <div class="notification-group">
              <h4>Avertissements</h4>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Maintenance préventive</strong>
                    <p>Rappels pour les maintenances programmées</p>
                  </div>
                </label>
              </div>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox">
                  <div>
                    <strong>Équipes incomplètes</strong>
                    <p>Alerte si un shift n'a pas d'équipe complète</p>
                  </div>
                </label>
              </div>
            </div>

            <div class="notification-group">
              <h4>Rapports</h4>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Rapport quotidien</strong>
                    <p>Recevoir un résumé quotidien des activités</p>
                  </div>
                </label>
              </div>
              <div class="notification-item">
                <label class="checkbox-label">
                  <input type="checkbox" checked>
                  <div>
                    <strong>Rapport hebdomadaire</strong>
                    <p>Statistiques et performances de la semaine</p>
                  </div>
                </label>
              </div>
            </div>

            <button class="btn btn--primary">
              <i data-feather="save"></i> Enregistrer les préférences
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Nouvel Utilisateur -->
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
            <label>Rôle *</label>
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

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Gestion des tabs
      const tabs = document.querySelectorAll('.settings-tab');
      const tabContents = document.querySelectorAll('.tab-content');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          const targetTab = this.dataset.tab;
          
          // Retirer active de tous les tabs
          tabs.forEach(t => t.classList.remove('active'));
          tabContents.forEach(tc => tc.classList.remove('active'));
          
          // Ajouter active au tab cliqué
          this.classList.add('active');
          document.getElementById('tab-' + targetTab).classList.add('active');
          
          feather.replace();
        });
      });
      
      // Modal utilisateur
      const modal = document.getElementById('modalUtilisateur');
      const modalTitle = modal.querySelector('.modal__header h2');
      const modalForm = modal.querySelector('form');
      const btnSubmit = modal.querySelector('button[type="submit"]');
      const btnNouvel = document.getElementById('btnNouvelUtilisateur');
      const btnClose = document.getElementById('btnCloseModal');
      const btnAnnuler = document.getElementById('btnAnnuler');
      
      // Ouvrir modal pour nouvel utilisateur
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
      
      // Fermer modal
      btnClose.addEventListener('click', function() {
        modal.classList.remove('active');
      });
      
      btnAnnuler.addEventListener('click', function() {
        modal.classList.remove('active');
      });
      
      modal.querySelector('.modal__overlay').addEventListener('click', function() {
        modal.classList.remove('active');
      });
      
      // Gestion des actions utilisateurs
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
              
              // Remplir le formulaire
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
      
      // Soumettre le formulaire
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
      
      // ========================================
      // GESTION DES PERMISSIONS
      // ========================================
      
      // Charger toutes les permissions au chargement de la page
      async function chargerToutesLesPermissions() {
        const container = document.getElementById('permissionsContainer');
        container.innerHTML = '<div style="padding: 40px; text-align: center;"><div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top-color: #1B4B7F; border-radius: 50%; animation: spin 1s linear infinite;"></div></div>';
        
        try {
          // Charger les permissions pour tous les rôles
          const roles = ['admin', 'supervisor', 'operator', 'viewer'];
          const roleLabels = {
            'admin': 'Administrateur',
            'supervisor': 'Superviseur',
            'operator': 'Opérateur',
            'viewer': 'Lecteur'
          };
          
          let html = '';
          
          for (const role of roles) {
            const response = await fetch(`/permissions/get-by-role?role=${role}&departement=RH`);
            const data = await response.json();
            
            console.log(`Données pour ${role}:`, data);
            
            if (data.success) {
              html += genererCarteRole(role, roleLabels[role], data.modules);
            }
          }
          
          container.innerHTML = html;
          feather.replace();
          
          // Ajouter les écouteurs d'événements
          document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', handlePermissionChange);
          });
        } catch (error) {
          console.error('Erreur:', error);
          container.innerHTML = '<p style="color: #ef4444; text-align: center; padding: 40px;">Erreur lors du chargement des permissions</p>';
        }
      }
      
      // Générer une carte de rôle avec ses permissions
      function genererCarteRole(role, label, modulesGrouped) {
        const isAdmin = role === 'admin';
        const roleClass = `role-badge--${role}`;
        
        const departementLabels = {
          'PL': 'ÉTUDE ET ANALYSE',
          'BT': 'BILLETTERIE',
          'RH': 'RESSOURCES HUMAINES'
        };
        
        let html = `
          <div class="permission-card">
            <div class="permission-card__header">
              <h4>${label}</h4>
              <span class="role-badge ${roleClass}">${label}</span>
            </div>
            <div class="permission-card__body">
        `;
        
        // Parcourir chaque département
        for (const [dept, sections] of Object.entries(modulesGrouped)) {
          // Afficher le titre du département
          html += `
            <div style="margin-bottom: 16px;">
              <div style="font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                ${departementLabels[dept] || dept}
              </div>
          `;
          
          console.log(`Département ${dept}:`, sections);
          
          // Trier les sections : GENERAL en premier, puis les autres
          const sortedSections = Object.entries(sections).sort(([a], [b]) => {
            if (a === 'GENERAL') return -1;
            if (b === 'GENERAL') return 1;
            return a.localeCompare(b);
          });
          
          // Parcourir chaque section du département
          for (const [section, modules] of sortedSections) {
            console.log(`Section ${section}:`, modules);
            
            // Afficher le titre de la section (sauf GENERAL)
            if (section !== 'GENERAL' && section !== 'null') {
              html += `
                <div class="nav__section" style="margin: 8px 0 4px 0;">${section}</div>
              `;
            }
            
            // Afficher les modules de cette section
            if (Array.isArray(modules)) {
              modules.forEach(module => {
                const disabled = isAdmin ? 'disabled' : '';
                
                html += `
                  <div class="permission-item">
                    <label class="checkbox-label">
                      <input type="checkbox" 
                             class="permission-checkbox" 
                             data-module-id="${module.id}"
                             data-role="${role}"
                             data-action="peut_voir"
                             ${module.peut_voir == 1 ? 'checked' : ''} 
                             ${disabled}>
                      <span>${module.nom}</span>
                    </label>
                  </div>
                `;
              });
            }
          }
          
          html += `</div>`;
        }
        
        if (isAdmin) {
          html += `
            <div class="permission-item" style="margin-top: 12px; padding: 12px; background: #fef3c7; border-radius: 6px;">
              <small style="color: #92400e;">✓ Accès complet au système</small>
            </div>
          `;
        }
        
        html += `
            </div>
          </div>
        `;
        
        return html;
      }
      
      // Gérer le changement d'une permission
      async function handlePermissionChange(e) {
        const checkbox = e.target;
        const moduleId = checkbox.dataset.moduleId;
        const role = checkbox.dataset.role;
        const action = checkbox.dataset.action;
        const value = checkbox.checked;
        
        try {
          const response = await fetch('/permissions/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              role: role,
              module_id: moduleId,
              action: action,
              value: value
            })
          });
          
          const result = await response.json();
          
          if (!result.success) {
            alert('Erreur: ' + result.message);
            checkbox.checked = !checkbox.checked;
          }
        } catch (error) {
          console.error('Erreur:', error);
          alert('Erreur lors de la mise à jour');
          checkbox.checked = !checkbox.checked;
        }
      }
      
      // Charger les permissions au chargement de l'onglet
      chargerToutesLesPermissions();
      
      feather.replace();
    });
    
    // Ajouter l'animation de chargement
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
