# Guide de déploiement Railway pour MaxIT

## 🚂 Configuration Railway

### 1. Prérequis
- Compte Railway : https://railway.app/
- CLI Railway installé : `npm install -g @railway/cli`
- Git configuré dans votre projet

### 2. Installation CLI Railway
```bash
npm install -g @railway/cli
railway login
```

### 3. Initialisation du projet Railway
```bash
# Dans le dossier de votre projet
railway init
```

### 4. Configuration de la base de données PostgreSQL

1. **Créer une base PostgreSQL sur Railway :**
   - Allez sur railway.app
   - Créez un nouveau projet
   - Ajoutez une base PostgreSQL
   - Notez les variables de connexion

2. **Variables d'environnement à configurer :**
   ```
   DATABASE_URL=postgresql://username:password@host:port/database
   APP_ENV=production
   ```

### 5. Migration de la base de données

#### Option A : Via script PHP (recommandé)
```bash
php init-railway-db.php
```

#### Option B : Via psql
```bash
# Rendre le script exécutable
chmod +x migrate-railway.sh

# Exécuter les migrations
./migrate-railway.sh
```

### 6. Déploiement

#### Méthode 1 : CLI Railway
```bash
# Connexion au projet
railway link

# Déploiement
railway up
```

#### Méthode 2 : Connexion GitHub
1. Connectez votre repo GitHub à Railway
2. Railway déploiera automatiquement à chaque push

### 7. Configuration des variables d'environnement

Dans le dashboard Railway, configurez :
```
DATABASE_URL=postgresql://[vos-credentials]
APP_ENV=production
APP_DEBUG=false
```

### 8. Test du déploiement

1. **Vérifiez l'URL de votre application**
2. **Testez la connexion avec :**
   - Login : `771234567`
   - Password : `test123` ou `1234`

### 9. Structure des fichiers Railway

- `railway.json` : Configuration Railway
- `nixpacks.toml` : Configuration du build
- `Dockerfile` : Image Docker optimisée pour Railway
- `init-railway-db.php` : Script d'initialisation BDD
- `migrate-railway.sh` : Script de migration

### 🔧 Dépannage

#### Erreur de connexion DB
```bash
# Vérifiez vos variables
railway variables

# Testez la connexion
php test-railway-connection.php
```

#### Erreur de build
```bash
# Voir les logs
railway logs

# Rebuild
railway up --detach
```

### 📝 Notes importantes

1. **Sécurité** : Ne jamais committer les vraies credentials dans le code
2. **Logs** : Utilisez `railway logs` pour debugger
3. **Domaine** : Railway fournit un domaine automatique ou configurez le vôtre
4. **SSL** : Activé automatiquement sur Railway

### 🚀 Commandes utiles

```bash
# Voir le statut
railway status

# Ouvrir l'app dans le navigateur
railway open

# Voir les variables
railway variables

# Voir les logs en temps réel
railway logs --follow

# Redémarrer le service
railway restart
```

---

✅ **Votre application MaxIT sera accessible sur Railway !**
