This project is a Laravel application that implements API authentication using Laravel Passport.

## Features

- User registration
- User login with token-based authentication
- User logout
- Secure Customers management page
- Secure Customers CRUD API endpoints using Laravel Passport

## Requirements

- PHP >= 8.2
- Composer
- Laravel 11.x
- MySQL

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/jomtoledo/oauth2-api-jtoledo.git
    cd oauth2-api-jtoledo
2. Install Composer dependencies
    ```bash
    composer install
3. Create 2 empty mysql database named api_db and test_api_db
4. Update .env file for Database credentials
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=api_db
    DB_USERNAME=root
    DB_PASSWORD=
    
    TEST_DB_HOST=127.0.0.1
    TEST_DB_DATABASE=test_api_db
    TEST_DB_USERNAME=root
    TEST_DB_PASSWORD=
5. Run migrations and seed
    ```bash
    php artisan migrate --seed
6. Start the server
    ```bash
    php artisan serve
