<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Billetterie • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BT.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Tableau de bord - Billetterie</h1>
          <p>Gestion des ventes et réservations de billets</p>
        </div>
      </header>

      <!-- Stats rapides -->
      <section class="bi-stats">
        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--blue">
            <i data-feather="shopping-cart"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Billets vendus aujourd'hui</div>
            <div class="bi-stat-card__value"><?= number_format($stats['billets_vendus']) ?></div>
            <?php if ($stats['tendance_billets'] != 0) : ?>
            <div class="bi-stat-card__trend bi-stat-card__trend--<?= $stats['tendance_billets'] > 0 ? 'up' : 'down' ?>">
              <i data-feather="trending-<?= $stats['tendance_billets'] > 0 ? 'up' : 'down' ?>"></i> <?= abs($stats['tendance_billets']) ?>% vs hier
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--green">
            <i data-feather="dollar-sign"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Revenus du jour</div>
            <div class="bi-stat-card__value"><?= number_format($stats['revenus'], 0, ',', ' ') ?> CDF</div>
            <?php if ($stats['tendance_revenus'] != 0) : ?>
            <div class="bi-stat-card__trend bi-stat-card__trend--<?= $stats['tendance_revenus'] > 0 ? 'up' : 'down' ?>">
              <i data-feather="trending-<?= $stats['tendance_revenus'] > 0 ? 'up' : 'down' ?>"></i> <?= abs($stats['tendance_revenus']) ?>%
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--yellow">
            <i data-feather="bookmark"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Réservations en attente</div>
            <div class="bi-stat-card__value"><?= $stats['reservations'] ?></div>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--red">
            <i data-feather="credit-card"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Cartes actives</div>
            <div class="bi-stat-card__value"><?= $stats['cartes_actives'] ?></div>
          </div>
        </div>
      </section>

      <!-- Transactions récentes -->
      <section class="card">
        <div class="card__header">
          <h3>Transactions récentes</h3>
          <button class="btn btn--secondary btn--sm">
            <i data-feather="download"></i> Exporter
          </button>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>N° Transaction</th>
              <th>Type</th>
              <th>Client</th>
              <th>Trajet</th>
              <th>Date voyage</th>
              <th>Montant</th>
              <th>Paiement</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($transactions)) : ?>
              <?php foreach ($transactions as $transaction) : 
                $typeBadge = match($transaction['type_transaction']) {
                  'vente' => 'primary',
                  'reservation' => 'warning',
                  'annulation' => 'danger',
                  'remboursement' => 'info',
                  default => 'secondary'
                };
                
                $statutBadge = match($transaction['statut_transaction']) {
                  'reussie' => 'actif',
                  'en_attente' => 'warning',
                  'echouee' => 'inactif',
                  'annulee' => 'inactif',
                  default => 'warning'
                };
                
                $modePaiementBadge = match($transaction['mode_paiement']) {
                  'mobile_money' => 'success',
                  'carte_bancaire' => 'info',
                  'especes' => 'primary',
                  default => 'secondary'
                };
              ?>
            <tr>
              <td><strong><?= htmlspecialchars($transaction['numero_billet'] ?? 'N/A') ?></strong></td>
              <td><span class="badge badge--<?= $typeBadge ?>"><?= ucfirst($transaction['type_transaction']) ?></span></td>
              <td><?= htmlspecialchars($transaction['client_nom'] ?? 'N/A') ?> <?= htmlspecialchars($transaction['client_prenom'] ?? '') ?></td>
              <td><?= htmlspecialchars($transaction['arret_depart'] ?? 'N/A') ?> → <?= htmlspecialchars($transaction['arret_arrivee'] ?? 'N/A') ?></td>
              <td><?= $transaction['date_voyage'] ? date('d/m/Y', strtotime($transaction['date_voyage'])) : 'N/A' ?></td>
              <td><strong><?= number_format($transaction['montant'], 0, ',', ' ') ?> <?= $transaction['devise'] ?></strong></td>
              <td><span class="badge badge--<?= $modePaiementBadge ?>"><?= ucfirst(str_replace('_', ' ', $transaction['mode_paiement'])) ?></span></td>
              <td><span class="status-badge status-badge--<?= $statutBadge ?>"><?= ucfirst($transaction['statut_transaction']) ?></span></td>
              <td>
                <div class="action-buttons">
                  <?php if (!empty($transaction['billet_id'])) : ?>
                  <button class="btn-icon btn-icon--view" title="Voir" onclick="voirTransaction(<?= (int)$transaction['billet_id'] ?>); return false;">
                    <i data-feather="eye"></i>
                  </button>
                  <?php if ($transaction['statut_transaction'] === 'reussie') : ?>
                  <button class="btn-icon btn-icon--print" title="Imprimer PDF" onclick="imprimerBilletPDF(<?= (int)$transaction['billet_id'] ?>); return false;">
                    <i data-feather="printer"></i>
                  </button>
                  <?php endif; ?>
                  <?php else : ?>
                  <span style="color: #9ca3af; font-size: 12px;">N/A</span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">
                <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
                <p>Aucune transaction récente</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails Billet -->
  <div id="modalDetailsBillet" class="modal">
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h3>Détails du billet</h3>
        <button class="modal__close" onclick="fermerModalBillet()">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body" id="detailsBilletContent">
        <div style="text-align: center; padding: 40px;">
          <div class="spinner"></div>
          <p style="margin-top: 16px; color: #6b7280;">Chargement...</p>
        </div>
      </div>
      <div class="modal__footer">
        <button class="btn btn--secondary" onclick="fermerModalBillet()">Fermer</button>
        <button class="btn btn--primary" onclick="imprimerBilletPDF(billetIdActuel)">
          <i data-feather="printer"></i> Imprimer PDF
        </button>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    let billetIdActuel = null;
    let billetDataActuel = null;

    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
    });

    // Voir les détails d'une transaction/billet
    async function voirTransaction(billetId) {
      if (!billetId) {
        alert('Erreur: ID du billet manquant');
        return;
      }

      billetIdActuel = billetId;
      const modal = document.getElementById('modalDetailsBillet');
      const content = document.getElementById('detailsBilletContent');
      
      // Afficher le modal avec loader
      modal.classList.add('active');
      content.innerHTML = `
        <div style="text-align: center; padding: 40px;">
          <div class="spinner"></div>
          <p style="margin-top: 16px; color: #6b7280;">Chargement des détails...</p>
        </div>
      `;

      try {
        const response = await fetch(`billets/details?id=${billetId}`);
        const data = await response.json();

        if (data.success) {
          const billet = data.billet;
          billetDataActuel = billet;
          
          // Badges de statut
          const statutBadges = {
            'paye': { class: 'actif', label: 'Payé' },
            'utilise': { class: 'actif', label: 'Utilisé' },
            'reserve': { class: 'warning', label: 'Réservé' },
            'annule': { class: 'inactif', label: 'Annulé' },
            'expire': { class: 'inactif', label: 'Expiré' }
          };
          
          const statutInfo = statutBadges[billet.statut_billet] || { class: 'warning', label: billet.statut_billet };
          
          content.innerHTML = `
            <div class="billet-details">
              <!-- En-tête du billet -->
              <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; border-radius: 8px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                  <div>
                    <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">N° Billet</div>
                    <div style="font-size: 24px; font-weight: 700;">${billet.numero_billet}</div>
                  </div>
                  <span class="status-badge status-badge--${statutInfo.class}" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                    ${statutInfo.label}
                  </span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                  <div>
                    <div style="opacity: 0.8; margin-bottom: 4px;">Date de voyage</div>
                    <div style="font-weight: 600;">${new Date(billet.date_voyage).toLocaleDateString('fr-FR')}</div>
                  </div>
                  <div>
                    <div style="opacity: 0.8; margin-bottom: 4px;">Heure de départ</div>
                    <div style="font-weight: 600;">${billet.heure_depart || 'Non définie'}</div>
                  </div>
                </div>
              </div>

              <!-- Informations du trajet -->
              <div style="margin-bottom: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="map-pin" style="width: 16px; height: 16px;"></i> Trajet
                </h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border-left: 4px solid #667eea;">
                  <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 32px; height: 32px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px;">D</div>
                    <div style="flex: 1;">
                      <div style="font-size: 12px; color: #6b7280;">Départ</div>
                      <div style="font-weight: 600; color: #111827;">${billet.arret_depart}</div>
                    </div>
                  </div>
                  <div style="border-left: 2px dashed #d1d5db; height: 20px; margin-left: 15px;"></div>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; background: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px;">A</div>
                    <div style="flex: 1;">
                      <div style="font-size: 12px; color: #6b7280;">Arrivée</div>
                      <div style="font-weight: 600; color: #111827;">${billet.arret_arrivee}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informations du client -->
              <div style="margin-bottom: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="user" style="width: 16px; height: 16px;"></i> Informations client
                </h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Nom complet</div>
                      <div style="font-weight: 600;">${billet.client_nom || 'N/A'} ${billet.client_prenom || ''}</div>
                    </div>
                    <div>
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Téléphone</div>
                      <div style="font-weight: 600;">${billet.client_telephone || 'N/A'}</div>
                    </div>
                    ${billet.client_email ? `
                    <div style="grid-column: 1 / -1;">
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Email</div>
                      <div style="font-weight: 600;">${billet.client_email}</div>
                    </div>
                    ` : ''}
                  </div>
                </div>
              </div>

              <!-- Informations de paiement -->
              <div style="margin-bottom: 24px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="credit-card" style="width: 16px; height: 16px;"></i> Paiement
                </h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Montant payé</div>
                      <div style="font-weight: 700; font-size: 18px; color: #10b981;">${parseFloat(billet.prix_paye).toLocaleString('fr-FR')} ${billet.devise}</div>
                    </div>
                    <div>
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Mode de paiement</div>
                      <div style="font-weight: 600;">${billet.mode_paiement.replace('_', ' ')}</div>
                    </div>
                    ${billet.reference_paiement ? `
                    <div style="grid-column: 1 / -1;">
                      <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Référence</div>
                      <div style="font-weight: 600; font-family: monospace;">${billet.reference_paiement}</div>
                    </div>
                    ` : ''}
                  </div>
                </div>
              </div>

              <!-- Dates importantes -->
              <div>
                <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="clock" style="width: 16px; height: 16px;"></i> Dates
                </h4>
                <div style="background: #f9fafb; padding: 16px; border-radius: 8px;">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div>
                      <div style="color: #6b7280; margin-bottom: 4px;">Date d'achat</div>
                      <div style="font-weight: 600;">${new Date(billet.date_achat).toLocaleString('fr-FR')}</div>
                    </div>
                    ${billet.date_utilisation ? `
                    <div>
                      <div style="color: #6b7280; margin-bottom: 4px;">Date d'utilisation</div>
                      <div style="font-weight: 600;">${new Date(billet.date_utilisation).toLocaleString('fr-FR')}</div>
                    </div>
                    ` : ''}
                    ${billet.date_annulation ? `
                    <div style="grid-column: 1 / -1;">
                      <div style="color: #6b7280; margin-bottom: 4px;">Date d'annulation</div>
                      <div style="font-weight: 600; color: #ef4444;">${new Date(billet.date_annulation).toLocaleString('fr-FR')}</div>
                      ${billet.motif_annulation ? `<div style="margin-top: 8px; padding: 8px; background: #fee2e2; border-radius: 4px; color: #991b1b; font-size: 12px;"><strong>Motif:</strong> ${billet.motif_annulation}</div>` : ''}
                    </div>
                    ` : ''}
                  </div>
                </div>
              </div>
            </div>
          `;
          
          // Réinitialiser les icônes Feather
          feather.replace();
        } else {
          content.innerHTML = `
            <div style="text-align: center; padding: 40px;">
              <i data-feather="alert-circle" style="width: 48px; height: 48px; color: #ef4444; margin-bottom: 16px;"></i>
              <p style="color: #ef4444; font-weight: 600;">${data.message || 'Erreur lors du chargement'}</p>
            </div>
          `;
          feather.replace();
        }
      } catch (error) {
        console.error('Erreur:', error);
        content.innerHTML = `
          <div style="text-align: center; padding: 40px;">
            <i data-feather="alert-circle" style="width: 48px; height: 48px; color: #ef4444; margin-bottom: 16px;"></i>
            <p style="color: #ef4444; font-weight: 600;">Erreur de connexion</p>
          </div>
        `;
        feather.replace();
      }
    }

    // Fermer le modal détails
    function fermerModalBillet() {
      const modal = document.getElementById('modalDetailsBillet');
      modal.classList.remove('active');
      billetIdActuel = null;
      billetDataActuel = null;
    }

    // Générer et télécharger le PDF du billet (design du modal)
    async function imprimerBilletPDF(billetId) {
      if (!billetId) {
        alert('Erreur: ID du billet manquant');
        return;
      }
      
      try {
        // Récupérer les données du billet
        const response = await fetch(`billets/details?id=${billetId}`);
        const data = await response.json();
        
        if (!data.success) {
          alert('Erreur: ' + (data.message || 'Impossible de charger le billet'));
          return;
        }
        
        const billet = data.billet;
        
        // Générer le QR code
        const qrData = `${billet.numero_billet}|${billet.id}|${billet.date_voyage}`;
        
        const qrContainer = document.createElement('div');
        qrContainer.style.display = 'none';
        document.body.appendChild(qrContainer);
        
        const qrcode = new QRCode(qrContainer, {
          text: qrData,
          width: 200,
          height: 200,
          correctLevel: QRCode.CorrectLevel.L
        });
        
        await new Promise(resolve => setTimeout(resolve, 200));
        
        const qrImage = qrContainer.querySelector('img');
        const qrDataUrl = qrImage.src;
        
        // Créer le PDF format A5
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
          orientation: 'portrait',
          unit: 'mm',
          format: 'a5'
        });
        
        const pageWidth = 148;
        const pageHeight = 210;
        const margin = 12;
        let yPos = margin;
        
        // Couleurs (comme le modal)
        const violet = [102, 126, 234];
        const violetFonce = [118, 75, 162];
        const vert = [16, 185, 129];
        const rouge = [239, 68, 68];
        const gris = [107, 114, 128];
        const grisClair = [249, 250, 251];
        const noir = [17, 24, 39];
        
        // En-tête avec dégradé violet (simulé avec rectangle)
        doc.setFillColor(...violet);
        doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 35, 3, 3, 'F');
        
        // N° Billet
        yPos += 8;
        doc.setFontSize(8);
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'normal');
        doc.text('N° Billet', margin + 5, yPos);
        
        yPos += 7;
        doc.setFontSize(18);
        doc.setFont(undefined, 'bold');
        doc.text(billet.numero_billet, margin + 5, yPos);
        
        // Statut
        const statutLabels = {
          'paye': 'Payé',
          'utilise': 'Utilisé',
          'reserve': 'Réservé',
          'annule': 'Annulé',
          'expire': 'Expiré'
        };
        const statutLabel = statutLabels[billet.statut_billet] || billet.statut_billet;
        doc.setFontSize(9);
        doc.setFont(undefined, 'bold');
        const statutWidth = doc.getTextWidth(statutLabel);
        doc.setFillColor(255, 255, 255);
        doc.setDrawColor(255, 255, 255);
        doc.roundedRect(pageWidth - margin - statutWidth - 10, yPos - 6, statutWidth + 8, 8, 2, 2, 'FD');
        doc.setTextColor(...violet);
        doc.text(statutLabel, pageWidth - margin - statutWidth - 6, yPos - 1);
        
        // Date et heure
        yPos += 10;
        doc.setFontSize(7);
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'normal');
        doc.text('Date de voyage', margin + 5, yPos);
        doc.text('Heure de départ', pageWidth / 2 + 5, yPos);
        
        yPos += 5;
        doc.setFontSize(10);
        doc.setFont(undefined, 'bold');
        const dateVoyage = new Date(billet.date_voyage).toLocaleDateString('fr-FR');
        doc.text(dateVoyage, margin + 5, yPos);
        doc.text(billet.heure_depart || 'Non définie', pageWidth / 2 + 5, yPos);
        
        // Section Trajet
        yPos += 12;
        doc.setFontSize(10);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        doc.text('Trajet', margin + 5, yPos);
        
        yPos += 5;
        doc.setFillColor(...grisClair);
        doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 28, 3, 3, 'F');
        
        // Départ
        yPos += 7;
        doc.setFillColor(...vert);
        doc.circle(margin + 8, yPos, 4, 'F');
        doc.setFontSize(9);
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'bold');
        doc.text('D', margin + 8, yPos + 1, { align: 'center' });
        
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Départ', margin + 15, yPos - 2);
        
        doc.setFontSize(9);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        let arretDepart = billet.arret_depart;
        if (arretDepart.length > 40) arretDepart = arretDepart.substring(0, 40) + '...';
        doc.text(arretDepart, margin + 15, yPos + 3);
        
        // Ligne pointillée
        yPos += 5;
        doc.setDrawColor(209, 213, 219);
        doc.setLineDash([1, 1]);
        doc.line(margin + 8, yPos, margin + 8, yPos + 3);
        doc.setLineDash([]);
        
        // Arrivée
        yPos += 5;
        doc.setFillColor(...rouge);
        doc.circle(margin + 8, yPos, 4, 'F');
        doc.setFontSize(9);
        doc.setTextColor(255, 255, 255);
        doc.setFont(undefined, 'bold');
        doc.text('A', margin + 8, yPos + 1, { align: 'center' });
        
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Arrivée', margin + 15, yPos - 2);
        
        doc.setFontSize(9);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        let arretArrivee = billet.arret_arrivee;
        if (arretArrivee.length > 40) arretArrivee = arretArrivee.substring(0, 40) + '...';
        doc.text(arretArrivee, margin + 15, yPos + 3);
        
        // Section Client
        yPos += 12;
        doc.setFontSize(10);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        doc.text('Informations client', margin + 5, yPos);
        
        yPos += 5;
        doc.setFillColor(...grisClair);
        doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 18, 3, 3, 'F');
        
        yPos += 6;
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Nom complet', margin + 5, yPos);
        doc.text('Téléphone', pageWidth / 2 + 5, yPos);
        
        yPos += 5;
        doc.setFontSize(9);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        const nomComplet = (billet.client_nom || 'N/A') + ' ' + (billet.client_prenom || '');
        doc.text(nomComplet.substring(0, 25), margin + 5, yPos);
        doc.text(billet.client_telephone || 'N/A', pageWidth / 2 + 5, yPos);
        
        // Section Paiement
        yPos += 12;
        doc.setFontSize(10);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        doc.text('Paiement', margin + 5, yPos);
        
        yPos += 5;
        doc.setFillColor(...grisClair);
        doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 18, 3, 3, 'F');
        
        yPos += 6;
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Montant payé', margin + 5, yPos);
        doc.text('Mode de paiement', pageWidth / 2 + 5, yPos);
        
        yPos += 5;
        doc.setFontSize(11);
        doc.setTextColor(...vert);
        doc.setFont(undefined, 'bold');
        const montantValue = parseFloat(billet.prix_paye);
        const montantFormate = Math.round(montantValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        doc.text(montantFormate + ' ' + billet.devise, margin + 5, yPos);
        
        doc.setFontSize(9);
        doc.setTextColor(...noir);
        doc.text(billet.mode_paiement.replace('_', ' '), pageWidth / 2 + 5, yPos);
        
        // Section Dates
        yPos += 12;
        doc.setFontSize(10);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        doc.text('Dates', margin + 5, yPos);
        
        yPos += 5;
        doc.setFillColor(...grisClair);
        doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 12, 3, 3, 'F');
        
        yPos += 6;
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Date d\'achat', margin + 5, yPos);
        
        yPos += 4;
        doc.setFontSize(8);
        doc.setTextColor(...noir);
        doc.setFont(undefined, 'bold');
        doc.text(new Date(billet.date_achat).toLocaleString('fr-FR'), margin + 5, yPos);
        
        // QR Code
        yPos += 15;
        const qrSize = 35;
        const qrX = (pageWidth - qrSize) / 2;
        doc.addImage(qrDataUrl, 'PNG', qrX, yPos, qrSize, qrSize);
        
        yPos += qrSize + 3;
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Scannez pour vérifier', pageWidth / 2, yPos, { align: 'center' });
        
        // Pied de page
        yPos = pageHeight - 15;
        doc.setFontSize(7);
        doc.setTextColor(...gris);
        doc.setFont(undefined, 'normal');
        doc.text('Safari Transport • Kinshasa, RDC', pageWidth / 2, yPos, { align: 'center' });
        
        yPos += 4;
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...rouge);
        doc.text('DUPLICAT', pageWidth / 2, yPos, { align: 'center' });
        
        // Nettoyer le QR code temporaire
        document.body.removeChild(qrContainer);
        
        // Télécharger le PDF
        doc.save(`Billet_${billet.numero_billet}.pdf`);
        
      } catch (error) {
        alert('Erreur lors de la génération du PDF: ' + error.message);
      }
    }

    // Fermer le modal en cliquant en dehors
    document.addEventListener('click', function(e) {
      const modalDetails = document.getElementById('modalDetailsBillet');
      
      if (e.target === modalDetails) {
        fermerModalBillet();
      }
    });
  </script>
</body>
</html>
