<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Historique des Réservations • Safari</title>
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
          <h1>Historique des Réservations</h1>
          <p>Consultation de toutes les réservations effectuées</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary">
            <i data-feather="download"></i> Exporter
          </button>
        </div>
      </header>

      <!-- Filtres -->
      <section class="card" style="margin-bottom: 20px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date début</label>
              <input type="date" class="form-control" value="2025-10-01">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date fin</label>
              <input type="date" class="form-control" value="2025-10-07">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control">
                <option value="">Tous les statuts</option>
                <option>En attente</option>
                <option>Confirmée</option>
                <option>Payée</option>
                <option>Annulée</option>
                <option>Expirée</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Trajet</label>
              <select class="form-control">
                <option value="">Tous les trajets</option>
                <option>Kinshasa → Matadi</option>
                <option>Kinshasa → Lubumbashi</option>
                <option>Kinshasa → Kikwit</option>
                <option>Kinshasa → Kananga</option>
              </select>
            </div>
          </div>
          <div style="margin-top: 16px; display: flex; gap: 12px;">
            <button class="btn btn--primary">
              <i data-feather="search"></i> Rechercher
            </button>
            <button class="btn btn--secondary">
              <i data-feather="refresh-cw"></i> Réinitialiser
            </button>
          </div>
        </div>
      </section>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total réservations</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;">542</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">En attente</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;">18</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Confirmées</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;">487</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Annulées</div>
          <div style="font-size: 32px; font-weight: 800; color: #ef4444;">25</div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Expirées</div>
          <div style="font-size: 32px; font-weight: 800; color: #6b7280;">12</div>
        </div>
      </div>

      <!-- Tableau des réservations -->
      <section class="card">
        <div class="card__header">
          <h3>Liste des réservations (542 résultats)</h3>
        </div>
        
        <div style="overflow-x: auto;">
          <table class="table" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>N° Réservation</th>
              <th>Date création</th>
              <th>Client</th>
              <th>Trajet</th>
              <th>Date voyage</th>
              <th>Places</th>
              <th>Montant</th>
              <th>Expiration</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>RES-2025-00456</strong></td>
              <td>07/10/2025<br><small style="color: #6b7280;">14:20</small></td>
              <td>Grace Lumbu<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Matadi</td>
              <td>09/10/2025 - 06:00</td>
              <td><span class="badge badge--info">2 places</span></td>
              <td><strong>10,000 CDF</strong></td>
              <td><span class="badge badge--warning">2h 15min</span></td>
              <td><span class="status-badge status-badge--warning">En attente</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Confirmer">
                    <i data-feather="check"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Annuler">
                    <i data-feather="x"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00455</strong></td>
              <td>07/10/2025<br><small style="color: #6b7280;">13:45</small></td>
              <td>Joseph Kabila<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Lubumbashi</td>
              <td>09/10/2025 - 08:00</td>
              <td><span class="badge badge--info">1 place</span></td>
              <td><strong>7,000 CDF</strong></td>
              <td><span class="badge badge--warning">1h 45min</span></td>
              <td><span class="status-badge status-badge--warning">En attente</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--edit" title="Confirmer">
                    <i data-feather="check"></i>
                  </button>
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--delete" title="Annuler">
                    <i data-feather="x"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00454</strong></td>
              <td>07/10/2025<br><small style="color: #6b7280;">12:30</small></td>
              <td>Marie Nkulu<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Kikwit</td>
              <td>08/10/2025 - 14:00</td>
              <td><span class="badge badge--info">3 places</span></td>
              <td><strong>12,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00453</strong></td>
              <td>07/10/2025<br><small style="color: #6b7280;">11:15</small></td>
              <td>Patrick Mulamba<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Matadi</td>
              <td>08/10/2025 - 08:00</td>
              <td><span class="badge badge--info">1 place</span></td>
              <td><strong>5,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00452</strong></td>
              <td>06/10/2025<br><small style="color: #6b7280;">16:50</small></td>
              <td>Sophie Kasongo<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Kananga</td>
              <td>08/10/2025 - 10:00</td>
              <td><span class="badge badge--info">2 places</span></td>
              <td><strong>13,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00451</strong></td>
              <td>06/10/2025<br><small style="color: #6b7280;">15:22</small></td>
              <td>Daniel Ilunga<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Lubumbashi</td>
              <td>07/10/2025 - 12:00</td>
              <td><span class="badge badge--info">1 place</span></td>
              <td><strong>3,500 CDF</strong></td>
              <td><span style="color: #ef4444; font-weight: 600;">Expirée</span></td>
              <td><span class="status-badge status-badge--inactif">Expirée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00450</strong></td>
              <td>06/10/2025<br><small style="color: #6b7280;">14:08</small></td>
              <td>Alice Mbuyi<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Matadi</td>
              <td>07/10/2025 - 08:00</td>
              <td><span class="badge badge--info">4 places</span></td>
              <td><strong>20,000 CDF</strong></td>
              <td><span style="color: #ef4444; font-weight: 600;">Annulée</span></td>
              <td><span class="status-badge status-badge--danger">Annulée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00449</strong></td>
              <td>06/10/2025<br><small style="color: #6b7280;">13:35</small></td>
              <td>Jean Mukendi<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Kikwit</td>
              <td>08/10/2025 - 14:00</td>
              <td><span class="badge badge--info">2 places</span></td>
              <td><strong>8,000 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00448</strong></td>
              <td>06/10/2025<br><small style="color: #6b7280;">12:10</small></td>
              <td>Esther Mpiana<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Lubumbashi</td>
              <td>09/10/2025 - 10:00</td>
              <td><span class="badge badge--info">1 place</span></td>
              <td><strong>3,500 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td><strong>RES-2025-00447</strong></td>
              <td>05/10/2025<br><small style="color: #6b7280;">17:45</small></td>
              <td>Paul Nsimba<br><small style="color: #6b7280;">+243 XXX XXX XXX</small></td>
              <td>Kinshasa → Kananga</td>
              <td>07/10/2025 - 08:00</td>
              <td><span class="badge badge--info">3 places</span></td>
              <td><strong>19,500 CDF</strong></td>
              <td><span style="color: #10b981; font-weight: 600;">Confirmée</span></td>
              <td><span class="status-badge status-badge--actif">Payée</span></td>
              <td>
                <div class="action-buttons">
                  <button class="btn-icon btn-icon--view" title="Voir">
                    <i data-feather="eye"></i>
                  </button>
                  <button class="btn-icon btn-icon--print" title="Imprimer">
                    <i data-feather="printer"></i>
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
            Affichage de <strong>1-10</strong> sur <strong>542</strong> résultats
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
            <button class="btn btn--secondary btn--sm">55</button>
            <button class="btn btn--secondary btn--sm">
              Suivant <i data-feather="chevron-right"></i>
            </button>
          </div>
        </div>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
    });
  </script>
</body>
</html>
