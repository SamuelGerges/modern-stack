# Makefile Task Management System (Laravel + Node.js Microservice)

## build file docker
```bash
  build
  docker compose build
```

## Start all services
```bash
  up
  docker compose up -d
```

## Stop all services
```bash
  down
  docker compose down
```
## Run Laravel tests
```bash
  test
  docker compose exec laravel-app php artisan test
```

## Run database migrations with seed data
```bash
  migrate
  docker compose exec laravel-app php artisan migrate:fresh --seed
```

### Full CI pipeline: lint + tests
    ci: lint test
