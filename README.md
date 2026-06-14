# Warehouse Management System

A comprehensive web-based warehouse and inventory management system built with Laravel, designed to streamline operations for warehouses, sales teams, and customers. This system features multi-role access control, real-time inventory tracking, order management, and a customer-facing portal for online ordering.

## 🌟 Features

### 🔐 Multi-Role Authentication System
- **Super Admin**: Full system control with feature toggles and user management
- **Admin**: Comprehensive warehouse and sales management
- **Staff Gudang (Warehouse Staff)**: Inventory management, stock operations, order fulfillment
- **Staff Penjualan (Sales/CS)**: Customer management, order processing, sales operations
- **Kurir (Courier)**: Shipping and delivery management
- **Customer**: Self-service portal for browsing and ordering products

### 📦 Inventory & Product Management
- **Product Master Data**: Complete product information with images, categories, pricing, and specifications
- **Category Management**: Organize products with hierarchical categories
- **Stock Tracking**: Real-time stock levels with minimum stock alerts
- **Stock Movements**: Automated logging of all stock in/out operations
- **Stock Reservations**: Automatic stock reservation for pending orders
- **Warehouse Location**: Track products by rack/bin locations
- **Product Specifications**: Support for electronic products with warranty information

### 🛒 Order Management System
- **Multi-Channel Orders**: Support for both admin-created and customer portal orders
- **Order Workflow States**:
  - Pending → Waiting Restock → Reserved → Picking → Packed → Shipped → Completed
- **Payment Management**: Upload and verify payment proofs
- **Order Approval**: Multi-stage approval process
- **Order Tracking**: Real-time order status updates
- **Inventory Integration**: Automatic stock deduction and reservation

### 👥 Customer Management
- **Customer Portal**: Self-service interface for customers
- **Product Catalog**: Browse products with search and category filters
- **Shopping Cart**: Full cart functionality with quantity management
- **Checkout Process**: Streamlined ordering with payment proof upload
- **Order History**: Track all orders with detailed status
- **Notifications**: Real-time updates on order status changes

### 📊 Reporting & Analytics
- **Inventory Reports**: Stock levels, movement history, and analytics
- **Stock Summary Dashboard**: 
  - Total items and stock quantities
  - Reserved stock tracking
  - Available stock calculations
  - Low stock alerts
- **Order Reports**: Sales and fulfillment analytics
- **Export Functions**: PDF and Excel exports for reports

### 🤖 AI-Powered Chatbot
- **FAQ System**: Manage frequently asked questions
- **Chatbot Integration**: AI-powered customer support (via API integration)
- **Chat Logging**: Track all chatbot interactions

### ⚙️ Dynamic Feature Toggles
- **Feature Management**: Enable/disable features without code deployment
- **Role-Based Access**: Control feature access per user role
- **Dynamic Sidebar**: Automatically show/hide menu items based on enabled features
- **Supported Toggles**:
  - Data Barang (Product Management)
  - Kategori Barang (Category Management)
  - Laporan Barang (Inventory Reports)
  - Custom feature pages

### 🔔 Notification System
- **User Notifications**: Real-time updates for order changes
- **Customer Alerts**: Notify customers of order status updates
- **Staff Notifications**: Alert warehouse and sales teams of new orders

### 🔒 Security Features
- **Role-Based Access Control (RBAC)**: Granular permissions per role
- **Route Middleware Protection**: Feature and role-based route guards
- **Authentication**: Laravel Breeze integration
- **Session Management**: Secure user sessions

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **PHP**: 8.1+
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **API Token**: Laravel Sanctum

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Mazer Admin Dashboard Template
- **JavaScript**: Vanilla JS with modern features
- **Icons**: Bootstrap Icons

### Key Packages
- **barryvdh/laravel-dompdf**: PDF generation for reports
- **maatwebsite/excel**: Excel export functionality
- **guzzlehttp/guzzle**: HTTP client for external API integration

## 📁 Project Architecture

```
TheVoid/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── ChatbotController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── CustomerPortalController.php
│   │   │   ├── DataBarangController.php
│   │   │   ├── FaqController.php
│   │   │   ├── FeaturePageController.php
│   │   │   ├── FeatureToggleController.php
│   │   │   ├── InventoryLogController.php
│   │   │   ├── KategoriBarangController.php
│   │   │   ├── LaporanController.php
│   │   │   ├── OrderController.php
│   │   │   ├── UserManagementController.php
│   │   │   └── Auth/
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── DataBarang.php
│   │   ├── KategoriBarang.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Customer.php
│   │   ├── BarangKeluar.php
│   │   ├── BarangKeluarItem.php
│   │   ├── Pengiriman.php
│   │   ├── StockReservation.php
│   │   ├── StockMovement.php
│   │   ├── InventoryLog.php
│   │   ├── FeatureToggle.php
│   │   ├── Faq.php
│   │   ├── ChatbotLog.php
│   │   └── UserNotification.php
│   ├── Services/
│   │   ├── NotificationService.php
│   │   └── StockMovementService.php
│   └── Helpers/
│       └── helpers.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── admin/          # Admin panel views
│       ├── customer/       # Customer portal views
│       ├── auth/           # Authentication views
│       ├── data-barang.blade.php
│       ├── kategori-barang.blade.php
│       ├── laporan.blade.php
│       └── dashboard.blade.php
└── routes/
    ├── web.php
    ├── api.php
    └── auth.php
```

## 🚀 Demo

> **🔗 [Live Demo](https://your-demo-url.com)** — Klik untuk langsung coba aplikasi!

> **Note:** Login pakai akun admin atau buat akun baru di halaman registrasi.

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & NPM (for asset compilation)

### Step 1: Clone the Repository
```bash
git clone https://github.com/riorizqi-dev/Warehouse-Management-System.git
cd Warehouse-Management-System
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_barang
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:
```bash
mysql -u root -p
CREATE DATABASE inventaris_barang;
exit;
```

Run migrations:
```bash
php artisan migrate
```

### Step 5: Storage Setup
```bash
# Create storage symlink
php artisan storage:link
```

### Step 6: Compile Assets
```bash
npm run build
```

### Step 7: Run the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Default Admin Credentials
After running migrations, create a super admin:
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Super Admin',
    'username' => 'superadmin',
    'email' => 'admin@warehouse.com',
    'password' => bcrypt('password'),
    'role' => 'superadmin',
    'email_verified_at' => now()
]);
```

## 📖 Usage Guide

### For Administrators

1. **Managing Products**
   - Navigate to "Data Barang" menu
   - Add products with details, images, and categories
   - Set minimum stock levels for alerts
   - Assign warehouse locations

2. **Processing Orders**
   - View incoming orders in "Orders" menu
   - Approve/reject orders
   - Verify payment proofs
   - Track order fulfillment stages

3. **Managing Users**
   - Access "User Management" (Super Admin only)
   - Create users with appropriate roles
   - Assign permissions based on responsibilities

4. **Feature Toggles**
   - Enable/disable system features dynamically
   - Control access per user role
   - Add custom feature pages

### For Warehouse Staff

1. **Stock Management**
   - Monitor stock levels in real-time
   - Process stock in/out operations
   - Track stock movements and reservations
   - Pick and pack orders

2. **Order Fulfillment**
   - View orders with "reserved" status
   - Start picking process
   - Mark orders as packed
   - Hand off to shipping

### For Sales Staff

1. **Customer Management**
   - Add and manage customer records
   - Create orders on behalf of customers
   - Process payments and verifications

2. **Order Processing**
   - Create new orders
   - Approve pending orders
   - Verify payment proofs
   - Handle customer inquiries

### For Customers

1. **Shopping**
   - Browse product catalog
   - Search and filter products
   - Add items to cart
   - Proceed to checkout

2. **Order Tracking**
   - View order history
   - Track current orders
   - Upload payment proofs
   - Confirm delivery receipt

## 🔮 Future Improvements

### Planned Features
- [ ] **Barcode Integration**: Barcode scanning for faster stock operations
- [ ] **Multi-Warehouse Support**: Manage multiple warehouse locations
- [ ] **Advanced Analytics**: Data visualization with charts and trends
- [ ] **Email Notifications**: Automated email alerts for order updates
- [ ] **SMS Integration**: SMS notifications for critical updates
- [ ] **Mobile Application**: Native iOS/Android apps
- [ ] **API Documentation**: RESTful API for third-party integrations
- [ ] **Supplier Management**: Track suppliers and purchase orders
- [ ] **Return Management**: Handle product returns and refunds
- [ ] **Batch Operations**: Bulk import/export of products
- [ ] **Audit Trail**: Comprehensive activity logging
- [ ] **Inventory Forecasting**: AI-powered stock predictions
- [ ] **Multi-Currency Support**: International sales capability
- [ ] **Invoice Generation**: Automated invoice creation
- [ ] **Reporting Dashboard**: Advanced BI dashboards

### Technical Enhancements
- [ ] Redis caching for improved performance
- [ ] Queue system for background jobs
- [ ] Automated testing suite (Unit & Feature tests)
- [ ] Docker containerization
- [ ] CI/CD pipeline setup
- [ ] Database optimization and indexing
- [ ] Real-time updates with WebSockets
- [ ] Progressive Web App (PWA) support

## 👨‍💻 Author

**Rio Rizqi Saputra**

- GitHub: [@riorizqi-dev](https://github.com/riorizqi-dev)
- Email: riorizqi918@gmail.com
- Portfolio: [github.com/riorizqi-dev](https://github.com/riorizqi-dev)

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/riorizqi-dev/Warehouse-Management-System/issues).

## ⭐ Show your support

Give a ⭐️ if this project helped you!

---

**Built with ❤️ using Laravel**
