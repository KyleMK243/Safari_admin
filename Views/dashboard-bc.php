<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Safari • Bureau de conception</title>
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
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Dashboard • Bureau de conception</h1>
          <p>Suivi des lignes et trajets proposés par le bureau de conception</p>
        </div>
      </header>

      <!-- Filtres trajets Bureau de conception -->
      <section class="filters card">
        <div class="filters__title">
          <span class="dot dot--success"></span>
          Filtres des trajets du Bureau de conception
        </div>
        <div class="filters__controls">
          <input type="text" id="filtreNomTrajetBC" placeholder="Nom ou code de trajet...">
          <select id="filtreSecteurBC">
            <option value="">Tous secteurs</option>
            <?php if (!empty($secteursBC ?? [])): ?>
              <?php foreach ($secteursBC as $secteur): ?>
                <option value="<?php echo htmlspecialchars($secteur); ?>"><?php echo htmlspecialchars($secteur); ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <button class="btn btn--primary" id="btnFiltrerBC">Filtrer</button>
          <button class="btn btn--secondary" id="btnResetBC">Réinitialiser</button>
        </div>
      </section>

      <!-- Carte Google Maps des trajets du Bureau de conception -->
      <section class="content-grid" style="margin-top: 24px;">
        <!-- Carte -->
        <div class="map card">
          <div class="map__canvas" id="mapCanvasBC" aria-label="Carte Google Maps des trajets du Bureau de conception"></div>
        </div>

        <!-- Panneau d'informations de la ligne / trajet sélectionné -->
        <aside class="bus card" id="trajetInfoPanel" style="display: none;">
          <div class="bus__header">
            <div>
              <div class="bus__title" id="trajetTitre">Trajet</div>
              <div class="bus__subtitle" id="trajetSousTitre">Sélectionnez un trajet sur la carte</div>
            </div>
            <div class="bus__avatar"><i data-feather="map"></i></div>
          </div>

          <ul class="bus__stats list">
            <li><i data-feather="activity"></i> <strong>Distance :</strong> <span id="trajetDistance">-</span> km</li>
            <li><i data-feather="clock"></i> <strong>Durée estimée :</strong> <span id="trajetDuree">-</span> min</li>
            <li><i data-feather="toggle-right"></i> <strong>Statut :</strong> <span id="trajetStatut">-</span></li>
            <li><i data-feather="map-pin"></i> <strong>Nombre d'arrêts :</strong> <span id="trajetNbArrets">-</span></li>
            <li><i data-feather="flag"></i> <strong>Points de roulement / prise de service :</strong> <span id="trajetNbPointsShift">-</span></li>
            <li><i data-feather="truck"></i> <strong>Bus actifs :</strong> <span class="badge badge--green" id="trajetBusActifs">0</span></li>
            <li><i data-feather="tool"></i> <strong>Bus en maintenance :</strong> <span class="badge badge--warning" id="trajetBusMaintenance">0</span></li>
            <li><i data-feather="alert-triangle"></i> <strong>Bus en panne :</strong> <span class="badge badge--danger" id="trajetBusPanne">0</span></li>
          </ul>
        </aside>
      </section>

      <?php require_once 'includes/footer.php'; ?>
    </main>
  </div>

  <script src="Public/js/app.js"></script>
  <script src="Public/js/debug-mobile.js"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }

    const trajetsBCData = <?php echo json_encode($trajetsBC ?? []); ?>;
    let mapBC;
    let polylinesBC = [];
    let markersBC = [];
    const trajetsBCOrigin = Array.isArray(trajetsBCData) ? trajetsBCData : [];

    function getCouleurTrajetBC(trajet) {
      if (trajet.couleur && trajet.couleur.trim() !== '') {
        return trajet.couleur;
      }

      const colors = [
        '#3b82f6',
        '#ef4444',
        '#10b981',
        '#f59e0b',
        '#8b5cf6',
        '#ec4899',
        '#06b6d4',
        '#f97316',
        '#14b8a6',
        '#a855f7'
      ];

      let hash = 0;
      if (trajet.code) {
        for (let i = 0; i < trajet.code.length; i++) {
          hash = trajet.code.charCodeAt(i) + ((hash << 5) - hash);
        }
      }

      return colors[Math.abs(hash) % colors.length];
    }

    function initBCMap() {
      if (typeof google === 'undefined' || !document.getElementById('mapCanvasBC')) {
        setTimeout(initBCMap, 500);
        return;
      }

      let center = { lat: -11.6667, lng: 27.4833 };
      const first = trajetsBCData.find(t => t.coordonnees && t.coordonnees.length > 0);
      if (first && first.coordonnees[0]) {
        const p = first.coordonnees[0];
        center = { lat: parseFloat(p.lat), lng: parseFloat(p.lng) };
      }

      mapBC = new google.maps.Map(document.getElementById('mapCanvasBC'), {
        zoom: 13,
        center: center,
        mapTypeControl: true,
        streetViewControl: false,
        fullscreenControl: true
      });

      appliquerFiltresBC();
    }

    function afficherTrajetBC(trajet) {
      const path = [];

      if (trajet.coordonnees && trajet.coordonnees.length > 0) {
        trajet.coordonnees.forEach(pt => {
          if (pt.lat && pt.lng) {
            path.push({
              lat: parseFloat(pt.lat),
              lng: parseFloat(pt.lng)
            });
          }
        });
      } else if (trajet.latitude_depart && trajet.longitude_depart && trajet.latitude_arrivee && trajet.longitude_arrivee) {
        path.push({
          lat: parseFloat(trajet.latitude_depart),
          lng: parseFloat(trajet.longitude_depart)
        });
        path.push({
          lat: parseFloat(trajet.latitude_arrivee),
          lng: parseFloat(trajet.longitude_arrivee)
        });
      }

      if (path.length < 2) {
        return;
      }

      const couleur = getCouleurTrajetBC(trajet);

      const polyline = new google.maps.Polyline({
        path: path,
        strokeColor: couleur,
        strokeOpacity: 0.9,
        strokeWeight: 5,
        map: mapBC
      });

      polyline.addListener('click', function () {
        surlignerTrajetBC(polyline);
        afficherInfosTrajetBC(trajet);
        centrerSurTrajetBC(path);
      });

      polylinesBC.push({ polyline: polyline, id: trajet.id });

      const markerDepart = new google.maps.Marker({
        position: path[0],
        map: mapBC,
        title: 'Départ ' + (trajet.nom || ''),
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 8,
          fillColor: '#10b981',
          fillOpacity: 1,
          strokeColor: '#ffffff',
          strokeWeight: 3
        }
      });

      const markerArrivee = new google.maps.Marker({
        position: path[path.length - 1],
        map: mapBC,
        title: 'Arrivée ' + (trajet.nom || ''),
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 8,
          fillColor: '#ef4444',
          fillOpacity: 1,
          strokeColor: '#ffffff',
          strokeWeight: 3
        }
      });

      markersBC.push(markerDepart);
      markersBC.push(markerArrivee);
    }

    function surlignerTrajetBC(polylineActif) {
      polylinesBC.forEach(item => {
        item.polyline.setOptions({
          strokeOpacity: item.polyline === polylineActif ? 1 : 0.6,
          strokeWeight: item.polyline === polylineActif ? 7 : 5
        });
      });
    }

    function centrerSurTrajetBC(path) {
      const bounds = new google.maps.LatLngBounds();
      path.forEach(p => bounds.extend(p));
      mapBC.fitBounds(bounds);
    }

    function nettoyerCarteBC() {
      polylinesBC.forEach(item => {
        item.polyline.setMap(null);
      });
      polylinesBC = [];

      markersBC.forEach(marker => {
        marker.setMap(null);
      });
      markersBC = [];
    }

    function appliquerFiltresBC() {
      if (!mapBC) return;

      const inputNom = document.getElementById('filtreNomTrajetBC');
      const selectSecteur = document.getElementById('filtreSecteurBC');

      const terme = inputNom ? inputNom.value.trim().toLowerCase() : '';
      const secteur = selectSecteur ? selectSecteur.value : '';

      const filtrés = trajetsBCOrigin.filter(trajet => {
        const nom = ((trajet.nom || '') + ' ' + (trajet.code || '')).toLowerCase();
        const secteurTrajet = (trajet.secteur || '');

        if (terme && !nom.includes(terme)) {
          return false;
        }

        if (secteur && secteurTrajet !== secteur) {
          return false;
        }

        return true;
      });

      nettoyerCarteBC();
      filtrés.forEach(trajet => {
        afficherTrajetBC(trajet);
      });
    }

    function afficherInfosTrajetBC(trajet) {
      const panel = document.getElementById('trajetInfoPanel');
      if (!panel) return;
      panel.style.display = 'block';

      const titre = document.getElementById('trajetTitre');
      const sousTitre = document.getElementById('trajetSousTitre');
      const distance = document.getElementById('trajetDistance');
      const duree = document.getElementById('trajetDuree');
      const statut = document.getElementById('trajetStatut');
      const nbArrets = document.getElementById('trajetNbArrets');
      const nbPointsShift = document.getElementById('trajetNbPointsShift');
      const busActifs = document.getElementById('trajetBusActifs');
      const busMaintenance = document.getElementById('trajetBusMaintenance');
      const busPanne = document.getElementById('trajetBusPanne');

      if (titre) titre.textContent = trajet.code ? 'Ligne ' + trajet.code : 'Trajet';
      if (sousTitre) sousTitre.textContent = trajet.nom || '';
      if (distance) distance.textContent = trajet.distance_totale !== undefined ? trajet.distance_totale : '-';
      if (duree) duree.textContent = trajet.duree_estimee !== undefined ? trajet.duree_estimee : '-';
      if (statut) statut.textContent = trajet.statut || '-';

      const details = trajet.stats_detaillees || {};
      const nArrets = details.nb_arrets !== undefined ? details.nb_arrets : '-';
      const nPoints = details.nb_points_shift !== undefined ? details.nb_points_shift : '-';
      const nActifs = details.bus_actifs || 0;
      const nMaintenance = details.bus_maintenance || 0;
      const nPanne = details.bus_panne || 0;

      if (nbArrets) nbArrets.textContent = nArrets;
      if (nbPointsShift) nbPointsShift.textContent = nPoints;
      if (busActifs) busActifs.textContent = nActifs;
      if (busMaintenance) busMaintenance.textContent = nMaintenance;
      if (busPanne) busPanne.textContent = nPanne;

      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    }

    window.initBCMap = initBCMap;

    document.addEventListener('DOMContentLoaded', function () {
      const inputNom = document.getElementById('filtreNomTrajetBC');
      const selectSecteur = document.getElementById('filtreSecteurBC');
      const btnFiltrer = document.getElementById('btnFiltrerBC');
      const btnReset = document.getElementById('btnResetBC');

      function declencherFiltre() {
        appliquerFiltresBC();
      }

      if (btnFiltrer) {
        btnFiltrer.addEventListener('click', function (e) {
          e.preventDefault();
          declencherFiltre();
        });
      }

      if (btnReset) {
        btnReset.addEventListener('click', function (e) {
          e.preventDefault();
          if (inputNom) inputNom.value = '';
          if (selectSecteur) selectSecteur.value = '';
          declencherFiltre();
        });
      }

      if (inputNom) {
        inputNom.addEventListener('keyup', function (e) {
          if (e.key === 'Enter') {
            declencherFiltre();
          }
        });
      }

      if (selectSecteur) {
        selectSecteur.addEventListener('change', function () {
          declencherFiltre();
        });
      }
    });
  </script>
  <!-- Google Maps API pour le dashboard Bureau de conception -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCokbp76WRQybewzj87ZwNeT6xdplTSyPA&callback=initBCMap" async defer></script>
</body>
</html>
