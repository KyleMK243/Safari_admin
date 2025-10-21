// ========================================
// DONNÉES DE TEST - SHIFTS
// À SUPPRIMER EN PRODUCTION
// ========================================

window.shiftsData = [
  {
    id: 1,
    busId: 1,
    busNumero: '421',
    date: '2025-10-07',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 1, nom: 'Jean Kabila' },
    controleur: { id: 2, nom: 'Pierre Mbuyi' },
    receveur: { id: 3, nom: 'Marie Nsimba' },
    statut: 'actif',
    createdAt: '2025-10-06T10:00:00'
  },
  {
    id: 2,
    busId: 1,
    busNumero: '421',
    date: '2025-10-07',
    heureDebut: '14:00',
    heureFin: '22:00',
    chauffeur: { id: 4, nom: 'Marie Tshala' },
    controleur: { id: 5, nom: 'Joseph Lumbu' },
    receveur: { id: 6, nom: 'Grace Kalonji' },
    statut: 'planifie',
    createdAt: '2025-10-06T10:05:00'
  },
  {
    id: 3,
    busId: 2,
    busNumero: '315',
    date: '2025-10-07',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 4, nom: 'Marie Tshala' },
    controleur: { id: 5, nom: 'Joseph Lumbu' },
    receveur: { id: 6, nom: 'Grace Kalonji' },
    statut: 'actif',
    createdAt: '2025-10-06T10:10:00'
  },
  {
    id: 4,
    busId: 4,
    busNumero: '156',
    date: '2025-10-07',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 7, nom: 'Paul Mukendi' },
    controleur: { id: 12, nom: 'André Mukendi' },
    receveur: { id: 15, nom: 'Sylvie Kabongo' },
    statut: 'actif',
    createdAt: '2025-10-06T10:15:00'
  },
  {
    id: 5,
    busId: 6,
    busNumero: '512',
    date: '2025-10-08',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 8, nom: 'Sarah Mbuyi' },
    controleur: { id: 2, nom: 'Pierre Mbuyi' },
    receveur: { id: 3, nom: 'Marie Nsimba' },
    statut: 'planifie',
    createdAt: '2025-10-06T10:20:00'
  },
  {
    id: 6,
    busId: 7,
    busNumero: '238',
    date: '2025-10-08',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 9, nom: 'David Nsimba' },
    controleur: { id: 5, nom: 'Joseph Lumbu' },
    receveur: { id: 6, nom: 'Grace Kalonji' },
    statut: 'planifie',
    createdAt: '2025-10-06T10:25:00'
  },
  {
    id: 7,
    busId: 8,
    busNumero: '310',
    date: '2025-10-08',
    heureDebut: '14:00',
    heureFin: '22:00',
    chauffeur: { id: 10, nom: 'Grace Lumbu' },
    controleur: { id: 12, nom: 'André Mukendi' },
    receveur: { id: 15, nom: 'Sylvie Kabongo' },
    statut: 'planifie',
    createdAt: '2025-10-06T10:30:00'
  },
  {
    id: 8,
    busId: 10,
    busNumero: '642',
    date: '2025-10-09',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 11, nom: 'Patrick Kalonji' },
    controleur: { id: 2, nom: 'Pierre Mbuyi' },
    receveur: { id: 3, nom: 'Marie Nsimba' },
    statut: 'planifie',
    createdAt: '2025-10-06T10:35:00'
  },
  {
    id: 9,
    busId: 1,
    busNumero: '421',
    date: '2025-10-06',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 1, nom: 'Jean Kabila' },
    controleur: { id: 2, nom: 'Pierre Mbuyi' },
    receveur: { id: 3, nom: 'Marie Nsimba' },
    statut: 'termine',
    createdAt: '2025-10-05T10:00:00'
  },
  {
    id: 10,
    busId: 2,
    busNumero: '315',
    date: '2025-10-06',
    heureDebut: '06:00',
    heureFin: '14:00',
    chauffeur: { id: 4, nom: 'Marie Tshala' },
    controleur: { id: 5, nom: 'Joseph Lumbu' },
    receveur: { id: 6, nom: 'Grace Kalonji' },
    statut: 'termine',
    createdAt: '2025-10-05T10:05:00'
  }
];

console.log('Données shifts chargées:', window.shiftsData.length, 'shifts');
