# Task Modern Stack (Laravel + Node.js Microservice)

## Quick Installation

```bash
    git clone git@github.com:SamuelGerges/modern-stack.git
    cd ./laravel
    cp .env.example .env
    cd ./node-notify
    cp .env.example .env
```

You may use either `docker compose` or `docker-compose` depending on your version like I use docker compose build
This project uses specific ports, if the ports are already taken on your machine please change them in `docker-compose.yml`.
---

## Installation with Docker

Build and start the containers:
```bash
  docker compose build && docker compose up -d
  if not service node work run this command 
  docker compose run --rm node-notify sh -lc "npm ci || npm install"
  docker compose up -d laravel-app node-notify
```


Install dependencies and run setup:
```bash
  docker compose exec laravel-app bash
  composer install
  php artisan key:generate
  php artisan migrate
  php artisan db:seed
  php artisan test
```

Access the Laravel API at:
```
http://localhost:8000/api/tasks
```

The Node.js notification service runs on: return empty notifications when run 
this endpoint of complete task return  notification
```
curl http://localhost:3001/notifications  
```

this endpoint return {"data":"OK"}
```
curl http://localhost:3001/health
```

## video Of task ``` url: https://www.youtube.com/watch?v=X872TjbDAhg

## Postman Collection

You can test the API endpoints using the Postman collection:

```
postman/Modern Task.postman_collection.json
```

---

## Features & Skills Used

* Repository Design Pattern
* Service Layer (Task Service)
* Request Validation
* Laravel Auth (Register, Login)
* Task Management CRUD
* Status Update (e.g., mark task as done)
* Node.js Microservice for webhook notifications
* Event & Listener for task events
* Unit & Feature Testing with PHPUnit Pest
* Dockerized environment

---

## About the Task

The system allows users to manage tasks with authentication and notification integration.

**Endpoints of API:**

* `POST /api/register` => User sign up
* `POST /api/login` => User sign in
* `GET /api/tasks/all` => List all tasks it is a plus 
* `GET /api/tasks` => List owner tasks
* `POST /api/tasks` => Create task
* `PUT /api/tasks/{id}` => Update task
* `DELETE /api/tasks/{id}` => Delete task
* `POST /api/tasks/{id}/complete` => Change task status (to done)



## Usage Example

```bash
# Run all services
make up

# Run tests
make test
```
