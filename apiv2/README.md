# API v2 - Safari Smart Mobility

API REST complète en PHP pur (sans framework) pour la gestion du système Safari Smart Mobility.

## 🚀 Caractéristiques

- **PHP pur** - Aucun framework requis
- **Base de données** - Utilise la configuration existante de SafariSmartMobily
- **CRUD complet** - Toutes les opérations Create, Read, Update, Delete
- **Réponses JSON** - Format standardisé pour toutes les réponses
- **CORS activé** - Prêt pour les applications frontend
- **Documentation HTML** - Documentation interactive complète

## 📁 Structure du projet

```
apiv2/
├── config/
│   ├── Database.php      # Connexion à la base de données
│   └── Response.php      # Helper pour les réponses JSON
├── routes/
│   ├── bus.php          # Routes CRUD pour les bus
│   ├── utilisateurs.php # Routes CRUD pour les utilisateurs
│   ├── trajets.php      # Routes CRUD pour les trajets
│   ├── billets.php      # Routes CRUD pour les billets
│   ├── equipe_bord.php  # Routes CRUD pour l'équipe de bord
│   ├── colis.php        # Routes CRUD pour les colis
│   ├── shifts.php       # Routes CRUD pour les shifts
│   └── alertes.php      # Routes CRUD pour les alertes
├── index.php            # Routeur principal
├── documentation.html   # Documentation complète
└── README.md           # Ce fichier
```

## 🔧 Installation

1. **Aucune installation requise** - L'API utilise la configuration existante de SafariSmartMobily

2. **Vérifier la configuration** - Assurez-vous que le fichier `.env` est configuré correctement à la racine du projet

3. **Accéder à l'API** :
   ```
   http://localhost/SafariSmartMobily/apiv2
   ```

## 📚 Documentation

Accédez à la documentation complète en HTML :
```
http://localhost/SafariSmartMobily/apiv2/documentation.html
```

## 🎯 Endpoints disponibles

### Bus (`/bus`)
- `GET /bus` - Liste tous les bus
- `GET /bus/{id}` - Détails d'un bus
- `POST /bus` - Créer un bus
- `PUT /bus/{id}` - Mettre à jour un bus
- `DELETE /bus/{id}` - Supprimer un bus

### Utilisateurs (`/utilisateurs`)
- `GET /utilisateurs` - Liste tous les utilisateurs
- `GET /utilisateurs/{id}` - Détails d'un utilisateur
- `POST /utilisateurs` - Créer un utilisateur
- `PUT /utilisateurs/{id}` - Mettre à jour un utilisateur
- `DELETE /utilisateurs/{id}` - Supprimer un utilisateur

### Trajets (`/trajets`)
- `GET /trajets` - Liste tous les trajets
- `GET /trajets/{id}` - Détails d'un trajet
- `POST /trajets` - Créer un trajet
- `PUT /trajets/{id}` - Mettre à jour un trajet
- `DELETE /trajets/{id}` - Supprimer un trajet

### Billets (`/billets`)
- `GET /billets` - Liste tous les billets (100 derniers)
- `GET /billets/{id}` - Détails d'un billet
- `POST /billets` - Créer un billet
- `PUT /billets/{id}` - Mettre à jour un billet
- `DELETE /billets/{id}` - Supprimer un billet

### Équipe de Bord (`/equipe_bord`)
- `GET /equipe_bord` - Liste tous les membres
- `GET /equipe_bord/{id}` - Détails d'un membre
- `POST /equipe_bord` - Créer un membre
- `PUT /equipe_bord/{id}` - Mettre à jour un membre
- `DELETE /equipe_bord/{id}` - Supprimer un membre

### Colis (`/colis`)
- `GET /colis` - Liste tous les colis (100 derniers)
- `GET /colis/{id}` - Détails d'un colis
- `POST /colis` - Créer un colis
- `PUT /colis/{id}` - Mettre à jour un colis
- `DELETE /colis/{id}` - Supprimer un colis

### Shifts (`/shifts`)
- `GET /shifts` - Liste tous les shifts
- `GET /shifts/{id}` - Détails d'un shift
- `POST /shifts` - Créer un shift
- `PUT /shifts/{id}` - Mettre à jour un shift
- `DELETE /shifts/{id}` - Supprimer un shift

### Alertes (`/alertes`)
- `GET /alertes` - Liste toutes les alertes (100 dernières)
- `GET /alertes/{id}` - Détails d'une alerte
- `POST /alertes` - Créer une alerte
- `PUT /alertes/{id}` - Mettre à jour une alerte
- `DELETE /alertes/{id}` - Supprimer une alerte

## 💡 Exemples d'utilisation

### Avec cURL

```bash
# GET - Obtenir tous les bus
curl http://localhost/SafariSmartMobily/apiv2/bus

# POST - Créer un bus
curl -X POST http://localhost/SafariSmartMobily/apiv2/bus \
  -H "Content-Type: application/json" \
  -d '{
    "numero": "BUS001",
    "immatriculation": "CD-123-AB",
    "marque": "Mercedes",
    "capacite": 50
  }'

# PUT - Mettre à jour un bus
curl -X PUT http://localhost/SafariSmartMobily/apiv2/bus/1 \
  -H "Content-Type: application/json" \
  -d '{"statut": "maintenance"}'

# DELETE - Supprimer un bus
curl -X DELETE http://localhost/SafariSmartMobily/apiv2/bus/1
```

### Avec JavaScript (Fetch)

```javascript
// GET
fetch('http://localhost/SafariSmartMobily/apiv2/bus')
  .then(response => response.json())
  .then(data => console.log(data));

// POST
fetch('http://localhost/SafariSmartMobily/apiv2/bus', {
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

### Avec PHP

```php
<?php
// GET
$response = file_get_contents('http://localhost/SafariSmartMobily/apiv2/bus');
$data = json_decode($response, true);
print_r($data);

// POST
$data = [
    'numero' => 'BUS001',
    'immatriculation' => 'CD-123-AB',
    'marque' => 'Mercedes',
    'capacite' => 50
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents('http://localhost/SafariSmartMobily/apiv2/bus', false, $context);
$response = json_decode($result, true);
print_r($response);
?>
```

## 📋 Format des réponses

### Succès
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... }
}
```

### Erreur
```json
{
    "success": false,
    "message": "Error message"
}
```

### Erreur de validation
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field1": "Error message",
        "field2": "Error message"
    }
}
```

## 🔒 Codes HTTP

- **200** - Succès
- **201** - Créé avec succès
- **400** - Requête invalide
- **404** - Ressource non trouvée
- **405** - Méthode non autorisée
- **409** - Conflit (doublon)
- **422** - Erreur de validation
- **500** - Erreur serveur

## 🛠️ Configuration

L'API utilise automatiquement la configuration de la base de données depuis le fichier `.env` à la racine du projet SafariSmartMobily.

Variables d'environnement utilisées :
- `DB_HOST` - Hôte de la base de données (défaut: localhost)
- `DB_NAME` - Nom de la base de données (défaut: safari_smart_mobility)
- `DB_USER` - Utilisateur de la base de données (défaut: root)
- `DB_PASS` - Mot de passe de la base de données (défaut: vide)

## 🧪 Tests

Pour tester l'API, vous pouvez :

1. **Utiliser la documentation HTML** - Interface interactive avec exemples
2. **Utiliser Postman** - Importer les endpoints et tester
3. **Utiliser cURL** - Tester depuis la ligne de commande
4. **Créer un frontend** - L'API est prête pour être consommée

## 📝 Notes importantes

- L'API utilise **PDO** avec des requêtes préparées pour la sécurité
- Les mots de passe sont **hashés** avec `password_hash()`
- **CORS** est activé pour permettre les requêtes cross-origin
- Les réponses sont en **UTF-8** avec support des caractères spéciaux
- Les erreurs sont **loggées** mais pas affichées en production

## 🚀 Prochaines étapes

Pour améliorer l'API, vous pouvez ajouter :
- Authentification JWT
- Rate limiting
- Pagination avancée
- Filtres et recherche
- Upload de fichiers
- Webhooks
- Versioning de l'API

## 📞 Support

Pour toute question ou problème, consultez la documentation HTML complète ou vérifiez les logs d'erreur PHP.

---

**Version:** 2.0.0  
**Date:** 2025-10-10  
**Auteur:** Safari Smart Mobility Team
