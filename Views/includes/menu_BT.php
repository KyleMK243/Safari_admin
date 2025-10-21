<!-- Sidebar Menu Billetterie -->
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
            <div class="user__role"><?php echo e(ucfirst($_SESSION['role'] ?? 'viewer')); ?> - Billetterie</div>
        </div>
    </div>

    <nav class="sidebar__nav">
        <?php
        // Charger les modules accessibles selon le rôle
        require_once ROOT_PATH . '/Model/Permission.php';
        $permissionModel = new Permission();
        $modules = $permissionModel->getModulesAccessibles($_SESSION['role'] ?? 'viewer', 'BT');
        
        // Afficher les modules autorisés
        foreach ($modules as $module):
        ?>
            <a class="nav__item <?php echo defined('CURRENT_ROUTE') && CURRENT_ROUTE === $module['route'] ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>/<?php echo $module['route']; ?>">
                <i data-feather="<?php echo $module['icone']; ?>"></i> <?php echo e($module['nom']); ?>
            </a>
        <?php endforeach; ?>
        
        <!-- Deconnexion -->
        <div class="nav__section">Exit</div>
        <a class="nav__item nav__item--logout" href="<?php echo BASE_URL; ?>/logout">
            <i data-feather="log-out"></i> Déconnexion
        </a>
    </nav>

    <div class="sidebar__footer">© 2024 Dare-Dare</div>
</aside>
