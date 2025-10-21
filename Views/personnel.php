<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Gestion du Personnel • Safari</title>
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
          <h1>Gestion du Personnel</h1>
          <p>Liste complète des employés</p>
        </div>
        <div class="header__actions">
          <button class="btn btn--primary" onclick="window.location.href='<?php echo BASE_URL; ?>/nouveau-agent'">
            <i data-feather="user-plus"></i> Ajouter un agent
          </button>
        </div>
      </header>

      <!-- Statistiques rapides -->
      <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Total</div>
          <div style="font-size: 32px; font-weight: 800; color: #1B4B7F;" id="statTotal"><?php echo $stats['total'] ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Chauffeurs</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;" id="statChauffeurs"><?php echo $stats['poste_chauffeur'] ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Receveurs</div>
          <div style="font-size: 32px; font-weight: 800; color: #3b82f6;" id="statReceveurs"><?php echo $stats['poste_receveur'] ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Contrôleurs</div>
          <div style="font-size: 32px; font-weight: 800; color: #f59e0b;" id="statControleurs"><?php echo $stats['poste_controleur'] ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 20px;">
          <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Actifs</div>
          <div style="font-size: 32px; font-weight: 800; color: #10b981;" id="statActifs"><?php echo $stats['statut_actif'] ?? 0; ?></div>
        </div>
      </div>

      <!-- Onglets par fonction -->
      <div class="tabs">
        <button class="tab-btn active" data-tab="tous">
          <i data-feather="users"></i> Tous
        </button>
        <button class="tab-btn" data-tab="chauffeurs">
          <i data-feather="truck"></i> Chauffeurs
        </button>
        
        <button class="tab-btn" data-tab="receveurs">
          <i data-feather="dollar-sign"></i> Receveurs
        </button>
        <button class="tab-btn" data-tab="controleurs">
          <i data-feather="clipboard"></i> Contrôleurs
        </button>
        <button class="tab-btn" data-tab="autres">
          <i data-feather="briefcase"></i> Autres
        </button>
      </div>

      <!-- Filtres -->
      <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 20px;">
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Rechercher</label>
              <input type="text" class="form-control" placeholder="Nom, matricule..." id="searchPersonnel">
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Fonction</label>
              <select class="form-control" id="filterFonction">
                <option value="">Toutes</option>
                <option value="chauffeur">Chauffeur</option>
                <option value="receveur">Receveur</option>
                <option value="controleur">Contrôleur</option>
                <option value="mecanicien">Mécanicien</option>
                <option value="administratif">Administratif</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Statut</label>
              <select class="form-control" id="filterStatut">
                <option value="">Tous</option>
                <option value="actif">Actif</option>
                <option value="conge">En congé</option>
                <option value="suspendu">Suspendu</option>
                <option value="inactif">Inactif</option>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151;">Type contrat</label>
              <select class="form-control" id="filterContrat">
                <option value="">Tous</option>
                <option value="cdi">CDI</option>
                <option value="cdd">CDD</option>
                <option value="stage">Stage</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu Tous -->
      <div class="tab-content active" id="tab-tous">
        <section class="card">
          <div class="card__header">
            <h3>Liste complète du personnel</h3>
          </div>
          
          <div style="overflow-x: auto;">
            <table class="table">
              <thead>
                <tr>
                  <th>Agent</th>
                  <th>Fonction</th>
                  <th>Contact</th>
                  <th>Date d'embauche</th>
                  <th>Type contrat</th>
                  <th>Salaire</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="personnelTableBody">
                <!-- Data will be populated by JS -->
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <div class="pagination__info">
              Affichage de <strong id="paginationStart">0</strong> à <strong id="paginationEnd">0</strong> sur <strong id="paginationTotal">0</strong> agents
            </div>
            <div class="pagination__controls">
              <button class="pagination__btn" id="btnPrevPage" disabled>
                <i data-feather="chevron-left"></i> Précédent
              </button>
              <div class="pagination__pages" id="paginationPages">
                <!-- Pages générées par JS -->
              </div>
              <button class="pagination__btn" id="btnNextPage">
                Suivant <i data-feather="chevron-right"></i>
              </button>
            </div>
          </div>
        </section>
      </div>

      <!-- Autres onglets -->
      <div class="tab-content" id="tab-chauffeurs">
        <section class="card">
          <div class="card__header">
            <h3>Chauffeurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par fonction "Chauffeurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <div class="tab-content" id="tab-receveurs">
        <section class="card">
          <div class="card__header">
            <h3>Receveurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par fonction "Receveurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <div class="tab-content" id="tab-controleurs">
        <section class="card">
          <div class="card__header">
            <h3>Contrôleurs</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Filtrage par fonction "Contrôleurs" - Même structure que le tableau principal
          </div>
        </section>
      </div>

      <div class="tab-content" id="tab-autres">
        <section class="card">
          <div class="card__header">
            <h3>Autres fonctions</h3>
          </div>
          <div style="padding: 24px; text-align: center; color: #6b7280;">
            Mécaniciens, Administratif, etc.
          </div>
        </section>
      </div>

      <?php require_once 'includes/footer.php';  ?>
    </main>
  </div>

  <!-- Modal Détails Agent -->
  <div class="modal" id="modalDetails">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2 id="modalDetailsTitle">Détails de l'agent</h2>
        <button class="modal__close" id="closeModalDetails">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body" id="detailsContent">
        <!-- Contenu dynamique -->
      </div>
    </div>
  </div>

  <!-- Modal Modifier Agent -->
  <div class="modal" id="modalModifier">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 800px;">
      <div class="modal__header">
        <h2>Modifier l'agent</h2>
        <button class="modal__close" id="closeModalModifier">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <form id="formModifier">
          <input type="hidden" id="modifierId">
          
          <!-- Informations personnelles -->
          <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
              <i data-feather="user" style="width: 18px; height: 18px; margin-right: 8px;"></i>
              Informations personnelles
            </h3>
            <div class="form-grid">
              <div class="form-group" style="grid-column: 1 / -1;">
                <label>Nom complet *</label>
                <input type="text" id="modifierNom" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" id="modifierDateNaissance" class="form-control">
              </div>
              <div class="form-group">
                <label>Téléphone *</label>
                <input type="tel" id="modifierTelephone" class="form-control" placeholder="+243 XXX XXX XXX" required>
              </div>
              <div class="form-group" style="grid-column: 1 / -1;">
                <label>Email</label>
                <input type="email" id="modifierEmail" class="form-control" placeholder="agent@safari.cd">
              </div>
              <div class="form-group" style="grid-column: 1 / -1;">
                <label>Adresse</label>
                <textarea id="modifierAdresse" class="form-control" rows="2" placeholder="Avenue de la Gare, N°123, Kinshasa"></textarea>
              </div>
            </div>
          </div>

          <!-- Informations professionnelles -->
          <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
              <i data-feather="briefcase" style="width: 18px; height: 18px; margin-right: 8px;"></i>
              Informations professionnelles
            </h3>
            <div class="form-grid">
              <div class="form-group">
                <label>Poste *</label>
                <select id="modifierPoste" class="form-control" required>
                  <option value="chauffeur">Chauffeur</option>
                  <option value="receveur">Receveur</option>
                  <option value="controleur">Contrôleur</option>
                  <option value="mecanicien">Mécanicien</option>
                  <option value="administratif">Administratif</option>
                </select>
              </div>
              <div class="form-group">
                <label>Date d'embauche *</label>
                <input type="date" id="modifierDateEmbauche" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Type de contrat *</label>
                <select id="modifierTypeContrat" class="form-control" required>
                  <option value="cdi">CDI (Contrat à Durée Indéterminée)</option>
                  <option value="cdd">CDD (Contrat à Durée Déterminée)</option>
                  <option value="stage">Stage</option>
                  <option value="interim">Intérim</option>
                </select>
              </div>
              <div class="form-group">
                <label>Salaire mensuel (CDF)</label>
                <input type="number" id="modifierSalaire" class="form-control" placeholder="Ex: 450000" min="0">
              </div>
              <div class="form-group">
                <label>Statut *</label>
                <select id="modifierStatut" class="form-control" required>
                  <option value="actif">Actif</option>
                  <option value="conge">En congé</option>
                  <option value="suspendu">Suspendu</option>
                  <option value="inactif">Inactif</option>
                </select>
              </div>
              <div class="form-group">
                <label>Bus affecté</label>
                <input type="text" id="modifierBusAffecte" class="form-control" placeholder="Ex: 421">
              </div>
            </div>
          </div>

          <!-- Notes -->
          <div>
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
              <i data-feather="file-text" style="width: 18px; height: 18px; margin-right: 8px;"></i>
              Notes et observations
            </h3>
            <div class="form-group">
              <label>Notes</label>
              <textarea id="modifierNotes" class="form-control" rows="3" placeholder="Informations complémentaires..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnAnnulerModifier">Annuler</button>
        <button type="button" class="btn btn--primary" id="btnConfirmerModifier">
          <i data-feather="save"></i> Enregistrer les modifications
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Supprimer Agent -->
  <div class="modal" id="modalSupprimer">
    <div class="modal__overlay"></div>
    <div class="modal__content" style="max-width: 500px;">
      <div class="modal__header">
        <h2>Supprimer l'agent</h2>
        <button class="modal__close" id="closeModalSupprimer">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal__body">
        <div class="alert alert--danger">
          <i data-feather="alert-triangle"></i>
          <p style="margin: 0;">Êtes-vous sûr de vouloir supprimer cet agent ? Cette action est irréversible.</p>
        </div>
        <div style="margin-top: 16px;">
          <p style="margin: 0;"><strong id="supprimerNom"></strong></p>
          <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 14px;" id="supprimerMatricule"></p>
        </div>
        <input type="hidden" id="supprimerId">
      </div>
      <div class="modal__footer">
        <button type="button" class="btn btn--secondary" id="btnAnnulerSupprimer">Annuler</button>
        <button type="button" class="btn btn--danger" id="btnConfirmerSupprimer">Supprimer</button>
      </div>
    </div>
  </div>

  <!-- Application principale -->
  <script src="Public/js/app.js"></script>
  
  <script>
    let agentsData = [];
    let currentFilters = {
      poste: '',
      statut: '',
      search: ''
    };
    let currentPage = 1;
    let totalPages = 1;
    let limit = 10;

    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
      
      // Charger les agents
      chargerAgents();

      // Gestion des onglets
      const tabBtns = document.querySelectorAll('.tab-btn');
      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const tabId = btn.getAttribute('data-tab');
          
          // Mettre à jour le filtre de poste selon l'onglet
          if (tabId === 'tous') {
            currentFilters.poste = '';
          } else if (tabId === 'chauffeurs') {
            currentFilters.poste = 'chauffeur';
          } else if (tabId === 'receveurs') {
            currentFilters.poste = 'receveur';
          } else if (tabId === 'controleurs') {
            currentFilters.poste = 'controleur';
          } else {
            currentFilters.poste = 'autre';
          }
          
          tabBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          
          currentPage = 1;
          chargerAgents();
        });
      });

      // Recherche avec debounce
      let searchTimeout;
      document.getElementById('searchPersonnel').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          currentFilters.search = e.target.value;
          currentPage = 1;
          chargerAgents();
        }, 500);
      });

      document.getElementById('filterFonction').addEventListener('change', function(e) {
        currentFilters.poste = e.target.value;
        currentPage = 1;
        chargerAgents();
      });

      document.getElementById('filterStatut').addEventListener('change', function(e) {
        currentFilters.statut = e.target.value;
        currentPage = 1;
        chargerAgents();
      });

      // Pagination
      document.getElementById('btnPrevPage').addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          chargerAgents();
        }
      });

      document.getElementById('btnNextPage').addEventListener('click', () => {
        if (currentPage < totalPages) {
          currentPage++;
          chargerAgents();
        }
      });

      // Modals
      setupModals();
    });

    function chargerAgents() {
      const params = new URLSearchParams();
      if (currentFilters.poste) params.append('poste', currentFilters.poste);
      if (currentFilters.statut) params.append('statut', currentFilters.statut);
      if (currentFilters.search) params.append('search', currentFilters.search);
      params.append('page', currentPage);
      params.append('limit', limit);

      fetch('<?php echo BASE_URL; ?>/personnel/get?' + params.toString())
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            agentsData = data.agents;
            afficherAgents(data.agents);
            updatePagination(data.total, data.page, data.totalPages);
            updateStatistiques(data.stats);
            totalPages = data.totalPages;
          } else {
            console.error('Erreur:', data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
        });
    }

    function afficherAgents(agents) {
      const tbody = document.getElementById('personnelTableBody');
      
      if (agents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #9ca3af;">Aucun agent trouvé</td></tr>';
        return;
      }

      tbody.innerHTML = agents.map(agent => {
        const nomParts = agent.nom.split(' ');
        const initiales = nomParts.length > 1 
          ? (nomParts[0].charAt(0) + nomParts[nomParts.length - 1].charAt(0)).toUpperCase()
          : agent.nom.substring(0, 2).toUpperCase();
        const posteColors = {
          'chauffeur': 'badge--info',
          'receveur': 'badge--success',
          'controleur': 'badge--primary',
          'mecanicien': 'badge--warning',
          'administratif': 'badge--secondary'
        };
        const statutColors = {
          'actif': 'status-badge--actif',
          'conge': 'status-badge--conge',
          'suspendu': 'status-badge--panne',
          'inactif': 'status-badge--inactif'
        };
        const statutLabels = {
          'actif': 'Actif',
          'conge': 'En congé',
          'suspendu': 'Suspendu',
          'inactif': 'Inactif'
        };

        return `
          <tr>
            <td>
              <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center; color: white; font-weight: 700;">${initiales}</div>
                <div>
                  <div style="font-weight: 600;">${agent.nom}</div>
                  <div style="font-size: 12px; color: #6b7280;">${agent.matricule}</div>
                </div>
              </div>
            </td>
            <td><span class="badge ${posteColors[agent.poste] || 'badge--secondary'}">${agent.poste.charAt(0).toUpperCase() + agent.poste.slice(1)}</span></td>
            <td>
              <div>${agent.telephone || '-'}</div>
              <div style="font-size: 12px; color: #6b7280;">${agent.email || '-'}</div>
            </td>
            <td>${new Date(agent.date_embauche).toLocaleDateString('fr-FR')}</td>
            <td><strong>${agent.type_contrat.toUpperCase()}</strong></td>
            <td><strong style="color: #10b981;">${agent.salaire ? Number(agent.salaire).toLocaleString('fr-FR') + ' CDF' : '-'}</strong></td>
            <td><span class="status-badge ${statutColors[agent.statut]}">${statutLabels[agent.statut]}</span></td>
            <td>
              <div class="action-buttons">
                <button class="btn-icon btn-icon--view" title="Détails" onclick="voirAgent(${agent.id})">
                  <i data-feather="eye"></i>
                </button>
                <button class="btn-icon btn-icon--edit" title="Modifier" onclick="modifierAgent(${agent.id})">
                  <i data-feather="edit-2"></i>
                </button>
                <button class="btn-icon btn-icon--danger" title="Supprimer" onclick="supprimerAgent(${agent.id})">
                  <i data-feather="trash-2"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');

      feather.replace();
    }

    function updatePagination(total, page, pages) {
      const start = total > 0 ? ((page - 1) * limit) + 1 : 0;
      const end = Math.min(page * limit, total);
      
      document.getElementById('paginationStart').textContent = start;
      document.getElementById('paginationEnd').textContent = end;
      document.getElementById('paginationTotal').textContent = total;
      
      // Boutons prev/next
      const btnPrev = document.getElementById('btnPrevPage');
      const btnNext = document.getElementById('btnNextPage');
      
      btnPrev.disabled = page <= 1;
      btnNext.disabled = page >= pages;
      
      // Générer les numéros de page
      const pagesContainer = document.getElementById('paginationPages');
      pagesContainer.innerHTML = '';
      
      if (pages <= 7) {
        // Afficher toutes les pages
        for (let i = 1; i <= pages; i++) {
          pagesContainer.appendChild(createPageButton(i, i === page));
        }
      } else {
        // Afficher avec ellipses
        pagesContainer.appendChild(createPageButton(1, page === 1));
        
        if (page > 3) {
          pagesContainer.appendChild(createEllipsis());
        }
        
        for (let i = Math.max(2, page - 1); i <= Math.min(pages - 1, page + 1); i++) {
          pagesContainer.appendChild(createPageButton(i, i === page));
        }
        
        if (page < pages - 2) {
          pagesContainer.appendChild(createEllipsis());
        }
        
        pagesContainer.appendChild(createPageButton(pages, page === pages));
      }
      
      feather.replace();
    }
    
    function createPageButton(pageNum, isActive) {
      const btn = document.createElement('button');
      btn.className = 'pagination__page' + (isActive ? ' active' : '');
      btn.textContent = pageNum;
      btn.addEventListener('click', () => {
        currentPage = pageNum;
        chargerAgents();
      });
      return btn;
    }
    
    function createEllipsis() {
      const span = document.createElement('span');
      span.className = 'pagination__dots';
      span.textContent = '...';
      return span;
    }
    
    function updateStatistiques(stats) {
      if (!stats) return;
      
      document.getElementById('statTotal').textContent = stats.total || 0;
      document.getElementById('statChauffeurs').textContent = stats.poste_chauffeur || 0;
      document.getElementById('statReceveurs').textContent = stats.poste_receveur || 0;
      document.getElementById('statControleurs').textContent = stats.poste_controleur || 0;
      document.getElementById('statActifs').textContent = stats.statut_actif || 0;
    }

    function voirAgent(id) {
      fetch('<?php echo BASE_URL; ?>/personnel/details?id=' + id)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const agent = data.agent;
            const nomParts = agent.nom.split(' ');
            const initiales = nomParts.length > 1 
              ? (nomParts[0].charAt(0) + nomParts[nomParts.length - 1].charAt(0)).toUpperCase()
              : agent.nom.substring(0, 2).toUpperCase();
            
            document.getElementById('detailsContent').innerHTML = `
              <div style="display: grid; gap: 20px;">
                <div style="text-align: center;">
                  <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: grid; place-items: center; color: white; font-weight: 700; font-size: 48px; margin: 0 auto 16px;">${initiales}</div>
                  <h3 style="margin: 0 0 8px 0;">${agent.nom}</h3>
                  <div style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">${agent.matricule}</div>
                  <span class="badge badge--info" style="font-size: 14px;">${agent.poste.charAt(0).toUpperCase() + agent.poste.slice(1)}</span>
                </div>

                <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                  <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations personnelles</h4>
                  <div style="display: grid; gap: 8px;">
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Téléphone</span>
                      <span style="font-weight: 600;">${agent.telephone || '-'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Email</span>
                      <span style="font-weight: 600;">${agent.email || '-'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Adresse</span>
                      <span style="font-weight: 600;">${agent.adresse || '-'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Date de naissance</span>
                      <span style="font-weight: 600;">${agent.date_naissance ? new Date(agent.date_naissance).toLocaleDateString('fr-FR') : '-'}</span>
                    </div>
                  </div>
                </div>

                <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                  <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Informations professionnelles</h4>
                  <div style="display: grid; gap: 8px;">
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Date d'embauche</span>
                      <span style="font-weight: 600;">${new Date(agent.date_embauche).toLocaleDateString('fr-FR')}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Type de contrat</span>
                      <span style="font-weight: 600;">${agent.type_contrat.toUpperCase()}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Salaire</span>
                      <span style="font-weight: 600; color: #10b981;">${agent.salaire ? Number(agent.salaire).toLocaleString('fr-FR') + ' CDF' : '-'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Bus affecté</span>
                      <span style="font-weight: 600;">${agent.bus_affecte ? 'Bus #' + agent.bus_affecte : 'Non affecté'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                      <span style="color: #6b7280;">Statut</span>
                      <span class="status-badge status-badge--actif">${agent.statut}</span>
                    </div>
                  </div>
                </div>

                ${agent.notes ? `
                <div style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                  <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">Notes</h4>
                  <p style="margin: 0; color: #6b7280;">${agent.notes}</p>
                </div>
                ` : ''}
              </div>
            `;
            document.getElementById('modalDetails').classList.add('active');
            feather.replace();
          }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function modifierAgent(id) {
      fetch('<?php echo BASE_URL; ?>/personnel/details?id=' + id)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const agent = data.agent;
            
            // Informations de base
            document.getElementById('modifierId').value = agent.id;
            document.getElementById('modifierNom').value = agent.nom;
            
            // Informations personnelles
            document.getElementById('modifierDateNaissance').value = agent.date_naissance || '';
            document.getElementById('modifierTelephone').value = agent.telephone || '';
            document.getElementById('modifierEmail').value = agent.email || '';
            document.getElementById('modifierAdresse').value = agent.adresse || '';
            
            // Informations professionnelles
            document.getElementById('modifierPoste').value = agent.poste;
            document.getElementById('modifierDateEmbauche').value = agent.date_embauche;
            document.getElementById('modifierTypeContrat').value = agent.type_contrat;
            document.getElementById('modifierSalaire').value = agent.salaire || '';
            document.getElementById('modifierStatut').value = agent.statut;
            document.getElementById('modifierBusAffecte').value = agent.bus_affecte || '';
            
            // Notes
            document.getElementById('modifierNotes').value = agent.notes || '';
            
            document.getElementById('modalModifier').classList.add('active');
            feather.replace();
          }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function supprimerAgent(id) {
      const agent = agentsData.find(a => a.id == id);
      if (agent) {
        document.getElementById('supprimerId').value = id;
        document.getElementById('supprimerNom').textContent = agent.nom;
        document.getElementById('supprimerMatricule').textContent = agent.matricule;
        document.getElementById('modalSupprimer').classList.add('active');
        feather.replace();
      }
    }

    function setupModals() {
      // Modal Détails
      document.getElementById('closeModalDetails').addEventListener('click', () => {
        document.getElementById('modalDetails').classList.remove('active');
      });

      // Modal Modifier
      document.getElementById('closeModalModifier').addEventListener('click', () => {
        document.getElementById('modalModifier').classList.remove('active');
      });

      document.getElementById('btnAnnulerModifier').addEventListener('click', () => {
        document.getElementById('modalModifier').classList.remove('active');
      });

      document.getElementById('btnConfirmerModifier').addEventListener('click', () => {
        const data = {
          id: document.getElementById('modifierId').value,
          nom: document.getElementById('modifierNom').value,
          date_naissance: document.getElementById('modifierDateNaissance').value,
          telephone: document.getElementById('modifierTelephone').value,
          email: document.getElementById('modifierEmail').value,
          adresse: document.getElementById('modifierAdresse').value,
          poste: document.getElementById('modifierPoste').value,
          date_embauche: document.getElementById('modifierDateEmbauche').value,
          type_contrat: document.getElementById('modifierTypeContrat').value,
          salaire: document.getElementById('modifierSalaire').value,
          statut: document.getElementById('modifierStatut').value,
          bus_affecte: document.getElementById('modifierBusAffecte').value,
          notes: document.getElementById('modifierNotes').value
        };

        fetch('<?php echo BASE_URL; ?>/personnel/modifier', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alert('✅ ' + result.message);
            document.getElementById('modalModifier').classList.remove('active');
            chargerAgents();
          } else {
            alert('❌ ' + result.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('❌ Erreur lors de la modification');
        });
      });

      // Modal Supprimer
      document.getElementById('closeModalSupprimer').addEventListener('click', () => {
        document.getElementById('modalSupprimer').classList.remove('active');
      });

      document.getElementById('btnAnnulerSupprimer').addEventListener('click', () => {
        document.getElementById('modalSupprimer').classList.remove('active');
      });

      document.getElementById('btnConfirmerSupprimer').addEventListener('click', () => {
        const id = document.getElementById('supprimerId').value;

        fetch('<?php echo BASE_URL; ?>/personnel/supprimer', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            alert('✅ ' + result.message);
            document.getElementById('modalSupprimer').classList.remove('active');
            chargerAgents();
          } else {
            alert('❌ ' + result.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert('❌ Erreur lors de la suppression');
        });
      });

      // Fermer sur overlay
      document.querySelectorAll('.modal__overlay').forEach(overlay => {
        overlay.addEventListener('click', function() {
          this.parentElement.classList.remove('active');
        });
      });
    }
  </script>
</body>
</html>
