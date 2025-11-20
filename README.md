# PC Maintenance - Laravel Application

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

A Laravel application for PC maintenance management.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
  - [Installing PHP](#installing-php)
  - [Installing Composer](#installing-composer)
  - [Installing Node.js](#installing-nodejs)
- [Project Setup](#project-setup)
- [Running the Application](#running-the-application)
- [Database Configuration](#database-configuration)
- [Available Commands](#available-commands)
- [Troubleshooting](#troubleshooting)
- [About Laravel](#about-laravel)
- [License](#license)

## Requirements

Before you begin, ensure you have the following installed on your system:

- **PHP 8.2 or higher**
- **Composer** (PHP dependency manager)
- **Node.js 16.x or higher** and npm
- **SQLite** (default) or MySQL/PostgreSQL
- Git

## Installation

### Installing PHP

#### Windows

1. Download PHP 8.2+ from [windows.php.net/download](https://windows.php.net/download/)
2. Extract the ZIP file to `C:\php`
3. Add `C:\php` to your system PATH:
   - Right-click "This PC" → Properties → Advanced System Settings
   - Click "Environment Variables"
   - Under "System Variables", find "Path" and click "Edit"
   - Click "New" and add `C:\php`
4. Rename `php.ini-development` to `php.ini` in the PHP folder
5. Enable required extensions in `php.ini`:
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   extension=mbstring
   extension=openssl
   extension=fileinfo
   extension=curl
   ```
6. Verify installation:
   ```bash
   php -v
   ```

#### macOS

Using Homebrew:
```bash
brew install php@8.2
brew link php@8.2
php -v
```

#### Linux (Ubuntu/Debian)

```bash
sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip php8.2-sqlite3
php -v
```

### Installing Composer

#### Windows

1. Download and run the installer from [getcomposer.org/download](https://getcomposer.org/download/)
2. Follow the installation wizard
3. Verify installation:
   ```bash
   composer --version
   ```

#### macOS/Linux

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### Installing Node.js

#### Windows

1. Download the installer from [nodejs.org](https://nodejs.org/)
2. Run the installer and follow the wizard
3. Verify installation:
   ```bash
   node -v
   npm -v
   ```

#### macOS

Using Homebrew:
```bash
brew install node
node -v
npm -v
```

#### Linux (Ubuntu/Debian)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
node -v
npm -v
```

## Project Setup

Once you have PHP, Composer, and Node.js installed, follow these steps to set up the project:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/MUGWANEZAMANZI/pc-maintenance.git
   cd pc-maintenance
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**:
   ```bash
   npm install
   ```

4. **Create environment file**:
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

6. **Create database file** (for SQLite):
   ```bash
   touch database/database.sqlite
   ```

7. **Run database migrations**:
   ```bash
   php artisan migrate
   ```

8. **Build frontend assets**:
   ```bash
   npm run build
   ```

## Running the Application

### Development Mode

You can run the application in development mode with all necessary services:

```bash
composer dev
```

This command will start:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log viewer (Pail)
- Vite development server for hot module replacement

### Manual Start

Alternatively, you can start services individually:

1. **Start the development server**:
   ```bash
   php artisan serve
   ```
   The application will be available at [http://localhost:8000](http://localhost:8000)

2. **Watch and compile frontend assets** (in a new terminal):
   ```bash
   npm run dev
   ```

3. **Run queue worker** (optional, in a new terminal):
   ```bash
   php artisan queue:work
   ```

## Database Configuration

### SQLite (Default)

The project uses SQLite by default. The database file is created at `database/database.sqlite`.

### MySQL/PostgreSQL

To use MySQL or PostgreSQL instead:

1. Update `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pc_maintenance
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. Create the database:
   ```bash
   mysql -u your_username -p -e "CREATE DATABASE pc_maintenance;"
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

## Available Commands

### Composer Scripts

- `composer setup` - Complete project setup (install dependencies, generate key, run migrations, build assets)
- `composer dev` - Start development environment with all services
- `composer test` - Run PHPUnit tests

### Artisan Commands

- `php artisan serve` - Start the development server
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh` - Drop all tables and re-run migrations
- `php artisan db:seed` - Seed the database
- `php artisan tinker` - Interact with the application via REPL
- `php artisan queue:work` - Process queue jobs
- `php artisan test` - Run tests

### NPM Scripts

- `npm run dev` - Start Vite development server
- `npm run build` - Build assets for production

## Troubleshooting

### Common Issues

**Issue: "Class not found" errors**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

**Issue: Permission errors (Linux/macOS)**
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

**Issue: Database connection errors**
- Verify the `.env` file has correct database credentials
- Ensure the database exists
- Check that the database service is running

**Issue: Port 8000 already in use**
```bash
php artisan serve --port=8001
```

**Issue: Missing PHP extensions**
```bash
# Check enabled extensions
php -m

# Install missing extensions (Ubuntu/Debian)
sudo apt install php8.2-{extension-name}
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing)
- [Powerful dependency injection container](https://laravel.com/docs/container)
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent)
- Database agnostic [schema migrations](https://laravel.com/docs/migrations)
- [Robust background job processing](https://laravel.com/docs/queues)
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting)

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to this project! Please feel free to submit pull requests or open issues.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
