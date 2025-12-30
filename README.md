# atrium-simple-form-mvc

A simple PHP MVC application for managing records with loan-to-value calculations.

## Features

- Create, read, update, and delete records
- Automatic LTV (Loan-to-Value) calculation
- SQLite database
- Form validation
- Dockerized environment

## Prerequisites

- Docker Desktop
- Docker Compose

## Setup

1. **Clone the repository** (or navigate to the project directory)

2. **Build and start the Docker containers:**
   ```powershell
   docker compose up -d --build
   ```

3. **Run database migrations:**
   ```powershell
   docker compose exec web php migrate.php run
   ```

4. **Seed sample data (optional):**
   ```powershell
   docker compose exec web php seed-data.php
   ```

5. **Access the application:**
   - Open your browser to http://localhost:8080

## Docker Configuration

The application uses the following Docker setup:

- **Dockerfile**: Custom PHP Apache image with SQLite support
- **docker-compose.yml**: Service configuration mapping port 8080 to container port 80
- **000-default.conf**: Apache virtual host configuration for the `/public` directory

## Project Structure

```
├── public/              # Web root (index.php, assets)
├── src/
│   ├── Controllers/     # Application controllers
│   ├── Core/           # Core framework classes (Router, Database, etc.)
│   ├── Database/       # SQLite database file
│   ├── Helpers/        # Utility classes
│   ├── Models/         # Data models
│   └── Views/          # View templates
├── config/             # Configuration files
├── vendor/             # Composer dependencies
├── Dockerfile          # Docker image definition
├── docker-compose.yml  # Docker Compose configuration
└── migrate.php         # Database migration script
```

## Available Commands

### Docker Management
```powershell
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Rebuild containers
docker compose up -d --build

# View logs
docker compose logs web

# Access container shell
docker compose exec web bash
```

### Database Management
```powershell
# Run migrations
docker compose exec web php migrate.php run

# Seed sample data
docker compose exec web php seed-data.php

# Rollback migrations
docker compose exec web php migrate.php rollback

# Check migration status
docker compose exec web php migrate.php status
```

## Development Notes

- The application runs on Apache with PHP 8.5 and SQLite
- The `public/` directory is the web root
- Database file is located at `src/Database/data.db`
- The `www-data` user needs write permissions on the database directory (handled automatically in Docker)

## Troubleshooting

**If records don't load:**
1. Ensure migrations have been run: `docker compose exec web php migrate.php run`
2. Check database permissions: `docker compose exec web ls -la /var/www/html/src/Database/`
3. Seed sample data: `docker compose exec web php seed-data.php`

**If the page shows "Not Found":**
1. Verify containers are running: `docker compose ps`
2. Check Apache configuration: `docker compose exec web apache2ctl -t`
3. Restart containers: `docker compose restart`
