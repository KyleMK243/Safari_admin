// Données de test - Trajets
window.trajetsData = [
  {
    id: 1,
    nom: 'Ligne 1 - Kinshasa Centre',
    distanceTotale: 28.5,
    statut: 'actif',
    arrets: [
      { nom: 'Gare Centrale', distance: 0 },
      { nom: 'Victoire', distance: 3.2 },
      { nom: 'Lemba', distance: 8.5 },
      { nom: 'UPN', distance: 12.3 },
      { nom: 'Matete', distance: 18.7 },
      { nom: 'Kintambo', distance: 28.5 }
    ],
    pointsChifte: [
      { nom: 'Point de relève Lemba', distance: 9.0 },
      { nom: 'Point de relève Matete', distance: 19.0 }
    ]
  },
  {
    id: 2,
    nom: 'Ligne 2 - Route de Matadi',
    distanceTotale: 35.2,
    statut: 'actif',
    arrets: [
      { nom: 'Gare Centrale', distance: 0 },
      { nom: 'Limete', distance: 5.5 },
      { nom: 'Kingabwa', distance: 12.8 },
      { nom: 'Kinkole', distance: 22.4 },
      { nom: 'Nsele', distance: 35.2 }
    ],
    pointsChifte: [
      { nom: 'Point de relève Kingabwa', distance: 13.0 },
      { nom: 'Point de relève Kinkole', distance: 23.0 }
    ]
  },
  {
    id: 3,
    nom: 'Ligne 3 - Ngaliema',
    distanceTotale: 22.0,
    statut: 'actif',
    arrets: [
      { nom: 'Gare Centrale', distance: 0 },
      { nom: 'Gombe', distance: 4.2 },
      { nom: 'Ngaliema', distance: 10.5 },
      { nom: 'Mont Ngafula', distance: 18.3 },
      { nom: 'Terminus Sud', distance: 22.0 }
    ],
    pointsChifte: [
      { nom: 'Point de relève Ngaliema', distance: 11.0 }
    ]
  }
];

console.log('Données trajets chargées:', window.trajetsData.length, 'trajets');
