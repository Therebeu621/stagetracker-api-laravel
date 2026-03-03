#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ -z "${TF_DB_PASSWORD:-}" ]]; then
  echo "TF_DB_PASSWORD is required."
  echo "Example: TF_DB_PASSWORD=anisse ./scripts/up-compose-with-terraform-db.sh"
  exit 1
fi

docker-compose -f docker-compose.yml -f docker-compose.terraform-db.yml up -d --build --no-deps app nginx
docker-compose -f docker-compose.yml -f docker-compose.terraform-db.yml exec -T app php artisan migrate --seed

echo "StageTracker UI is up on http://localhost:8000 using Terraform PostgreSQL."
