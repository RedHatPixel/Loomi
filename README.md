# Loomi

Loomi is a multi-vendor e-commerce platform built for independent clothing brands. It lets sellers open their own storefronts and sell directly to customers, while giving the platform owner full administrative control through a dedicated admin panel and an official **Loomi Sellers** storefront.

---

## Overview

Every registered user starts as a customer. Any customer can open one or more stores and become a seller, with each store fully scoped to its owner. Admins operate independently of the normal registration flow and have full access to all platform data, in addition to running the official Loomi Sellers brand.

### Core Capabilities

- **Customer storefront** — browse products, search, manage a cart, check out, and track orders
- **Seller dashboard** — create and manage stores, list products, fulfill orders
- **Admin panel** — full platform visibility and control, plus management of the Loomi Sellers storefront
- **Multi-store support** — a single user account can own and manage multiple stores
- **Role-based access** — Customer, Seller, and Admin roles with strict data scoping

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 |
| Frontend bridge | Inertia.js v2 |
| Frontend | React 18 + TypeScript |
| Styling | TailwindCSS v3 |
| UI primitives | Headless UI |
| Database | MySQL |
| Bundler | Vite |
| Routing helper | Ziggy (`route()` in JS) |
| Auth scaffolding | Laravel Breeze |

This is an **Inertia.js application**, not a separate REST API + SPA. Pages receive data as server-rendered props, forms are submitted with Inertia's `useForm`, and navigation uses Inertia's `<Link>` and `router` — there's no client-side data fetching for initial page loads.

---

## Project Structure

```
app/
  Http/Controllers/
    Admin/
    Auth/
    Customer/
    Seller/
  Models/
  Policies/
  Services/
database/
  migrations/
  seeders/
  factories/
routes/
  web.php
  auth.php
resources/
  js/
    Components/
    Hooks/
    Layouts/
    Pages/
    Store/
    Types/
    Utils/
    Constants/
  css/
```

---

## User Roles

| Role | Description |
|---|---|
| **Customer** | Default for every registered account. Browses the marketplace, manages cart and orders. |
| **Seller** | A customer who has created at least one store. Manages products and fulfillment for their own store(s) only. |
| **Admin** | Created outside normal registration. Full read/write access to all data, plus control of the Loomi Sellers storefront. |

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL
- A local PHP environment (e.g. XAMPP, Laravel Herd, Valet)

### Installation

```bash
# Clone the repo
git clone https://github.com/RedHatPixel/Loomi.git
cd Loomi

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file and configure database credentials
cp .env.example .env
php artisan key:generate
```

Update `.env` with your MySQL connection details, then run:

```bash
# Run migrations and seeders
php artisan migrate --seed

# Link public storage (for product images)
php artisan storage:link
```

### Running Locally

```bash
# Start the Laravel dev server
php artisan serve

# In a separate terminal, start Vite for asset compilation
npm run dev
```

Visit `http://localhost:8000` in your browser.

---

## License

This project is licensed under the [MIT License](LICENSE).
