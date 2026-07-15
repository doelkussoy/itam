# IT Asset Management (ITAM)

A comprehensive IT Asset Management system built with Laravel. This application helps organizations track, manage, and optimize their IT assets, network resources, and helpdesk operations.

## Features

- **Role-Based Access Control (RBAC):** Secure access with Super Admin, Admin, and User roles.
- **Master Data Management:** Easily manage Departments, Vendors, Brands, Locations, Categories, and Employees.
- **Asset Lifecycle Management:** Track assets from procurement to disposal. Assign (checkout) and return (checkin) assets to employees. Generate asset tags.
- **Network Management:** Manage VLANs and IP Addresses, including a built-in ping feature to check IP status.
- **Licenses & Credentials:** Securely store and manage Software Licenses and Password Vaults.
- **Helpdesk & Maintenance:** Built-in ticketing system for IT support and maintenance tracking for hardware/assets.
- **Import & Export:** Bulk import and export data using Excel for easy data migration and reporting.
- **Multi-language & Theming:** Support for multiple languages and theme switching.

## Tech Stack

- **Backend Framework:** Laravel 12
- **Authentication & Authorization:** Laravel Breeze, Spatie Laravel Permission
- **Excel Export/Import:** Maatwebsite Excel
- **Frontend:** Tailwind CSS, Vite

## Installation

1. Clone the repository.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies:
   ```bash
   npm install
   ```
4. Copy `.env.example` to `.env` and configure your database and environment settings.
   ```bash
   cp .env.example .env
   ```
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```
6. Run database migrations:
   ```bash
   php artisan migrate
   ```
7. Build frontend assets:
   ```bash
   npm run build
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```
   *Note: You can also use `composer run dev` which runs `php artisan serve`, queue listeners, and `npm run dev` concurrently.*

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
