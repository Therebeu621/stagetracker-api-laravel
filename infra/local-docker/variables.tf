variable "project_name" {
  description = "Project prefix used in resource labels."
  type        = string
  default     = "stagetracker"
}

variable "docker_network_name" {
  description = "Docker network name created by Terraform."
  type        = string
  default     = "stagetracker_tf_net"
}

variable "postgres_image" {
  description = "PostgreSQL Docker image."
  type        = string
  default     = "postgres:16-alpine"
}

variable "postgres_container_name" {
  description = "PostgreSQL container name."
  type        = string
  default     = "stagetracker_tf_postgres"
}

variable "postgres_db" {
  description = "PostgreSQL database name."
  type        = string
  default     = "stagetracker_tf"
}

variable "postgres_user" {
  description = "PostgreSQL username."
  type        = string
  default     = "stagetracker"
}

variable "postgres_password" {
  description = "PostgreSQL password. Set via terraform.tfvars (non-empty)."
  type        = string
  sensitive   = true
  default     = "change_me"

  validation {
    condition     = length(trimspace(var.postgres_password)) > 0
    error_message = "postgres_password must not be empty."
  }
}

variable "postgres_host_port" {
  description = "Host port mapped to PostgreSQL container port."
  type        = number
  default     = 5433

  validation {
    condition     = var.postgres_host_port > 0 && var.postgres_host_port < 65536
    error_message = "postgres_host_port must be between 1 and 65535."
  }
}

variable "postgres_container_port" {
  description = "Internal PostgreSQL container port."
  type        = number
  default     = 5432

  validation {
    condition     = var.postgres_container_port > 0 && var.postgres_container_port < 65536
    error_message = "postgres_container_port must be between 1 and 65535."
  }
}

variable "postgres_volume_name" {
  description = "Docker volume used for PostgreSQL data persistence."
  type        = string
  default     = "stagetracker_tf_pgdata"
}

variable "enable_adminer" {
  description = "Whether to run an Adminer container for DB inspection."
  type        = bool
  default     = true
}

variable "adminer_image" {
  description = "Adminer Docker image."
  type        = string
  default     = "adminer:4"
}

variable "adminer_container_name" {
  description = "Adminer container name."
  type        = string
  default     = "stagetracker_tf_adminer"
}

variable "adminer_host_port" {
  description = "Host port mapped to Adminer HTTP port."
  type        = number
  default     = 8081

  validation {
    condition     = var.adminer_host_port > 0 && var.adminer_host_port < 65536
    error_message = "adminer_host_port must be between 1 and 65535."
  }
}
