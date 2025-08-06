# Configuration Docker pour le développement

## 🚀 Démarrage rapide

1. **Lancer les services Docker :**
   ```bash
   docker-compose -f docker-compose.dev.yml up -d
   ```

2. **Installer les dépendances :**
   ```bash
   composer install
   npm install
   ```

3. **Générer la clé secrète :**
   ```bash
   php bin/console secrets:generate-keys
   ```

4. **Créer la base de données et lancer les migrations :**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Compiler les assets :**
   ```bash
   npm run dev
   ```

## 🛠 Services disponibles

- **Application Symfony** : http://localhost:8000 (avec `symfony serve`)
- **Base de données** : MySQL sur le port 3306
- **PHPMyAdmin** : http://localhost:8080
- **MailHog** (emails de test) : http://localhost:8025

## 🔧 Commandes utiles

### Docker
```bash
# Démarrer les services
docker-compose -f docker-compose.dev.yml up -d

# Voir les logs
docker-compose -f docker-compose.dev.yml logs -f

# Arrêter les services
docker-compose -f docker-compose.dev.yml down
```

### Symfony
```bash
# Serveur de développement
symfony serve

# Ou avec PHP
php -S 127.0.0.1:8000 -t public/
```

### Base de données
```bash
# Créer la BDD
php bin/console doctrine:database:create

# Migrations
php bin/console doctrine:migrations:migrate

# Mettre à jour le schéma
php bin/console doctrine:schema:update --force
```

## 💡 Pourquoi cette config est compatible prod ?

- **MySQL 8.0** : Version stable et largement supportée
- **Variables d'environnement** : Faciles à adapter en prod
- **Pas de dépendances Docker** : L'app Symfony reste autonome
- **Configuration standard** : Marche avec n'importe quel hébergeur

## 🚨 Important pour la production

En prod, tu devras juste changer le `.env` :
```bash
APP_ENV=prod
APP_DEBUG=0
DATABASE_URL="mysql://user:password@prod-server:3306/prod_db"
```

Les migrations Doctrine s'adaptent automatiquement !