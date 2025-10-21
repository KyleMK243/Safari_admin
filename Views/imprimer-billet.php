<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Billet <?= htmlspecialchars($billet['numero_billet']) ?> - Safari Transport</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: #f3f4f6;
      padding: 20px;
    }

    .ticket-container {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .ticket-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }

    .ticket-header h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .ticket-header p {
      font-size: 14px;
      opacity: 0.9;
    }

    .ticket-number {
      background: rgba(255, 255, 255, 0.2);
      padding: 12px 24px;
      border-radius: 8px;
      margin-top: 16px;
      display: inline-block;
    }

    .ticket-number span {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .ticket-body {
      padding: 30px;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.paye {
      background: #d1fae5;
      color: #065f46;
    }

    .status-badge.utilise {
      background: #dbeafe;
      color: #1e40af;
    }

    .status-badge.reserve {
      background: #fef3c7;
      color: #92400e;
    }

    .status-badge.annule {
      background: #fee2e2;
      color: #991b1b;
    }

    .ticket-section {
      margin-bottom: 30px;
    }

    .ticket-section h2 {
      font-size: 16px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 2px solid #e5e7eb;
    }

    .route-display {
      background: #f9fafb;
      padding: 24px;
      border-radius: 8px;
      border-left: 4px solid #667eea;
    }

    .route-point {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 16px;
    }

    .route-point:last-child {
      margin-bottom: 0;
    }

    .route-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: 16px;
      flex-shrink: 0;
    }

    .route-icon.depart {
      background: #10b981;
    }

    .route-icon.arrivee {
      background: #ef4444;
    }

    .route-divider {
      border-left: 2px dashed #d1d5db;
      height: 24px;
      margin-left: 19px;
    }

    .route-info {
      flex: 1;
    }

    .route-label {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 4px;
    }

    .route-value {
      font-size: 16px;
      font-weight: 600;
      color: #111827;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .info-item {
      background: #f9fafb;
      padding: 16px;
      border-radius: 8px;
    }

    .info-label {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 6px;
    }

    .info-value {
      font-size: 15px;
      font-weight: 600;
      color: #111827;
    }

    .price-display {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
    }

    .price-label {
      font-size: 14px;
      opacity: 0.9;
      margin-bottom: 8px;
    }

    .price-value {
      font-size: 32px;
      font-weight: 700;
    }

    .ticket-footer {
      background: #f9fafb;
      padding: 20px 30px;
      border-top: 2px dashed #d1d5db;
      text-align: center;
      font-size: 12px;
      color: #6b7280;
    }

    .duplicat-watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 72px;
      font-weight: 700;
      color: rgba(239, 68, 68, 0.1);
      pointer-events: none;
      z-index: 1;
    }

    @media print {
      body {
        background: white;
        padding: 0;
      }

      .ticket-container {
        box-shadow: none;
        border-radius: 0;
      }

      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="ticket-container">
    <div class="duplicat-watermark">DUPLICAT</div>
    
    <!-- En-tête -->
    <div class="ticket-header">
      <h1>🚌 Safari Transport</h1>
      <p>Votre partenaire de confiance pour vos déplacements</p>
      <div class="ticket-number">
        <span><?= htmlspecialchars($billet['numero_billet']) ?></span>
      </div>
    </div>

    <!-- Corps du billet -->
    <div class="ticket-body">
      <!-- Statut -->
      <div style="text-align: center; margin-bottom: 30px;">
        <?php
        $statutClass = match($billet['statut_billet']) {
          'paye' => 'paye',
          'utilise' => 'utilise',
          'reserve' => 'reserve',
          'annule' => 'annule',
          default => 'reserve'
        };
        
        $statutLabel = match($billet['statut_billet']) {
          'paye' => 'Payé',
          'utilise' => 'Utilisé',
          'reserve' => 'Réservé',
          'annule' => 'Annulé',
          'expire' => 'Expiré',
          default => $billet['statut_billet']
        };
        ?>
        <span class="status-badge <?= $statutClass ?>"><?= $statutLabel ?></span>
      </div>

      <!-- Informations du trajet -->
      <div class="ticket-section">
        <h2>📍 Itinéraire</h2>
        <div class="route-display">
          <div class="route-point">
            <div class="route-icon depart">D</div>
            <div class="route-info">
              <div class="route-label">Point de départ</div>
              <div class="route-value"><?= htmlspecialchars($billet['arret_depart']) ?></div>
            </div>
          </div>
          <div class="route-divider"></div>
          <div class="route-point">
            <div class="route-icon arrivee">A</div>
            <div class="route-info">
              <div class="route-label">Point d'arrivée</div>
              <div class="route-value"><?= htmlspecialchars($billet['arret_arrivee']) ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Informations du voyage -->
      <div class="ticket-section">
        <h2>🗓️ Détails du voyage</h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Date de voyage</div>
            <div class="info-value"><?= date('d/m/Y', strtotime($billet['date_voyage'])) ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Heure de départ</div>
            <div class="info-value"><?= $billet['heure_depart'] ?: 'Non définie' ?></div>
          </div>
          <?php if ($billet['trajet_nom']) : ?>
          <div class="info-item">
            <div class="info-label">Ligne</div>
            <div class="info-value"><?= htmlspecialchars($billet['trajet_nom']) ?></div>
          </div>
          <?php endif; ?>
          <?php if ($billet['siege_numero']) : ?>
          <div class="info-item">
            <div class="info-label">Siège</div>
            <div class="info-value"><?= htmlspecialchars($billet['siege_numero']) ?></div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Informations du passager -->
      <div class="ticket-section">
        <h2>👤 Informations du passager</h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Nom complet</div>
            <div class="info-value">
              <?= htmlspecialchars($billet['client_nom'] ?: 'N/A') ?> 
              <?= htmlspecialchars($billet['client_prenom'] ?: '') ?>
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Téléphone</div>
            <div class="info-value"><?= htmlspecialchars($billet['client_telephone'] ?: 'N/A') ?></div>
          </div>
        </div>
      </div>

      <!-- Tarif -->
      <div class="ticket-section">
        <h2>💰 Tarif</h2>
        <div class="price-display">
          <div class="price-label">Montant payé</div>
          <div class="price-value">
            <?= number_format($billet['prix_paye'], 0, ',', ' ') ?> <?= htmlspecialchars($billet['devise']) ?>
          </div>
        </div>
        <div style="margin-top: 16px;">
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Mode de paiement</div>
              <div class="info-value"><?= ucfirst(str_replace('_', ' ', $billet['mode_paiement'])) ?></div>
            </div>
            <div class="info-item">
              <div class="info-label">Date d'achat</div>
              <div class="info-value"><?= date('d/m/Y à H:i', strtotime($billet['date_achat'])) ?></div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($billet['date_annulation']) : ?>
      <!-- Annulation -->
      <div class="ticket-section">
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px;">
          <h3 style="color: #991b1b; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
            ⚠️ Billet annulé
          </h3>
          <p style="color: #7f1d1d; font-size: 13px; margin-bottom: 4px;">
            <strong>Date d'annulation :</strong> <?= date('d/m/Y à H:i', strtotime($billet['date_annulation'])) ?>
          </p>
          <?php if ($billet['motif_annulation']) : ?>
          <p style="color: #7f1d1d; font-size: 13px;">
            <strong>Motif :</strong> <?= htmlspecialchars($billet['motif_annulation']) ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Pied de page -->
    <div class="ticket-footer">
      <p><strong>DUPLICAT - Ce document est une copie du billet original</strong></p>
      <p style="margin-top: 8px;">
        Safari Transport • Kinshasa, RDC<br>
        Tél: +243 XXX XXX XXX • Email: contact@safari.cd<br>
        Imprimé le <?= date('d/m/Y à H:i') ?>
      </p>
    </div>
  </div>

  <div class="no-print" style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()" style="background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
      🖨️ Imprimer
    </button>
    <button onclick="window.close()" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-left: 12px;">
      ✕ Fermer
    </button>
  </div>
</body>
</html>
