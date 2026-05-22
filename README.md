# Laravel Full-Stack Mini Booking System

A lightweight hotel inventory and search system built with Laravel 12, Blade views, Sanctum API authentication, and a service/repository architecture.

## Features

- Web authentication with login/logout and protected dashboard
- API authentication using Laravel Sanctum tokens
- Hotel and room management from Blade UI
- Hotel search availability with pricing and room details
- Search results are cached for repeated queries using repository-backed search logic
- Eloquent relationships, Form Requests, Service layer, API resources
- Custom rate limiting for search endpoints
- Docker Compose setup for local development
- Database seeders with sample hotel and room data
- Postman collection included

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- SQLite, MySQL, or MariaDB
- Node.js / npm (optional for frontend asset compilation)

### Install

```bash
cd c:/xampp/htdocs/hotelmanagementsystem
composer install
cp .env.example .env
php artisan key:generate
```

### Database

This project is configured to use the default database connection from `.env`.

For SQLite, create the database file:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

Update `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=${PWD}/database/database.sqlite
```

Run migrations and seed sample data:

```bash
php artisan migrate --seed
```

### Run the application

```bash
php artisan serve
```

Then open `http://127.0.0.1:8000`.

## Docker

Build and run the application with Docker Compose:

```bash
docker compose up --build
```

Open `http://127.0.0.1:8080` in your browser.

If needed, install dependencies inside the container:

```bash
docker compose run --rm app composer install
```

## Default Credentials

- Email: `test@example.com`
- Password: `password`

## Routes

### Web

- `GET /login` - Login page
- `POST /login` - Authenticate user
- `POST /logout` - Sign out
- `GET /dashboard` - Admin dashboard
- `GET /hotels` - Hotel list and create form
- `POST /hotels` - Create a hotel
- `GET /rooms` - Room list and create form
- `POST /rooms` - Create a room
- `GET /search` - Search available rooms

### API

- `POST /api/login` - Get Sanctum token
- `POST /api/logout` - Revoke token
- `GET /api/hotels` - List hotels
- `POST /api/hotels` - Create hotel
- `GET /api/rooms` - List rooms
- `POST /api/rooms` - Create room
- `GET /api/search` - Search availability

## Sample API Collection

Postman collection available at `postman_collection.json`.

## Testing

Run unit tests:

```bash
php artisan test
```

## Notes

- The application uses Blade views with Bootstrap styling.
- API responses are returned via Laravel API Resources.
- Search results are available through the API and web interfaces.
