# Sales, Inventory & CRM System

A full-stack business management web application built with **Laravel 12 (PHP)** and **Vue 3 (Vite + TypeScript)**. Designed for managing multi-branch sales, product inventories, customer relationship management (CRM) with lost-customer recovery mechanisms, employee KPI tracking, automated email invoices, and third-party e-commerce API integration.

---

## 🌟 Key Features

### 1. Sales & Inventory Management
* **Product Catalog**: SKU, Product Name, Price, Description, and Soft Delete support.
* **Multi-Branch Inventory**: Maintain branch-specific stock levels across multiple locations.
* **Race-Condition Safe Checkout**: Uses `DB::transaction()` with pessimistic row-locking (`lockForUpdate()`) to guarantee stock consistency under concurrent orders and prevent negative inventory/overselling.
* **Stock Deductions**: Prevents sales when stock quantity is insufficient and throws custom `InsufficientStockException`.

### 2. Customer Relationship Management (CRM)
* **Customer History**: Tracks full transaction history, purchase frequency (total orders), and total lifetime spending.
* **Configurable Lost Customer Detection**: Automatically flags inactive customers based on `LOST_CUSTOMER_DAYS` (default: 90 days). Includes an Artisan command `crm:detect-lost-customers` scheduled to run daily.
* **Employee Assignment & Auto KPI Tracking**: Admin/Managers can assign inactive customers to employees for follow-up. When a recovered customer makes a new purchase, the assigned employee's KPI score is automatically incremented via Laravel Event Listeners (`SaleCompleted`).
* **Customer Re-Engagement**: Simulated and logged email/SMS promotional campaigns (`promotion_logs`).

### 3. Bonus Features Implemented
* 🏢 **Multi-Branch Support**: Dedicated branch management and branch-scoped inventory & sales tracking.
* 📧 **Automated Email Invoices**: Sends styled HTML email invoices (`SaleInvoiceMail`) to customers upon successful purchase checkout.
* 🛒 **E-Commerce Integration API**: Secure REST API endpoints (`/api/v1/ecommerce/products`) exposing SKU, product name, price, and aggregate/branch available stock for third-party platforms.

---

## 🛠️ Technology Stack

* **Backend**: Laravel 12, PHP 8.2+, Spatie Permission, Laravel Sanctum
* **Database**: MySQL
* **Mail/SMTP**: Mailtrap / SMTP / Log
* **Frontend**: Vue 3, Vite, TypeScript, Pinia, Vue Router, Tailwind CSS

---

## 📋 System Requirements

* PHP >= 8.2 with OpenSSL, PDO, Mbstring extensions
* Composer >= 2.0
* Node.js >= 18.x & NPM
* MySQL Server >= 8.0

---

## 🚀 Step-by-Step Local Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/sohelr-dev/sinodTech.git
cd sinodTech
```

---

### 2. Backend Setup (Laravel API)

```bash
# Navigate to backend directory
cd backend-api

# Install PHP dependencies
composer install

# Environment Configuration
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

#### `.env` Configuration
Ensure your database and mail credentials are setup in `backend-api/.env`:

```env
APP_NAME="SinodTech CRM"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sinodtech
DB_USERNAME=root
DB_PASSWORD=

# CRM Settings
LOST_CUSTOMER_DAYS=90

# Mail Configuration (Mailtrap or SMTP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@sinodtech.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Database Migration & Seeders
Run database migrations and seeders to populate initial realistic data (Users, Branches, Products, Customers, Sales, and Assignments):

```bash
# Run fresh migrations with realistic sample data
php artisan migrate:fresh --seed
```

#### Default Credentials (Seeded Users)
* **Super Admin**: `admin@gmail.com` | `password`
* **Manager**: `manager@gmail.com` | `password`
* **Sales Employee**: `sales@gmail.com` | `password`
* **Editor**: `editor@gmail.com` | `password`

#### Run Backend Dev Server & Queue Worker
```bash
# Start Laravel API server
php artisan serve

# In a separate terminal, start queue listener to process queued email invoices
php artisan queue:listen
```
Backend API will be accessible at: `http://localhost:8000`

---

### 3. Frontend Setup (Vue.js)

Open a new terminal window:

```bash
# Navigate to frontend directory
cd sinodTech/frontend-vue

# Install Node dependencies
npm install

# Start Frontend Development Server
npm run dev
```
Frontend App will be accessible at: `http://localhost:5173`

---

## 🧪 Running Automated Tests

To execute the backend feature and unit test suite:

```bash
cd backend-api
php artisan test
```

---

## 🔌 E-Commerce Integration API Reference

* **List Products & Stock**: `GET /api/v1/ecommerce/products`
  * *Query Params*: `search`, `sku`, `branch_id`, `per_page`
  * *Response*:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "sku": "HP-PB-450",
      "product_name": "Laptop HP ProBook",
      "price": 75000,
      "available_stock": 145,
      "description": "HP ProBook 450 G10"
    }
  ]
}
```
* **Get Product Details**: `GET /api/v1/ecommerce/products/{sku_or_id}`

---

## 📅 Scheduled Tasks & Background Queues

### Queue Worker (Email Invoices & Background Jobs)
Background jobs (such as queued invoice emails) are processed using Laravel's queue worker:

```bash
php artisan queue:listen
```

### Lost Customer Detection Task
Lost customer detection is registered in `routes/console.php` and can be manually triggered via Artisan:

```bash
# Run manually
php artisan crm:detect-lost-customers --days=90

# Or execute scheduler
php artisan schedule:run
```
