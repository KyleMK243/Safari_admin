<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Paramètres Généraux • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php'; ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Paramètres Généraux</h1>
          <p>Configuration globale du système</p>
        </div>
      </header>

      <!-- Tabs -->
      <section class="settings-tabs">
        <button class="settings-tab active" data-tab="general">
          <i data-feather="sliders"></i> Général
        </button>
        <button class="settings-tab" data-tab="notifications">
          <i data-feather="bell"></i> Notifications
        </button>
        <button class="settings-tab" data-tab="security">
          <i data-feather="lock"></i> Sécurité
        </button>
      </section>

      <!-- Tab Content: Général -->
      <section class="tab-content active" id="tab-general">
        <div class="settings-sections">
          <div class="card">
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
                  <option>UTC+1 (Afrique de l'Ouest)</option>
                  <option selected>UTC+2 (Afrique Centrale)</option>
                  <option>UTC+3 (Afrique de l'Est)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Langue</label>
                <select class="form-control">
                  <option selected>Français</option>
                  <option>English</option>
                  <option>Lingala</option>
                </select>
              </div>
              <div class="form-group">
                <label>Format de date</label>
                <select class="form-control">
                  <option selected>DD/MM/YYYY</option>
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
            <h3>Préférences de notification</h3>
          </div>
          <div class="settings-form">
            <div class="notification-item">
              <div>
                <strong>Alertes système</strong>
                <p>Recevoir des notifications pour les alertes critiques</p>
              </div>
              <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
              </label>
            </div>
            <div class="notification-item">
              <div>
                <strong>Notifications par email</strong>
                <p>Recevoir un résumé quotidien par email</p>
              </div>
              <label class="switch">
                <input type="checkbox">
                <span class="slider"></span>
              </label>
            </div>
            <div class="notification-item">
              <div>
                <strong>Notifications push</strong>
                <p>Recevoir des notifications push sur le navigateur</p>
              </div>
              <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
              </label>
            </div>
            <div class="notification-item">
              <div>
                <strong>Rapports hebdomadaires</strong>
                <p>Recevoir un rapport d'activité chaque lundi</p>
              </div>
              <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
              </label>
            </div>
            <button class="btn btn--primary" style="margin-top: 20px;">
              <i data-feather="save"></i> Enregistrer les préférences
            </button>
          </div>
        </div>
      </section>

      <!-- Tab Content: Sécurité -->
      <section class="tab-content" id="tab-security">
        <div class="card">
          <div class="card__header">
            <h3>Paramètres de sécurité</h3>
          </div>
          <div class="settings-form">
            <div class="form-group">
              <label>Durée de session (minutes)</label>
              <input type="number" value="60" class="form-control" min="15" max="480">
              <small>Temps d'inactivité avant déconnexion automatique</small>
            </div>
            <div class="form-group">
              <label>Tentatives de connexion maximales</label>
              <input type="number" value="5" class="form-control" min="3" max="10">
              <small>Nombre de tentatives avant blocage du compte</small>
            </div>
            <div class="notification-item">
              <div>
                <strong>Authentification à deux facteurs</strong>
                <p>Ajouter une couche de sécurité supplémentaire</p>
              </div>
              <label class="switch">
                <input type="checkbox">
                <span class="slider"></span>
              </label>
            </div>
            <div class="notification-item">
              <div>
                <strong>Forcer le changement de mot de passe</strong>
                <p>Obliger les utilisateurs à changer leur mot de passe tous les 90 jours</p>
              </div>
              <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
              </label>
            </div>
            <button class="btn btn--primary" style="margin-top: 20px;">
              <i data-feather="save"></i> Enregistrer
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php'; ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  
  <script>
    // Gestion des onglets
    document.querySelectorAll('.settings-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        // Retirer la classe active de tous les onglets
        document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Ajouter la classe active à l'onglet cliqué
        tab.classList.add('active');
        const tabId = tab.getAttribute('data-tab');
        document.getElementById(`tab-${tabId}`).classList.add('active');
        
        feather.replace();
      });
    });
    
    // Initialiser les icônes
    feather.replace();
  </script>
</body>
</html>
