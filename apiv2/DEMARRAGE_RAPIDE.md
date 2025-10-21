# 🚀 Démarrage Rapide - API v2

## ⚡ En 3 étapes

### 1️⃣ Vérifier que ça fonctionne

Ouvrez votre navigateur et accédez à :
```
http://localhost/SafariSmartMobily/apiv2
```

Vous devriez voir une réponse JSON avec les informations de l'API.

### 2️⃣ Consulter la documentation

Ouvrez la documentation interactive :
```
http://localhost/SafariSmartMobily/apiv2/documentation.html
```

### 3️⃣ Tester l'API

Ouvrez la page de test :
```
http://localhost/SafariSmartMobily/apiv2/test.html
```

Cliquez sur les boutons pour tester chaque endpoint !

---

## 📱 Exemples pratiques

### Exemple 1 : Récupérer tous les bus

**Avec le navigateur :**
```
http://localhost/SafariSmartMobily/apiv2/bus
```

**Avec JavaScript :**
```javascript
fetch('http://localhost/SafariSmartMobily/apiv2/bus')
  .then(res => res.json())
  .then(data => console.log(data));
```

### Exemple 2 : Créer un nouveau bus

**Avec JavaScript :**
```javascript
fetch('http://localhost/SafariSmartMobily/apiv2/bus', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    numero: 'BUS001',
    immatriculation: 'CD-123-AB',
    marque: 'Mercedes',
    capacite: 50
  })
})
.then(res => res.json())
.then(data => console.log(data));
```

### Exemple 3 : Mettre à jour un bus

**Avec JavaScript :**
```javascript
fetch('http://localhost/SafariSmartMobily/apiv2/bus/1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    statut: 'maintenance'
  })
})
.then(res => res.json())
.then(data => console.log(data));
```

---

## 🎯 Endpoints principaux

| Ressource | GET (liste) | GET (détail) | POST | PUT | DELETE |
|-----------|-------------|--------------|------|-----|--------|
| Bus | `/bus` | `/bus/{id}` | `/bus` | `/bus/{id}` | `/bus/{id}` |
| Utilisateurs | `/utilisateurs` | `/utilisateurs/{id}` | `/utilisateurs` | `/utilisateurs/{id}` | `/utilisateurs/{id}` |
| Trajets | `/trajets` | `/trajets/{id}` | `/trajets` | `/trajets/{id}` | `/trajets/{id}` |
| Billets | `/billets` | `/billets/{id}` | `/billets` | `/billets/{id}` | `/billets/{id}` |
| Équipe | `/equipe_bord` | `/equipe_bord/{id}` | `/equipe_bord` | `/equipe_bord/{id}` | `/equipe_bord/{id}` |
| Colis | `/colis` | `/colis/{id}` | `/colis` | `/colis/{id}` | `/colis/{id}` |
| Shifts | `/shifts` | `/shifts/{id}` | `/shifts` | `/shifts/{id}` | `/shifts/{id}` |
| Alertes | `/alertes` | `/alertes/{id}` | `/alertes` | `/alertes/{id}` | `/alertes/{id}` |

---

## 💡 Conseils

### ✅ À faire
- Toujours envoyer `Content-Type: application/json` pour POST/PUT
- Vérifier le code HTTP de la réponse
- Lire le message d'erreur en cas de problème
- Utiliser la page de test pour déboguer

### ❌ À éviter
- Ne pas oublier le `/` au début de l'URL
- Ne pas envoyer de données vides pour les champs requis
- Ne pas utiliser GET pour créer/modifier des données

---

## 🔍 Déboguer

### L'API ne répond pas ?
1. Vérifiez que XAMPP est démarré
2. Vérifiez l'URL : `http://localhost/SafariSmartMobily/apiv2`
3. Vérifiez les logs PHP dans XAMPP

### Erreur 500 ?
1. Vérifiez la connexion à la base de données
2. Vérifiez le fichier `.env` à la racine
3. Vérifiez que la base de données `safari_smart_mobility` existe

### Erreur 404 ?
1. Vérifiez l'URL (orthographe)
2. Vérifiez que le fichier `.htaccess` existe
3. Vérifiez que mod_rewrite est activé dans Apache

---

## 📚 Documentation complète

Pour plus de détails, consultez :
- **README.md** - Documentation technique complète
- **documentation.html** - Documentation interactive avec exemples
- **SUMMARY.md** - Résumé de tout ce qui a été créé

---

## 🎉 C'est tout !

Votre API v2 est prête à l'emploi. Bon développement ! 🚀
