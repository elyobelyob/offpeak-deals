# offpeak-deals
Dockerised PHP & MySQL app for off peak deals.

This repository contains a PHP-based platform that allows businesses to publish lunch and off peak food deals, with separate portals for consumers, business owners and administrators. It includes a Docker Compose environment, database migrations, seed data and a PHPUnit test suite.

## Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/elyobelyob/offpeak-deals.git
   cd offpeak-deals
   ```

2. **Start the services with Docker Compose**  
   Ensure Docker and Docker Compose are installed on your machine. Then run:
   ```bash
   docker compose up -d
   ```
   This will build and start the PHP (Apache) and MySQL containers.

3. **Run database migrations**  
   The initial database schema lives in `migrations/001_initial_schema.sql` and subsequent changes are stored as numbered migration files in the `migrations/` folder. Apply them to your MySQL database in order. For example:
   ```bash
   # inside the database container
   docker compose exec db bash -c "mysql -u root -p$MYSQL_ROOT_PASSWORD offpeak < /var/www/html/migrations/001_initial_schema.sql"
   docker compose exec db bash -c "mysql -u root -p$MYSQL_ROOT_PASSWORD offpeak < /var/www/html/migrations/001_add_lat_long.sql"
   ```
   You can automate this with your own migration script as the project grows.

4. **Load sample data (optional)**  
   To try the application with some example restaurants and deals, run the seed script:
   ```bash
   docker compose exec db bash -c "mysql -u root -p$MYSQL_ROOT_PASSWORD offpeak < /var/www/html/sql/seed.sql"
   ```

5. **Install PHP dependencies**  
   Inside the PHP container, install Composer dependencies (including PHPUnit):
   ```bash
   docker compose exec web bash -c "composer install"
   ```

6. **Run the test suite**  
   After installing dependencies, you can run the tests to confirm everything is set up correctly:
   ```bash
   docker compose exec web bash -c "./vendor/bin/phpunit"
   ```

## Usage

- Visit `http://localhost:8080/php/public/` to access the consumer-facing site and search for deals.
- Use the `business` and `admin` portals to manage businesses, locations and deals. Users must be logged in with appropriate roles to access these pages.

## Contributing

Contributions are welcome! This project follows an open-source model under the AGPL 3.0 licence. Feel free to fork the repo, submit pull requests or open issues. For database changes, please add a new migration file under `migrations/` rather than editing the existing schema directly.
