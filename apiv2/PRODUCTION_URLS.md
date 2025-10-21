# 🌐 URLs de Production - API v2

## 🚀 API en ligne !

L'API v2 est maintenant déployée et accessible en ligne.

### 📍 URL de base
```
https://safari.hakika.events/apiv2/
```

---

## 📚 Documentation et Tests

### Documentation interactive
```
https://safari.hakika.events/apiv2/documentation.html
```

### Page de test
```
https://safari.hakika.events/apiv2/test.html
```

---

## 🎯 Endpoints disponibles

### Bus
- **GET** `https://safari.hakika.events/apiv2/bus` - Liste tous les bus
- **GET** `https://safari.hakika.events/apiv2/bus/{id}` - Détails d'un bus
- **POST** `https://safari.hakika.events/apiv2/bus` - Créer un bus
- **PUT** `https://safari.hakika.events/apiv2/bus/{id}` - Mettre à jour un bus
- **DELETE** `https://safari.hakika.events/apiv2/bus/{id}` - Supprimer un bus

### Utilisateurs
- **GET** `https://safari.hakika.events/apiv2/utilisateurs` - Liste tous les utilisateurs
- **GET** `https://safari.hakika.events/apiv2/utilisateurs/{id}` - Détails d'un utilisateur
- **POST** `https://safari.hakika.events/apiv2/utilisateurs` - Créer un utilisateur
- **PUT** `https://safari.hakika.events/apiv2/utilisateurs/{id}` - Mettre à jour un utilisateur
- **DELETE** `https://safari.hakika.events/apiv2/utilisateurs/{id}` - Supprimer un utilisateur

### Trajets
- **GET** `https://safari.hakika.events/apiv2/trajets` - Liste tous les trajets
- **GET** `https://safari.hakika.events/apiv2/trajets/{id}` - Détails d'un trajet
- **POST** `https://safari.hakika.events/apiv2/trajets` - Créer un trajet
- **PUT** `https://safari.hakika.events/apiv2/trajets/{id}` - Mettre à jour un trajet
- **DELETE** `https://safari.hakika.events/apiv2/trajets/{id}` - Supprimer un trajet

### Billets
- **GET** `https://safari.hakika.events/apiv2/billets` - Liste tous les billets
- **GET** `https://safari.hakika.events/apiv2/billets/{id}` - Détails d'un billet
- **POST** `https://safari.hakika.events/apiv2/billets` - Créer un billet
- **PUT** `https://safari.hakika.events/apiv2/billets/{id}` - Mettre à jour un billet
- **DELETE** `https://safari.hakika.events/apiv2/billets/{id}` - Supprimer un billet

### Équipe de Bord
- **GET** `https://safari.hakika.events/apiv2/equipe_bord` - Liste tous les membres
- **GET** `https://safari.hakika.events/apiv2/equipe_bord/{id}` - Détails d'un membre
- **POST** `https://safari.hakika.events/apiv2/equipe_bord` - Créer un membre
- **PUT** `https://safari.hakika.events/apiv2/equipe_bord/{id}` - Mettre à jour un membre
- **DELETE** `https://safari.hakika.events/apiv2/equipe_bord/{id}` - Supprimer un membre

### Colis
- **GET** `https://safari.hakika.events/apiv2/colis` - Liste tous les colis
- **GET** `https://safari.hakika.events/apiv2/colis/{id}` - Détails d'un colis
- **POST** `https://safari.hakika.events/apiv2/colis` - Créer un colis
- **PUT** `https://safari.hakika.events/apiv2/colis/{id}` - Mettre à jour un colis
- **DELETE** `https://safari.hakika.events/apiv2/colis/{id}` - Supprimer un colis

### Shifts
- **GET** `https://safari.hakika.events/apiv2/shifts` - Liste tous les shifts
- **GET** `https://safari.hakika.events/apiv2/shifts/{id}` - Détails d'un shift
- **POST** `https://safari.hakika.events/apiv2/shifts` - Créer un shift
- **PUT** `https://safari.hakika.events/apiv2/shifts/{id}` - Mettre à jour un shift
- **DELETE** `https://safari.hakika.events/apiv2/shifts/{id}` - Supprimer un shift

### Alertes
- **GET** `https://safari.hakika.events/apiv2/alertes` - Liste toutes les alertes
- **GET** `https://safari.hakika.events/apiv2/alertes/{id}` - Détails d'une alerte
- **POST** `https://safari.hakika.events/apiv2/alertes` - Créer une alerte
- **PUT** `https://safari.hakika.events/apiv2/alertes/{id}` - Mettre à jour une alerte
- **DELETE** `https://safari.hakika.events/apiv2/alertes/{id}` - Supprimer une alerte

---

## 💡 Exemples d'utilisation

### Avec cURL
```bash
# GET - Obtenir tous les bus
curl https://safari.hakika.events/apiv2/bus

# POST - Créer un bus
curl -X POST https://safari.hakika.events/apiv2/bus \
  -H "Content-Type: application/json" \
  -d '{
    "numero": "BUS001",
    "immatriculation": "CD-123-AB",
    "marque": "Mercedes",
    "capacite": 50
  }'

# PUT - Mettre à jour un bus
curl -X PUT https://safari.hakika.events/apiv2/bus/1 \
  -H "Content-Type: application/json" \
  -d '{"statut": "maintenance"}'

# DELETE - Supprimer un bus
curl -X DELETE https://safari.hakika.events/apiv2/bus/1
```

### Avec JavaScript (Fetch)
```javascript
// GET
fetch('https://safari.hakika.events/apiv2/bus')
  .then(response => response.json())
  .then(data => console.log(data));

// POST
fetch('https://safari.hakika.events/apiv2/bus', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    numero: 'BUS001',
    immatriculation: 'CD-123-AB',
    marque: 'Mercedes',
    capacite: 50
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Avec Axios
```javascript
// GET
axios.get('https://safari.hakika.events/apiv2/bus')
  .then(response => console.log(response.data));

// POST
axios.post('https://safari.hakika.events/apiv2/bus', {
  numero: 'BUS001',
  immatriculation: 'CD-123-AB',
  marque: 'Mercedes',
  capacite: 50
})
.then(response => console.log(response.data));
```

---

## 🔐 Configuration de la base de données

L'API utilise la base de données suivante :
- **Host:** localhost
- **Database:** ngla4195_safari
- **User:** ngla4195_ngla4195

---

## 📊 Statut de l'API

✅ **EN LIGNE** - L'API est opérationnelle et prête à l'emploi !

### Vérifier le statut
```bash
curl https://safari.hakika.events/apiv2/
```

Réponse attendue :
```json
{
    "success": true,
    "message": "Safari Smart Mobility API v2",
    "version": "2.0.0",
    "endpoints": { ... }
}
```

---

## 🎉 Prochaines étapes

1. **Tester l'API** - Utilisez la page de test : https://safari.hakika.events/apiv2/test.html
2. **Consulter la documentation** - https://safari.hakika.events/apiv2/documentation.html
3. **Intégrer dans votre application** - Utilisez les endpoints ci-dessus

---

**Date de déploiement:** 2025-10-10  
**Version:** 2.0.0  
**Statut:** ✅ Production
