
# 02_WEB_ADMIN.md

---

# Application Web (Desktop/Admin) — Plan de réalisation (HTML/CSS/JS + PHP)

## 1. Objectifs fonctionnels
Interface d'administration desktop accessible par rôles (RH, Chef d'Exploitation, Directeur Billetterie) pour :
- Gestion du personnel (création Fiches RH)
- Planification des lignes, trajets, bus
- Assignation équipages → bus → trajet
- Configuration des tarifs et profils d'abonnement
- Consultation en temps réel des transactions et positions
- Rapports et exports (CSV, Excel)

## 2. Choix techniques
- Langage back-end : PHP (recommandation : Laravel pour rapidité et sécurité — compatible avec HTML/CSS/JS)
- Front-end : Blade (si Laravel) ou HTML5 + CSS3 + JS vanilla / Alpine.js pour interactions légères
- Base de données : MySQL / MariaDB
- Auth admin : sessions sécurisées + 2FA (optionnel)

## 3. Architecture applicative
- MVC (Laravel) : Controllers (RHController, PlanningController, BillingController, ReportsController), Models (User, Bus, Line, Trip, Fare), Views (Blade templates)
- API interne (routes API) pour feed temps réel du mobile (ex: positions, validation)
- WebSocket / Redis pour updates temps réel (positions/transactions)

## 4. Fonctionnalités par rôle
- RH
  - CRUD agents (chauffeur, contrôleur, receveur)
  - Import CSV pour masse d'agents
- Chef d'Exploitation
  - CRUD Lignes, Trajets (distance en km)
  - CRUD Bus (matricule, capacité)
  - Assignations : selection d’équipage + bus + période
  - Visualisation planning (calendar/week view)
- Directeur Billetterie
  - Définition prix par km, profils d’abonnement et règles de remise
  - Configuration options de recharge (modes, limites)
  - Historique des ventes et rapports

## 5. Pages clés (UI)
- Login Admin
- Dashboard (KPIs : recettes journalières, bus en service, tickets validés)
- Gestion RH
- Planification & Affectations (drag & drop)
- Configuration Billetterie
- Transactions en temps réel
- Logs & Audit

## 6. Structure recommandée (arborescence MVC)
```
/project
  /app
    /Controllers
    /Models
    /Views
  /public
    /css
    /js
    /images
  /routes
  /database
  /tests
```

## 7. Intégration avec l'API
- Toutes les actions mobiles critiques s'appuient sur API RESTful (voir doc API)
- Web admin utilisera soit les mêmes endpoints API (consommer REST) soit drivers Eloquent direct
- Jobs & scheduled tasks (Laravel Scheduler) pour tâches périodiques (reconciliations, génération rapports)

## 8. Sécurité
- Auth + roles + policies (gate/perm)
- Validation côté serveur des imports et affectations
- HTTPS + CSP headers + protection CSRF (Laravel gère nativement)

## 9. Tests & déploiement
- Tests unitaires (PHPUnit), tests fonctionnels (Laravel Dusk)
- CI/CD : GitHub Actions pour tests, déploiement via Forge / Capistrano / Docker

---