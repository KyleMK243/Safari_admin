<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Ajouter un Agent • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Public/css/styles.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">
    <?php require_once 'includes/menu_RH.php';  ?>

    <!-- Main content -->
    <main class="main">
      <!-- Header -->
      <header class="header">
        <div>
          <h1>Ajouter un nouvel agent</h1>
          <p>Enregistrement d'un nouveau membre du personnel</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--secondary" onclick="window.location.href='<?php echo BASE_URL; ?>/personnel'">
            <i data-feather="arrow-left"></i> Retour
          </button>
        </div>
      </header>

      <!-- Formulaire -->
      <form id="formNouvelAgent">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
          <!-- Formulaire principal -->
          <div>
            <!-- Informations personnelles -->
            <div class="card" style="margin-bottom: 24px;">
              <div class="card__header">
                <h3>Informations personnelles</h3>
              </div>
              <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Nom complet *</label>
                    <input type="text" name="nom" class="form-control" placeholder="Ex: Jean Mukendi" required>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Matricule</label>
                    <input type="text" class="form-control" value="<?php echo $matricule ?? ''; ?>" readonly style="background: #f3f4f6;">
                  </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control">
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Sexe</label>
                    <select class="form-control">
                      <option value="">Sélectionner...</option>
                      <option value="M">Masculin</option>
                      <option value="F">Féminin</option>
                    </select>
                  </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Téléphone *</label>
                    <input type="tel" name="telephone" class="form-control" placeholder="+243 XXX XXX XXX" required>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="agent@safari.cd">
                  </div>
                </div>

                <div style="margin-bottom: 20px;">
                  <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Adresse complète</label>
                  <textarea name="adresse" class="form-control" rows="2" placeholder="Ex: Avenue de la Gare, N°123, Kinshasa"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Sexe *</label>
                    <select class="form-control" required>
                      <option value="">Sélectionner...</option>
                      <option value="M">Masculin</option>
                      <option value="F">Féminin</option>
                    </select>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">État civil</label>
                    <select class="form-control">
                      <option value="">Sélectionner...</option>
                      <option value="celibataire">Célibataire</option>
                      <option value="marie">Marié(e)</option>
                      <option value="divorce">Divorcé(e)</option>
                      <option value="veuf">Veuf/Veuve</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="card" style="margin-bottom: 24px;">
              <div class="card__header">
                <h3>Informations professionnelles</h3>
              </div>
              <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Fonction *</label>
                    <select name="poste" class="form-control" id="fonction" required>
                      <option value="">Sélectionner...</option>
                      <option value="chauffeur">Chauffeur</option>
                      <option value="receveur">Receveur</option>
                      <option value="controleur">Contrôleur</option>
                      <option value="mecanicien">Mécanicien</option>
                      <option value="administratif">Administratif</option>
                    </select>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Date d'embauche *</label>
                    <input type="date" name="date_embauche" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                  </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type de contrat *</label>
                    <select name="type_contrat" class="form-control" required>
                      <option value="">Sélectionner...</option>
                      <option value="cdi">CDI (Contrat à Durée Indéterminée)</option>
                      <option value="cdd">CDD (Contrat à Durée Déterminée)</option>
                      <option value="stage">Stage</option>
                      <option value="interim">Intérim</option>
                    </select>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Salaire mensuel (CDF)</label>
                    <input type="number" name="salaire" class="form-control" placeholder="Ex: 450000" min="0">
                  </div>
                </div>

                <div style="margin-bottom: 20px;">
                  <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Notes</label>
                  <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires..."></textarea>
                </div>
              </div>
            </div>

            <!-- Documents -->
            <div class="card">
              <div class="card__header">
                <h3>Documents</h3>
              </div>
              <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Carte d'identité</label>
                    <input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">CV</label>
                    <input type="file" class="form-control" accept=".pdf,.doc,.docx">
                  </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Diplômes/Certificats</label>
                    <input type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple>
                  </div>
                  <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">Photo d'identité</label>
                    <input type="file" class="form-control" accept=".jpg,.jpeg,.png">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Récapitulatif -->
          <div>
            <div class="card" style="position: sticky; top: 20px;">
              <div class="card__header">
                <h3>Récapitulatif</h3>
              </div>
              <div style="padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                  <div id="recapAvatar" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center; margin: 0 auto 12px; color: white; font-size: 32px; font-weight: 700;">
                    <i data-feather="user" style="width: 40px; height: 40px;"></i>
                  </div>
                  <div id="recapNom" style="font-weight: 600; color: #1f2937; font-size: 16px;">Nouvel agent</div>
                  <div id="recapMatricule" style="font-size: 12px; color: #6b7280; margin-top: 4px;"><?php echo $matricule ?? ''; ?></div>
                </div>

                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Fonction</div>
                  <div id="recapFonction" style="font-weight: 700; color: #1B4B7F;">Non définie</div>
                </div>

                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Type de contrat</div>
                  <div id="recapContrat" style="font-weight: 700; color: #1B4B7F;">Non défini</div>
                </div>

                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                  <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Salaire mensuel</div>
                  <div id="recapSalaire" style="font-weight: 700; color: #10b981; font-size: 20px;">-</div>
                </div>

                <div style="background: #dbeafe; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                  <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <i data-feather="info" style="width: 16px; height: 16px; color: #1e40af;"></i>
                    <strong style="color: #1e40af; font-size: 14px;">Information</strong>
                  </div>
                  <div style="font-size: 12px; color: #1e40af; line-height: 1.6;">
                    Un matricule unique sera généré automatiquement lors de l'enregistrement.
                  </div>
                </div>

                <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center;">
                  <i data-feather="check"></i> Enregistrer l'agent
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();

      // Gestion des champs spécifiques selon la fonction
      const fonctionSelect = document.getElementById('fonction');
      const champsSpecifiques = document.getElementById('champsSpecifiques');

      fonctionSelect?.addEventListener('change', (e) => {
        const fonction = e.target.value;
        champsSpecifiques.innerHTML = '';

        if (fonction === 'chauffeur') {
          champsSpecifiques.innerHTML = `
            <div style="padding: 16px; background: #dbeafe; border-radius: 8px; margin-bottom: 16px;">
              <h4 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #1e40af;">Informations spécifiques - Chauffeur</h4>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #1e40af;">N° Permis de conduire *</label>
                  <input type="text" class="form-control" placeholder="Ex: CD123456" required>
                </div>
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #1e40af;">Catégorie *</label>
                  <select class="form-control" required>
                    <option value="">Sélectionner...</option>
                    <option value="B">B (Véhicules légers)</option>
                    <option value="C">C (Poids lourds)</option>
                    <option value="D">D (Transport de personnes)</option>
                  </select>
                </div>
              </div>
              <div style="margin-top: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #1e40af;">Date d'expiration du permis *</label>
                <input type="date" class="form-control" required>
              </div>
            </div>
          `;
        } else if (fonction === 'mecanicien') {
          champsSpecifiques.innerHTML = `
            <div style="padding: 16px; background: #fef3c7; border-radius: 8px; margin-bottom: 16px;">
              <h4 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #92400e;">Informations spécifiques - Mécanicien</h4>
              <div style="display: grid; grid-template-columns: 1fr; gap: 16px;">
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #92400e;">Spécialité</label>
                  <select class="form-control">
                    <option value="">Sélectionner...</option>
                    <option value="moteur">Moteur</option>
                    <option value="electricite">Électricité</option>
                    <option value="carrosserie">Carrosserie</option>
                    <option value="generale">Mécanique générale</option>
                  </select>
                </div>
                <div>
                  <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #92400e;">Années d'expérience</label>
                  <input type="number" class="form-control" placeholder="Ex: 5" min="0">
                </div>
              </div>
            </div>
          `;
        }

        // Mettre à jour le récapitulatif
        updateRecap();
      });

      // Mise à jour du récapitulatif
      function updateRecap() {
        // Nom
        const nomInput = document.querySelector('input[name="nom"]');
        if (nomInput && nomInput.value) {
          document.getElementById('recapNom').textContent = nomInput.value;
          
          // Mettre à jour l'avatar avec les initiales
          const nomParts = nomInput.value.trim().split(' ');
          const initiales = nomParts.length > 1 
            ? (nomParts[0].charAt(0) + nomParts[nomParts.length - 1].charAt(0)).toUpperCase()
            : nomInput.value.substring(0, 2).toUpperCase();
          
          document.getElementById('recapAvatar').innerHTML = initiales;
        } else {
          document.getElementById('recapNom').textContent = 'Nouvel agent';
          document.getElementById('recapAvatar').innerHTML = '<i data-feather="user" style="width: 40px; height: 40px;"></i>';
        }

        // Fonction
        const fonctionSelect = document.querySelector('select[name="poste"]');
        if (fonctionSelect && fonctionSelect.value) {
          const fonctionText = fonctionSelect.options[fonctionSelect.selectedIndex].text;
          document.getElementById('recapFonction').textContent = fonctionText;
        } else {
          document.getElementById('recapFonction').textContent = 'Non définie';
        }

        // Type de contrat
        const contratSelect = document.querySelector('select[name="type_contrat"]');
        if (contratSelect && contratSelect.value) {
          const contratText = contratSelect.options[contratSelect.selectedIndex].text;
          document.getElementById('recapContrat').textContent = contratText;
        } else {
          document.getElementById('recapContrat').textContent = 'Non défini';
        }

        // Salaire
        const salaireInput = document.querySelector('input[name="salaire"]');
        if (salaireInput && salaireInput.value) {
          const salaire = parseInt(salaireInput.value);
          document.getElementById('recapSalaire').textContent = salaire.toLocaleString('fr-FR') + ' CDF';
        } else {
          document.getElementById('recapSalaire').textContent = '-';
        }

        feather.replace();
      }

      // Écouter les changements pour le récapitulatif
      const nomInput = document.querySelector('input[name="nom"]');
      const fonctionInput = document.querySelector('select[name="poste"]');
      const contratInput = document.querySelector('select[name="type_contrat"]');
      const salaireInput = document.querySelector('input[name="salaire"]');

      if (nomInput) {
        nomInput.addEventListener('input', updateRecap);
      }

      if (fonctionInput) {
        fonctionInput.addEventListener('change', updateRecap);
      }

      if (contratInput) {
        contratInput.addEventListener('change', updateRecap);
      }

      if (salaireInput) {
        salaireInput.addEventListener('input', updateRecap);
      }

      // Soumission du formulaire
      document.getElementById('formNouvelAgent')?.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if(!confirm('Voulez-vous enregistrer ce nouvel agent ?')) {
          return;
        }

        // Récupérer les données du formulaire
        const form = e.target;
        const formData = new FormData(form);
        
        const data = {
          nom: formData.get('nom') || '',
          matricule: '<?php echo $matricule ?? ""; ?>',
          poste: formData.get('poste') || '',
          telephone: formData.get('telephone') || '',
          email: formData.get('email') || '',
          adresse: formData.get('adresse') || '',
          date_naissance: formData.get('date_naissance') || null,
          date_embauche: formData.get('date_embauche') || '',
          type_contrat: formData.get('type_contrat') || '',
          salaire: formData.get('salaire') || null,
          statut: 'actif',
          notes: formData.get('notes') || null
        };

        // Envoyer au serveur
        fetch('<?php echo BASE_URL; ?>/personnel/creer', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alert(`✅ Agent enregistré avec succès !\nMatricule: ${result.matricule}`);
            window.location.href = '<?php echo BASE_URL; ?>/personnel';
          } else {
            alert('❌ Erreur: ' + result.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('❌ Erreur lors de l\'enregistrement');
        });
      });
    });
  </script>
</body>
</html>
