/**
 * Gestion du menu mobile (hamburger)
 */

document.addEventListener('DOMContentLoaded', function() {
  // Créer le bouton hamburger
  const menuToggle = document.createElement('button');
  menuToggle.className = 'menu-toggle';
  menuToggle.innerHTML = '<span></span><span></span><span></span>';
  menuToggle.setAttribute('aria-label', 'Toggle menu');
  
  // Créer l'overlay
  const overlay = document.createElement('div');
  overlay.className = 'sidebar-overlay';
  
  // Ajouter au DOM
  document.body.appendChild(menuToggle);
  document.body.appendChild(overlay);
  
  const sidebar = document.querySelector('.sidebar');
  
  // Toggle menu
  function toggleMenu() {
    menuToggle.classList.toggle('active');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
  }
  
  // Fermer le menu
  function closeMenu() {
    menuToggle.classList.remove('active');
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }
  
  // Event listeners
  menuToggle.addEventListener('click', toggleMenu);
  overlay.addEventListener('click', closeMenu);
  
  // Fermer le menu lors du clic sur un lien de navigation
  const navItems = document.querySelectorAll('.nav__item');
  navItems.forEach(item => {
    item.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        closeMenu();
      }
    });
  });
  
  // Gérer le redimensionnement de la fenêtre
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (window.innerWidth > 768) {
        closeMenu();
      }
    }, 250);
  });
  
  // Masquer le bouton hamburger sur desktop
  function checkScreenSize() {
    if (window.innerWidth > 768) {
      menuToggle.style.display = 'none';
      overlay.style.display = 'none';
    } else {
      menuToggle.style.display = 'flex';
    }
  }
  
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
});
