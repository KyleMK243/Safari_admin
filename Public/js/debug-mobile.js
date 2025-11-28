/**
 * Script de débogage pour vérifier la responsivité mobile
 */

document.addEventListener('DOMContentLoaded', function() {
  // Vérifier la largeur de l'écran
  console.log('=== DEBUG MOBILE ===');
  console.log('Largeur écran:', window.innerWidth);
  console.log('Largeur body:', document.body.offsetWidth);
  
  const main = document.querySelector('.main');
  if (main) {
    console.log('Main width:', main.offsetWidth);
    console.log('Main margin-left:', window.getComputedStyle(main).marginLeft);
    console.log('Main padding:', window.getComputedStyle(main).padding);
  }
  
  const app = document.querySelector('.app');
  if (app) {
    console.log('App width:', app.offsetWidth);
    console.log('App overflow-x:', window.getComputedStyle(app).overflowX);
  }
  
  const sidebar = document.querySelector('.sidebar');
  if (sidebar) {
    console.log('Sidebar position:', window.getComputedStyle(sidebar).position);
    console.log('Sidebar left:', window.getComputedStyle(sidebar).left);
  }
  
  // Vérifier les cards
  const cards = document.querySelectorAll('.card');
  console.log('Nombre de cards:', cards.length);
  if (cards.length > 0) {
    console.log('Première card width:', cards[0].offsetWidth);
    console.log('Première card max-width:', window.getComputedStyle(cards[0]).maxWidth);
  }
  
  // Vérifier le stats-grid
  const statsGrid = document.querySelector('.stats-grid');
  if (statsGrid) {
    console.log('Stats-grid width:', statsGrid.offsetWidth);
    console.log('Stats-grid grid-template-columns:', window.getComputedStyle(statsGrid).gridTemplateColumns);
  }
  
  console.log('===================');
});
