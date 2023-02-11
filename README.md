## Redeem Ticket System

This is a system to redeem tickets for events. Features of this system include
- Ticket redemption
- Rate limiting system on how many tickets can be redeemed per hour

### Tech Stack
- PHP 8.1
- Laravel
- Composer
- Postgresql
- Redis
- Docker Compose (Laravel Sail)
- Scout APM - for monitoring

### Prerequisites
- [Docker with docker compose](https://www.docker.com/).
- [Git](https://git-scm.com/) - to clone repository (optional)
- [PHP 8.1](https://php.net) must be installed on the command line

### How to set up
- Clone the repository to your computer from your terminal using `git clone https://github.com/brytey2k/redeem-ticket-system.git` or download the repository
- Run `cp .env.example .env` from your command line to create the system environment configuration file
- Open `.env` file with your favourite editor and insert the correct configurations.
- Run `composer install`
  - You may see a `Predis\Connection\ConnectionException` exception but this is because laravel sail has not been started yet. We start laravel sail in the next step
- Run `./vendor/bin/sail up -d` to start up the application
- Create database in postgres giving it the same name specified in `.env` file. You can use your favourite database client for this. DataGrip from Jetbrains is what was used for this.
- Run `./vendor/bin/sail artisan key:generate`
- Run `./vendor/bin/sail artisan migrate --seed` to create the database with initial data for testing
- Run `./vendor/bin/sail npm install`
- Run `./vendor/bin/sail npm run dev`
- Queue processing is processed with Redis. To execute the queue worker, run `./vendor/bin/sail artisan queue:work`
- System can accessed at http://laravel.test
- Use email: admin@admin.com and password: password to login
- Mailhog has been included for receiving emails from the system. To test the password reset feature, visit http://localhost:8025. This service would have already started. After clicking on forgot password and providing your email. Submit the form and go to the mailhog interface to get get the password reset email that was sent to you from the application.

### Tests
- To run tests, run `./vendor/bin/sail artisan test`
- To generate code coverage reports with tests, run `./vendor/bin/sail artisan test --coverage-html coverage-report`

### Architecture and Design Choices
A note on this has been added to the ArchitectureAndDesignNotes.txt file in the root of the project directory

### Security Vulnerabilities

If you discover a security vulnerability within this system, please send an e-mail to Bright Nkrumah via [brytey2k@gmail.com](mailto:brytey2k@gmail.com). All security vulnerabilities will be promptly addressed.
