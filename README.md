# StageApplication

Application Symfony de gestion des périodes en entreprise, utilisateurs, formations et jours fériés.

## Prérequis

- PHP >= 8.1
- Composer
- SQLite (par défaut) ou MySQL/PostgreSQL
- Node.js (pour les assets si besoin)
- MailHog (pour le développement des emails)

## Installation

1. **Cloner le dépôt**

```sh
git clone <url-du-repo>
cd StageApplication/StageApplication
```

2. **Installer les dépendances PHP**

```sh
composer install
```

3. **Configurer les variables d'environnement**

Copier `.env` en `.env.local` et adapter si besoin.

4. **Installer la base de données**

```sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Lancer le serveur de développement**

```sh
symfony server:start
```
ou
```sh
php -S localhost:8000 -t public
```

6. **Lancer MailHog pour tester les emails**

```sh
./MailHog_linux_amd64
```
Accéder à [http://localhost:8025](http://localhost:8025) pour voir les emails.

## Lancement des tests

```sh
php bin/phpunit
```

## Fonctionnalités principales

- Gestion des utilisateurs (création, édition, rôles)
- Invitations par email avec rôles
- Gestion des formations et périodes en entreprise
- Gestion et import/synchronisation des jours fériés (CSV ou API)
- Authentification et édition de profil
- Envoi d'emails via [src/Service/SendMailService.php](src/Service/SendMailService.php)

## Structure du projet

- `src/Controller/` : Contrôleurs principaux
- `src/Form/` : Formulaires Symfony
- `src/Entity/` : Entités Doctrine
- `src/Service/` : Services (ex: envoi d'emails)
- `templates/` : Vues Twig
- `assets/` : Fichiers JS/CSS
- `migrations/` : Migrations de base de données

## Import et synchronisation des jours fériés

- Import CSV : via l'interface admin ou la commande :
  ```sh
  php bin/console app:import:jours-feries [chemin/vers/fichier.csv]
  ```
- Synchronisation API : bouton dans l'admin ou commande :
  ```sh
  php bin/console app:update:jours-feries [année]
  ```

## Développement

- Les assets JS sont dans `assets/` et configurés via [importmap.php](importmap.php).
- Les emails sont envoyés via le service [`App\Service\SendMailService`](src/Service/SendMailService.php).

## Contribution

1. Fork le projet
2. Crée une branche (`git checkout -b feature/ma-feature`)
3. Commit tes modifications
4. Push la branche (`git push origin feature/ma-feature`)
5. Ouvre une Pull Request

---

Pour toute question, consulte la documentation Symfony :