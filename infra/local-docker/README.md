# Terraform local-docker (0EUR)

Ce dossier permet d'apprendre Terraform en local, sans cloud et sans cout.

## Ce que Terraform cree

- 1 reseau Docker dedie
- 1 volume Docker persistant pour PostgreSQL
- 1 conteneur PostgreSQL
- 1 conteneur Adminer (optionnel)

## Prerequis

- Terraform >= 1.8
- Docker Engine ou Docker Desktop en cours d'execution

## Demarrage rapide

```bash
cd infra/local-docker
cp terraform.tfvars.example terraform.tfvars
terraform init
terraform workspace new dev || terraform workspace select dev
terraform fmt -recursive
terraform validate
terraform plan -lock=false -out=tfplan
terraform apply tfplan
```

## Verifier que tout tourne

```bash
docker ps --filter name=stagetracker_tf
docker logs stagetracker_tf_postgres --tail 50
```

Connexion PostgreSQL:

- Host: `127.0.0.1`
- Port: `5433` (par defaut)
- Database/User/Password: valeurs Terraform

Adminer (si active):

- URL: `http://127.0.0.1:8081`
- System: `PostgreSQL`
- Server: `stagetracker_tf_postgres`

## Nettoyage

```bash
terraform destroy -lock=false
```

Optionnel: revenir au workspace par defaut.

```bash
terraform workspace select default
```

Suppression du volume persistant (optionnel):

```bash
docker volume rm stagetracker_tf_pgdata
```

## Depannage

- Port deja utilise: change `postgres_host_port` ou `adminer_host_port` dans `terraform.tfvars`.
- Docker daemon inaccessible: demarre Docker Desktop/Engine puis relance `terraform plan`.
- Container deja existant: execute `terraform destroy` puis `docker ps -a` pour verifier.
- Config desync: utilise `terraform state list` pour inspecter l'etat local.

## State: local vs remote

- Ici, le state est local (`terraform.tfstate`) pour rester simple et 0EUR.
- En equipe/prod, on utilise souvent un remote state (S3 + lock DynamoDB, Terraform Cloud, etc.) pour partager l'etat et eviter les collisions.

## Notes CI

Le workflow GitHub Actions Terraform lance `fmt` + `validate` uniquement.
`plan/apply/destroy` restent locaux pour eviter toute dependance Docker daemon en CI.
