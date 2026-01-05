# Menma-Shop — Instructions de déploiement

Ce dépôt contient une petite boutique PHP. Cette configuration prépare le projet pour un déploiement sur Render en utilisant Docker et PostgreSQL.

## Local (docker-compose)

1. Copier `.env.example` en `.env` et adapter les valeurs si besoin.
2. Lancer les services :

   docker-compose up --build

3. Ouvrir http://localhost:8080

## Déploiement sur Render

1. Pousser le repo sur GitHub.
2. Créer un service web sur Render en choisissant le déploiement via Docker (ou utiliser `render.yaml`).
3. Définir les variables d'environnement : `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `APP_ENV`.
4. N'oubliez pas d'initialiser la base PostgreSQL avec `db/init_postgres.sql`.

## Notes techniques

- `includes/db.php` utilise désormais les variables d'environnement et supporte `pgsql` et `mysql`.
- L'image Docker installe `pdo_pgsql` pour communiquer avec PostgreSQL.
- Une table `admins` a été ajoutée dans `db/init_postgres.sql` pour l'authentification administrateur (username + mot de passe haché).
- Pour créer un administrateur, exécuter (dans le conteneur ou local) :

  php admin/create_admin.php <username> <password>

  Le mot de passe sera haché automatiquement.

