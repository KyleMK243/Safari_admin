
# 03_API_PHP.md

---

# API PHP — Spécification technique (RESTful)

## 1. But
Fournir un backend centralisé permettant :
- Authentification / gestion des utilisateurs
- Gestion des entités RH / Buses / Lignes / Trajets / Assignations
- Achats, recharges, génération de billets (QR)
- Validation des billets depuis l'app mobile (contrôleur)
- Enregistrement des transactions cash (receveur)
- Endpoints pour positions GPS des bus

## 2. Choix techniques
- Framework : PHP vanilla
- Auth : JWT (typiquement `tymon/jwt-auth`) ou Laravel Sanctum pour SPA + token-based
- DB : MySQL 
- Queue : A Definir une techno pour tâches asynchrones (envoi OTP, generation PDF, envoi email)
- Stockage : local ou O2Switch (hebergement mutualisE)
- Documentation : OpenAPI (Swagger)

## 3. Principales ressources & schémas (table simplifié)
- users (id, name, phone, email, role, password_hash, created_at)
- roles (id, name)
- buses (id, plate, capacity, status)
- lines (id, name)
- trips (id, line_id, distance_km, start_time, end_time)
- assignments (id, bus_id, driver_id, controller_id, receiver_id, trip_id, start_date, end_date)
- fares (id, trip_id, base_per_km, profile, discount_rules)
- tickets (id, user_id, trip_id, qr_code, status, used_at)
- transactions (id, ticket_id, amount, method[cash/card], agent_id, timestamp)
- recharges (id, user_id, amount, method, status, timestamp)

## 4. Endpoints détaillés (sélection clé)
**Auth**
- `POST /api/auth/register` — payload: {phone, otp, meta:{answers}} → create user; returns tokens
- `POST /api/auth/login` — payload: {phone, password_or_otp} → returns access + refresh
- `POST /api/auth/refresh` — refresh token
- `POST /api/auth/verify-otp` — verify OTP

**Users / RH**
- `GET /api/users` (admin)
- `POST /api/users` — create agent
- `PUT /api/users/{id}` — update

**Planning / Assets**
- `GET /api/lines`
- `POST /api/lines`
- `GET /api/trips`
- `POST /api/buses`
- `POST /api/assignments` — payload includes crew ids, bus id, trip id, period

**Billetterie & Transactions**
- `POST /api/tickets/purchase` — payload {user_id, trip_id, fare_id, payment_method}
  - returns {ticket_id, qr_code}
- `POST /api/tickets/validate` — payload {qr_code, bus_id, controller_id, gps:{lat,lng}} → returns {valid:true/false, reason}
- `POST /api/transactions/cash` — record cash payment
- `POST /api/recharges` — top-up user balance

**Positions**
- `POST /api/buses/{id}/position` — {lat, lng, timestamp}
- `GET /api/buses/{id}/position?from=&to=` — history

## 5. Logique métier essentielle
- **Validation QR** : vérifier ticket existant, non utilisé, correspond au trip_id/line/bus affecté, TTL (durée de validité). Atomique update (DB transaction) pour marquer `used_at`.
- **Affectations** : vérification conflits sur période (un agent ou bus ne peut pas être affecté à 2 trajets simultanément).
- **Prix** : calcul = base_per_km * distance * (1 - discount) ; support pour coupons/promo.
- **Recharges** : traitement asynchrone si paiement externe (webhook)

## 6. Sécurité & scalabilité
- TLS everywhere
- JWT short-lived + refresh
- Rate-limit endpoints sensibles (auth, validate)
- DB indices sur qr_code, trip_id, bus_id
- Horizontal scale: stateless API, sessions via tokens
- CDN pour assets, Redis pour cache

## 7. Tests & documentation
- Contract tests (Postman / Newman)
- Unit tests sur règles métier (PHPUnit)
- Swagger/OpenAPI schema auto-généré

## 8. Observabilité
- Logs structurés (JSON)
- Metrics : requests/sec, errors, avg latency
- Alerts : validation failures rate, queue backlog

## 9. Exemple d'implémentation : validation atomique (pseudocode)
```php
DB::transaction(function() use($qrCode, $busId, $controllerId, $gps){
  $ticket = Ticket::where('qr_code', $qrCode)->lockForUpdate()->first();
  if(!$ticket) throw new Exception('Ticket not found');
  if($ticket->used_at) throw new Exception('Ticket already used');
  // check assignment / trip / bus
  $assignment = Assignment::currentForAgent($controllerId);
  if($assignment->bus_id != $busId) throw new Exception('Mismatch bus');
  $ticket->used_at = now();
  $ticket->used_by = $controllerId;
  $ticket->save();
  // create transaction log
  Transaction::create([...]);
});
```

## 10. Déploiement recommandé
- Utiliser Docker + Docker Compose
- Env : .env with secrets in vault (HashiCorp / AWS Secrets Manager)
- Auto-scaling via Kubernetes (si besoin) ou PaaS (Forge / Heroku)

---

# Annexes communes

## 1. Diagramme de séquence (résumé)
1. Admin (web) configure affectations & tarifs → stockés en DB
2. Mobile agent récupère assignments via /api/assignments/my
3. Bus publie sa position → /api/buses/{id}/position
4. Usager achète ticket via mobile → /api/tickets/purchase (QR généré)
5. Contrôleur scanne QR → /api/tickets/validate (DB transaction)
6. Si paiement cash → /api/transactions/cash

## 2. Règles d'or
- Toujours prévoir rollback atomic sur validations
- Prévoir chemin de secours (offline puis sync)
- Séparer rôles & principes least-privilege

---

*Document préparé pour WindSurf — livrable technique complet pour développement et intégration. Si tu veux, je peux :*
- *générer ces 3 markdowns en fichiers séparés téléchargeables,*
- *ajouter un schéma ER en SVG,*
- *fournir des Postman collections d'exemples d'appels API.*


  <script>
(() => {
  // ===========================
  // Utils
  // ===========================
  const BASE_URL = "<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>"; // fournie par PHP
  const log = (...args) => console.debug('[BusManager]', ...args);

  // Échappe le texte pour insertion sûre dans le DOM
  function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // Récupère un élément (safe)
  const $ = id => document.getElementById(id);

  // ===========================
  // État
  // ===========================
  let currentPage = 1;
  const itemsPerPage = 5;
  let busEnEdition = null; // objet bus si édition, sinon null

  // Données injectées par PHP (assure-toi que window.busData existe)
  if (!window.busData) window.busData = [];
  if (!Array.isArray(window.busData)) window.busData = [];

  // ===========================
  // DOM
  // ===========================
  const modal = $('modalBus');
  const modalTitle = $('modalTitle');
  const btnNouveauBus = $('btnNouveauBus');
  const btnCloseModal = $('btnCloseModal');
  const btnAnnuler = $('btnAnnuler');
  const busTableBody = $('busTableBody');
  const formBus = $('formBus');

  const btnPrevPage = $('btnPrevPage');
  const btnNextPage = $('btnNextPage');
  const paginationPages = $('paginationPages');
  const paginationStart = $('paginationStart');
  const paginationEnd = $('paginationEnd');
  const paginationTotal = $('paginationTotal');

  // Modal profil
  const modalProfil = $('modalProfil');

  // sécurité : éléments requis
  if (!busTableBody || !formBus) {
    console.error('Éléments clés manquants dans le DOM (busTableBody ou formBus).');
    return;
  }

  // ===========================
  // Fonctions modal add/edit
  // ===========================
  function ouvrirModalNouveau() {
    busEnEdition = null;
    modalTitle.textContent = 'Nouveau Bus';
    formBus.reset();
    if (modal) modal.classList.add('active');
    setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
  }

  async function ouvrirModalEdition(busId) {
    log('ouvrirModalEdition', busId);
    // Chercher dans data locale d'abord
    let bus = window.busData.find(b => Number(b.id) === Number(busId));
    if (!bus) {
      // récupérer via AJAX si absent
      try {
        const resp = await fetch(`${BASE_URL}/bus/details?bus_id=${encodeURIComponent(busId)}`);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const data = await resp.json();
        if (!data.success) throw new Error(data.message || 'Erreur serveur');
        bus = data.bus;
      } catch (err) {
        console.error('Erreur récupération bus:', err);
        alert('Impossible de charger les informations du bus.');
        return;
      }
    }

    busEnEdition = bus;
    modalTitle.textContent = `Modifier le Bus #${bus.numero || bus.id || ''}`;
    // Remplir les champs (vérifie que les inputs existent)
    const setIf = (id, val) => {
      const el = $(id);
      if (el) el.value = val ?? '';
    };

    setIf('numeroBus', bus.numero ?? '');
    setIf('immatriculation', bus.immatriculation ?? '');
    setIf('marque', bus.marque ?? '');
    setIf('modele', bus.modele ?? '');
    setIf('annee', bus.annee ?? '');
    setIf('capacite', bus.capacite ?? '');
    setIf('kilometrage', bus.kilometrage ?? '');
    setIf('ligneAffectee', bus.ligne_affectee ?? bus.ligne ?? '');
    setIf('statut', bus.statut ?? 'disponible');
    setIf('notes', bus.notes ?? '');

    // modules - checkbox
    const modulesArray = (bus.modules && typeof bus.modules === 'string')
      ? bus.modules.split(',').map(m => m.trim())
      : (Array.isArray(bus.modules) ? bus.modules : []);

    document.querySelectorAll('input[name="modules"]').forEach(cb => {
      cb.checked = modulesArray.includes(cb.value);
    });

    if (modal) modal.classList.add('active');
    setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
  }

  function fermerModal() {
    if (modal) modal.classList.remove('active');
    formBus.reset();
    busEnEdition = null;
  }

  // ===========================
  // Voir profil (AJAX + affichage)
  // ===========================
  async function voirProfilBus(busId) {
    if (!modalProfil) {
      alert('Fenêtre de profil non disponible.');
      return;
    }
    // show loader simple
    modalProfil.classList.add('active');
    const titreEl = $('profilTitle');
    if (titreEl) titreEl.textContent = 'Chargement...';

    try {
      const resp = await fetch(`${BASE_URL}/bus/details?bus_id=${encodeURIComponent(busId)}`);
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const text = await resp.text();
      // Essayer parsing sûr
      let data;
      try {
        data = JSON.parse(text);
      } catch (err) {
        console.error('Response non JSON:', text);
        throw new Error('Réponse invalide du serveur');
      }
      if (!data.success) throw new Error(data.message || 'Erreur serveur');
      afficherProfilBus(data.bus);
    } catch (err) {
      console.error('Erreur voirProfilBus:', err);
      alert('Erreur lors du chargement du profil: ' + (err.message || err));
      modalProfil.classList.remove('active');
    }
  }

  function afficherProfilBus(bus) {
    // sécuriser l'affichage avec escapeHtml
    $('profilTitle').textContent = `Profil du Bus #${escapeHtml(bus.numero ?? bus.id ?? '')}`;
    $('profilNumero').textContent = '#' + (bus.numero ?? bus.id ?? '-');
    $('profilImmatriculation').textContent = bus.immatriculation ?? '-';
    $('profilMarque').textContent = `${bus.marque ?? ''} ${bus.modele ?? ''}`.trim() || '-';
    $('profilAnnee').textContent = bus.annee ?? '-';
    $('profilCapacite').textContent = bus.capacite ? `${bus.capacite} places` : '-';
    $('profilKilometrage').textContent = (bus.kilometrage ?? 0) + ' km';
    $('profilLigne').textContent = bus.ligne_affectee || bus.ligne || 'Non affecté';

    $('profilStatut').innerHTML = `
      <span class="status-badge status-badge--${escapeHtml(bus.statut ?? 'disponible')}">
        ${escapeHtml((bus.statut ?? 'disponible').charAt(0).toUpperCase() + (bus.statut ?? 'disponible').slice(1))}
      </span>
    `;

    $('profilActivite').textContent = bus.derniere_activite ?? bus.derniereActivite ?? '-';

    // équipe
    const equipeEl = $('profilEquipeHoraires');
    if (equipeEl) {
      const equipe = Array.isArray(bus.equipe) ? bus.equipe : [];
      if (equipe.length === 0) {
        equipeEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucune équipe affectée actuellement.</p>';
      } else {
        // grouper par poste
        const group = {};
        equipe.forEach(m => {
          const poste = m.poste || 'autre';
          group[poste] = group[poste] || [];
          group[poste].push(m);
        });
        let html = '';
        for (const [poste, membres] of Object.entries(group)) {
          html += `<div class="equipe-role-section"><div class="equipe-role-title"><i data-feather="user"></i> ${escapeHtml(poste.charAt(0).toUpperCase() + poste.slice(1))}</div>`;
          membres.forEach(m => {
            html += `<div class="equipe-membre-horaire"><span class="equipe-membre-horaire__nom">${escapeHtml(m.nom)}</span><span class="equipe-membre-horaire__time"><i data-feather="phone"></i> ${escapeHtml(m.telephone || 'N/A')}</span></div>`;
          });
          html += '</div>';
        }
        equipeEl.innerHTML = html;
      }
    }

    // modules
    const modulesEl = $('profilModules');
    if (modulesEl) {
      const modulesArray = (bus.modules && typeof bus.modules === 'string')
        ? bus.modules.split(',').map(s => s.trim()).filter(Boolean)
        : (Array.isArray(bus.modules) ? bus.modules : []);
      if (modulesArray.length === 0) {
        modulesEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucun module installé</p>';
      } else {
        const icons = { datcha: 'credit-card', wifi: 'wifi', pos: 'shopping-cart', gps: 'map-pin', camera: 'camera' };
        const labels = { datcha: 'Datcha', wifi: 'WiFi', pos: 'POS', gps: 'GPS', camera: 'Caméra' };
        modulesEl.innerHTML = modulesArray.map(m => `<span class="profil-module"><i data-feather="${icons[m] || 'box'}"></i>${escapeHtml(labels[m] || m)}</span>`).join('');
      }
    }

    // documents
    const docsEl = $('profilDocuments');
    if (docsEl) {
      const docs = Array.isArray(bus.documents) ? bus.documents : [];
      if (docs.length === 0) {
        docsEl.innerHTML = '<tr><td colspan="2" style="text-align:center; color:var(--muted);">Aucun document enregistré</td></tr>';
      } else {
        const icons = { valide: 'check-circle', expire: 'x-circle', bientot: 'alert-circle' };
        const labels = { valide: 'Valide', expire: 'Expiré', bientot: 'Expire bientôt' };
        docsEl.innerHTML = docs.map(doc => `<tr><td>${escapeHtml(doc.designation)}</td><td><span class="doc-status doc-status--${escapeHtml(doc.statut)}"><i data-feather="${icons[doc.statut] || 'file'}"></i>${escapeHtml(labels[doc.statut] || doc.statut)}${doc.date_expiration ? `<br><small>Exp: ${escapeHtml(doc.date_expiration)}</small>` : ''}</span></td></tr>`).join('');
      }
    }

    // shifts
    const shiftsEl = $('profilShifts');
    if (shiftsEl) {
      const shifts = Array.isArray(window.shiftsData) ? window.shiftsData.filter(s => Number(s.busId) === Number(bus.id) && s.statut !== 'termine') : [];
      if (shifts.length === 0) {
        shiftsEl.innerHTML = '<p style="color:var(--muted); font-size:14px;">Aucun shift planifié pour ce bus.</p>';
      } else {
        shiftsEl.innerHTML = shifts.map(shift => `<div class="shift-card"><div class="shift-card__header"><div class="shift-card__date"><i data-feather="calendar"></i><strong>${escapeHtml(new Date(shift.date).toLocaleDateString('fr-FR'))}</strong></div><span class="status-badge status-badge--${escapeHtml(shift.statut)}">${escapeHtml(shift.statut.charAt(0).toUpperCase() + shift.statut.slice(1))}</span></div><div class="shift-card__time"><i data-feather="clock"></i>${escapeHtml(shift.heureDebut)} - ${escapeHtml(shift.heureFin)}</div><div class="shift-card__equipe"><div class="shift-card__membre"><i data-feather="user"></i><span><strong>Chauffeur:</strong> ${escapeHtml((shift.chauffeur && shift.chauffeur.nom) || 'N/A')}</span></div></div></div>`).join('');
      }
    }

    // notes
    const notesSec = $('profilNotesSection');
    if (notesSec) {
      if (bus.notes) {
        notesSec.style.display = 'block';
        $('profilNotes').textContent = bus.notes;
      } else {
        notesSec.style.display = 'none';
      }
    }

    setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
  }

  // fermer modal profil
  const btnCloseModalProfil = $('btnCloseModalProfil');
  if (btnCloseModalProfil) btnCloseModalProfil.addEventListener('click', () => modalProfil.classList.remove('active'));
  if (modalProfil) {
    const overlay = modalProfil.querySelector('.modal__overlay');
    if (overlay) overlay.addEventListener('click', () => modalProfil.classList.remove('active'));
  }

  // ===========================
  // Soumission form (add / edit)
  // ===========================
  formBus.addEventListener('submit', async (e) => {
    e.preventDefault();
    log('Soumission formulaire');

    const numero = $('numeroBus')?.value.trim() || '';
    const immatriculation = $('immatriculation')?.value.trim() || '';
    if (!numero || !immatriculation) {
      alert('Le numéro et l\'immatriculation sont obligatoires');
      return;
    }

    const modulesChecked = Array.from(document.querySelectorAll('input[name="modules"]:checked')).map(cb => cb.value);

    const payload = {
      numero,
      immatriculation,
      marque: $('marque')?.value.trim() || '',
      modele: $('modele')?.value.trim() || '',
      annee: $('annee')?.value || 0,
      capacite: $('capacite')?.value || 0,
      kilometrage: $('kilometrage')?.value || 0,
      ligne_affectee: $('ligneAffectee')?.value || null,
      statut: $('statut')?.value || 'disponible',
      modules: modulesChecked.join(','),
      notes: $('notes')?.value.trim() || ''
    };

    // Mode édition ?
    let url = `${BASE_URL}/bus/ajouter`;
    if (busEnEdition && busEnEdition.id) {
      url = `${BASE_URL}/bus/modifier`;
      payload.id = busEnEdition.id;
    }

    try {
      const resp = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const data = await resp.json();
      if (data.status === 'success' || data.success) {
        alert(data.message || 'Enregistré avec succès');

        // Mettre à jour window.busData localement si le serveur renvoie le bus
        const returnedBus = data.bus || data.data || null;
        if (returnedBus) {
          // normalise id en number
          if (payload.id) {
            // modif : remplacer l'élément
            const idx = window.busData.findIndex(b => Number(b.id) === Number(returnedBus.id));
            if (idx !== -1) window.busData[idx] = returnedBus;
            else window.busData.unshift(returnedBus);
          } else {
            // ajout
            window.busData.unshift(returnedBus);
          }
        } else {
          // Si serveur n'envoie pas l'objet, faire une mise à jour basique
          if (payload.id) {
            const idx = window.busData.findIndex(b => Number(b.id) === Number(payload.id));
            if (idx !== -1) window.busData[idx] = { ...window.busData[idx], ...payload };
          } else {
            // générer id temporaire négatif
            const tempId = -(Math.floor(Math.random() * 1000000));
            window.busData.unshift({ id: tempId, numero, immatriculation, trajet_nom: 'Non affecté', statut: payload.statut, chauffeur: '-', derniereActivite: '-', ...payload });
          }
        }

        fermerModal();
        currentPage = 1;
        afficherBus();

        // Si tu préfères recharger la page :
        // location.reload();
      } else {
        throw new Error(data.message || 'Erreur serveur');
      }
    } catch (err) {
      console.error('Erreur enregistrement:', err);
      alert('Erreur lors de l\'enregistrement du bus: ' + (err.message || err));
    }
  });

  // ===========================
  // Supprimer (placeholder)
  // ===========================
  async function supprimerBus(busId) {
    // Pour l'instant, on affiche un message. Tu peux implémenter une requête DELETE si nécessaire.
    if (!confirm('Voulez-vous vraiment supprimer ce bus ? Cette action est irréversible.')) return;
    try {
      // exemple:
      // const resp = await fetch(`${BASE_URL}/bus/supprimer`, { method: 'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({id:busId})});
      // const data = await resp.json();
      // if(data.success) { ... }
      alert('Fonction de suppression à implémenter côté serveur.');
    } catch (err) {
      console.error('Erreur suppression:', err);
      alert('Impossible de supprimer le bus.');
    }
  }

  // Rendre disponible globalement les fonctions nécessaires par onclick inline
  window.voirProfilBus = voirProfilBus;
  window.modifierBus = ouvrirModalEdition;
  window.supprimerBus = supprimerBus;

  // ===========================
  // Rendu tableau & pagination
  // ===========================
  function renderRow(bus) {
    // utilise escapeHtml pour protéger
    return `
      <tr>
        <td><strong>#${escapeHtml(bus.numero ?? bus.id ?? '')}</strong></td>
        <td>${escapeHtml(bus.immatriculation ?? '')}</td>
        <td>${escapeHtml(bus.trajet_nom ?? 'Non affecté')}</td>
        <td>
          <span class="status-badge status-badge--${escapeHtml(bus.statut ?? 'disponible')}">
            ${escapeHtml((bus.statut ?? 'disponible').charAt(0).toUpperCase() + (bus.statut ?? 'disponible').slice(1))}
          </span>
        </td>
        <td>${escapeHtml(bus.chauffeur ?? '-')}</td>
        <td>${escapeHtml(bus.derniereActivite ?? bus.derniere_activite ?? '-')}</td>
        <td>
          <div class="action-buttons">
            <button class="btn-icon btn-icon--edit" onclick="voirProfilBus(${JSON.stringify(bus.id)})" title="Voir le profil">
              <i data-feather="eye"></i>
            </button>
            <button class="btn-icon btn-icon--assign" onclick="modifierBus(${JSON.stringify(bus.id)})" title="Modifier">
              <i data-feather="edit-2"></i>
            </button>
            <button class="btn-icon btn-icon--delete" onclick="supprimerBus(${JSON.stringify(bus.id)})" title="Supprimer">
              <i data-feather="trash-2"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  }

  function afficherBus() {
    log('afficherBus - total', window.busData.length);
    if (!window.busData || window.busData.length === 0) {
      busTableBody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center; padding:40px;">
            <div style="color:#6b7280;">
              <i data-feather="inbox" style="width:48px;height:48px;margin-bottom:12px;"></i>
              <p style="font-size:16px;font-weight:600;margin:12px 0 8px;">Aucun bus enregistré</p>
              <p style="font-size:14px;margin:0;">Cliquez sur "Nouveau Bus" pour ajouter votre premier véhicule</p>
            </div>
          </td>
        </tr>
      `;
      setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
      // pagination update
      paginationStart.textContent = 0;
      paginationEnd.textContent = 0;
      paginationTotal.textContent = 0;
      paginationPages.innerHTML = '';
      btnPrevPage.disabled = true;
      btnNextPage.disabled = true;
      return;
    }

    const totalItems = window.busData.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
    const currentData = window.busData.slice(startIndex, endIndex);

    busTableBody.innerHTML = currentData.map(renderRow).join('');
    paginationStart.textContent = startIndex + 1;
    paginationEnd.textContent = endIndex;
    paginationTotal.textContent = totalItems;

    // pages
    paginationPages.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.className = 'pagination__page' + (i === currentPage ? ' active' : '');
      btn.textContent = i;
      btn.addEventListener('click', () => { currentPage = i; afficherBus(); });
      paginationPages.appendChild(btn);
    }

    btnPrevPage.disabled = (currentPage === 1);
    btnNextPage.disabled = (currentPage === totalPages);

    setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
  }

  // ===========================
  // Pagination buttons
  // ===========================
  if (btnPrevPage) btnPrevPage.addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; afficherBus(); }
  });
  if (btnNextPage) btnNextPage.addEventListener('click', () => {
    const totalPages = Math.ceil(window.busData.length / itemsPerPage);
    if (currentPage < totalPages) { currentPage++; afficherBus(); }
  });

  // ===========================
  // Evénements DOM
  // ===========================
  if (btnNouveauBus) btnNouveauBus.addEventListener('click', (e) => { e.preventDefault(); ouvrirModalNouveau(); });
  if (btnCloseModal) btnCloseModal.addEventListener('click', fermerModal);
  if (btnAnnuler) btnAnnuler.addEventListener('click', (e) => { e.preventDefault(); fermerModal(); });

  // Fermer modal en cliquant sur overlay
  if (modal) {
    const overlay = modal.querySelector('.modal__overlay');
    if (overlay) overlay.addEventListener('click', fermerModal);
  }

  // Initial rendering
  document.addEventListener('DOMContentLoaded', () => {
    log('DOMContentLoaded - initialisation');
    afficherBus();
    setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 10);
  });

  // Exports (si tu veux appeler depuis console)
  window.BusManager = {
    ouvrirModalNouveau,
    ouvrirModalEdition,
    afficherBus,
    getData: () => window.busData
  };

})();
</script>

