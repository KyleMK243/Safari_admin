<!-- Sidebar Menu Bureau de conception -->
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
            <div class="user__role"><?php echo e(ucfirst($_SESSION['role'] ?? 'viewer')); ?> - Bureau de conception</div>
        </div>
    </div>

    <nav class="sidebar__nav">
        <!-- Dashboard BC -->
        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && CURRENT_ROUTE === 'dashboard_BC' ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/dashboard_BC">
            <i data-feather="home"></i> Dashboard
        </a>

        <!-- Lignes / trajets -->
        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && strpos(CURRENT_ROUTE, 'trajets') === 0 ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/trajets">
            <i data-feather="map"></i> Lignes / trajets
        </a>

        <div class="nav__section">Gestion des roulements</div>

        <!-- Roulements Controlleurs -->
        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && strpos(CURRENT_ROUTE, 'roulements-bc') === 0 ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/roulements-bc">
            <i data-feather="repeat"></i> Roulements Controlleurs
        </a>

        <!-- Paramètres -->
        <a class="nav__item <?php echo defined('CURRENT_ROUTE') && strpos(CURRENT_ROUTE, 'parametres') === 0 ? 'active' : ''; ?>" 
           href="<?php echo BASE_URL; ?>/parametres">
            <i data-feather="settings"></i> Paramètres
        </a>

        <!-- Déconnexion -->
        <div class="nav__section">Exit</div>
        <a class="nav__item nav__item--logout" href="<?php echo BASE_URL; ?>/logout">
            <i data-feather="log-out"></i> Déconnexion
        </a>
    </nav>

    <div class="sidebar__footer">© 2025 Pimacle RDC</div>
</aside>
