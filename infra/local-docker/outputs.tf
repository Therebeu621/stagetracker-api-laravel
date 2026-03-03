output "postgres_connection_host" {
  description = "Host used to connect to PostgreSQL from your machine."
  value       = "127.0.0.1"
}

output "postgres_connection_port" {
  description = "Host port mapped to PostgreSQL."
  value       = var.postgres_host_port
}

output "postgres_database" {
  description = "PostgreSQL database name."
  value       = var.postgres_db
}

output "postgres_username" {
  description = "PostgreSQL username."
  value       = var.postgres_user
}

output "postgres_volume" {
  description = "Docker volume storing PostgreSQL data."
  value       = docker_volume.postgres_data.name
}

output "adminer_url" {
  description = "Adminer URL (empty string when disabled)."
  value       = var.enable_adminer ? "http://127.0.0.1:${var.adminer_host_port}" : ""
}
