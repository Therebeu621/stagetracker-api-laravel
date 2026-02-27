# StageTracker API

API REST Laravel pour gerer ses candidatures de stage, avec interface web de test.

## Stack

- PHP 8.3
- Laravel 12
- PostgreSQL
- Authentification: Laravel Sanctum (Bearer token)
- Documentation: Swagger / OpenAPI
- Tests: PHPUnit
- CI: GitHub Actions

## Fonctionnalites

- Register / Login / Logout
- CRUD candidatures
- CRUD followups (email, call, linkedin)
- Export CSV
- Interface web sur `/` pour test manuel
- Isolation par utilisateur (chaque user ne voit que ses donnees)

## Lancement avec Docker (recommande)

Les commandes ci-dessous utilisent `docker-compose` (version classique).

```bash
# 1. Copier l'env Docker
cp .env.docker .env

# 2. Build + demarrage
docker-compose up -d --build

# 3. Installer les dependances PHP (si necessaire)
docker-compose exec app composer install --no-interaction --prefer-dist

# 4. Generer la cle
docker-compose exec app php artisan key:generate

# 5. Migrations + seed users de demo
docker-compose exec app php artisan migrate --seed
```

URLs:

- App / UI web: http://localhost:8000
- Swagger UI: http://localhost:8000/api/documentation

Comptes de demo:

- `demo1@stagetracker.test` / `password123`
- `demo2@stagetracker.test` / `password123`

Arret:

```bash
docker-compose down
```

Reset complet (supprime aussi la base):

```bash
docker-compose down -v
```

## UI web (test manuel)

Depuis http://localhost:8000, tu peux tester:

- Login / Register
- Creation, edition, suppression de candidatures
- Followups
- Recherche, filtres, tri
- Export CSV
- Separation des donnees entre utilisateurs

## Installation locale (optionnelle, sans Docker)

Cette section est optionnelle. Utilise-la seulement si tu ne veux pas Docker.

```bash
cd ~/testlaravel/stagetracker
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Authentification

Le projet utilise Sanctum en mode API token:

- Header requis sur routes protegees: `Authorization: Bearer <token>`
- Pas de flow SPA cookie/CSRF pour le front de ce projet

Exemple login:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"demo1@stagetracker.test","password":"password123"}'
```

## Endpoints

Legende colonne Auth:

- `Public` = pas de token requis
- `Token` = Bearer token requis

| Methode | URI | Description | Auth |
|---|---|---|---|
| `POST` | `/api/register` | Inscription + token | Public |
| `POST` | `/api/login` | Connexion + token | Public |
| `POST` | `/api/logout` | Deconnexion | Token |
| `GET` | `/api/applications` | Liste (paginee, filtrable) | Token |
| `POST` | `/api/applications` | Creer une candidature | Token |
| `GET` | `/api/applications/{id}` | Detail candidature | Token |
| `PATCH` | `/api/applications/{id}` | Modifier candidature | Token |
| `DELETE` | `/api/applications/{id}` | Supprimer candidature | Token |
| `GET` | `/api/applications/export.csv` | Export CSV | Token |
| `GET` | `/api/applications/{id}/followups` | Liste followups | Token |
| `POST` | `/api/applications/{id}/followups` | Ajouter followup | Token |
| `DELETE` | `/api/followups/{id}` | Supprimer followup | Token |

## Tests

Lancer tous les tests:

```bash
php artisan test
```

Avec Docker:

```bash
docker-compose exec app php artisan test
```

Etat actuel:

- 19 tests passent
- dont 17 tests API dans `StageTrackerApiTest`

## CI

Workflow GitHub Actions: `.github/workflows/tests.yml`

Sur `push` et `pull_request`:

- setup PHP 8.3
- install dependencies
- `php artisan key:generate`
- `php artisan test`

## Annexe - Exemples API (curl)

Remplace `TOKEN` par le token recu au login.

### Register

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"Alice","email":"alice@test.com","password":"password123"}'
```

### Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"demo1@stagetracker.test","password":"password123"}'
```

### Creer une candidature

```bash
curl -X POST http://localhost:8000/api/applications \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "company": "Google",
    "position": "Backend Intern",
    "location": "Paris",
    "status": "applied",
    "applied_at": "2026-02-10",
    "notes": "Applied via careers page."
  }'
```

### Lister les candidatures filtrees

```bash
curl "http://localhost:8000/api/applications?status=applied&sort=applied_at&direction=desc&per_page=10" \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

### Modifier une candidature

```bash
curl -X PATCH http://localhost:8000/api/applications/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"interview"}'
```

### Supprimer une candidature

```bash
curl -X DELETE http://localhost:8000/api/applications/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

### Ajouter un followup

```bash
curl -X POST http://localhost:8000/api/applications/1/followups \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"type":"email","done_at":"2026-02-26","notes":"Relance envoyee"}'
```

### Export CSV

```bash
curl http://localhost:8000/api/applications/export.csv \
  -H "Authorization: Bearer TOKEN" \
  -o applications.csv
```

## Annexe - Structure du projet

```text
app/
  Http/
    Controllers/Api/
      AuthController.php
      ApplicationController.php
      FollowupController.php
    Requests/
      StoreApplicationRequest.php
      UpdateApplicationRequest.php
      StoreFollowupRequest.php
    Resources/
      ApplicationResource.php
      FollowupResource.php
  Models/
    User.php
    Application.php
    Followup.php
database/
  migrations/
  factories/
  seeders/
routes/
  api.php
  web.php
tests/
  Feature/Api/StageTrackerApiTest.php
```

## Annexe - Modele de donnees

### applications

- `id` (bigint, PK)
- `user_id` (FK nullable -> users)
- `company` (string, required)
- `position` (string, required)
- `location` (string, nullable)
- `status` (enum: applied, interview, offer, rejected)
- `applied_at` (date, nullable)
- `next_followup_at` (date, nullable)
- `notes` (text, nullable)
- `created_at`, `updated_at`

### followups

- `id` (bigint, PK)
- `application_id` (FK -> applications, cascade delete)
- `type` (enum: email, call, linkedin)
- `done_at` (date, nullable)
- `notes` (text, nullable)
- `created_at`, `updated_at`
