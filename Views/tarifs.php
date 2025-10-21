<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion des Tarifs • Safari</title>
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
          <h1>Gestion des Tarifs</h1>
          <p>Configuration des prix par trajet et type de client</p>
        </div>
        <!-- <div class="header__actions">
          <button class="btn btn--primary" id="btnNouveauTarif">
            <i data-feather="plus"></i> Nouveau tarif
          </button>
        </div> -->
      </header>

      <!-- Messages de succès/erreur -->
      <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #d1fae5; border: 1px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #065f46; font-weight: 600;">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </p>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #fee2e2; border: 1px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
          <p style="margin: 0; color: #991b1b; font-weight: 600;">
            <?= htmlspecialchars($_SESSION['error']) ?>
          </p>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Onglets -->
      <div class="tabs">
        <button class="tab-btn active" data-tab="trajets">
          <i data-feather="map"></i> Tarifs par trajet
        </button>
        <button class="tab-btn" data-tab="categories">
          <i data-feather="users"></i> Catégories de clients
        </button>
        <button class="tab-btn" data-tab="promotions">
          <i data-feather="percent"></i> Promotions
        </button>
      </div>

      <!-- Onglet Tarifs par trajet -->
      <div class="tab-content active" id="tab-trajets">
        <!-- Statistiques rapides -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total trajets</div>
            <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;"><?= $stats['total_trajets'] ?? 0 ?></div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Prix moyen</div>
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= number_format($stats['prix_moyen'] ?? 0, 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Prix min</div>
            <div style="font-size: 32px; font-weight: 800; color: #3b82f6;"><?= number_format($stats['prix_min'] ?? 0, 0, ',', ' ') ?> CDF</div>
          </div>
          <div class="card" style="padding: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Prix max</div>
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= number_format($stats['prix_max'] ?? 0, 0, ',', ' ') ?> CDF</div>
          </div>
        </div>

        <!-- Tableau des tarifs -->
        <section class="card">
          <div class="card__header">
            <h3>Liste des tarifs par trajet</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table" id="tableTarifs">
              <thead>
                <tr>
                  <th>Trajet</th>
                  <th>Distance (km)</th>
                  <th>Durée estimée</th>
                  <th>Prix Normal</th>
                  <th>Étudiant (-15%)</th>
                  <th>Senior (-10%)</th>
                  <th>Enfant (-20%)</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($trajetsAvecTarifs)): ?>
                  <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #9ca3af;">
                      <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 12px;"></i>
                      <p style="margin: 0;">Aucun tarif configuré</p>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($trajetsAvecTarifs as $trajet): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($trajet['nom']) ?></strong></td>
                      <td><?= number_format($trajet['distance_totale'] ?? 0, 0, ',', ' ') ?> km</td>
                      <td><?= htmlspecialchars($trajet['duree_estimee'] ?? '-') ?> min</td>
                      <td>
                        <?php if (isset($trajet['tarifs']['normal'])): ?>
                          <strong style="color: #1B4B7F;"><?= number_format($trajet['tarifs']['normal']['prix'], 0, ',', ' ') ?> CDF</strong>
                        <?php else: ?>
                          <span style="color: #9ca3af;">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (isset($trajet['tarifs']['etudiant'])): ?>
                          <?= number_format($trajet['tarifs']['etudiant']['prix'], 0, ',', ' ') ?> CDF
                        <?php else: ?>
                          <span style="color: #9ca3af;">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (isset($trajet['tarifs']['senior'])): ?>
                          <?= number_format($trajet['tarifs']['senior']['prix'], 0, ',', ' ') ?> CDF
                        <?php else: ?>
                          <span style="color: #9ca3af;">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (isset($trajet['tarifs']['enfant'])): ?>
                          <?= number_format($trajet['tarifs']['enfant']['prix'], 0, ',', ' ') ?> CDF
                        <?php else: ?>
                          <span style="color: #9ca3af;">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="status-badge status-badge--<?= $trajet['statut'] ?>">
                          <?= ucfirst($trajet['statut']) ?>
                        </span>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <?php if (isset($trajet['tarifs']['normal'])): ?>
                            <button class="btn-icon btn-icon--edit btn-modifier-tarif" title="Modifier les tarifs" 
                                    data-trajet-id="<?= $trajet['id'] ?>" 
                                    data-trajet-nom="<?= htmlspecialchars($trajet['nom'], ENT_QUOTES) ?>"
                                    data-tarifs='<?= json_encode($trajet['tarifs'], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                              <i data-feather="edit-2"></i>
                            </button>
                          <?php else: ?>
                            <button class="btn-icon btn-icon--view btn-creer-auto" title="Créer tarifs auto" 
                                    data-trajet-id="<?= $trajet['id'] ?>" 
                                    data-trajet-nom="<?= htmlspecialchars($trajet['nom'], ENT_QUOTES) ?>">
                              <i data-feather="zap"></i>
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-top: 1px solid #e5e7eb;">
            <div style="color: #6b7280; font-size: 14px;">
              Affichage de <strong id="paginationStart">1</strong> à <strong id="paginationEnd">10</strong> sur <strong id="paginationTotal"><?= count($trajetsAvecTarifs) ?></strong> trajets
            </div>
            <div style="display: flex; gap: 8px;" id="paginationControls">
              <button class="btn btn--secondary" id="btnPrevPage" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
              <div style="display: flex; gap: 4px;" id="paginationNumbers">
                <!-- Les numéros de page seront générés par JavaScript -->
              </div>
              <button class="btn btn--secondary" id="btnNextPage">
                Suivant <i data-feather="chevron-right"></i>
              </button>
            </div>
          </div>
        </section>
      </div>

      <!-- Onglet Catégories de clients -->
      <div class="tab-content" id="tab-categories">
        <section class="card">
          <div class="card__header">
            <h3>Configuration des catégories</h3>
          </div>
          <div style="padding: 24px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
              <!-- Étudiant -->
              <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                  <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; display: grid; place-items: center;">
                    <i data-feather="book" style="width: 24px; height: 24px; color: #1B4B7F;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0; font-size: 18px; font-weight: 700;">Étudiant</h4>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Carte étudiant requise</p>
                  </div>
                </div>
                <div style="margin-bottom: 16px;">
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Pourcentage de réduction</label>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" min="0" max="50" value="15" class="slider" id="reductionEtudiant" style="flex: 1;">
                    <span id="valeurEtudiant" style="font-weight: 700; font-size: 20px; color: #1B4B7F; min-width: 60px;">15%</span>
                  </div>
                </div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Exemple : Trajet 5,000 CDF</div>
                  <div style="font-weight: 700; font-size: 16px; color: #10b981;">Prix étudiant : <span id="exempleEtudiant">4,250 CDF</span></div>
                </div>
                <button class="btn btn--primary" style="width: 100%; margin-top: 16px;">
                  <i data-feather="save"></i> Enregistrer
                </button>
              </div>

              <!-- Senior -->
              <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                  <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: grid; place-items: center;">
                    <i data-feather="user-check" style="width: 24px; height: 24px; color: #f59e0b;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0; font-size: 18px; font-weight: 700;">Senior</h4>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">60 ans et plus</p>
                  </div>
                </div>
                <div style="margin-bottom: 16px;">
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Pourcentage de réduction</label>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" min="0" max="50" value="10" class="slider" id="reductionSenior" style="flex: 1;">
                    <span id="valeurSenior" style="font-weight: 700; font-size: 20px; color: #1B4B7F; min-width: 60px;">10%</span>
                  </div>
                </div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Exemple : Trajet 5,000 CDF</div>
                  <div style="font-weight: 700; font-size: 16px; color: #10b981;">Prix senior : <span id="exempleSenior">4,500 CDF</span></div>
                </div>
                <button class="btn btn--primary" style="width: 100%; margin-top: 16px;">
                  <i data-feather="save"></i> Enregistrer
                </button>
              </div>

              <!-- Enfant -->
              <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                  <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; display: grid; place-items: center;">
                    <i data-feather="smile" style="width: 24px; height: 24px; color: #10b981;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0; font-size: 18px; font-weight: 700;">Enfant</h4>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Moins de 12 ans</p>
                  </div>
                </div>
                <div style="margin-bottom: 16px;">
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Pourcentage de réduction</label>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" min="0" max="50" value="20" class="slider" id="reductionEnfant" style="flex: 1;">
                    <span id="valeurEnfant" style="font-weight: 700; font-size: 20px; color: #1B4B7F; min-width: 60px;">20%</span>
                  </div>
                </div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Exemple : Trajet 5,000 CDF</div>
                  <div style="font-weight: 700; font-size: 16px; color: #10b981;">Prix enfant : <span id="exempleEnfant">4,000 CDF</span></div>
                </div>
                <button class="btn btn--primary" style="width: 100%; margin-top: 16px;">
                  <i data-feather="save"></i> Enregistrer
                </button>
              </div>

              <!-- Touriste -->
              <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                  <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: grid; place-items: center;">
                    <i data-feather="camera" style="width: 24px; height: 24px; color: #f59e0b;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0; font-size: 18px; font-weight: 700;">Touriste</h4>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Pass découverte</p>
                  </div>
                </div>
                <div style="margin-bottom: 16px;">
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Pourcentage de réduction</label>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" min="0" max="50" value="5" class="slider" id="reductionTouriste" style="flex: 1;">
                    <span id="valeurTouriste" style="font-weight: 700; font-size: 20px; color: #1B4B7F; min-width: 60px;">5%</span>
                  </div>
                </div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Exemple : Trajet 5,000 CDF</div>
                  <div style="font-weight: 700; font-size: 16px; color: #10b981;">Prix touriste : <span id="exempleTouriste">4,750 CDF</span></div>
                </div>
                <button class="btn btn--primary" style="width: 100%; margin-top: 16px;">
                  <i data-feather="save"></i> Enregistrer
                </button>
              </div>

              <!-- Entreprise -->
              <div style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                  <div style="width: 48px; height: 48px; border-radius: 12px; background: #fee2e2; display: grid; place-items: center;">
                    <i data-feather="briefcase" style="width: 24px; height: 24px; color: #ef4444;"></i>
                  </div>
                  <div>
                    <h4 style="margin: 0; font-size: 18px; font-weight: 700;">Entreprise</h4>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Carte entreprise</p>
                  </div>
                </div>
                <div style="margin-bottom: 16px;">
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Pourcentage de réduction</label>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" min="0" max="50" value="20" class="slider" id="reductionEntreprise" style="flex: 1;">
                    <span id="valeurEntreprise" style="font-weight: 700; font-size: 20px; color: #1B4B7F; min-width: 60px;">20%</span>
                  </div>
                </div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 8px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Exemple : Trajet 5,000 CDF</div>
                  <div style="font-weight: 700; font-size: 16px; color: #10b981;">Prix entreprise : <span id="exempleEntreprise">4,000 CDF</span></div>
                </div>
                <button class="btn btn--primary" style="width: 100%; margin-top: 16px;">
                  <i data-feather="save"></i> Enregistrer
                </button>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Onglet Promotions -->
      <div class="tab-content" id="tab-promotions">
        <section class="card">
          <div class="card__header">
            <h3>Promotions actives</h3>
          </div>
          <div style="padding: 24px;">
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
              <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i data-feather="alert-circle" style="width: 20px; height: 20px; color: #f59e0b;"></i>
                <strong style="color: #92400e;">Fonctionnalité en développement</strong>
              </div>
              <p style="margin: 0; color: #92400e; font-size: 14px;">
                La gestion des promotions sera disponible prochainement. Vous pourrez créer des offres spéciales, des codes promo et des réductions temporaires.
              </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
              <div style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 24px; text-align: center; opacity: 0.5;">
                <i data-feather="gift" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 12px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #6b7280;">Codes promo</h4>
                <p style="margin: 0; font-size: 13px; color: #9ca3af;">Bientôt disponible</p>
              </div>
              <div style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 24px; text-align: center; opacity: 0.5;">
                <i data-feather="calendar" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 12px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #6b7280;">Offres saisonnières</h4>
                <p style="margin: 0; font-size: 13px; color: #9ca3af;">Bientôt disponible</p>
              </div>
              <div style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 24px; text-align: center; opacity: 0.5;">
                <i data-feather="users" style="width: 48px; height: 48px; color: #6b7280; margin-bottom: 12px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #6b7280;">Réductions groupes</h4>
                <p style="margin: 0; font-size: 13px; color: #9ca3af;">Bientôt disponible</p>
              </div>
            </div>
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Nouveau Tarif -->
  <div class="modal" id="modalNouveauTarif">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Nouveau tarif</h2>
        <button class="modal__close" id="closeModalTarif">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formNouveauTarif">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Arrêt de départ *</label>
              <select class="form-control" id="arretDepartTarif" required>
                <option value="">Sélectionner...</option>
                <optgroup label="Kinshasa">
                  <option value="kinshasa-gare">Kinshasa - Gare centrale</option>
                  <option value="kinshasa-lemba">Kinshasa - Lemba</option>
                  <option value="kinshasa-universite">Kinshasa - Université</option>
                  <option value="kinshasa-gombe">Kinshasa - Gombe</option>
                  <option value="kinshasa-kintambo">Kinshasa - Kintambo</option>
                </optgroup>
                <optgroup label="Matadi">
                  <option value="matadi-gare">Matadi - Gare</option>
                  <option value="matadi-centre">Matadi - Centre-ville</option>
                </optgroup>
                <optgroup label="Lubumbashi">
                  <option value="lubumbashi-centre">Lubumbashi - Centre</option>
                  <option value="lubumbashi-katuba">Lubumbashi - Katuba</option>
                </optgroup>
                <optgroup label="Kikwit">
                  <option value="kikwit-terminal">Kikwit - Terminal</option>
                </optgroup>
                <optgroup label="Kananga">
                  <option value="kananga-gare">Kananga - Gare</option>
                </optgroup>
              </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Arrêt d'arrivée *</label>
              <select class="form-control" id="arretArriveeTarif" required>
                <option value="">Sélectionner...</option>
                <optgroup label="Kinshasa">
                  <option value="kinshasa-gare">Kinshasa - Gare centrale</option>
                  <option value="kinshasa-lemba">Kinshasa - Lemba</option>
                  <option value="kinshasa-universite">Kinshasa - Université</option>
                  <option value="kinshasa-gombe">Kinshasa - Gombe</option>
                  <option value="kinshasa-kintambo">Kinshasa - Kintambo</option>
                </optgroup>
                <optgroup label="Matadi">
                  <option value="matadi-gare">Matadi - Gare</option>
                  <option value="matadi-centre">Matadi - Centre-ville</option>
                </optgroup>
                <optgroup label="Lubumbashi">
                  <option value="lubumbashi-centre">Lubumbashi - Centre</option>
                  <option value="lubumbashi-katuba">Lubumbashi - Katuba</option>
                </optgroup>
                <optgroup label="Kikwit">
                  <option value="kikwit-terminal">Kikwit - Terminal</option>
                </optgroup>
                <optgroup label="Kananga">
                  <option value="kananga-gare">Kananga - Gare</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Distance (km) *</label>
              <input type="number" class="form-control" placeholder="Ex: 366" min="0" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Durée estimée *</label>
              <input type="text" class="form-control" placeholder="Ex: 6h 30min" required>
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Prix normal (CDF) *</label>
            <input type="number" class="form-control" placeholder="Ex: 5000" min="0" required>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelTarif">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="check"></i> Créer le tarif
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Modifier Tarif -->
  <div class="modal" id="modalModifierTarif">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 600px;">
      <div class="modal__header">
        <h2>Modifier les tarifs</h2>
        <button class="modal__close" id="closeModalModifier">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formModifierTarif">
          <input type="hidden" id="trajetIdModif">
          
          <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Trajet</div>
            <div id="trajetNomModif" style="font-weight: 700; font-size: 18px; color: #1B4B7F;"></div>
          </div>

          <!-- Grille des tarifs -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <!-- Tarif Normal -->
            <div class="form-group" style="margin: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                <i data-feather="user" style="width: 16px; height: 16px;"></i> Prix Normal (CDF) *
              </label>
              <input type="number" class="form-control tarif-input" id="prixNormal" data-type="normal" placeholder="5000" min="0" required style="font-size: 16px; font-weight: 700;">
            </div>

            <!-- Tarif Étudiant -->
            <div class="form-group" style="margin: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                <i data-feather="book" style="width: 16px; height: 16px;"></i> Étudiant (-15%)
              </label>
              <input type="number" class="form-control tarif-input" id="prixEtudiant" data-type="etudiant" placeholder="4250" min="0" style="font-size: 16px; font-weight: 700;">
            </div>

            <!-- Tarif Senior -->
            <div class="form-group" style="margin: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                <i data-feather="users" style="width: 16px; height: 16px;"></i> Senior (-10%)
              </label>
              <input type="number" class="form-control tarif-input" id="prixSenior" data-type="senior" placeholder="4500" min="0" style="font-size: 16px; font-weight: 700;">
            </div>

            <!-- Tarif Enfant -->
            <div class="form-group" style="margin: 0;">
              <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                <i data-feather="smile" style="width: 16px; height: 16px;"></i> Enfant (-20%)
              </label>
              <input type="number" class="form-control tarif-input" id="prixEnfant" data-type="enfant" placeholder="4000" min="0" style="font-size: 16px; font-weight: 700;">
            </div>
          </div>

          <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fbbf24;">
            <div style="font-size: 12px; color: #92400e;">
              <i data-feather="info" style="width: 14px; height: 14px;"></i>
              <strong>Astuce :</strong> Modifiez le prix normal, les autres se calculent automatiquement. Vous pouvez aussi les modifier manuellement.
            </div>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelModifier">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="save"></i> Enregistrer les modifications
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Créer Tarifs Automatiquement -->
  <div class="modal" id="modalCreerTarifsAuto">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 550px;">
      <div class="modal__header">
        <h2>Créer les tarifs automatiquement</h2>
        <button class="modal__close" id="closeModalCreerAuto">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formCreerTarifsAuto">
          <input type="hidden" id="trajetIdAuto">
          
          <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Trajet sélectionné</div>
            <div id="trajetNomAuto" style="font-weight: 700; font-size: 18px; color: #1B4B7F;"></div>
          </div>

          <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
              <i data-feather="dollar-sign" style="width: 16px; height: 16px;"></i> Prix Normal (CDF) *
            </label>
            <input type="number" class="form-control" id="prixNormalAuto" placeholder="Ex: 5000" min="0" required 
                   style="font-size: 20px; font-weight: 700; text-align: center;">
            <small style="color: #6b7280; font-size: 12px;">Les autres tarifs seront calculés automatiquement</small>
          </div>

          <div style="background: #dbeafe; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #3b82f6;">
            <div style="font-size: 13px; color: #1e40af; margin-bottom: 12px; font-weight: 700;">
              <i data-feather="zap" style="width: 16px; height: 16px;"></i> Aperçu des tarifs qui seront créés :
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <i data-feather="user" style="width: 16px; height: 16px; color: #1e40af;"></i>
                <span style="color: #1e40af;">Normal :</span>
              </div>
              <div id="previewNormalAuto" style="font-weight: 700; color: #1e40af; text-align: right;">0 CDF</div>
              
              <div style="display: flex; align-items: center; gap: 8px;">
                <i data-feather="book" style="width: 16px; height: 16px; color: #1e40af;"></i>
                <span style="color: #1e40af;">Étudiant (-15%) :</span>
              </div>
              <div id="previewEtudiantAuto" style="font-weight: 700; color: #1e40af; text-align: right;">0 CDF</div>
              
              <div style="display: flex; align-items: center; gap: 8px;">
                <i data-feather="users" style="width: 16px; height: 16px; color: #1e40af;"></i>
                <span style="color: #1e40af;">Senior (-10%) :</span>
              </div>
              <div id="previewSeniorAuto" style="font-weight: 700; color: #1e40af; text-align: right;">0 CDF</div>
              
              <div style="display: flex; align-items: center; gap: 8px;">
                <i data-feather="smile" style="width: 16px; height: 16px; color: #1e40af;"></i>
                <span style="color: #1e40af;">Enfant (-20%) :</span>
              </div>
              <div id="previewEnfantAuto" style="font-weight: 700; color: #1e40af; text-align: right;">0 CDF</div>
            </div>
          </div>

          <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fbbf24;">
            <div style="font-size: 12px; color: #92400e;">
              <i data-feather="info" style="width: 14px; height: 14px;"></i>
              <strong>Info :</strong> Les 4 tarifs (Normal, Étudiant, Senior, Enfant) seront créés automatiquement avec les réductions standards.
            </div>
          </div>

          <div class="modal__actions">
            <button type="button" class="btn btn--secondary" id="cancelCreerAuto">Annuler</button>
            <button type="submit" class="btn btn--primary">
              <i data-feather="zap"></i> Créer les tarifs
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
          tabBtns.forEach(b => b.classList.remove('active'));
          tabContents.forEach(c => c.classList.remove('active'));
          
          btn.classList.add('active');
          const tabId = btn.getAttribute('data-tab');
          document.getElementById(tabId).classList.add('active');
          
          // Réinitialiser la pagination quand on revient sur l'onglet tarifs
          if (tabId === 'tab-tarifs') {
            setTimeout(() => {
              initPagination();
            }, 100);
          }
          
          feather.replace();
        });
      });

      // Modal Nouveau Tarif
      const btnNouveauTarif = document.getElementById('btnNouveauTarif');
      const modalNouveauTarif = document.getElementById('modalNouveauTarif');
      const closeModalTarif = document.getElementById('closeModalTarif');
      const cancelTarif = document.getElementById('cancelTarif');

      btnNouveauTarif?.addEventListener('click', () => {
        modalNouveauTarif.classList.add('active');
        feather.replace();
      });

      closeModalTarif?.addEventListener('click', () => {
        modalNouveauTarif.classList.remove('active');
      });

      cancelTarif?.addEventListener('click', () => {
        modalNouveauTarif.classList.remove('active');
      });

      // Modal Modifier Tarif
      const modalModifierTarif = document.getElementById('modalModifierTarif');
      const closeModalModifier = document.getElementById('closeModalModifier');
      const cancelModifier = document.getElementById('cancelModifier');

      closeModalModifier?.addEventListener('click', () => {
        modalModifierTarif.classList.remove('active');
      });

      cancelModifier?.addEventListener('click', () => {
        modalModifierTarif.classList.remove('active');
      });

      // Event delegation pour les boutons modifier (corrige le problème de modification)
      document.addEventListener('click', function(e) {
        const btnModifier = e.target.closest('.btn-modifier-tarif');
        if (btnModifier) {
          e.preventDefault();
          const trajetId = btnModifier.dataset.trajetId;
          const trajetNom = btnModifier.dataset.trajetNom;
          const tarifs = JSON.parse(btnModifier.dataset.tarifs);
          
          console.log('📝 Ouverture modal modification:', { trajetId, trajetNom, tarifs });
          
          document.getElementById('trajetIdModif').value = trajetId;
          document.getElementById('trajetNomModif').textContent = trajetNom;
          
          // Remplir les champs avec les tarifs existants
          document.getElementById('prixNormal').value = tarifs.normal?.prix || '';
          document.getElementById('prixEtudiant').value = tarifs.etudiant?.prix || '';
          document.getElementById('prixSenior').value = tarifs.senior?.prix || '';
          document.getElementById('prixEnfant').value = tarifs.enfant?.prix || '';
          
          modalModifierTarif.classList.add('active');
          setTimeout(() => feather.replace(), 100);
        }

        // Event delegation pour les boutons créer auto
        const btnCreerAuto = e.target.closest('.btn-creer-auto');
        if (btnCreerAuto) {
          e.preventDefault();
          const trajetId = btnCreerAuto.dataset.trajetId;
          const trajetNom = btnCreerAuto.dataset.trajetNom;
          creerTarifsAuto(trajetId, trajetNom);
        }
      });

      // Calculer automatiquement les autres tarifs quand on modifie le prix normal
      document.getElementById('prixNormal')?.addEventListener('input', (e) => {
        const prixNormal = parseFloat(e.target.value) || 0;
        if (prixNormal > 0) {
          document.getElementById('prixEtudiant').value = Math.round(prixNormal * 0.85);
          document.getElementById('prixSenior').value = Math.round(prixNormal * 0.90);
          document.getElementById('prixEnfant').value = Math.round(prixNormal * 0.80);
        }
      });

      // Modal Créer Tarifs Auto
      const modalCreerTarifsAuto = document.getElementById('modalCreerTarifsAuto');
      const closeModalCreerAuto = document.getElementById('closeModalCreerAuto');
      const cancelCreerAuto = document.getElementById('cancelCreerAuto');

      closeModalCreerAuto?.addEventListener('click', () => {
        modalCreerTarifsAuto.classList.remove('active');
      });

      cancelCreerAuto?.addEventListener('click', () => {
        modalCreerTarifsAuto.classList.remove('active');
      });

      // Fonction pour ouvrir le modal de création auto
      window.creerTarifsAuto = function(trajetId, trajetNom) {
        console.log('⚡ Ouverture modal création auto:', { trajetId, trajetNom });
        
        document.getElementById('trajetIdAuto').value = trajetId;
        document.getElementById('trajetNomAuto').textContent = trajetNom;
        document.getElementById('prixNormalAuto').value = '';
        
        // Réinitialiser l'aperçu
        calculerPreviewAuto(0);
        
        modalCreerTarifsAuto.classList.add('active');
        setTimeout(() => {
          feather.replace();
          document.getElementById('prixNormalAuto').focus();
        }, 100);
      };

      // Calculer l'aperçu des tarifs auto
      function calculerPreviewAuto(prix) {
        const prixNum = parseFloat(prix) || 0;
        document.getElementById('previewNormalAuto').textContent = Math.round(prixNum).toLocaleString() + ' CDF';
        document.getElementById('previewEtudiantAuto').textContent = Math.round(prixNum * 0.85).toLocaleString() + ' CDF';
        document.getElementById('previewSeniorAuto').textContent = Math.round(prixNum * 0.90).toLocaleString() + ' CDF';
        document.getElementById('previewEnfantAuto').textContent = Math.round(prixNum * 0.80).toLocaleString() + ' CDF';
      }

      // Mettre à jour l'aperçu en temps réel
      document.getElementById('prixNormalAuto')?.addEventListener('input', (e) => {
        calculerPreviewAuto(e.target.value);
      });

      // Soumettre le formulaire de création auto
      document.getElementById('formCreerTarifsAuto')?.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const trajetId = document.getElementById('trajetIdAuto').value;
        const trajetNom = document.getElementById('trajetNomAuto').textContent;
        const prix = parseFloat(document.getElementById('prixNormalAuto').value);
        
        if (isNaN(prix) || prix <= 0) {
          alert('Prix invalide');
          return;
        }

        console.log('💾 Création des tarifs auto:', { trajetId, prix });

        fetch('/tarifs/creerAuto', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            trajet_id: trajetId,
            prix_normal: prix
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            modalCreerTarifsAuto.classList.remove('active');
            window.location.reload();
          } else {
            alert('Erreur: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('Erreur lors de la création des tarifs');
        });
      });

      // Calculer preview des prix
      function calculerPreview(prix) {
        const prixNum = parseFloat(prix) || 0;
        document.getElementById('previewEtudiant').textContent = Math.round(prixNum * 0.85).toLocaleString() + ' CDF';
        document.getElementById('previewSenior').textContent = Math.round(prixNum * 0.90).toLocaleString() + ' CDF';
        document.getElementById('previewEnfant').textContent = Math.round(prixNum * 0.80).toLocaleString() + ' CDF';
      }

      document.getElementById('nouveauPrix')?.addEventListener('input', (e) => {
        calculerPreview(e.target.value);
      });

      // Sliders des catégories
      const sliders = [
        { id: 'reductionEtudiant', valeur: 'valeurEtudiant', exemple: 'exempleEtudiant' },
        { id: 'reductionSenior', valeur: 'valeurSenior', exemple: 'exempleSenior' },
        { id: 'reductionEnfant', valeur: 'valeurEnfant', exemple: 'exempleEnfant' },
        { id: 'reductionTouriste', valeur: 'valeurTouriste', exemple: 'exempleTouriste' },
        { id: 'reductionEntreprise', valeur: 'valeurEntreprise', exemple: 'exempleEntreprise' }
      ];

      sliders.forEach(slider => {
        const input = document.getElementById(slider.id);
        const valeur = document.getElementById(slider.valeur);
        const exemple = document.getElementById(slider.exemple);

        input?.addEventListener('input', (e) => {
          const reduction = e.target.value;
          valeur.textContent = reduction + '%';
          const prixReduit = 5000 * (1 - reduction / 100);
          exemple.textContent = Math.round(prixReduit).toLocaleString() + ' CDF';
        });
      });

      // Fermer les modals en cliquant sur l'overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', () => {
          overlay.parentElement.classList.remove('active');
        });
      });

      // Formulaire Nouveau Tarif
      document.getElementById('formNouveauTarif')?.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Tarif créé avec succès !');
        modalNouveauTarif.classList.remove('active');
      });

      // Formulaire Modifier Tarif
      document.getElementById('formModifierTarif')?.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const trajetId = document.getElementById('trajetIdModif').value;
        const trajetNom = document.getElementById('trajetNomModif').textContent;
        
        const tarifs = [
          { type: 'normal', prix: document.getElementById('prixNormal').value },
          { type: 'etudiant', prix: document.getElementById('prixEtudiant').value },
          { type: 'senior', prix: document.getElementById('prixSenior').value },
          { type: 'enfant', prix: document.getElementById('prixEnfant').value }
        ];
        
        // Vérifier qu'au moins le prix normal est renseigné
        if (!tarifs[0].prix || tarifs[0].prix <= 0) {
          alert('Le prix normal est obligatoire');
          return;
        }
        
        console.log('💾 Enregistrement des tarifs:', { trajetId, tarifs });
        
        // Enregistrer chaque tarif
        let promises = [];
        tarifs.forEach(tarif => {
          if (tarif.prix && tarif.prix > 0) {
            promises.push(
              fetch('/tarifs/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                  trajet_id: trajetId,
                  type_tarif: tarif.type,
                  prix: tarif.prix,
                  statut: 'actif'
                })
              })
            );
          }
        });
        
        Promise.all(promises)
          .then(responses => Promise.all(responses.map(r => r.json())))
          .then(results => {
            const errors = results.filter(r => !r.success);
            if (errors.length > 0) {
              alert('Erreur lors de l\'enregistrement: ' + errors[0].message);
            } else {
              alert('Tarifs modifiés avec succès !');
              modalModifierTarif.classList.remove('active');
              window.location.reload();
            }
          })
          .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'enregistrement des tarifs');
          });
      });

      // ===== PAGINATION =====
      let currentPage = 1;
      const itemsPerPage = 10;
      let allRows = [];

      function initPagination() {
        const tbody = document.querySelector('#tableTarifs tbody');
        if (!tbody) {
          console.warn('⚠️ Tableau des tarifs non trouvé');
          return;
        }

        allRows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
          return !row.querySelector('td[colspan]'); // Exclure la ligne "Aucun tarif"
        });

        console.log('📊 Pagination initialisée:', allRows.length, 'lignes trouvées');

        if (allRows.length === 0) {
          document.getElementById('paginationControls').style.display = 'none';
          return;
        }

        // Masquer la pagination si moins de 10 trajets
        if (allRows.length <= itemsPerPage) {
          const paginationContainer = document.querySelector('#tab-tarifs .card > div:last-child');
          if (paginationContainer) {
            paginationContainer.style.display = 'none';
          }
          return;
        }

        renderPage(currentPage);
        renderPaginationControls();
      }

      function renderPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Masquer toutes les lignes
        allRows.forEach(row => row.style.display = 'none');

        // Afficher les lignes de la page actuelle
        const pageRows = allRows.slice(start, end);
        pageRows.forEach(row => row.style.display = '');

        // Mettre à jour les informations de pagination
        document.getElementById('paginationStart').textContent = allRows.length > 0 ? start + 1 : 0;
        document.getElementById('paginationEnd').textContent = Math.min(end, allRows.length);
        document.getElementById('paginationTotal').textContent = allRows.length;

        // Mettre à jour les boutons
        document.getElementById('btnPrevPage').disabled = page === 1;
        document.getElementById('btnNextPage').disabled = end >= allRows.length;

        // Rafraîchir les icônes Feather
        setTimeout(() => feather.replace(), 50);
      }

      function renderPaginationControls() {
        const totalPages = Math.ceil(allRows.length / itemsPerPage);
        const paginationNumbers = document.getElementById('paginationNumbers');
        paginationNumbers.innerHTML = '';

        // Afficher au maximum 5 numéros de page
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);

        if (endPage - startPage < 4) {
          startPage = Math.max(1, endPage - 4);
        }

        for (let i = startPage; i <= endPage; i++) {
          const btn = document.createElement('button');
          btn.className = 'btn btn--secondary';
          btn.textContent = i;
          btn.style.minWidth = '40px';
          
          if (i === currentPage) {
            btn.style.background = '#1B4B7F';
            btn.style.color = 'white';
            btn.style.fontWeight = '700';
          }

          btn.addEventListener('click', () => {
            currentPage = i;
            renderPage(currentPage);
            renderPaginationControls();
          });

          paginationNumbers.appendChild(btn);
        }
      }

      // Event listeners pour les boutons Précédent/Suivant
      document.getElementById('btnPrevPage')?.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          renderPage(currentPage);
          renderPaginationControls();
        }
      });

      document.getElementById('btnNextPage')?.addEventListener('click', () => {
        const totalPages = Math.ceil(allRows.length / itemsPerPage);
        if (currentPage < totalPages) {
          currentPage++;
          renderPage(currentPage);
          renderPaginationControls();
        }
      });

      // Initialiser la pagination après le chargement complet
      // Attendre que tous les éléments soient rendus
      if (document.readyState === 'complete') {
        initPagination();
      } else {
        window.addEventListener('load', () => {
          setTimeout(() => {
            initPagination();
          }, 200);
        });
      }
    });
  </script>
</body>
</html>
