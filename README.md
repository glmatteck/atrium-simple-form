# atrium-simple-form-mvc

A simple PHP MVC application for managing records with loan-to-value calculations.

## Features

- Create, read, update, and delete records
- Automatic LTV (Loan-to-Value) calculation
- SQLite database
- Form validation
- Dockerized environment

## Prerequisites

**Option 1: Built-in PHP Server (Recommended for Development)**
- PHP 8.0+ with PDO SQLite extension
- PowerShell (Windows)

**Option 2: Docker**
- Docker Desktop
- Docker Compose

## Setup

### Option 1: Using Built-in PHP Server

1. **Clone the repository** (or navigate to the project directory)

2. **Install dependencies (if not already installed):**
   ```powershell
   composer install
   ```

3. **Run database migrations:**
   ```powershell
   php migrate.php run
   ```

4. **Start the development server:**
   ```powershell
   .\serve.ps1
   ```

5. **Access the application:**
   - Open your browser to http://localhost:8080

### Option 2: Using Docker

1. **Clone the repository** (or navigate to the project directory)

2. **Build and start the Docker containers:**
   ```powershell
   docker compose up -d --build
   ```

3. **Run database migrations:**
   ```powershell
   docker compose exec web php migrate.php run
   ```

4. **Access the application:**
   - Open your browser to http://localhost:8080

## Docker Configuration (Optional)

If using Docker, the application uses the following setup:

- **Dockerfile**: Custom PHP Apache image with SQLite support
- **docker-compose.yml**: Service configuration mapping port 8080 to container port 80
- **default.conf**: Apache virtual host configuration for the `/public` directory

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

### Development Server (Built-in PHP)
```powershell
# Start server
.\serve.ps1

# Server runs on http://localhost:8080
```

### Database Management (Local PHP)
```powershell
# Run migrations
php migrate.php run

# Seed sample data
php seed-data.php

# Rollback migrations
php migrate.php rollback
 (Docker)
# Check migration status
php migrate.php status
```

### Docker Management (Optional)
```powershell
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Rebuild containers
docker compose up -d --build

# View logs
docker`public/` directory is the web root
- Database file is located at `src/Database/data.db`
- Both built-in PHP server and Docker are supported
- Docker uses Apache with PHP 8.5 and SQLite
- For Docker: The `www-data` user needs write permissions on the database directory (handled automatically
```

### Using Built-in PHP Server

**If records don't load:**
1. Ensure migrations have been run: `php migrate.php run`
2. Check database file exists: `ls src/Database/data.db`
3. Seed sample data: `php seed-data.php`
4. Verify PDO SQLite extension is installed: `php -m | Select-String pdo_sqlite`

**If server won't start:**
1. Check if port 8080 is already in use
2. Ensure PHP is installed and in your PATH: `php --version`
3. Try running directly: `php -S localhost:8080 -t public/`

### Using Docker

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
