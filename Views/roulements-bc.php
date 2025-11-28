<?php
  // Mois sélectionné (YYYY-MM), fallback sur le mois courant si invalide ou absent
  $moisSelectionne = isset($_GET['mois']) && preg_match('/^\d{4}-\d{2}$/', $_GET['mois'])
    ? $_GET['mois']
    : date('Y-m');

  [$anneeSelectionnee, $moisSelectionneNum] = explode('-', $moisSelectionne);
  $anneeSelectionnee = (int) $anneeSelectionnee;
  $moisSelectionneNum = (int) $moisSelectionneNum;

  // Nombre de jours dans le mois sélectionné
  $nombreJoursMois = cal_days_in_month(CAL_GREGORIAN, $moisSelectionneNum, $anneeSelectionnee);

  // Étiquettes des jours de la semaine (0 = Dimanche)
  $joursSemaine = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
  
  // Onglet actif (par défaut : controleurs)
  $ongletActif = isset($_GET['tab']) ? $_GET['tab'] : 'controleurs';
  $ongletsValides = ['controleurs', 'chef-secteur', 'renfort-fixes', 'interurbain', 'brigadier-nuit', 'brigadier-jour'];
  if (!in_array($ongletActif, $ongletsValides, true)) {
    $ongletActif = 'controleurs';
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Roulements controlleurs • Bureau de conception</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BC.php'; ?>

    <main class="main">
      <!-- En-tête -->
      <header class="header">
        <div>
          <h1>Roulements • Controlleurs</h1>
          <p>Tableau détaillé des affectations controlleurs</p>
        </div>
      </header>

      <!-- Onglets types d'agents -->
      <section class="settings-tabs roulements-tabs">
        <button class="settings-tab <?php echo $ongletActif === 'controleurs' ? 'active' : ''; ?>" data-tab="controleurs">
          <i data-feather="users"></i> CONTROLEURS
        </button>
        <button class="settings-tab <?php echo $ongletActif === 'chef-secteur' ? 'active' : ''; ?>" data-tab="chef-secteur">
          <i data-feather="users"></i> CHEF DE SECTEUR
        </button>
        <button class="settings-tab <?php echo $ongletActif === 'renfort-fixes' ? 'active' : ''; ?>" data-tab="renfort-fixes">
          <i data-feather="users"></i> CONTROLEURS RENFORT FIXES
        </button>
        <button class="settings-tab <?php echo $ongletActif === 'interurbain' ? 'active' : ''; ?>" data-tab="interurbain">
          <i data-feather="users"></i> CONTROLEURS INTERURBAIN
        </button>
        <button class="settings-tab <?php echo $ongletActif === 'brigadier-nuit' ? 'active' : ''; ?>" data-tab="brigadier-nuit">
          <i data-feather="moon"></i> BRIGADIER NUIT
        </button>
        <button class="settings-tab <?php echo $ongletActif === 'brigadier-jour' ? 'active' : ''; ?>" data-tab="brigadier-jour">
          <i data-feather="sun"></i> BRIGADIER JOUR
        </button>
      </section>

      <!-- Onglet 1 : CONTROLEURS (tableau Excel existant) -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'controleurs' ? 'active' : ''; ?>" id="tab-controleurs">
        <div class="card" style="margin-bottom: 28px;">
          <div class="card__body" style="padding:20px 24px;">
            <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
              <div style="min-width:260px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <form method="GET" action="<?php echo BASE_URL; ?>/roulements-bc" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; margin:0;">
                  <div style="display:flex; flex-direction:column; gap:4px; min-width:200px;">
                    <label style="font-size:13px; font-weight:500;">Sélectionner le mois</label>
                    <input type="month" name="mois" class="form-control" value="<?php echo htmlspecialchars($moisSelectionne); ?>" required>
                  </div>
                  <input type="hidden" name="tab" value="controleurs">
                  <button type="submit" class="btn btn--primary" style="display:inline-flex; align-items:center; gap:6px; height:36px;">
                    <i data-feather="filter"></i>
                    <span>Appliquer</span>
                  </button>
                </form>
              </div>

              <div style="min-width:260px; display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="printer"></i>
                  <span>Imprimer</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file-text"></i>
                  <span>PDF</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file"></i>
                  <span>Excel</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tableau type Excel -->
        <section class="card">
          <div class="card__header" style="padding:16px 16px 0 16px; border-bottom:none;">
            <h2 style="font-size:16px; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
              <i data-feather="calendar"></i>
              Détails des roulements par mois
            </h2>
            <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
              <label for="searchAgent" style="font-size:13px; color:#6b7280;">Rechercher un controlleur</label>
              <input type="text" id="searchAgent" class="form-control" placeholder="Nom ou matricule..." style="max-width:260px;">
            </div>
          </div>
          <div class="card__body" style="padding:16px;">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%; border-collapse:collapse;">
                <thead class="table-light">
                  <tr>
                    <th style="position:sticky; left:0; background:#f9fafb; z-index:1;">Agent</th>
                    <th style="position:sticky; left:140px; background:#f9fafb; z-index:1;">Matricule</th>
                    <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): 
                      $timestampJour = mktime(0, 0, 0, $moisSelectionneNum, $jour, $anneeSelectionnee);
                      $indexJourSemaine = (int) date('w', $timestampJour); // 0 = Dimanche
                      $libelleSemaine = $joursSemaine[$indexJourSemaine];
                    ?>
                      <th style="text-align:center;">
                        <div><?php echo $jour; ?></div>
                        <div style="font-size:11px; color:#6b7280;"><?php echo $libelleSemaine; ?></div>
                      </th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Données de maquette : 10 agents congolais avec matricules numériques uniquement
                  $agents = [
                    ['nom' => 'Jean-Pierre Mukendi', 'matricule' => '100001'],
                    ['nom' => 'Marie Tshala', 'matricule' => '100002'],
                    ['nom' => 'Patrick Kabongo', 'matricule' => '100003'],
                    ['nom' => 'Chantal Ilunga', 'matricule' => '100004'],
                    ['nom' => 'Serge Kanku', 'matricule' => '100005'],
                    ['nom' => 'Esther Mbuyi', 'matricule' => '100006'],
                    ['nom' => 'Eric Kabasele', 'matricule' => '100007'],
                    ['nom' => 'Nadine Kasongo', 'matricule' => '100008'],
                    ['nom' => 'Christian Tshimanga', 'matricule' => '100009'],
                    ['nom' => 'Aline Mbala', 'matricule' => '100010'],
                  ];

                  foreach ($agents as $indexAgent => $agent):
                  ?>
                    <tr>
                      <td style="position:sticky; left:0; background:#ffffff; z-index:1; font-weight:600;">
                        <?php echo htmlspecialchars($agent['nom']); ?>
                      </td>
                      <td style="position:sticky; left:140px; background:#ffffff; z-index:1; font-size:13px; color:#6b7280;">
                        <?php echo htmlspecialchars($agent['matricule']); ?>
                      </td>
                      <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): ?>
                        <td class="cell-roulement" data-agent="<?php echo $indexAgent; ?>" data-jour="<?php echo $jour; ?>" style="font-size:13px; text-align:center; cursor:pointer;">
                          -
                        </td>
                      <?php endfor; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </section>

      <!-- Onglet 2 : CHEF DE SECTEUR (même structure que contrôleurs avec colonne Secteur) -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'chef-secteur' ? 'active' : ''; ?>" id="tab-chef-secteur">
        <div class="card" style="margin-bottom: 28px;">
          <div class="card__body" style="padding:20px 24px;">
            <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
              <div style="min-width:260px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <form method="GET" action="<?php echo BASE_URL; ?>/roulements-bc" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; margin:0;">
                  <div style="display:flex; flex-direction:column; gap:4px; min-width:200px;">
                    <label style="font-size:13px; font-weight:500;">Sélectionner le mois</label>
                    <input type="month" name="mois" class="form-control" value="<?php echo htmlspecialchars($moisSelectionne); ?>" required>
                  </div>
                  <input type="hidden" name="tab" value="chef-secteur">
                  <button type="submit" class="btn btn--primary" style="display:inline-flex; align-items:center; gap:6px; height:36px;">
                    <i data-feather="filter"></i>
                    <span>Appliquer</span>
                  </button>
                </form>
              </div>

              <div style="min-width:260px; display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="printer"></i>
                  <span>Imprimer</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file-text"></i>
                  <span>PDF</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file"></i>
                  <span>Excel</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <section class="card">
          <div class="card__header" style="padding:16px 16px 0 16px; border-bottom:none;">
            <h2 style="font-size:16px; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
              <i data-feather="calendar"></i>
              Détails des roulements par mois • Chefs de secteur
            </h2>
            <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
              <label for="searchChef" style="font-size:13px; color:#6b7280;">Rechercher un chef de secteur</label>
              <input type="text" id="searchChef" class="form-control" placeholder="Nom ou matricule..." style="max-width:260px;">
            </div>
          </div>
          <div class="card__body" style="padding:16px;">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%; border-collapse:collapse;">
                <thead class="table-light">
                  <tr>
                    <th style="position:sticky; left:0; background:#f9fafb; z-index:1;">Chef de secteur</th>
                    <th style="position:sticky; left:140px; background:#f9fafb; z-index:1;">Matricule</th>
                    <th style="background:#f9fafb; z-index:1;">Secteur</th>
                    <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): 
                      $timestampJour = mktime(0, 0, 0, $moisSelectionneNum, $jour, $anneeSelectionnee);
                      $indexJourSemaine = (int) date('w', $timestampJour); // 0 = Dimanche
                      $libelleSemaine = $joursSemaine[$indexJourSemaine];
                    ?>
                      <th style="text-align:center;">
                        <div><?php echo $jour; ?></div>
                        <div style="font-size:11px; color:#6b7280;"><?php echo $libelleSemaine; ?></div>
                      </th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Données de maquette : 10 chefs de secteur avec matricules et secteurs
                  $chefsSecteur = [
                    ['nom' => 'Jean-Pierre Mukendi', 'matricule' => 'CS200001', 'secteur' => 'Masina',      'badge' => 'badge--primary'],
                    ['nom' => 'Marie Tshala',        'matricule' => 'CS200002', 'secteur' => 'Matete',      'badge' => 'badge--success'],
                    ['nom' => 'Patrick Kabongo',     'matricule' => 'CS200003', 'secteur' => 'Lemba',       'badge' => 'badge--warning'],
                    ['nom' => 'Chantal Ilunga',      'matricule' => 'CS200004', 'secteur' => 'Ngaliema',    'badge' => 'badge--danger'],
                    ['nom' => 'Serge Kanku',         'matricule' => 'CS200005', 'secteur' => 'Limete',      'badge' => 'badge--info'],
                    ['nom' => 'Esther Mbuyi',        'matricule' => 'CS200006', 'secteur' => 'Kintambo',    'badge' => 'badge--primary'],
                    ['nom' => 'Eric Kabasele',       'matricule' => 'CS200007', 'secteur' => 'Selembao',    'badge' => 'badge--success'],
                    ['nom' => 'Nadine Kasongo',      'matricule' => 'CS200008', 'secteur' => 'Bandalungwa', 'badge' => 'badge--warning'],
                    ['nom' => 'Christian Tshimanga', 'matricule' => 'CS200009', 'secteur' => 'Gombe',       'badge' => 'badge--danger'],
                    ['nom' => 'Aline Mbala',         'matricule' => 'CS200010', 'secteur' => 'Masina II',   'badge' => 'badge--info'],
                  ];

                  foreach ($chefsSecteur as $indexChef => $chef):
                  ?>
                    <tr>
                      <td style="position:sticky; left:0; background:#ffffff; z-index:1; font-weight:600;">
                        <?php echo htmlspecialchars($chef['nom']); ?>
                      </td>
                      <td style="position:sticky; left:140px; background:#ffffff; z-index:1; font-size:13px; color:#6b7280;">
                        <?php echo htmlspecialchars($chef['matricule']); ?>
                      </td>
                      <td style="font-size:13px;">
                        <span class="badge <?php echo htmlspecialchars($chef['badge']); ?>">
                          <?php echo htmlspecialchars($chef['secteur']); ?>
                        </span>
                      </td>
                      <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): ?>
                        <td class="cell-roulement" data-agent="<?php echo $indexChef; ?>" data-jour="<?php echo $jour; ?>" style="font-size:13px; text-align:center; cursor:pointer;">-</td>
                      <?php endfor; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </section>

      <!-- Onglet 3 : CONTROLEURS RENFORT FIXES (similaire au premier onglet) -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'renfort-fixes' ? 'active' : ''; ?>" id="tab-renfort-fixes">
        <div class="card" style="margin-bottom: 28px;">
          <div class="card__body" style="padding:20px 24px;">
            <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
              <div style="min-width:260px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <form method="GET" action="<?php echo BASE_URL; ?>/roulements-bc" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; margin:0;">
                  <div style="display:flex; flex-direction:column; gap:4px; min-width:200px;">
                    <label style="font-size:13px; font-weight:500;">Sélectionner le mois</label>
                    <input type="month" name="mois" class="form-control" value="<?php echo htmlspecialchars($moisSelectionne); ?>" required>
                  </div>
                  <input type="hidden" name="tab" value="renfort-fixes">
                  <button type="submit" class="btn btn--primary" style="display:inline-flex; align-items:center; gap:6px; height:36px;">
                    <i data-feather="filter"></i>
                    <span>Appliquer</span>
                  </button>
                </form>
              </div>

              <div style="min-width:260px; display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="printer"></i>
                  <span>Imprimer</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file-text"></i>
                  <span>PDF</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file"></i>
                  <span>Excel</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <section class="card">
          <div class="card__header" style="padding:16px 16px 0 16px; border-bottom:none;">
            <h2 style="font-size:16px; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
              <i data-feather="calendar"></i>
              Détails des roulements par mois • Contrôleurs renfort fixes
            </h2>
            <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
              <label for="searchRenfort" style="font-size:13px; color:#6b7280;">Rechercher un contrôleur renfort fixe</label>
              <input type="text" id="searchRenfort" class="form-control" placeholder="Nom ou matricule..." style="max-width:260px;">
            </div>
          </div>
          <div class="card__body" style="padding:16px;">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%; border-collapse:collapse;">
                <thead class="table-light">
                  <tr>
                    <th style="position:sticky; left:0; background:#f9fafb; z-index:1;">Contrôleur renfort fixe</th>
                    <th style="position:sticky; left:140px; background:#f9fafb; z-index:1;">Matricule</th>
                    <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): 
                      $timestampJour = mktime(0, 0, 0, $moisSelectionneNum, $jour, $anneeSelectionnee);
                      $indexJourSemaine = (int) date('w', $timestampJour); // 0 = Dimanche
                      $libelleSemaine = $joursSemaine[$indexJourSemaine];
                    ?>
                      <th style="text-align:center;">
                        <div><?php echo $jour; ?></div>
                        <div style="font-size:11px; color:#6b7280;"><?php echo $libelleSemaine; ?></div>
                      </th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Données de maquette : 10 contrôleurs renfort fixes
                  $controleursRenforts = [
                    ['nom' => 'Blaise Mputu',       'matricule' => 'RF300001'],
                    ['nom' => 'Carine Kalala',      'matricule' => 'RF300002'],
                    ['nom' => 'Joseph Ilunga',      'matricule' => 'RF300003'],
                    ['nom' => 'Sarah Ngalula',      'matricule' => 'RF300004'],
                    ['nom' => 'Matthieu Kabeya',    'matricule' => 'RF300005'],
                    ['nom' => 'Prisca Mukonzo',     'matricule' => 'RF300006'],
                    ['nom' => 'Daniel Banza',       'matricule' => 'RF300007'],
                    ['nom' => 'Gloria Kanyama',     'matricule' => 'RF300008'],
                    ['nom' => 'Fabrice Mpiana',     'matricule' => 'RF300009'],
                    ['nom' => 'Patricia Kashala',   'matricule' => 'RF300010'],
                  ];

                  foreach ($controleursRenforts as $indexRenfort => $renfort):
                  ?>
                    <tr>
                      <td style="position:sticky; left:0; background:#ffffff; z-index:1; font-weight:600;">
                        <?php echo htmlspecialchars($renfort['nom']); ?>
                      </td>
                      <td style="position:sticky; left:140px; background:#ffffff; z-index:1; font-size:13px; color:#6b7280;">
                        <?php echo htmlspecialchars($renfort['matricule']); ?>
                      </td>
                      <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): ?>
                        <td class="cell-roulement" data-agent="<?php echo $indexRenfort; ?>" data-jour="<?php echo $jour; ?>" style="font-size:13px; text-align:center; cursor:pointer;">-</td>
                      <?php endfor; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </section>

      <!-- Onglet 4 : CONTROLEURS INTERURBAIN (similaire au deuxième onglet) -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'interurbain' ? 'active' : ''; ?>" id="tab-interurbain">
        <div class="card" style="margin-bottom: 28px;">
          <div class="card__body" style="padding:20px 24px;">
            <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
              <div style="min-width:260px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <form method="GET" action="<?php echo BASE_URL; ?>/roulements-bc" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; margin:0;">
                  <div style="display:flex; flex-direction:column; gap:4px; min-width:200px;">
                    <label style="font-size:13px; font-weight:500;">Sélectionner le mois</label>
                    <input type="month" name="mois" class="form-control" value="<?php echo htmlspecialchars($moisSelectionne); ?>" required>
                  </div>
                  <input type="hidden" name="tab" value="interurbain">
                  <button type="submit" class="btn btn--primary" style="display:inline-flex; align-items:center; gap:6px; height:36px;">
                    <i data-feather="filter"></i>
                    <span>Appliquer</span>
                  </button>
                </form>
              </div>

              <div style="min-width:260px; display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="printer"></i>
                  <span>Imprimer</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file-text"></i>
                  <span>PDF</span>
                </button>
                <button type="button" class="btn btn--secondary" style="display:inline-flex; align-items:center; gap:6px;">
                  <i data-feather="file"></i>
                  <span>Excel</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <section class="card">
          <div class="card__header" style="padding:16px 16px 0 16px; border-bottom:none;">
            <h2 style="font-size:16px; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
              <i data-feather="calendar"></i>
              Détails des roulements par mois • Contrôleurs interurbain
            </h2>
            <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
              <label for="searchInterurbain" style="font-size:13px; color:#6b7280;">Rechercher un contrôleur interurbain</label>
              <input type="text" id="searchInterurbain" class="form-control" placeholder="Nom ou matricule..." style="max-width:260px;">
            </div>
          </div>
          <div class="card__body" style="padding:16px;">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%; border-collapse:collapse;">
                <thead class="table-light">
                  <tr>
                    <th style="position:sticky; left:0; background:#f9fafb; z-index:1;">Contrôleur interurbain</th>
                    <th style="position:sticky; left:140px; background:#f9fafb; z-index:1;">Matricule</th>
                    <th style="background:#f9fafb; z-index:1;">Ligne interurbaine</th>
                    <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): 
                      $timestampJour = mktime(0, 0, 0, $moisSelectionneNum, $jour, $anneeSelectionnee);
                      $indexJourSemaine = (int) date('w', $timestampJour); // 0 = Dimanche
                      $libelleSemaine = $joursSemaine[$indexJourSemaine];
                    ?>
                      <th style="text-align:center;">
                        <div><?php echo $jour; ?></div>
                        <div style="font-size:11px; color:#6b7280;"><?php echo $libelleSemaine; ?></div>
                      </th>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Données de maquette : 10 contrôleurs interurbains
                  $controleursInterurbains = [
                    ['nom' => 'Jean-Claude Mutombo',  'matricule' => 'IU400001', 'ligne' => 'Kinshasa - Matadi',        'badge' => 'badge--primary'],
                    ['nom' => 'Brigitte Luyeye',      'matricule' => 'IU400002', 'ligne' => 'Kinshasa - Kikwit',        'badge' => 'badge--success'],
                    ['nom' => 'Alain Nsimba',         'matricule' => 'IU400003', 'ligne' => 'Kinshasa - Boma',          'badge' => 'badge--warning'],
                    ['nom' => 'Sylvie Kabasele',      'matricule' => 'IU400004', 'ligne' => 'Kinshasa - Matadi (Express)','badge' => 'badge--danger'],
                    ['nom' => 'Gédéon Kalombo',       'matricule' => 'IU400005', 'ligne' => 'Kinshasa - Kasangulu',     'badge' => 'badge--info'],
                    ['nom' => 'Nadia Mbuyamba',       'matricule' => 'IU400006', 'ligne' => 'Kinshasa - Bandundu',      'badge' => 'badge--primary'],
                    ['nom' => 'Richard Kanku',        'matricule' => 'IU400007', 'ligne' => 'Kinshasa - Mbanza-Ngungu', 'badge' => 'badge--success'],
                    ['nom' => 'Viviane Tshisekedi',   'matricule' => 'IU400008', 'ligne' => 'Kinshasa - Kenge',         'badge' => 'badge--warning'],
                    ['nom' => 'Dieudonné Kitenge',    'matricule' => 'IU400009', 'ligne' => 'Kinshasa - Masina inter',  'badge' => 'badge--danger'],
                    ['nom' => 'Olive Makiese',        'matricule' => 'IU400010', 'ligne' => 'Kinshasa - Lukaya',        'badge' => 'badge--info'],
                  ];

                  foreach ($controleursInterurbains as $indexInter => $ctrl):
                  ?>
                    <tr>
                      <td style="position:sticky; left:0; background:#ffffff; z-index:1; font-weight:600;">
                        <?php echo htmlspecialchars($ctrl['nom']); ?>
                      </td>
                      <td style="position:sticky; left:140px; background:#ffffff; z-index:1; font-size:13px; color:#6b7280;">
                        <?php echo htmlspecialchars($ctrl['matricule']); ?>
                      </td>
                      <td style="font-size:13px;">
                        <span class="badge <?php echo htmlspecialchars($ctrl['badge']); ?>">
                          <?php echo htmlspecialchars($ctrl['ligne']); ?>
                        </span>
                      </td>
                      <?php for ($jour = 1; $jour <= $nombreJoursMois; $jour++): ?>
                        <td class="cell-roulement" data-agent="<?php echo $indexInter; ?>" data-jour="<?php echo $jour; ?>" style="font-size:13px; text-align:center; cursor:pointer;">-</td>
                      <?php endfor; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </section>

      <!-- Onglet 5 : BRIGADIER NUIT -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'brigadier-nuit' ? 'active' : ''; ?>" id="tab-brigadier-nuit">
        <div class="card">
          <div class="card__header">
            <h2 style="font-size:16px; margin-bottom:4px;">Roulements • Brigadier nuit</h2>
          </div>
          <div class="card__body">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%;">
                <thead class="table-light">
                  <tr>
                    <th>Brigadier</th>
                    <th>Zone</th>
                    <th>Plage horaire</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="4" style="text-align:center; padding:24px; color:#6b7280;">Aucun roulement configuré pour les brigadiers de nuit.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- Onglet 6 : BRIGADIER JOUR -->
      <section class="tab-content roulements-tab-content <?php echo $ongletActif === 'brigadier-jour' ? 'active' : ''; ?>" id="tab-brigadier-jour">
        <div class="card">
          <div class="card__header">
            <h2 style="font-size:16px; margin-bottom:4px;">Roulements • Brigadier jour</h2>
          </div>
          <div class="card__body">
            <div class="table-responsive" style="overflow-x:auto;">
              <table class="table table-bordered table-hover" style="white-space:nowrap; width:100%;">
                <thead class="table-light">
                  <tr>
                    <th>Brigadier</th>
                    <th>Zone</th>
                    <th>Plage horaire</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="4" style="text-align:center; padding:24px; color:#6b7280;">Aucun roulement configuré pour les brigadiers de jour.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- Modal choix du type de roulement -->
      <div class="modal" id="modalChoixShift">
        <div class="modal__overlay"></div>
        <div class="modal__content" style="max-width: 420px;">
          <div class="modal__header">
            <h2>Choisir le type de roulement</h2>
            <button class="modal__close" id="btnCloseModalShift">
              <i data-feather="x"></i>
            </button>
          </div>
          <div class="modal__body">
            <p style="font-size:14px; color:#6b7280;">Sélectionnez une des options pour ce jour.</p>
            <div style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; margin-top:12px;">
              <button type="button" class="btn btn--primary" data-shift-value="1">1 - Shift du matin</button>
              <button type="button" class="btn btn--primary" data-shift-value="2">2 - Shift du soir</button>
              <button type="button" class="btn btn--secondary" data-shift-value="R">R - Repos</button>
              <button type="button" class="btn btn--secondary" data-shift-value="-">- Non affecté</button>
            </div>
          </div>
          <div class="modal__footer" style="text-align:right;">
            <button type="button" class="btn btn--secondary" id="btnAnnulerModalShift">Annuler</button>
          </div>
        </div>
      </div>

      <?php require_once 'includes/footer.php'; ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  <script src="Public/js/debug-mobile.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof feather !== 'undefined') {
        feather.replace();
      }

      // Onglets types d'agents (roulements)
      const tabs = document.querySelectorAll('.roulements-tabs .settings-tab');
      const tabContents = document.querySelectorAll('.roulements-tab-content');
      tabs.forEach(tab => {
        tab.addEventListener('click', function () {
          const targetTab = this.dataset.tab;
          tabs.forEach(t => t.classList.remove('active'));
          tabContents.forEach(tc => tc.classList.remove('active'));
          this.classList.add('active');
          const content = document.getElementById('tab-' + targetTab);
          if (content) {
            content.classList.add('active');
          }
          if (typeof feather !== 'undefined') {
            feather.replace();
          }
        });
      });

      // Filtre de recherche d'agent (par nom ou matricule) - seulement dans l'onglet CONTROLEURS
      const searchInput = document.getElementById('searchAgent');
      if (searchInput) {
        searchInput.addEventListener('input', function () {
          const term = this.value.toLowerCase();
          document.querySelectorAll('#tab-controleurs table tbody tr').forEach(row => {
            const nom = (row.querySelector('td:nth-child(1)')?.textContent || '').toLowerCase();
            const matricule = (row.querySelector('td:nth-child(2)')?.textContent || '').toLowerCase();
            row.style.display = (!term || nom.includes(term) || matricule.includes(term)) ? '' : 'none';
          });
        });
      }

      const searchInputChef = document.getElementById('searchChef');
      if (searchInputChef) {
        searchInputChef.addEventListener('input', function () {
          const term = this.value.toLowerCase();
          document.querySelectorAll('#tab-chef-secteur table tbody tr').forEach(row => {
            const nom = (row.querySelector('td:nth-child(1)')?.textContent || '').toLowerCase();
            const matricule = (row.querySelector('td:nth-child(2)')?.textContent || '').toLowerCase();
            row.style.display = (!term || nom.includes(term) || matricule.includes(term)) ? '' : 'none';
          });
        });
      }

      const searchInputRenfort = document.getElementById('searchRenfort');
      if (searchInputRenfort) {
        searchInputRenfort.addEventListener('input', function () {
          const term = this.value.toLowerCase();
          document.querySelectorAll('#tab-renfort-fixes table tbody tr').forEach(row => {
            const nom = (row.querySelector('td:nth-child(1)')?.textContent || '').toLowerCase();
            const matricule = (row.querySelector('td:nth-child(2)')?.textContent || '').toLowerCase();
            row.style.display = (!term || nom.includes(term) || matricule.includes(term)) ? '' : 'none';
          });
        });
      }

      const searchInputInterurbain = document.getElementById('searchInterurbain');
      if (searchInputInterurbain) {
        searchInputInterurbain.addEventListener('input', function () {
          const term = this.value.toLowerCase();
          document.querySelectorAll('#tab-interurbain table tbody tr').forEach(row => {
            const nom = (row.querySelector('td:nth-child(1)')?.textContent || '').toLowerCase();
            const matricule = (row.querySelector('td:nth-child(2)')?.textContent || '').toLowerCase();
            row.style.display = (!term || nom.includes(term) || matricule.includes(term)) ? '' : 'none';
          });
        });
      }

      // Gestion du modal de choix de shift (uniquement pour l'onglet CONTROLEURS)
      const modal = document.getElementById('modalChoixShift');
      if (!modal) return;

      let celluleActive = null;

      function ouvrirModalPourCellule(cell) {
        celluleActive = cell;
        modal.classList.add('active');
      }

      function fermerModal() {
        modal.classList.remove('active');
        celluleActive = null;
      }

      document.querySelectorAll('#tab-controleurs .cell-roulement, #tab-chef-secteur .cell-roulement, #tab-renfort-fixes .cell-roulement, #tab-interurbain .cell-roulement').forEach(cell => {
        cell.addEventListener('click', function () {
          ouvrirModalPourCellule(this);
        });
      });

      modal.querySelectorAll('[data-shift-value]').forEach(btn => {
        btn.addEventListener('click', function () {
          if (!celluleActive) return;
          const valeur = this.getAttribute('data-shift-value');
          celluleActive.textContent = valeur;
          fermerModal();
        });
      });

      const overlay = modal.querySelector('.modal__overlay');
      const btnClose = document.getElementById('btnCloseModalShift');
      const btnAnnuler = document.getElementById('btnAnnulerModalShift');

      if (overlay) overlay.addEventListener('click', fermerModal);
      if (btnClose) btnClose.addEventListener('click', fermerModal);
      if (btnAnnuler) btnAnnuler.addEventListener('click', fermerModal);
    });
  </script>
</body>
</html>
