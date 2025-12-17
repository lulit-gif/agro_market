# Agro Market (PHP + MySQL)

A simple e-commerce web app for connecting buyers with local farmers. Built with plain PHP (MVC-style) on XAMPP, using MySQL and sessions for auth.

## Project Structure

- `public/`: Web root (entrypoint `index.php`, dev router `router.php`, CSS/JS/assets)
- `src/`: App code (controllers, models, views, services, routes)
- `database/`: SQL dump for schema and seed users (`db.sql.sql`)
- `logs/`: Development logs (e.g., email fallback)

Key files:

- `public/index.php`: Front controller; loads `src/routes/web.php`
- `public/router.php`: Dev router for PHP built-in server (serves static files)
- `src/routes/web.php`: Simple routing table → controllers

## Prerequisites

- XAMPP (PHP 8+, MySQL/MariaDB)
- A MySQL database named `agro_market`

## Setup

1. Create the database (if not present):

   - In phpMyAdmin: create `agro_market`
   - Or via MySQL:
     ```powershell
     & "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS agro_market;"
     ```

2. Import the cleaned SQL (tables + 3 users):

   ```powershell
   # From C:\xampp\htdocs\agro
   & "C:\xampp\mysql\bin\mysql.exe" -u root agro_market < database\db.sql
   ```

3. Ensure XAMPP Apache/PHP is installed. For local dev without Apache, use PHP’s built-in server.

## Run (PHP built-in server)

Serve `public/` with the dev router (static files handled; dynamic requests go to `index.php`):

```powershell
# From C:\xampp\htdocs\agro
& "C:\xampp\php\php.exe" -S localhost:8000 -t public public\router.php
```

Open http://localhost:8000 in your browser.

Alternatively, if you run under Apache, set your document root to `public/`.

## Seed Users (credentials)

The database includes exactly three users:

- Admin: `admin@agro.local`
  - Password: Admin123!
- Farmer: `farmer1@example.com`
  - Password: Farmer123!
- Buyer: `atilahun690@gmail.com`
  - Password: `123456789` 


## Roles & Redirects

- Admin → `/admin/dashboard`
- Farmer (producer) → `/farmer/dashboard`
- Buyer (consumer) → `/`

## Features

- Auth: login/register, role-based redirects
- Password reset: token-based flow (`password_resets` table); emails logged to `logs/email.log` if `mail()` is unavailable
- Products: CRUD for farmers (with optional images)
- Cart & Checkout: cart management, order creation, confirmation
- Admin: manage users, products, orders, farmers

## Troubleshooting

- If static assets don’t load under the built-in server, ensure you start with `public\router.php` as above.
- If login fails due to password mismatch, reset using the commands in Seed Users.
- Ensure `agro_market` database is selected when importing `database\db.sql.sql`.

## Notes

- Reviews and sessions tables were removed to simplify the schema.
- `logs/email.log` is used as an email fallback in development.
- Images for products should be stored under `public/img/products/` when uploaded.
