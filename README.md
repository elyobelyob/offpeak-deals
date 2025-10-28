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

## Geocoding

This project now includes support for storing latitude and longitude for address records and tooling to resolve addresses into coordinates (geocoding). The implementation is available in the companion pull request: https://github.com/elyobelyob/offpeak-deals/pull/2 — review that PR for the code changes and scripts.

What changed
- Database: an additional migration (`migrations/001_add_lat_long.sql`) adds `latitude` and `longitude` columns to the addresses table.
- Geocoding providers: the implementation supports Nominatim (OpenStreetMap) as the default provider (no API key required) and optional Google Maps Geocoding (requires an API key).
- Utilities: the PR contains a lightweight geocoding utility, a rate-limited bulk geocoding script, and an example endpoint to geocode individual addresses.

Environment variables used by the geocoding tooling
- GEOCODER_PROVIDER — "nominatim" (default) or "google".
- GOOGLE_GEOCODE_API_KEY — required if GEOCODER_PROVIDER=google.
- NOMINATIM_THROTTLE_MS — throttle delay in milliseconds for bulk operations (default ≈ 1100 ms to be polite to Nominatim).
- GEOCODER_USER_AGENT — user agent string sent to Nominatim; set to identify your application.
- DATABASE_URL — a connection string used by the provided tooling (if running the Node scripts locally). Adjust as needed for your environment.

How to use the tools (overview)
- Migration: run the `migrations/001_add_lat_long.sql` migration as shown above to add `latitude` and `longitude` columns.
- Manual geocode (single address): the PR includes an example route that accepts an address and persists latitude/longitude. See the PR for the exact endpoint and usage.
- Bulk geocoding: a bulk script is provided which iterates over address records without coordinates, geocodes them with the configured provider, and updates the database. The script includes a dry-run mode and respects the NOMINATIM_THROTTLE_MS setting. See the PR for the exact script path and options.

Notes and recommendations
- Nominatim usage policy requires being polite with request rates and identifying your application via User-Agent. Use NOMINATIM_THROTTLE_MS ≈ 1100 ms for bulk operations.
- For production, consider implementing geocoding in a background job worker and using a shared cache (e.g., Redis) to reduce duplicate requests and avoid blocking web requests.
- If you prefer an all-PHP solution (no Node tooling), you can implement geocoding calls from PHP (for example using Guzzle) and update the MySQL rows directly. The migration and schema changes remain the same.

## Contributing

Contributions are welcome! This project follows an open-source model under the AGPL 3.0 licence. Feel free to fork the repo, submit pull requests or open issues. For database changes, please add a new migration file under `migrations/` rather than editing the existing schema directly.
