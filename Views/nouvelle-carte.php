<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Créer une Carte • Safari</title>
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
          <h1>Créer une carte prépayée</h1>
          <p>Création d'une nouvelle carte d'abonnement</p>
        </div>
      </header>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- Formulaire de création de carte -->
      <div class="card">
        <div class="card__header">
          <h3>Informations de la carte</h3>
        </div>
        <div style="padding: 24px;">
          <form id="formNouvelleCarte">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Type de carte *</label>
                <select class="form-control" id="typeCarte" required>
                  <option value="">Sélectionner...</option>
                  <option value="standard">Standard (0% réduction)</option>
                  <option value="etudiant">Étudiant (15% réduction)</option>
                  <option value="entreprise">Entreprise (20% réduction)</option>
                  <option value="senior">Senior (10% réduction)</option>
                  <option value="vip">VIP (25% réduction)</option>
                </select>
              </div>
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Client *</label>
                <select class="form-control" required>
                  <option value="">Sélectionner un client...</option>
                  <option>Jean Mukendi</option>
                  <option>Marie Tshala</option>
                  <option>Paul Nsimba</option>
                </select>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Date d'activation *</label>
                <input type="date" class="form-control" required>
              </div>
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Date d'expiration *</label>
                <input type="date" class="form-control" required>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Montant initial (CDF)</label>
                <input type="number" class="form-control" placeholder="0" min="0">
              </div>
              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Plafond journalier (CDF)</label>
                <input type="number" class="form-control" placeholder="50000" min="0">
              </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Code PIN (4 chiffres) *</label>
              <input type="password" class="form-control" maxlength="4" placeholder="****" required>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Observations</label>
              <textarea class="form-control" rows="3" placeholder="Notes supplémentaires..."></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid #e5e7eb;">
              <button type="button" class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/cartes-prepayees'">
                <i data-feather="x"></i> Annuler
              </button>
              <button type="submit" class="btn btn--primary">
                <i data-feather="credit-card"></i> Créer la carte
              </button>
            </div>
          </form>
        </div>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      // Gestion du formulaire
      document.getElementById('formNouvelleCarte')?.addEventListener('submit', (e) => {
        e.preventDefault();
        if(confirm('Confirmer la création de cette carte ?')) {
          alert('Carte créée avec succès !\nN° Carte: CARD-2025-' + Math.floor(Math.random() * 999999));
          window.location.href = '<?php echo BASE_URL; ?>/cartes-prepayees';
        }
      });
    });
  </script>
</body>
</html>
