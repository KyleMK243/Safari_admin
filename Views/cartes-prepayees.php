<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Cartes Prépayées • Safari</title>
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
          <h1>Gestion des Cartes Prépayées</h1>
          <p>Création, recharge et suivi des cartes clients</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" onclick="window.location.href='<?php echo BASE_URL; ?>/nouvelle-carte'">
            <i data-feather="plus"></i> Nouvelle carte
          </button>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <section class="bi-stats">
        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--blue">
            <i data-feather="credit-card"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Cartes actives</div>
            <div class="bi-stat-card__value">156</div>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--green">
            <i data-feather="dollar-sign"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Solde total</div>
            <div class="bi-stat-card__value">2,450,000 CDF</div>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--yellow">
            <i data-feather="trending-up"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Recharges ce mois</div>
            <div class="bi-stat-card__value">342</div>
          </div>
        </div>

        <div class="bi-stat-card">
          <div class="bi-stat-card__icon bi-stat-card__icon--red">
            <i data-feather="alert-circle"></i>
          </div>
          <div class="bi-stat-card__content">
            <div class="bi-stat-card__label">Cartes bloquées</div>
            <div class="bi-stat-card__value">8</div>
          </div>
        </div>
      </section>

      <!-- Message en cours de développement -->
      <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin: 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
          Fonctionnalité en cours de développement
        </p>
      </div>

      <!-- Filtres -->
      <section class="card" style="margin-bottom: 20px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Rechercher</label>
              <input type="text" class="form-control" placeholder="N° carte, nom client...">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de carte</label>
              <select class="form-control">
                <option value="">Tous les types</option>
                <option>Standard</option>
                <option>Étudiant</option>
                <option>Entreprise</option>
                <option>Senior</option>
                <option>VIP</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control">
                <option value="">Tous les statuts</option>
                <option>Active</option>
                <option>Bloquée</option>
                <option>Expirée</option>
                <option>Désactivée</option>
              </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
              <button class="btn btn--primary" style="width: 100%;">
                <i data-feather="search"></i> Rechercher
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Tableau des cartes -->
      <section class="card">
        <div class="card__header">
          <h3>Liste des cartes (156 résultats)</h3>
          <button class="btn btn--secondary btn--sm">
            <i data-feather="download"></i> Exporter
          </button>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>N° Carte</th>
              <th>Type</th>
              <th>Titulaire</th>
              <th>Contact</th>
              <th>Solde actuel</th>
              <th>Réduction</th>
              <th>Date activation</th>
              <th>Expiration</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>CARD-2025-00156</strong></td>
              <td><span class="badge badge--primary">Standard</span></td>
              <td>Jean Mukendi</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">j.mukendi@email.cd</small></td>
              <td><strong style="color: #10b981;">25,000 CDF</strong></td>
              <td>0%</td>
              <td>01/01/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00155</strong></td>
              <td><span class="badge badge--info">Étudiant</span></td>
              <td>Marie Tshala</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">m.tshala@email.cd</small></td>
              <td><strong style="color: #10b981;">15,500 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">15%</span></td>
              <td>15/02/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00154</strong></td>
              <td><span class="badge badge--success">Entreprise</span></td>
              <td>Paul Nsimba</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">Entreprise ABC</small></td>
              <td><strong style="color: #10b981;">85,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">20%</span></td>
              <td>10/03/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00153</strong></td>
              <td><span class="badge badge--warning">Senior</span></td>
              <td>Grace Lumbu</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">g.lumbu@email.cd</small></td>
              <td><strong style="color: #10b981;">12,300 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">10%</span></td>
              <td>05/04/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00152</strong></td>
              <td><span class="badge" style="background: #8b5cf6; color: white;">VIP</span></td>
              <td>Joseph Kabila</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">j.kabila@email.cd</small></td>
              <td><strong style="color: #10b981;">150,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">25%</span></td>
              <td>20/01/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00151</strong></td>
              <td><span class="badge badge--primary">Standard</span></td>
              <td>Alice Mbuyi</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">a.mbuyi@email.cd</small></td>
              <td><strong style="color: #f59e0b;">500 CDF</strong></td>
              <td>0%</td>
              <td>12/05/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00150</strong></td>
              <td><span class="badge badge--info">Étudiant</span></td>
              <td>Patrick Lumumba</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">Université de Kinshasa</small></td>
              <td><strong style="color: #10b981;">8,750 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">15%</span></td>
              <td>08/06/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00149</strong></td>
              <td><span class="badge badge--primary">Standard</span></td>
              <td>Sophie Kambale</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">s.kambale@email.cd</small></td>
              <td><strong style="color: #ef4444;">0 CDF</strong></td>
              <td>0%</td>
              <td>22/07/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--warning">Solde faible</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00148</strong></td>
              <td><span class="badge badge--success">Entreprise</span></td>
              <td>Daniel Ilunga</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">Société XYZ</small></td>
              <td><strong style="color: #10b981;">120,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">20%</span></td>
              <td>18/08/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--actif">Active</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Recharger">
                    <i data-feather="plus-circle"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Bloquer">
                    <i data-feather="lock"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>CARD-2025-00147</strong></td>
              <td><span class="badge badge--primary">Standard</span></td>
              <td>Esther Mpiana</td>
              <td>+243 XXX XXX XXX<br><small style="color: #6b7280;">e.mpiana@email.cd</small></td>
              <td><strong style="color: #6b7280;">0 CDF</strong></td>
              <td>0%</td>
              <td>30/09/2025</td>
              <td>31/12/2025</td>
              <td><span class="status-badge status-badge--danger">Bloquée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--edit" title="Débloquer">
                    <i data-feather="unlock"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb;">
          <div style="color: #6b7280; font-size: 14px;">
            Affichage de <strong>1-10</strong> sur <strong>156</strong> résultats
          </div>
          <div style="display: flex; gap: 8px;">
            <button class="btn btn--secondary btn--sm" disabled>
              <i data-feather="chevron-left"></i> Précédent
            </button>
            <button class="btn btn--secondary btn--sm active" style="background: #1B4B7F; color: white;">1</button>
            <button class="btn btn--secondary btn--sm">2</button>
            <button class="btn btn--secondary btn--sm">3</button>
            <button class="btn btn--secondary btn--sm">4</button>
            <button class="btn btn--secondary btn--sm">5</button>
            <span style="padding: 0 8px; color: #6b7280;">...</span>
            <button class="btn btn--secondary btn--sm">16</button>
            <button class="btn btn--secondary btn--sm">
              Suivant <i data-feather="chevron-right"></i>
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Recharger Carte -->
  <div class="modal" id="modalRechargerCarte">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2>Recharger la carte</h2>
        <button class="modal__close" id="closeModalRecharge">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <div style="background: #f3f4f6; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #6b7280;">N° Carte:</span>
            <strong>CARD-2025-00156</strong>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span style="color: #6b7280;">Titulaire:</span>
            <strong>Jean Mukendi</strong>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: #6b7280;">Solde actuel:</span>
            <strong style="color: #10b981; font-size: 18px;">25,000 CDF</strong>
          </div>
        </div>

        <form id="formRechargerCarte">
          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Montant de la recharge (CDF) *</label>
            <input type="number" class="form-control" placeholder="10000" min="500" required>
            <small style="color: #6b7280; display: block; margin-top: 6px;">Montant minimum: 500 CDF</small>
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Mode de paiement *</label>
            <select class="form-control" required>
              <option value="">Sélectionner...</option>
              <option value="especes">Espèces</option>
              <option value="mobile_money">Mobile Money</option>
              <option value="carte_bancaire">Carte bancaire</option>
              <option value="virement">Virement bancaire</option>
            </select>
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Référence de paiement</label>
            <input type="text" class="form-control" placeholder="Ex: TRX123456789">
          </div>

          <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Frais de recharge (CDF)</label>
            <input type="number" class="form-control" placeholder="0" min="0" value="0">
          </div>

          <div style="background: #dbeafe; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between;">
              <span style="color: #1e40af; font-weight: 600;">Nouveau solde:</span>
              <strong style="color: #1e40af; font-size: 20px;">35,000 CDF</strong>
            </div>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelRecharge">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="plus-circle"></i> Confirmer la recharge
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Voir Détails Carte -->
  <div class="modal" id="modalVoirCarte">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 700px;">
      <div class="modal__header">
        <h2>Détails de la carte</h2>
        <button class="modal__close" id="closeModalVoir">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <!-- Informations principales -->
        <div style="background: linear-gradient(135deg, #1B4B7F 0%, #0F3154 100%); padding: 24px; border-radius: 12px; margin-bottom: 24px; color: white;">
          <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div>
              <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">N° Carte</div>
              <div style="font-size: 20px; font-weight: 700;">CARD-2025-00156</div>
            </div>
            <span class="badge" style="background: #10b981; color: white; padding: 6px 12px;">Active</span>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: end;">
            <div>
              <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">Solde disponible</div>
              <div style="font-size: 32px; font-weight: 800; color: #FDB913;">25,000 CDF</div>
            </div>
            <div style="text-align: right;">
              <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">Type</div>
              <div style="font-size: 16px; font-weight: 600;">Standard</div>
            </div>
          </div>
        </div>

        <!-- Informations du titulaire -->
        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1B4B7F;">Informations du titulaire</h3>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Nom complet</div>
              <div style="font-weight: 600;">Jean Mukendi</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Téléphone</div>
              <div style="font-weight: 600;">+243 XXX XXX XXX</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Email</div>
              <div style="font-weight: 600;">j.mukendi@email.cd</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Type de client</div>
              <div style="font-weight: 600;">Particulier</div>
            </div>
          </div>
        </div>

        <!-- Détails de la carte -->
        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1B4B7F;">Détails de la carte</h3>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Date d'activation</div>
              <div style="font-weight: 600;">01/01/2025</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Date d'expiration</div>
              <div style="font-weight: 600;">31/12/2025</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Plafond journalier</div>
              <div style="font-weight: 600;">50,000 CDF</div>
            </div>
            <div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Réduction</div>
              <div style="font-weight: 600; color: #10b981;">0%</div>
            </div>
          </div>
        </div>

        <!-- Statistiques d'utilisation -->
        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1B4B7F;">Statistiques d'utilisation</h3>
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
            <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; text-align: center;">
              <div style="font-size: 24px; font-weight: 800; color: #1B4B7F;">47</div>
              <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Billets achetés</div>
            </div>
            <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; text-align: center;">
              <div style="font-size: 24px; font-weight: 800; color: #10b981;">12</div>
              <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Recharges</div>
            </div>
            <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; text-align: center;">
              <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">235,000 CDF</div>
              <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Total dépensé</div>
            </div>
          </div>
        </div>

        <!-- Dernières transactions -->
        <div>
          <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1B4B7F;">Dernières transactions</h3>
          <div style="max-height: 200px; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #e5e7eb;">
              <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Recharge</div>
                <div style="font-size: 12px; color: #6b7280;">07/10/2025 - 14:30</div>
              </div>
              <div style="font-weight: 700; color: #10b981;">+10,000 CDF</div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #e5e7eb;">
              <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Achat billet</div>
                <div style="font-size: 12px; color: #6b7280;">06/10/2025 - 10:15</div>
              </div>
              <div style="font-weight: 700; color: #ef4444;">-5,000 CDF</div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #e5e7eb;">
              <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Recharge</div>
                <div style="font-size: 12px; color: #6b7280;">05/10/2025 - 16:45</div>
              </div>
              <div style="font-weight: 700; color: #10b981;">+20,000 CDF</div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px;">
              <div>
                <div style="font-weight: 600; margin-bottom: 2px;">Achat billet</div>
                <div style="font-size: 12px; color: #6b7280;">04/10/2025 - 08:20</div>
              </div>
              <div style="font-weight: 700; color: #ef4444;">-3,500 CDF</div>
            </div>
          </div>
        </div>

        <div class="modal__actions" style="margin-top: 24px;">
          <button type="button" class="btn btn--secondary" id="closeVoir">Fermer</button>
          <button type="button" class="btn btn--primary" onclick="window.print()">
            <i data-feather="printer"></i> Imprimer
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      // Modal Recharger Carte
      const modalRechargerCarte = document.getElementById('modalRechargerCarte');
      const closeModalRecharge = document.getElementById('closeModalRecharge');
      const cancelRecharge = document.getElementById('cancelRecharge');
      const btnsRecharge = document.querySelectorAll('[title="Recharger"]');

      btnsRecharge.forEach(btn => {
        btn.addEventListener('click', () => {
          modalRechargerCarte.classList.add('active');
          feather.replace();
        });
      });

      closeModalRecharge?.addEventListener('click', () => {
        modalRechargerCarte.classList.remove('active');
      });

      cancelRecharge?.addEventListener('click', () => {
        modalRechargerCarte.classList.remove('active');
      });

      // Modal Voir Détails
      const modalVoirCarte = document.getElementById('modalVoirCarte');
      const closeModalVoir = document.getElementById('closeModalVoir');
      const closeVoir = document.getElementById('closeVoir');
      const btnsVoir = document.querySelectorAll('[title="Voir"]');

      btnsVoir.forEach(btn => {
        btn.addEventListener('click', () => {
          modalVoirCarte.classList.add('active');
          feather.replace();
        });
      });

      closeModalVoir?.addEventListener('click', () => {
        modalVoirCarte.classList.remove('active');
      });

      closeVoir?.addEventListener('click', () => {
        modalVoirCarte.classList.remove('active');
      });

      // Fermer les modals en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });

      // Formulaire Recharger Carte
      document.getElementById('formRechargerCarte')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Recharge effectuée avec succès !');
        modalRechargerCarte.classList.remove('active');
      });
    });
  </script>
</body>
</html>
