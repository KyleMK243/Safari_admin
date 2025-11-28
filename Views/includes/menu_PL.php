<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar__brand" style="display: flex; align-items: center; gap: 12px;">
        <img src="Public/img/icons/safari-icone1.jpeg" alt="Safari Logo" style="width: 50px; height: 50px; object-fit: contain; flex-shrink: 0;">
        <div style="flex: 1;">
            <div class="brand__logo">SAFARI</div>
            <div class="brand__tag">Smart mobility</div>
        </div>
    </div>

    <div class="sidebar__user">
        <div class="user__avatar"><?php echo e($_SESSION['avatar'] ?? 'U'); ?></div>
        <div class="user__info">
            <div class="user__name"><?php echo e($_SESSION['nom'] ?? 'Utilisateur'); ?></div>
            <div class="user__role"><?php echo e(ucfirst($_SESSION['role'] ?? 'viewer')); ?> - Planification</div>
        </div>
    </div>

    <nav class="sidebar__nav">
        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && CURRENT_ROUTE === 'dashboard_PL' ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/dashboard_PL">
            <i data-feather="home"></i> Dashboard
        </a>

        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && CURRENT_ROUTE === 'roulement-pl' ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/roulement-pl">
            <i data-feather="calendar"></i> Roulement journalier
        </a>

        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && CURRENT_ROUTE === 'trajets-pl' ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/trajets-pl">
            <i data-feather="map"></i> Lignes / trajets
        </a>

        <div class="nav__section">SYSTÈME</div>

        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && (CURRENT_ROUTE === 'parametres' || strpos(CURRENT_ROUTE, 'parametres-pl') === 0) ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/parametres">
            <i data-feather="settings"></i> Paramètres
        </a>

        <a class="nav__item nav__item--logout" href="<?php echo BASE_URL; ?>/logout">
            <i data-feather="log-out"></i> Déconnexion
        </a>
    </nav>

   <div class="sidebar__footer">© 2024 Pimacle RDC</div>
</aside>