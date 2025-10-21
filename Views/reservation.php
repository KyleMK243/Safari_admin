<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Réservation de Billets • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  <style>
    /* Personnalisation Select2 */
    .select2-container--default .select2-selection--single {
      height: 48px !important;
      border: 1px solid #d1d5db !important;
      border-radius: 8px !important;
      padding: 8px 12px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 32px !important;
      font-size: 16px !important;
      color: #374151 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 46px !important;
      right: 8px !important;
    }
    
    .select2-container--default .select2-results__option {
      padding: 10px 12px !important;
      font-size: 15px !important;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #1B4B7F !important;
    }
    
    .select2-dropdown {
      border: 1px solid #d1d5db !important;
      border-radius: 8px !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    
    .select2-search--dropdown .select2-search__field {
      border: 1px solid #d1d5db !important;
      border-radius: 6px !important;
      padding: 8px 12px !important;
      font-size: 15px !important;
    }
    
    .select2-container {
      width: 100% !important;
    }
  </style>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_BT.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Réservation de Billets</h1>
          <p>Réservation de places pour un voyage futur</p>
        </div>
      </header>

      <!-- Processus de vente par étapes -->
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Formulaire principal -->
        <div class="card">
          <div class="card__header">
            <h3>Réservation de billet - Processus guidé</h3>
          </div>
          <div style="padding: 24px;">
            <!-- Étape 1: Destination du client -->
            <div id="etape1" class="etape-vente">
              <div style="background: #dbeafe; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <div style="font-weight: 700; color: #1e40af; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="map-pin" style="width: 20px; height: 20px;"></i>
                  Étape 1/4 : Destination du client
                </div>
                <div style="font-size: 13px; color: #1e40af;">Choisissez la ligne, l'arrêt de montée et l'arrêt de descente</div>
              </div>

              <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 15px;">Ligne / Trajet *</label>
                <select class="form-control" id="ligneTrajet" required>
                  <option value="">Chargement des lignes...</option>
                </select>
              </div>

              <div class="form-group" id="groupeArretMontee" style="display: none; margin-bottom: 16px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 15px;">Arrêt de montée (Point de départ) *</label>
                <select class="form-control" id="arretMontee" required>
                  <option value="">Choisissez d'abord une ligne...</option>
                </select>
              </div>

              <div class="form-group" id="groupeArretDestination" style="display: none;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 15px;">Arrêt de descente (Destination) *</label>
                <select class="form-control" id="arretDestination" required>
                  <option value="">Choisissez d'abord une ligne...</option>
                </select>
              </div>

              <button type="button" class="btn btn--primary" onclick="allerEtape2()" id="btnEtape2" style="margin-top: 20px; width: 100%;" disabled>
                Continuer <i data-feather="arrow-right"></i>
              </button>
            </div>

            <!-- Étape 2: Bus disponibles -->
            <div id="etape2" class="etape-vente" style="display: none;">
              <div style="background: #dbeafe; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <div style="font-weight: 700; color: #1e40af; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="bus" style="width: 20px; height: 20px;"></i>
                  Étape 2/4 : Sélection du bus
                </div>
                <div style="font-size: 13px; color: #1e40af;">Choisissez le bus qui convient au client</div>
              </div>

              <div id="listeBusDisponibles" style="margin-bottom: 20px;">
                <!-- Les bus seront affichés ici dynamiquement -->
              </div>

              <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn--secondary" onclick="retourEtape1()">
                  <i data-feather="arrow-left"></i> Retour
                </button>
                <button type="button" class="btn btn--primary" onclick="allerEtape3()" id="btnEtape3" disabled style="flex: 1;">
                  Continuer <i data-feather="arrow-right"></i>
                </button>
              </div>
            </div>

            <!-- Étape 3: Informations client -->
            <div id="etape3" class="etape-vente" style="display: none;">
              <div style="background: #dbeafe; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <div style="font-weight: 700; color: #1e40af; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="user" style="width: 20px; height: 20px;"></i>
                  Étape 3/4 : Informations du client
                </div>
                <div style="font-size: 13px; color: #1e40af;">Nom et numéro de téléphone</div>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div class="form-group">
                  <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Nom complet *</label>
                  <input type="text" class="form-control" id="nomClient" placeholder="Ex: Jean Mukendi" required>
                </div>
                <div class="form-group">
                  <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Téléphone *</label>
                  <input type="tel" class="form-control" id="telClient" placeholder="+243 XXX XXX XXX" required>
                </div>
              </div>

              <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Mode de paiement *</label>
                <select class="form-control" id="modePaiement" required>
                  <option value="especes">Espèces</option>
                  <option value="mobile_money">Mobile Money</option>
                  <option value="carte_bancaire">Carte bancaire</option>
                </select>
              </div>

              <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn--secondary" onclick="retourEtape2()">
                  <i data-feather="arrow-left"></i> Retour
                </button>
                <button type="button" class="btn btn--primary" onclick="allerEtape4()" style="flex: 1;">
                  Continuer <i data-feather="arrow-right"></i>
                </button>
              </div>
            </div>

            <!-- Étape 4: Confirmation et impression -->
            <div id="etape4" class="etape-vente" style="display: none;">
              <div style="background: #dcfce7; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <div style="font-weight: 700; color: #166534; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                  <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
                  Étape 4/4 : Confirmation
                </div>
                <div style="font-size: 13px; color: #166534;">Vérifiez les informations avant de valider</div>
              </div>

              <div id="recapitulatifFinal" style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <!-- Récapitulatif sera affiché ici -->
              </div>

              <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn--secondary" onclick="retourEtape3()">
                  <i data-feather="arrow-left"></i> Retour
                </button>
                <button type="button" class="btn btn--primary" onclick="validerVente()" style="flex: 1; font-size: 16px; padding: 14px;">
                  <i data-feather="check-circle"></i> Valider et imprimer
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Récapitulatif -->
        <div>
          <div class="card" style="position: sticky; top: 20px;">
            <div class="card__header">
              <h3>Progression</h3>
            </div>
            <div style="padding: 20px;">
              <!-- Indicateur d'étapes -->
              <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                  <div id="step1" class="step-indicator active" style="text-align: center; flex: 1;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #1B4B7F; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">1</div>
                    <div style="font-size: 10px; color: #6b7280;">Destination</div>
                  </div>
                  <div id="step2" class="step-indicator" style="text-align: center; flex: 1;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">2</div>
                    <div style="font-size: 10px; color: #6b7280;">Bus</div>
                  </div>
                  <div id="step3" class="step-indicator" style="text-align: center; flex: 1;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">3</div>
                    <div style="font-size: 10px; color: #6b7280;">Client</div>
                  </div>
                  <div id="step4" class="step-indicator" style="text-align: center; flex: 1;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px; font-weight: 700;">4</div>
                    <div style="font-size: 10px; color: #6b7280;">Confirmer</div>
                  </div>
                </div>
              </div>

              <!-- Récapitulatif -->
              <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <div style="font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 12px;">📋 Récapitulatif</div>
                
                <div style="margin-bottom: 12px;">
                  <div style="font-size: 11px; color: #6b7280;">Destination</div>
                  <div id="recapDestination" style="font-weight: 600; font-size: 13px; color: #1B4B7F;">Non définie</div>
                </div>

                <div style="margin-bottom: 12px;">
                  <div style="font-size: 11px; color: #6b7280;">Bus sélectionné</div>
                  <div id="recapBus" style="font-weight: 600; font-size: 13px;">Non sélectionné</div>
                </div>

                <div style="margin-bottom: 12px;">
                  <div style="font-size: 11px; color: #6b7280;">Client</div>
                  <div id="recapClient" style="font-weight: 600; font-size: 13px;">Non renseigné</div>
                </div>

                <div style="border-top: 2px solid #e5e7eb; padding-top: 12px; margin-top: 12px;">
                  <div style="display: flex; justify-content: space-between;">
                    <span style="font-weight: 700; font-size: 14px;">Montant</span>
                    <span id="recapMontant" style="font-weight: 800; font-size: 20px; color: #1B4B7F;">-- CDF</span>
                  </div>
                </div>
              </div>

              <!-- Info -->
              <div style="background: #dbeafe; padding: 12px; border-radius: 8px;">
                <div style="font-size: 11px; color: #1e40af; line-height: 1.5;">
                  <strong>💡 Astuce :</strong> Suivez les étapes pour une vente rapide et efficace.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    // Variables globales pour stocker les données de la vente
    let venteData = {
      destination: null,
      destinationNom: null,
      busId: null,
      busNom: null,
      placesDisponibles: 0,
      pointMontee: null,
      pointMonteeNom: null,
      itineraire: null,
      distance: 0,
      prix: 0,
      nomClient: null,
      telClient: null,
      modePaiement: 'especes'
    };

    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
      
      // Initialiser Select2 pour les deux champs
      $('#ligneTrajet').select2({
        placeholder: 'Sélectionner une ligne...',
        allowClear: true,
        language: {
          noResults: function() {
            return "Aucune ligne trouvée";
          },
          searching: function() {
            return "Recherche en cours...";
          }
        }
      });

      $('#arretDestination').select2({
        placeholder: 'Sélectionner un arrêt...',
        allowClear: true,
        language: {
          noResults: function() {
            return "Aucun arrêt trouvé";
          },
          searching: function() {
            return "Recherche en cours...";
          }
        }
      });

      // Événement de changement pour ligneTrajet
      $('#ligneTrajet').on('change', function() {
        chargerArretsLigne();
      });

      // Vérifier si on peut activer le bouton Continuer
    function verifierEtape1() {
      const btnEtape2 = document.getElementById('btnEtape2');
      const arretMontee = $('#arretMontee').val();
      const arretDescente = $('#arretDestination').val();
      
      if (arretMontee && arretDescente) {
        btnEtape2.disabled = false;
      } else {
        btnEtape2.disabled = true;
      }
    }
    
    $('#arretMontee').on('change', verifierEtape1);
    $('#arretDestination').on('change', verifierEtape1);

      // Événement de changement pour arretDestination
      $('#arretDestination').on('change', function() {
        const btnEtape2 = document.getElementById('btnEtape2');
        btnEtape2.disabled = !this.value;
      });

      chargerTrajets();
    });

    // Charger tous les trajets depuis la base de données
    async function chargerTrajets() {
      const ligneSelect = $('#ligneTrajet');
      
      try {
        const response = await fetch('<?php echo BASE_URL; ?>/trajets/liste');
        const data = await response.json();

        if (data.success && data.trajets && data.trajets.length > 0) {
          // Vider et remplir le select
          ligneSelect.empty();
          ligneSelect.append(new Option('Sélectionner une ligne...', '', true, true));
          
          data.trajets.forEach(trajet => {
            ligneSelect.append(new Option(trajet.nom, trajet.id, false, false));
          });
          
          // Rafraîchir Select2
          ligneSelect.trigger('change');
        } else {
          ligneSelect.empty();
          ligneSelect.append(new Option('Aucune ligne disponible', '', true, true));
          ligneSelect.trigger('change');
        }
      } catch (error) {
        console.error('Erreur lors du chargement des trajets:', error);
        ligneSelect.empty();
        ligneSelect.append(new Option('Erreur de chargement', '', true, true));
        ligneSelect.trigger('change');
      }
    }

    // Charger les arrêts d'une ligne depuis la base de données
    async function chargerArretsLigne() {
      const ligneId = $('#ligneTrajet').val();
      const arretMonteeSelect = $('#arretMontee');
      const arretDescenteSelect = $('#arretDestination');
      const groupeArretMontee = document.getElementById('groupeArretMontee');
      const groupeArretDestination = document.getElementById('groupeArretDestination');
      const btnEtape2 = document.getElementById('btnEtape2');

      if (!ligneId) {
        groupeArretMontee.style.display = 'none';
        groupeArretDestination.style.display = 'none';
        btnEtape2.disabled = true;
        arretMonteeSelect.empty().trigger('change');
        arretDescenteSelect.empty().trigger('change');
        return;
      }

      // Afficher les champs arrêts
      groupeArretMontee.style.display = 'block';
      groupeArretDestination.style.display = 'block';
      
      // Vider et afficher message de chargement
      arretMonteeSelect.empty();
      arretMonteeSelect.append(new Option('Chargement des arrêts...', '', true, true));
      arretMonteeSelect.trigger('change');
      arretMonteeSelect.prop('disabled', true);
      
      arretDescenteSelect.empty();
      arretDescenteSelect.append(new Option('Chargement des arrêts...', '', true, true));
      arretDescenteSelect.trigger('change');
      arretDescenteSelect.prop('disabled', true);

      try {
        // Appel API pour récupérer les arrêts de la ligne
        const response = await fetch(`<?php echo BASE_URL; ?>/trajets/arrets?trajet_id=${ligneId}`);
        const data = await response.json();

        if (data.success && data.arrets && data.arrets.length > 0) {
          // Remplir arrêt de montée
          arretMonteeSelect.empty();
          arretMonteeSelect.append(new Option('Sélectionner un arrêt de montée...', '', true, true));
          
          // Remplir arrêt de descente
          arretDescenteSelect.empty();
          arretDescenteSelect.append(new Option('Sélectionner un arrêt de descente...', '', true, true));
          
          data.arrets.forEach(arret => {
            const optionMontee = new Option(
              `${arret.nom} (${arret.distance_avec_debut} km)`,
              arret.id,
              false,
              false
            );
            optionMontee.setAttribute('data-nom', arret.nom);
            optionMontee.setAttribute('data-distance', arret.distance_avec_debut);
            arretMonteeSelect.append(optionMontee);
            
            const optionDescente = new Option(
              `${arret.nom} (${arret.distance_avec_debut} km)`,
              arret.id,
              false,
              false
            );
            optionDescente.setAttribute('data-nom', arret.nom);
            optionDescente.setAttribute('data-distance', arret.distance_avec_debut);
            arretDescenteSelect.append(optionDescente);
          });
          
          arretMonteeSelect.prop('disabled', false);
          arretMonteeSelect.trigger('change');
          arretDescenteSelect.prop('disabled', false);
          arretDescenteSelect.trigger('change');
        } else {
          arretMonteeSelect.empty();
          arretMonteeSelect.append(new Option('Aucun arrêt disponible pour cette ligne', '', true, true));
          arretMonteeSelect.prop('disabled', true);
          arretMonteeSelect.trigger('change');
          
          arretDescenteSelect.empty();
          arretDescenteSelect.append(new Option('Aucun arrêt disponible pour cette ligne', '', true, true));
          arretDescenteSelect.prop('disabled', true);
          arretDescenteSelect.trigger('change');
        }
      } catch (error) {
        console.error('Erreur lors du chargement des arrêts:', error);
        arretMonteeSelect.empty();
        arretMonteeSelect.append(new Option('Erreur de chargement', '', true, true));
        arretMonteeSelect.prop('disabled', true);
        arretMonteeSelect.trigger('change');
        
        arretDescenteSelect.empty();
        arretDescenteSelect.append(new Option('Erreur de chargement', '', true, true));
        arretDescenteSelect.prop('disabled', true);
        arretDescenteSelect.trigger('change');
      }
    }

    // Fonctions de navigation entre étapes
    function allerEtape2() {
      console.log('=== allerEtape2 appelée ===');
      
      const ligneId = $('#ligneTrajet').val();
      const arretMonteeId = $('#arretMontee').val();
      const arretDescenteId = $('#arretDestination').val();
      
      console.log('ligneId:', ligneId);
      console.log('arretMonteeId:', arretMonteeId);
      console.log('arretDescenteId:', arretDescenteId);
      
      if (!ligneId || !arretMonteeId || !arretDescenteId) {
        alert('Veuillez sélectionner une ligne, un arrêt de montée et un arrêt de descente');
        return;
      }

      const ligneText = $('#ligneTrajet option:selected').text();
      const arretMonteeOption = $('#arretMontee option:selected');
      const arretDescenteOption = $('#arretDestination option:selected');
      const arretMonteeNom = arretMonteeOption.data('nom');
      const arretMonteeDistance = parseFloat(arretMonteeOption.data('distance'));
      const arretDescenteNom = arretDescenteOption.data('nom');
      const arretDescenteDistance = parseFloat(arretDescenteOption.data('distance'));
      
      console.log('arretMonteeDistance:', arretMonteeDistance);
      console.log('arretDescenteDistance:', arretDescenteDistance);
      
      // Vérifier que la descente est après la montée
      if (arretDescenteDistance <= arretMonteeDistance) {
        alert('L\'arrêt de descente doit être après l\'arrêt de montée');
        return;
      }
      
      venteData.ligneId = ligneId;
      venteData.ligneNom = ligneText;
      venteData.arretMontee = arretMonteeId;
      venteData.arretMonteeNom = arretMonteeNom;
      venteData.distanceMontee = arretMonteeDistance;
      venteData.destination = arretDescenteId;
      venteData.destinationNom = arretDescenteNom;
      venteData.distanceDestination = arretDescenteDistance;
      venteData.distanceParcourue = arretDescenteDistance - arretMonteeDistance;

      console.log('venteData:', venteData);

      // Charger les bus disponibles depuis l'API
      chargerBusDisponibles(ligneId, arretMonteeDistance, arretDescenteDistance);

      console.log('Affichage etape2');
      cacherToutesEtapes();
      document.getElementById('etape2').style.display = 'block';
      activerIndicateur(2);
      feather.replace();
      console.log('etape2 affichée');
    }

    // Charger les bus disponibles pour le trajet
    async function chargerBusDisponibles(trajetId, distanceMontee, distanceDescente) {
      const listeBus = document.getElementById('listeBusDisponibles');
      listeBus.innerHTML = '<div style="text-align: center; padding: 20px; color: #6b7280;">Chargement des bus disponibles...</div>';

      try {
        const url = `<?php echo BASE_URL; ?>/billets/bus-disponibles?trajet_id=${trajetId}&distance_montee=${distanceMontee}&distance_descente=${distanceDescente}&type_tarif=normal`;
        
        const response = await fetch(url);
        const data = await response.json();

        if (data.success && data.bus && data.bus.length > 0) {
          let html = '';
          data.bus.forEach(bus => {
            const prixTotal = Math.round(parseFloat(bus.prix_total));
            const busNom = `Bus ${bus.numero} - ${bus.marque} ${bus.modele}`;
            
            html += `
          <div onclick="selectionnerBus(${bus.id}, '${busNom}', ${bus.places_disponibles}, ${prixTotal})" 
               style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 12px; cursor: pointer; border: 2px solid #e5e7eb; transition: all 0.2s;"
               onmouseover="this.style.borderColor='#1B4B7F'; this.style.background='#eff6ff'"
               onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <div style="font-weight: 700; font-size: 16px; color: #1B4B7F; margin-bottom: 4px;">${busNom}</div>
                <div style="font-size: 13px; color: #6b7280;">
                  <span style="color: #10b981; font-weight: 600;">${bus.places_disponibles} places disponibles</span>
                </div>
              </div>
              <div style="text-align: right;">
                <div style="font-size: 18px; font-weight: 700; color: #1B4B7F;">${prixTotal.toLocaleString()} CDF</div>
              </div>
            </div>
          </div>
        `;
          });

          listeBus.innerHTML = html;
        } else {
          listeBus.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;"><i data-feather="alert-circle" style="width: 48px; height: 48px; margin-bottom: 12px;"></i><div style="font-weight: 600; font-size: 16px;">Aucun bus disponible pour cette ligne</div><div style="font-size: 14px; margin-top: 8px;">Veuillez choisir une autre ligne ou réessayer plus tard.</div></div>';
          feather.replace();
        }
      } catch (error) {
        console.error('Erreur lors du chargement des bus:', error);
        listeBus.innerHTML = '<div style="text-align: center; padding: 40px; color: #ef4444;">Erreur lors du chargement des bus disponibles</div>';
      }

      // Mettre à jour le récapitulatif
      document.getElementById('recapDestination').textContent = `${venteData.arretMonteeNom} → ${venteData.destinationNom} (${venteData.distanceParcourue} km)`;
    }

    function selectionnerBus(id, nom, places, prix) {
      venteData.busId = id;
      venteData.busNom = nom;
      venteData.placesDisponibles = places;
      venteData.prix = prix;

      // Mettre en surbrillance le bus sélectionné
      const buses = document.querySelectorAll('#listeBusDisponibles > div');
      buses.forEach(b => {
        b.style.borderColor = '#e5e7eb';
        b.style.background = '#f9fafb';
      });
      event.currentTarget.style.borderColor = '#10b981';
      event.currentTarget.style.background = '#dcfce7';

      document.getElementById('btnEtape3').disabled = false;
      document.getElementById('recapBus').textContent = nom + ' (' + places + ' places)';
      document.getElementById('recapMontant').textContent = prix.toLocaleString() + ' CDF';
    }

    function allerEtape3() {
      if (!venteData.busId) {
        alert('Veuillez sélectionner un bus');
        return;
      }

      cacherToutesEtapes();
      document.getElementById('etape3').style.display = 'block';
      activerIndicateur(3);
      feather.replace();
    }

    function allerEtape4() {
      const nomClient = document.getElementById('nomClient').value;
      const telClient = document.getElementById('telClient').value;
      const modePaiement = document.getElementById('modePaiement').value;

      if (!nomClient || !telClient) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
      }

      venteData.nomClient = nomClient;
      venteData.telClient = telClient;
      venteData.modePaiement = modePaiement;

      // Mettre à jour le récapitulatif dans la carte de progression
      document.getElementById('recapClient').textContent = nomClient + ' - ' + telClient;

      // Afficher le récapitulatif
      afficherRecapitulatif();

      cacherToutesEtapes();
      document.getElementById('etape4').style.display = 'block';
      activerIndicateur(4);
    }

    function afficherRecapitulatif() {
      // Afficher le récapitulatif final
      const recap = `
        <div style="margin-bottom: 16px;">
          <div style="font-weight: 700; color: #1B4B7F; margin-bottom: 12px; font-size: 16px;">📋 Récapitulatif complet</div>
        </div>

        <div style="background: white; padding: 16px; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #1B4B7F;">
          <div style="font-weight: 700; margin-bottom: 8px; color: #374151;">🚌 Voyage</div>
          <div style="font-size: 13px; margin-bottom: 4px;"><strong>Ligne:</strong> ${venteData.ligneNom}</div>
          <div style="font-size: 13px; margin-bottom: 4px;"><strong>Bus:</strong> ${venteData.busNom}</div>
          <div style="font-size: 13px; margin-bottom: 4px;"><strong>Trajet:</strong> ${venteData.arretMonteeNom} → ${venteData.destinationNom}</div>
          <div style="font-size: 13px;"><strong>Distance:</strong> ${venteData.distanceParcourue} km</div>
        </div>

        <div style="background: white; padding: 16px; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #10b981;">
          <div style="font-weight: 700; margin-bottom: 8px; color: #374151;">👤 Client</div>
          <div style="font-size: 13px; margin-bottom: 4px;"><strong>Nom:</strong> ${venteData.nomClient}</div>
          <div style="font-size: 13px;"><strong>Téléphone:</strong> ${venteData.telClient}</div>
        </div>

        <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #f59e0b;">
          <div style="font-weight: 700; margin-bottom: 8px; color: #374151;">💰 Paiement</div>
          <div style="font-size: 13px; margin-bottom: 4px;"><strong>Mode:</strong> ${document.getElementById('modePaiement').options[document.getElementById('modePaiement').selectedIndex].text}</div>
          <div style="font-size: 20px; font-weight: 800; color: #1B4B7F; margin-top: 8px;">${venteData.prix.toLocaleString()} CDF</div>
        </div>
      `;

      document.getElementById('recapitulatifFinal').innerHTML = recap;
    }

    async function validerVente() {
      if (!confirm('Confirmer la vente de ce billet ?')) {
        return;
      }

      try {
        // Préparer les données du billet
        const billetData = {
          trajet_id: venteData.ligneId,
          tarif_id: 1,
          bus_id: venteData.busId,
          arret_depart: venteData.arretMonteeNom,
          arret_arrivee: venteData.destinationNom,
          date_voyage: new Date().toISOString().split('T')[0],
          heure_depart: null,
          prix_paye: venteData.prix,
          devise: 'CDF',
          mode_paiement: venteData.modePaiement,
          nom_client: venteData.nomClient,
          tel_client: venteData.telClient
        };

        // Appeler l'API pour créer le billet
        const response = await fetch('<?php echo BASE_URL; ?>/billets/creer', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(billetData)
        });

        const result = await response.json();

        if (result.success) {
          // Générer le PDF avec QR code
          await genererFacturePDF(result.billet);

          alert('✓ Réservation effectuée avec succès !\nN° Réservation: ' + result.billet.numero_billet + '\n\nLe reçu a été généré.');

          // Réinitialiser
          window.location.reload();
        } else {
          alert('Erreur : ' + result.message);
        }
      } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la création de la réservation');
      }
    }

    async function genererFacturePDF(billet) {
      // Générer le QR code (données courtes pour éviter overflow)
      const qrData = `${billet.numero_billet}|${billet.id}`;

      const qrContainer = document.createElement('div');
      qrContainer.style.display = 'none';
      document.body.appendChild(qrContainer);

      const qrcode = new QRCode(qrContainer, {
        text: qrData,
        width: 200,
        height: 200,
        correctLevel: QRCode.CorrectLevel.M
      });

      await new Promise(resolve => setTimeout(resolve, 300));

      const qrImage = qrContainer.querySelector('img');
      const qrDataUrl = qrImage.src;

      // Créer le PDF avec le beau design
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a5'
      });

      const pageWidth = 148;
      const margin = 10;
      let yPos = margin;

      // Couleurs
      const bleu = [27, 75, 127];
      const vert = [16, 185, 129];
      const rouge = [239, 68, 68];
      const violet = [139, 92, 246];
      const gris = [107, 114, 128];
      const grisClair = [249, 250, 251];
      const noir = [0, 0, 0];

      // En-tête avec dégradé simulé
      doc.setFillColor(...bleu);
      doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 40, 4, 4, 'F');

      yPos += 12;
      doc.setFontSize(22);
      doc.setTextColor(255, 255, 255);
      doc.setFont(undefined, 'bold');
      doc.text('SAFARI TRANSPORT', pageWidth / 2, yPos, { align: 'center' });

      yPos += 8;
      doc.setFontSize(11);
      doc.setFont(undefined, 'normal');
      doc.text('Réservation de transport', pageWidth / 2, yPos, { align: 'center' });

      // Badge statut
      yPos += 8;
      const statutWidth = 25;
      doc.setFillColor(...violet);
      doc.setDrawColor(255, 255, 255);
      doc.roundedRect(pageWidth - margin - statutWidth - 10, yPos - 6, statutWidth + 8, 8, 2, 2, 'FD');
      doc.setFontSize(9);
      doc.setTextColor(255, 255, 255);
      doc.setFont(undefined, 'bold');
      doc.text('RÉSERVÉ', pageWidth - margin - statutWidth - 6, yPos - 1);

      // Numéro de billet
      yPos += 6;
      doc.setFontSize(9);
      doc.text(billet.numero_billet, pageWidth / 2, yPos, { align: 'center' });

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
      doc.text(billet.arret_depart, margin + 15, yPos + 3);

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
      doc.text(billet.arret_arrivee, margin + 15, yPos + 3);

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
      doc.text('Bus', pageWidth / 2 + 5, yPos);

      yPos += 5;
      doc.setFontSize(9);
      doc.setTextColor(...noir);
      doc.setFont(undefined, 'bold');
      doc.text(venteData.nomClient, margin + 5, yPos);
      doc.text(venteData.busNom, pageWidth / 2 + 5, yPos);

      // Section Prix
      yPos += 12;
      doc.setFontSize(10);
      doc.setTextColor(...noir);
      doc.setFont(undefined, 'bold');
      doc.text('Montant', margin + 5, yPos);

      yPos += 5;
      doc.setFillColor(...grisClair);
      doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 15, 3, 3, 'F');

      yPos += 10;
      doc.setFontSize(18);
      doc.setTextColor(...bleu);
      doc.setFont(undefined, 'bold');
      const prixFormate = parseFloat(billet.prix_paye).toLocaleString();
      doc.text(prixFormate + ' CDF', pageWidth / 2, yPos, { align: 'center' });

      // QR Code
      yPos += 15;
      const qrSize = 35;
      const qrX = (pageWidth - qrSize) / 2;
      doc.addImage(qrDataUrl, 'PNG', qrX, yPos, qrSize, qrSize);

      yPos += qrSize + 4;
      doc.setFontSize(7);
      doc.setTextColor(...gris);
      doc.setFont(undefined, 'normal');
      doc.text('Scannez pour vérifier l\'authenticité', pageWidth / 2, yPos, { align: 'center' });

      // Pied de page
      yPos += 10;
      doc.setFillColor(...bleu);
      doc.roundedRect(margin, yPos, pageWidth - (margin * 2), 12, 3, 3, 'F');

      yPos += 8;
      doc.setFontSize(8);
      doc.setTextColor(255, 255, 255);
      doc.setFont(undefined, 'bold');
      doc.text('SAFARI TRANSPORT', pageWidth / 2, yPos, { align: 'center' });

      yPos += 3;
      doc.setFontSize(7);
      doc.setFont(undefined, 'normal');
      doc.text('Kinshasa, République Démocratique du Congo', pageWidth / 2, yPos, { align: 'center' });

      document.body.removeChild(qrContainer);
      doc.save(`Reservation_${billet.numero_billet}.pdf`);
    }

    // Fonctions utilitaires
    function cacherToutesEtapes() {
      for (let i = 1; i <= 4; i++) {
        const etape = document.getElementById('etape' + i);
        if (etape) {
          etape.style.display = 'none';
        }
      }
    }

    function activerIndicateur(etape) {
      for (let i = 1; i <= 4; i++) {
        const step = document.getElementById('step' + i);
        if (step) {
          const circle = step.querySelector('div:first-child');
          if (circle) {
            if (i <= etape) {
              circle.style.background = '#1B4B7F';
              circle.style.color = 'white';
            } else {
              circle.style.background = '#e5e7eb';
              circle.style.color = '#6b7280';
            }
          }
        }
      }
    }

    function retourEtape1() {
      cacherToutesEtapes();
      document.getElementById('etape1').style.display = 'block';
      activerIndicateur(1);
    }

    function retourEtape2() {
      cacherToutesEtapes();
      document.getElementById('etape2').style.display = 'block';
      activerIndicateur(2);
    }

    function retourEtape3() {
      cacherToutesEtapes();
      document.getElementById('etape3').style.display = 'block';
      activerIndicateur(3);
    }
  </script>
</body>
</html>
