# StageTracker API

> API REST Laravel pour gérer ses candidatures de stage.  
> Projet pédagogique – Master ILI, backend/API.

## Stack

- PHP 8.3 · Laravel 12 · PostgreSQL
- Auth : Laravel Sanctum (token API)
- Tests : PHPUnit (Feature tests)

---

## 🚀 Installation

```bash
# 1. Cloner / se rendre dans le projet
cd ~/testlaravel/stagetracker

# 2. Installer les dépendances
composer install

# 3. Installer PostgreSQL et l'extension PHP (si pas déjà fait)
sudo apt install -y postgresql postgresql-client php8.3-pgsql
sudo service postgresql start

# 4. Créer la base de données
sudo -u postgres psql -c "CREATE USER stagetracker WITH PASSWORD 'secret';"
sudo -u postgres psql -c "CREATE DATABASE stagetracker OWNER stagetracker;"

# 5. Copier l'env (si pas déjà fait) et configurer la DB
cp .env.example .env
php artisan key:generate

# Le .env est déjà configuré pour PostgreSQL:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=stagetracker
# DB_USERNAME=stagetracker
# DB_PASSWORD=secret

# 6. Lancer les migrations
php artisan migrate

# 7. Créer un utilisateur (via tinker)
php artisan tinker
>>> \App\Models\User::factory()->create(['email'=>'admin@test.com','password'=>bcrypt('secret123')]);
>>> exit

# 8. Lancer le serveur
php artisan serve
```

---

## 🔐 Authentification

L'API utilise **Laravel Sanctum** avec des tokens Bearer.

### Login → obtenir un token

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@test.com","password":"secret123"}'
```

Réponse :
```json
{
  "message": "Authenticated",
  "token": "1|abc123..."
}
```

### Utiliser le token

Ajouter le header `Authorization: Bearer <token>` à chaque requête protégée.

### Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Accept: application/json"
```

---

## 📚 Documentation Interactive (Swagger/OpenAPI)

L'API est entièrement documentée via **Swagger UI** — testez tous les endpoints directement dans le navigateur !

### Accéder à Swagger UI

```
http://localhost:8000/api/documentation
```

### Utiliser l'authentification dans Swagger

1. Cliquez sur le bouton **"Authorize"** 🔒 en haut à droite
2. Dans le champ `bearerAuth`, entrez : `Bearer VOTRE_TOKEN`
   - Exemple : `Bearer 1|abc123...`
3. Cliquez sur **"Authorize"**, puis **"Close"**
4. Les requêtes protégées ✅ fonctionneront maintenant

### Re-générer la doc Swagger

Si vous modifiez les annotations :

```bash
php artisan l5-swagger:generate
```

---

## 📋 Endpoints

| Méthode | URI | Description | Auth |
|---------|-----|-------------|------|
| `POST` | `/api/login` | Connexion → token | ❌ |
| `POST` | `/api/logout` | Déconnexion | ✅ |
| `GET` | `/api/applications` | Liste (paginée, filtrable) | ✅ |
| `POST` | `/api/applications` | Créer une candidature | ✅ |
| `GET` | `/api/applications/{id}` | Détail | ✅ |
| `PATCH` | `/api/applications/{id}` | Modifier | ✅ |
| `DELETE` | `/api/applications/{id}` | Supprimer | ✅ |
| `GET` | `/api/applications/export.csv` | Export CSV | ✅ |
| `POST` | `/api/applications/{id}/followups` | Ajouter un suivi | ✅ |
| `GET` | `/api/applications/{id}/followups` | Liste des suivis | ✅ |
| `DELETE` | `/api/followups/{id}` | Supprimer un suivi | ✅ |

### Filtres et pagination

```
GET /api/applications?status=applied&sort=applied_at&direction=asc&per_page=10
```

| Paramètre | Valeurs | Défaut |
|-----------|---------|--------|
| `status` | `applied`, `interview`, `offer`, `rejected` | — |
| `sort` | `applied_at` | `created_at desc` |
| `direction` | `asc`, `desc` | `desc` |
| `per_page` | 1–100 | 15 |

---

## 📝 Exemples curl

> Remplacer `TOKEN` par votre token obtenu via `/api/login`.

### Créer une candidature

```bash
curl -X POST http://localhost:8000/api/applications \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "company": "Google",
    "position": "Backend Developer",
    "location": "Paris",
    "status": "applied",
    "applied_at": "2026-02-01",
    "notes": "Applied via website"
  }'
```

### Lister avec filtre

```bash
curl http://localhost:8000/api/applications?status=applied \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

### Modifier le statut

```bash
curl -X PATCH http://localhost:8000/api/applications/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status": "interview"}'
```

### Supprimer

```bash
curl -X DELETE http://localhost:8000/api/applications/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

### Ajouter un suivi

```bash
curl -X POST http://localhost:8000/api/applications/1/followups \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"type": "email", "done_at": "2026-02-10", "notes": "Relance envoyée"}'
```

### Export CSV

```bash
curl http://localhost:8000/api/applications/export.csv \
  -H "Authorization: Bearer TOKEN" \
  -o applications.csv
```

---

## 🧪 Tests

```bash
# Lancer tous les tests
php artisan test

# Lancer uniquement les tests API
php artisan test --filter=StageTrackerApiTest
```

### Tests inclus (12 tests)

| Test | Ce qu'il vérifie |
|------|-----------------|
| `test_login_returns_token` | Login → 200 + token |
| `test_login_fails_with_wrong_credentials` | Mauvais mdp → 422 |
| `test_unauthenticated_access_blocked` | Sans token → 401 |
| `test_logout_revokes_token` | Logout → 204, puis 401 |
| `test_create_application` | POST → 201 + JSON correct |
| `test_list_applications_with_filters` | GET ?status= → filtres + pagination |
| `test_show_application` | GET /{id} → 200 |
| `test_update_application` | PATCH → 200 + données mises à jour |
| `test_delete_application` | DELETE → 204 |
| `test_create_application_validation_fails` | Données invalides → 422 |
| `test_followup_crud` | CRUD complet des suivis |
| `test_csv_export` | Export CSV → 200 + contenu CSV |

---

## 📁 Structure du projet

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── ApplicationController.php
│   │   └── FollowupController.php
│   ├── Requests/
│   │   ├── StoreApplicationRequest.php
│   │   ├── UpdateApplicationRequest.php
│   │   └── StoreFollowupRequest.php
│   └── Resources/
│       ├── ApplicationResource.php
│       └── FollowupResource.php
├── Models/
│   ├── Application.php
│   ├── Followup.php
│   └── User.php
database/
├── factories/
│   ├── ApplicationFactory.php
│   └── FollowupFactory.php
├── migrations/
│   ├── ...create_applications_table.php
│   └── ...create_followups_table.php
routes/
└── api.php
tests/
└── Feature/Api/
    └── StageTrackerApiTest.php
```

---

## 📊 Modèle de données

### applications

| Colonne | Type | Contrainte |
|---------|------|------------|
| id | bigint | PK auto |
| company | string | required |
| position | string | required |
| location | string | nullable |
| status | enum | applied/interview/offer/rejected |
| applied_at | date | nullable |
| next_followup_at | date | nullable |
| notes | text | nullable |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |

### followups

| Colonne | Type | Contrainte |
|---------|------|------------|
| id | bigint | PK auto |
| application_id | FK | → applications (cascade) |
| type | enum | email/call/linkedin |
| done_at | date | nullable |
| notes | text | nullable |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |

---

