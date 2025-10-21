<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Safari • Smart mobility</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    #mapCanvas {
      width: 100%;
      height: 600px;
      background: #e5e7eb;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_PL.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Suivi temps réel de la flotte</h1>
          <p>Visualisation dynamique des bus sur le réseau</p>
        </div>
        <div class="header__actions">
          <a href="alerter" class="icon-btn" title="Alertes & Notifications" style="position: relative; text-decoration: none; color: inherit;">
            <i data-feather="bell"></i>
            <?php if (isset($nombreAlertesNonTraitees) && $nombreAlertesNonTraitees > 0): ?>
              <span style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; border: 2px solid white;">
                <?= $nombreAlertesNonTraitees > 99 ? '99+' : $nombreAlertesNonTraitees ?>
              </span>
            <?php endif; ?>
          </a>
        </div>
      </header>

      <!-- Stats rapides -->
      <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div class="card" style="padding: 16px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
              <i data-feather="truck" style="color: white; width: 20px; height: 20px;"></i>
            </div>
            <div>
              <div style="font-size: 24px; font-weight: 700; color: #111827;"><?= count(array_filter($buses, fn($b) => $b['statut'] === 'actif')) ?></div>
              <div style="font-size: 14px; color: #6b7280;">Bus actifs</div>
            </div>
          </div>
        </div>
        <div class="card" style="padding: 16px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
              <i data-feather="tool" style="color: white; width: 20px; height: 20px;"></i>
            </div>
            <div>
              <div style="font-size: 24px; font-weight: 700; color: #111827;"><?= count(array_filter($buses, fn($b) => $b['statut'] === 'maintenance')) ?></div>
              <div style="font-size: 14px; color: #6b7280;">En maintenance</div>
            </div>
          </div>
        </div>
        <div class="card" style="padding: 16px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
              <i data-feather="alert-circle" style="color: white; width: 20px; height: 20px;"></i>
            </div>
            <div>
              <div style="font-size: 24px; font-weight: 700; color: #111827;"><?= count(array_filter($buses, fn($b) => $b['statut'] === 'panne')) ?></div>
              <div style="font-size: 14px; color: #6b7280;">En panne</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter bar -->
      <section class="filters card">
        <div class="filters__title">
          <span class="dot dot--success"></span>
          Carte temps réel
        </div>
        <div class="filters__controls">
          <select id="filterLigne">
            <option value="">Toutes lignes</option>
            <?php foreach ($lignes as $ligne): ?>
              <option value="<?= $ligne['id'] ?>"><?= htmlspecialchars($ligne['nom']) ?></option>
            <?php endforeach; ?>
          </select>
          <select id="filterStatut">
            <option value="">Tous statuts</option>
            <option value="actif">Actif</option>
            <option value="maintenance">Maintenance</option>
            <option value="panne">En panne</option>
          </select>
          <input type="text" id="filterNumero" placeholder="Numéro de bus...">
          <button class="btn btn--primary" id="btnFiltrer">Filtrer</button>
          <button class="btn btn--secondary" id="btnReset">Réinitialiser</button>
        </div>
      </section>

      <section class="content-grid">
        <!-- Map card -->
        <div class="map card">
          <div class="map__canvas" id="mapCanvas" aria-label="Carte Google Maps avec bus en temps réel">
            <!-- Google Maps chargée ici -->
          </div>
        </div>

        <!-- Bus info card (caché par défaut) -->
        <aside class="bus card" id="busInfoPanel" style="display: none;">
          <div class="bus__header">
            <div>
              <div class="bus__title">Bus #421 <span class="badge badge--green">Actif</span></div>
              <div class="bus__subtitle">Ligne 2 • Zone Centre</div>
            </div>
            <div class="bus__avatar"><i data-feather="truck"></i></div>
          </div>

          <ul class="bus__stats list">
            <li><i data-feather="map-pin"></i> <strong>Position :</strong> Silikin, Kinshasa</li>
            <li><i data-feather="zap"></i> <strong>Vitesse :</strong> 36 km/h</li>
            <li><i data-feather="droplet"></i> <strong>Carburant :</strong> <span class="meter" data-value="58"></span> 58 %</li>
            <li><i data-feather="thermometer"></i> <strong>Température :</strong> 21°C</li>
            <li><i data-feather="cpu"></i> <strong>Modules actifs :</strong> Datcha WIFI POS</li>
          </ul>

          <div class="divider"></div>

          <div class="bus__route" id="busRoute">
            <div class="route__title">Trajet en cours</div>
            <div class="route__stops">
              <div class="stop">
                <div class="stop__name" id="routeDepart">-</div>
                <div class="stop__time" id="routeHeureDepart">-</div>
              </div>
              <div class="route__progress">
                <div class="progress__bar">
                  <span class="progress__value" id="routeProgressBar" style="width: 0%"></span>
                </div>
                <div class="progress__scale">
                  <span>0%</span><span>50%</span><span>100%</span>
                </div>
              </div>
              <div class="stop stop--dest">
                <div class="stop__name" id="routeArrivee">-</div>
                <div class="stop__time stop__time--warn" id="routeHeureArrivee">-</div>
              </div>
            </div>
          </div>

          <div class="divider"></div>

          <!-- Section dépliable : Informations détaillées -->
          <div class="bus__details">
            <button class="bus__details-toggle" onclick="toggleBusDetails(this)">
              <span>Informations détaillées</span>
              <i data-feather="chevron-down"></i>
            </button>
            
            <div class="bus__details-content" style="display: none;">
              <!-- Équipe Shift 1 (Matin) -->
              <div class="details__section">
                <div class="details__section-title">
                  <i data-feather="sun"></i>
                  Équipe Shift 1 - Matin (06h00 - 14h00)
                </div>
                
                <!-- Chauffeur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--driver">
                      <i data-feather="user"></i>
                      Chauffeur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Jean-Pierre Mukendi</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">DRV-2024-158</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 812 345 678</span>
                    </div>
                  </div>
                </div>

                <!-- Receveur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--receiver">
                      <i data-feather="credit-card"></i>
                      Receveur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Marie Tshala</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">RCV-2024-089</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 823 456 789</span>
                    </div>
                  </div>
                </div>

                <!-- Contrôleur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--controller">
                      <i data-feather="clipboard"></i>
                      Contrôleur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Patrick Kabongo</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">CTR-2024-045</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 834 567 890</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="divider"></div>

              <!-- Équipe Shift 2 (Après-midi) -->
              <div class="details__section">
                <div class="details__section-title">
                  <i data-feather="moon"></i>
                  Équipe Shift 2 - Après-midi (14h00 - 22h00)
                </div>
                
                <!-- Chauffeur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--driver">
                      <i data-feather="user"></i>
                      Chauffeur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Joseph Kalala</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">DRV-2024-201</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 845 678 901</span>
                    </div>
                  </div>
                </div>

                <!-- Receveur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--receiver">
                      <i data-feather="credit-card"></i>
                      Receveur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Grace Mbuyi</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">RCV-2024-112</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 856 789 012</span>
                    </div>
                  </div>
                </div>

                <!-- Contrôleur -->
                <div class="team__member">
                  <div class="member__header">
                    <div class="member__role member__role--controller">
                      <i data-feather="clipboard"></i>
                      Contrôleur
                    </div>
                  </div>
                  <div class="member__info">
                    <div class="info__row">
                      <span class="info__label">Nom :</span>
                      <span class="info__value">Daniel Ilunga</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Matricule :</span>
                      <span class="info__value">CTR-2024-078</span>
                    </div>
                    <div class="info__row">
                      <span class="info__label">Téléphone :</span>
                      <span class="info__value">+243 867 890 123</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="divider"></div>

              <!-- Documents de bord -->
              <div class="details__section">
                <div class="details__section-title">
                  <i data-feather="file-text"></i>
                  Documents de bord
                </div>
                <div class="details__documents">
                  <div class="document__item">
                    <div class="document__icon document__icon--valid">
                      <i data-feather="check-circle"></i>
                    </div>
                    <div class="document__info">
                      <div class="document__name">Assurance</div>
                      <div class="document__status">Valide jusqu'au 15/12/2025</div>
                    </div>
                  </div>
                  
                  <div class="document__item">
                    <div class="document__icon document__icon--valid">
                      <i data-feather="check-circle"></i>
                    </div>
                    <div class="document__info">
                      <div class="document__name">Contrôle technique</div>
                      <div class="document__status">Valide jusqu'au 20/08/2025</div>
                    </div>
                  </div>
                  
                  <div class="document__item">
                    <div class="document__icon document__icon--warning">
                      <i data-feather="alert-triangle"></i>
                    </div>
                    <div class="document__info">
                      <div class="document__name">Vignette</div>
                      <div class="document__status document__status--warning">Expire dans 15 jours</div>
                    </div>
                  </div>
                  
                  <div class="document__item">
                    <div class="document__icon document__icon--valid">
                      <i data-feather="check-circle"></i>
                    </div>
                    <div class="document__info">
                      <div class="document__name">Permis de circulation</div>
                      <div class="document__status">Valide jusqu'au 30/11/2025</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </section>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  
  <script>
    // Données PHP vers JavaScript
    const busesData = <?= json_encode($buses) ?>;
    const trajetsData = <?= json_encode($trajets) ?>;
    const arretsData = <?= json_encode($arrets) ?>;
    const pointsShiftData = <?= json_encode($pointsShift) ?>;
    
    // LOGS : Vérifier les données reçues
    console.log('=== DONNÉES REÇUES ===');
    console.log('Bus:', busesData.length);
    console.log('Trajets:', trajetsData.length);
    console.log('Arrêts:', arretsData.length);
    console.log('Points shift:', pointsShiftData.length);
    
    // Vérifier les positions des bus
    busesData.forEach(bus => {
      if (bus.position && bus.position.lat && bus.position.lng) {
        console.log(`✅ Bus #${bus.numero}: Position OK (${bus.position.lat}, ${bus.position.lng})`);
      } else {
        console.warn(`❌ Bus #${bus.numero}: Pas de position`, bus.position);
      }
    });
    
    let map;
    let markers = [];
    let infoWindows = [];
    let polylines = []; // Lignes des trajets
    let busActuel = null; // Bus sélectionné
    let busFiltered = [...busesData]; // Bus filtrés (copie)

    // Initialiser Google Maps
    function initMap() {
      console.log('=== INITIALISATION GOOGLE MAPS ===');
      
      // Vérifier que Google Maps est chargé
      if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error('❌ Google Maps pas encore chargé, nouvelle tentative dans 500ms...');
        setTimeout(initMap, 500);
        return;
      }
      
      // Centre sur Kinshasa
      const center = { lat: -4.3276, lng: 15.3136 };
      
      try {
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
          zoom: 13,
          center: center,
          mapTypeControl: true,
          streetViewControl: false,
          fullscreenControl: true
        });
        
        console.log('✅ Carte initialisée');

        // Afficher uniquement les bus
        afficherBus(busesData);
        
        // Actualiser toutes les 30 secondes
        setInterval(actualiserBus, 30000);
      } catch (error) {
        console.error('❌ Erreur initialisation carte:', error);
        setTimeout(initMap, 500);
      }
    }
    
    // S'assurer que initMap est disponible globalement
    window.initMap = initMap;
    
    // Fallback : Si Google Maps est déjà chargé, initialiser immédiatement
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
      console.log('🔄 Google Maps déjà chargé, initialisation immédiate');
      initMap();
    } else {
      console.log('⏳ En attente du chargement de Google Maps...');
    }

    // Afficher les bus sur la carte
    function afficherBus(buses) {
      console.log('=== AFFICHAGE DES BUS ===');
      console.log('Nombre de bus à afficher:', buses.length);
      
      // Vérifier que la carte est initialisée
      if (!map) {
        console.error('❌ Carte non initialisée, impossible d\'afficher les bus');
        return;
      }
      
      // Supprimer anciens marqueurs
      markers.forEach(m => m.setMap(null));
      infoWindows.forEach(iw => iw.close());
      markers = [];
      infoWindows = [];

      let busAffiches = 0;
      buses.forEach((bus, index) => {
        console.log(`Bus #${bus.numero}:`, bus.position);
        
        if (!bus.position || !bus.position.lat || !bus.position.lng) {
          console.warn(`⚠️ Bus #${bus.numero} ignoré: pas de position valide`);
          return;
        }
        
        busAffiches++;

        // Créer un marqueur avec le numéro du bus
        // Créer une icône de bus SVG personnalisée (plus grande et lisible)
        const busColor = getColorByStatus(bus.statut);
        const busIcon = {
          url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60">
              <!-- Ombre -->
              <ellipse cx="30" cy="52" rx="18" ry="4" fill="rgba(0,0,0,0.3)"/>
              
              <!-- Corps du bus -->
              <rect x="10" y="15" width="40" height="28" rx="4" fill="${busColor}" stroke="#fff" stroke-width="2.5"/>
              
              <!-- Fenêtres -->
              <rect x="13" y="19" width="14" height="10" rx="1.5" fill="#fff" opacity="0.95"/>
              <rect x="33" y="19" width="14" height="10" rx="1.5" fill="#fff" opacity="0.95"/>
              
              <!-- Roues -->
              <circle cx="20" cy="43" r="4.5" fill="#333" stroke="#fff" stroke-width="1.5"/>
              <circle cx="40" cy="43" r="4.5" fill="#333" stroke="#fff" stroke-width="1.5"/>
              
              <!-- Badge pour le numéro (fond blanc) -->
              <rect x="18" y="31" width="24" height="10" rx="2" fill="#fff" opacity="0.95"/>
              
              <!-- Numéro du bus (plus grand et en noir sur fond blanc) -->
              <text x="30" y="39" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" font-weight="bold" fill="#000">${bus.numero}</text>
            </svg>
          `)}`,
          scaledSize: new google.maps.Size(60, 60),
          anchor: new google.maps.Point(30, 52),
          labelOrigin: new google.maps.Point(30, -8)
        };
        
        const marker = new google.maps.Marker({
          position: { lat: parseFloat(bus.position.lat), lng: parseFloat(bus.position.lng) },
          map: map,
          icon: busIcon,
          title: `Bus #${bus.numero} - ${bus.statut}`
        });

        const infoWindow = new google.maps.InfoWindow({
          content: `
            <div style="padding: 8px; min-width: 220px;">
              <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">Bus #${bus.numero}</h3>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Statut:</strong> <span style="color: ${getColorByStatus(bus.statut)}">${bus.statut}</span></p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Ligne:</strong> ${bus.trajet_nom || 'Non affectée'}</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Immatriculation:</strong> ${bus.immatriculation}</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Vitesse:</strong> ${bus.position.vitesse} km/h</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Carburant:</strong> ${bus.position.carburant}%</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Position:</strong> ${bus.position.localisation}</p>
            </div>
          `
        });

        marker.addListener('click', () => {
          console.log(`🖱️ Clic sur bus #${bus.numero}`);
          
          // Fermer toutes les infoWindows
          infoWindows.forEach(iw => iw.close());
          
          // Afficher les infos du bus dans le panneau de droite
          afficherInfosBus(bus);
          
          // Afficher le panneau de droite
          const panel = document.getElementById('busInfoPanel');
          if (panel) {
            panel.style.display = 'block';
          }
        });

        markers.push(marker);
        infoWindows.push(infoWindow);
      });
      
      console.log(`✅ ${busAffiches} bus affichés sur la carte`);
      
      if (busAffiches === 0) {
        console.error('❌ AUCUN BUS AFFICHÉ ! Vérifiez les positions GPS dans la BDD');
      }
    }

    // ========================================
    // FILTRES
    // ========================================
    
    // Filtrer les bus selon les critères
    function filtrerBus() {
      const filterLigne = document.getElementById('filterLigne').value;
      const filterStatut = document.getElementById('filterStatut').value;
      const filterNumero = document.getElementById('filterNumero').value.trim().toLowerCase();
      
      console.log('=== FILTRAGE ===');
      console.log('Ligne:', filterLigne || 'Toutes');
      console.log('Statut:', filterStatut || 'Tous');
      console.log('Numéro:', filterNumero || 'Tous');
      
      busFiltered = busesData.filter(bus => {
        // Filtre par ligne
        if (filterLigne && bus.ligne_affectee != filterLigne) {
          return false;
        }
        
        // Filtre par statut
        if (filterStatut && bus.statut !== filterStatut) {
          return false;
        }
        
        // Filtre par numéro
        if (filterNumero && !bus.numero.toLowerCase().includes(filterNumero)) {
          return false;
        }
        
        return true;
      });
      
      console.log(`✅ ${busFiltered.length} bus après filtrage`);
      
      // Réafficher les bus filtrés
      afficherBus(busFiltered);
    }
    
    // Réinitialiser les filtres
    function resetFiltres() {
      document.getElementById('filterLigne').value = '';
      document.getElementById('filterStatut').value = '';
      document.getElementById('filterNumero').value = '';
      
      busFiltered = [...busesData];
      afficherBus(busFiltered);
      
      console.log('🔄 Filtres réinitialisés');
    }
    
    // Event listeners pour les filtres
    document.addEventListener('DOMContentLoaded', () => {
      const btnFiltrer = document.getElementById('btnFiltrer');
      const btnReset = document.getElementById('btnReset');
      const filterNumero = document.getElementById('filterNumero');
      
      if (btnFiltrer) {
        btnFiltrer.addEventListener('click', filtrerBus);
      }
      
      if (btnReset) {
        btnReset.addEventListener('click', resetFiltres);
      }
      
      // Filtrer en appuyant sur Entrée dans le champ numéro
      if (filterNumero) {
        filterNumero.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            filtrerBus();
          }
        });
      }
    });

    // Afficher les infos d'un bus dans le panneau de droite
    function afficherInfosBus(bus) {
      console.log('📋 Affichage infos bus:', bus);
      busActuel = bus;
      
      // Mettre à jour le titre
      const titre = document.querySelector('.bus__title');
      if (titre) {
        const badge = bus.statut === 'actif' ? 'badge--green' : 
                      bus.statut === 'maintenance' ? 'badge--orange' : 'badge--red';
        titre.innerHTML = `Bus #${bus.numero} <span class="badge ${badge}">${bus.statut}</span>`;
      }
      
      // Mettre à jour le sous-titre
      const sousTitre = document.querySelector('.bus__subtitle');
      if (sousTitre) {
        sousTitre.textContent = `${bus.trajet_nom || 'Ligne non affectée'} • ${bus.immatriculation}`;
      }
      
      // Mettre à jour les stats
      const stats = document.querySelector('.bus__stats');
      if (stats) {
        const vitesse = bus.position.vitesse === '-' ? '-' : `${bus.position.vitesse} km/h`;
        const carburant = bus.position.carburant === '-' ? '-' : `${bus.position.carburant} %`;
        const temperature = bus.position.temperature === '-' ? '-' : `${bus.position.temperature}°C`;
        
        stats.innerHTML = `
          <li><i data-feather="map-pin"></i> <strong>Position :</strong> ${bus.position.localisation}</li>
          <li><i data-feather="zap"></i> <strong>Vitesse :</strong> ${vitesse}</li>
          <li><i data-feather="droplet"></i> <strong>Carburant :</strong> ${carburant}</li>
          <li><i data-feather="thermometer"></i> <strong>Température :</strong> ${temperature}</li>
          <li><i data-feather="cpu"></i> <strong>Modules actifs :</strong> ${bus.modules || '-'}</li>
        `;
        feather.replace();
      }
      
      // Mettre à jour le trajet en cours
      afficherTrajetEnCours(bus);
    }
    
    // Afficher le trajet en cours du bus
    function afficherTrajetEnCours(bus) {
      // Vérifier si le bus a un trajet affecté
      if (!bus.trajet_nom || !bus.ligne_affectee) {
        // Pas de trajet affecté
        document.getElementById('routeDepart').textContent = 'Aucun trajet';
        document.getElementById('routeHeureDepart').textContent = '-';
        document.getElementById('routeArrivee').textContent = '-';
        document.getElementById('routeHeureArrivee').textContent = '-';
        document.getElementById('routeProgressBar').style.width = '0%';
        return;
      }
      
      // Trouver le trajet correspondant
      const trajet = trajetsData.find(t => t.id == bus.ligne_affectee);
      
      if (!trajet) {
        document.getElementById('routeDepart').textContent = bus.trajet_nom || 'Trajet inconnu';
        document.getElementById('routeHeureDepart').textContent = '-';
        document.getElementById('routeArrivee').textContent = '-';
        document.getElementById('routeHeureArrivee').textContent = '-';
        document.getElementById('routeProgressBar').style.width = '0%';
        return;
      }
      
      // Calculer les données du trajet
      const heureDepart = genererHeureDepart();
      const progression = calculerProgression(bus);
      const tempsRestant = calculerTempsRestant(progression, trajet.duree_estimee || 45);
      
      // Extraire les noms de départ et d'arrivée depuis le nom du trajet
      // Format attendu: "Centre ville - Kasapa"
      const parties = trajet.nom.split(' - ');
      const nomDepart = parties[0] || 'Départ';
      const nomArrivee = parties[1] || 'Arrivée';
      
      // Mettre à jour l'interface
      document.getElementById('routeDepart').textContent = nomDepart;
      document.getElementById('routeHeureDepart').textContent = `Départ: ${heureDepart}`;
      document.getElementById('routeArrivee').textContent = nomArrivee;
      document.getElementById('routeHeureArrivee').textContent = `Arrivée estimée: ${tempsRestant} min`;
      document.getElementById('routeProgressBar').style.width = `${progression}%`;
      
      console.log(`📍 Trajet: ${nomDepart} → ${nomArrivee} (${progression}%)`);
    }
    
    // Générer une heure de départ simulée
    function genererHeureDepart() {
      const now = new Date();
      const minutesAgo = Math.floor(Math.random() * 30) + 10; // Entre 10 et 40 min
      now.setMinutes(now.getMinutes() - minutesAgo);
      return now.toTimeString().substring(0, 5); // Format HH:MM
    }
    
    // Calculer la distance entre deux points GPS (formule de Haversine)
    function calculerDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Rayon de la Terre en km
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      
      const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
      
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      const distance = R * c; // Distance en km
      
      return distance;
    }
    
    // Calculer la progression du trajet (0-100%) basée sur les positions GPS réelles
    function calculerProgression(bus) {
      // Trouver le trajet correspondant
      const trajet = trajetsData.find(t => t.id == bus.ligne_affectee);
      
      if (!trajet || !trajet.coordonnees || !trajet.coordonnees.depart || !trajet.coordonnees.arrivee) {
        // Pas de coordonnées de trajet, utiliser une simulation
        console.warn('⚠️ Pas de coordonnées GPS pour le trajet, simulation utilisée');
        if (bus.statut === 'actif') {
          return Math.floor(Math.random() * 70) + 20;
        } else {
          return Math.floor(Math.random() * 30);
        }
      }
      
      // Coordonnées du début et de la fin du trajet
      const debut = trajet.coordonnees.depart;
      const fin = trajet.coordonnees.arrivee;
      
      // Position actuelle du bus
      const busLat = parseFloat(bus.position.lat);
      const busLng = parseFloat(bus.position.lng);
      
      // Distance totale du trajet (début → fin)
      const distanceTotale = calculerDistance(
        parseFloat(debut.lat), 
        parseFloat(debut.lng),
        parseFloat(fin.lat), 
        parseFloat(fin.lng)
      );
      
      // Distance parcourue (début → bus)
      const distanceParcourue = calculerDistance(
        parseFloat(debut.lat), 
        parseFloat(debut.lng),
        busLat, 
        busLng
      );
      
      // Calculer le pourcentage
      let progression = (distanceParcourue / distanceTotale) * 100;
      
      // Limiter entre 0 et 100%
      progression = Math.max(0, Math.min(100, progression));
      
      console.log(`📏 Distance totale: ${distanceTotale.toFixed(2)} km`);
      console.log(`📏 Distance parcourue: ${distanceParcourue.toFixed(2)} km`);
      console.log(`📊 Progression: ${progression.toFixed(1)}%`);
      
      return Math.round(progression);
    }
    
    // Calculer le temps restant en minutes
    function calculerTempsRestant(progression, dureeTotal) {
      const tempsEcoule = (progression / 100) * dureeTotal;
      const tempsRestant = Math.max(1, Math.round(dureeTotal - tempsEcoule));
      return tempsRestant;
    }
    
    // Actualiser les positions
    async function actualiserBus() {
      console.log('🔄 Actualisation des positions...');
      try {
        const response = await fetch('/dashboard/donnees');
        
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('📥 Données reçues:', data);
        
        if (data.success) {
          afficherBus(data.data.buses);
          console.log('✅ Actualisation réussie');
        } else {
          console.error('❌ Erreur serveur:', data.message);
        }
      } catch (error) {
        console.error('❌ Erreur actualisation:', error);
      }
    }

    // Couleur selon statut
    function getColorByStatus(statut) {
      switch(statut) {
        case 'actif': return '#10b981';
        case 'maintenance': return '#f59e0b';
        case 'panne': return '#ef4444';
        case 'inactif': return '#6b7280';
        default: return '#6b7280';
      }
    }

    feather.replace();

    // Fonction pour toggle les détails du bus
    function toggleBusDetails(button) {
      const content = button.nextElementSibling;
      const isVisible = content.style.display !== 'none';
      
      if (isVisible) {
        content.style.display = 'none';
        button.classList.remove('active');
      } else {
        content.style.display = 'block';
        button.classList.add('active');
        // Réinitialiser les icônes Feather après affichage
        setTimeout(() => feather.replace(), 10);
      }
    }
  </script>
  
  <!-- Google Maps API - Chargé en dernier après la définition de initMap -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCokbp76WRQybewzj87ZwNeT6xdplTSyPA&callback=initMap" async defer></script>
</body>
</html>
