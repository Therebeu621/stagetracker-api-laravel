resource "docker_network" "stagetracker" {
  name = var.docker_network_name
}

resource "docker_volume" "postgres_data" {
  name = var.postgres_volume_name
}

resource "docker_image" "postgres" {
  name         = var.postgres_image
  keep_locally = true
}

resource "docker_container" "postgres" {
  name    = var.postgres_container_name
  image   = docker_image.postgres.image_id
  restart = "unless-stopped"

  env = [
    "POSTGRES_DB=${var.postgres_db}",
    "POSTGRES_USER=${var.postgres_user}",
    "POSTGRES_PASSWORD=${var.postgres_password}",
  ]

  ports {
    internal = var.postgres_container_port
    external = var.postgres_host_port
  }

  volumes {
    volume_name    = docker_volume.postgres_data.name
    container_path = "/var/lib/postgresql/data"
  }

  networks_advanced {
    name = docker_network.stagetracker.name
  }
}

resource "docker_image" "adminer" {
  count        = var.enable_adminer ? 1 : 0
  name         = var.adminer_image
  keep_locally = true
}

resource "docker_container" "adminer" {
  count   = var.enable_adminer ? 1 : 0
  name    = var.adminer_container_name
  image   = docker_image.adminer[0].image_id
  restart = "unless-stopped"

  env = [
    "ADMINER_DEFAULT_SERVER=${docker_container.postgres.name}",
  ]

  ports {
    internal = 8080
    external = var.adminer_host_port
  }

  networks_advanced {
    name = docker_network.stagetracker.name
  }
}
