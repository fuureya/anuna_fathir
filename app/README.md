# 📚 Library Management System

A comprehensive library management system built with **Laravel 11**, migrated from a legacy PHP application. This system provides public-facing features for book browsing, reservations, and reviews, as well as administrative tools for managing books, users, reservations, and mobile library schedules with **FCFS (First Come First Served) scheduling algorithm**.

## ✨ Features

### 🌐 Public Features
- **Book Collection**: Browse and search books with detailed information and cover images
- **Book Reservations**: Submit reservation requests for books or mobile library visits with geolocation
  - **Real-time Schedule Check**: View already booked time slots before submitting
  - **Conflict Prevention**: Automatic validation to prevent time conflicts
  - **FCFS Queue Tracking**: Track your queue position and waiting time
- **Book Reviews**: Submit and view book reviews with sentiment analysis
- **E-Resources**: Browse digital book catalog with category filters
- **Mobile Library Schedule**: View scheduled mobile library visits with location details
- **Literacy Agenda**: Browse upcoming literacy events and workshops
- **Library Information**: Contact details, opening hours, and location map
- **My Reservations**: Track your reservation status with FCFS metrics

### 🔐 Admin Features
- **Book Management**: Full CRUD operations for books with cover image upload
- **User Management**: Manage user accounts with extended profile fields and avatar upload
- **Reservation Management**: Review, approve, or reject reservation requests with reason tracking
  - **FCFS Metrics Display**: View queue position, waiting time, and turnaround time for each reservation
  - **Auto-scheduling**: Automatic FCFS queue processing when approving reservations
- **Review Moderation**: View and delete inappropriate reviews
- **Schedule Generation**: Generate optimized mobile library schedules using interval scheduling algorithm
- **FCFS Queue Management**: Process and optimize reservation schedules using First Come First Served algorithm

## 📋 Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0 & NPM >= 9.0
- MySQL >= 5.7 or MariaDB >= 10.3
- Web server (Apache/Nginx) or use Laravel's built-in server
- Git (for cloning repository)

## 🚀 Installation

### 1. Clone the Repository

```bash
# Clone dari GitHub (jika ada)
git clone https://github.com/your-repo/library-system.git
cd library-system

# Atau jika sudah ada di local
cd d:\project_web\laravel
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
# Windows PowerShell
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

Update the database configuration in `.env`:

```env
APP_NAME="Library Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_web
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (Optional - untuk email notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
# Create database first (using MySQL client or phpMyAdmin)
# CREATE DATABASE project_web;

# Run migrations
php artisan migrate
```

Migrations akan membuat tabel-tabel berikut:
- `users` - User accounts dengan extended profile
- `books` - Book catalog
- `reservations` - Reservation requests dengan **FCFS fields**
- `reviews` - Book reviews dengan sentiment analysis
- `mobile_library_schedule` - Mobile library schedules
- Dan tabel-tabel sistem Laravel lainnya

### 6. Seed Sample Data

```bash
# Seed admin user (WAJIB)
php artisan db:seed --class=AdminSeeder

# Seed sample books (Opsional, 100 buku contoh)
php artisan db:seed --class=BookSeeder

# Atau seed semua data sekaligus
php artisan db:seed
```

**Default Admin Credentials:**
- Email: `admin@gmail.com`
- Password: `admin123`

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Build Frontend Assets

```bash
# For development (dengan hot reload)
npm run dev

# For production
npm run build
```

## 🖥️ Running the Application

### Development Mode

**Option 1: Menggunakan Laravel's Built-in Server (Recommended)**

```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Watch and compile frontend assets
npm run dev
```

Aplikasi akan berjalan di: **http://localhost:8000**

**Option 2: Menggunakan Artisan Serve dengan Custom Host/Port**

```bash
# Dengan custom port
php artisan serve --port=8080

# Dengan custom host (untuk akses dari network)
php artisan serve --host=0.0.0.0 --port=8000
```

### Production Mode

```bash
# Build production assets
npm run build

# Configure web server (Apache/Nginx)
# Point document root to: /path/to/project/public
```

### Quick Start Commands

```bash
# Start development (butuh 2 terminal)
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

### 🔄 FCFS Queue Processing

Setelah admin meng-approve reservasi, FCFS akan otomatis diproses. Untuk manual processing:

```bash
# Process FCFS queue untuk hari ini
php artisan fcfs:process

# Process FCFS queue untuk tanggal tertentu
php artisan fcfs:process --date=2025-12-15

# Process FCFS queue untuk tanggal tertentu (format lain)
php artisan fcfs:process --date=2025-11-25
```

**Output:**
```
🚀 Processing FCFS queue for date: 2025-11-11

✅ FCFS Processing Complete

+-------------------------+-------------+
| Metric                  | Value       |
+-------------------------+-------------+
| Date                    | 2025-11-11  |
| Processed Reservations  | 4           |
| Average Waiting Time    | 180 minutes |
| Average Turnaround Time | 300 minutes |
+-------------------------+-------------+
📊 Successfully processed 4 reservations!
```

### 🔑 Admin Password Management

```bash
# Reset admin password via command
php artisan admin:reset-password admin@gmail.com newpassword123

# Atau via tinker
php artisan tinker
```

Then execute in tinker:

```php
$user = App\Models\User::where('email', 'admin@gmail.com')->first();
$user->password = bcrypt('newpassword');
$user->save();
exit
```

## 🎯 Default Credentials

**Admin Account:**
- Email: `admin@gmail.com`
- Password: `admin123`

⚠️ **PENTING:** Ganti password default setelah first login!

## 📁 Directory Structure

### Key Directories

- `app/Http/Controllers/` - Application controllers
  - `Admin/` - Admin panel controllers (Books, Users, Reservations, Reviews, Schedule)
  - Public controllers (Home, Books, Reservations, Reviews, Schedule, StaticPages)
- `app/Models/` - Eloquent models
  - `User.php` - User model dengan role-based access
  - `Book.php` - Book model
  - `Reservation.php` - Reservation model dengan FCFS fields
  - `Review.php` - Review model dengan sentiment analysis
- `app/Services/` - Service layer
  - `FCFSScheduler.php` - FCFS scheduling algorithm implementation
- `app/Console/Commands/` - Artisan commands
  - `ProcessFCFSQueue.php` - FCFS queue processing command
  - `ResetAdminPassword.php` - Admin password reset command
- `resources/views/` - Blade templates
  - `admin/` - Admin panel views
    - `books/` - Book management
    - `reservations/` - Reservation management dengan FCFS metrics
    - `users/` - User management
    - `reviews/` - Review moderation
  - `books/` - Book listing and detail views
  - `reservations/` - Reservation form dengan jadwal terdaftar
  - `reviews/` - Review form
  - `schedule/` - Mobile library schedule
  - `static/` - Static informational pages
- `database/migrations/` - Database migrations
  - `2025_12_09_000001_add_fcfs_fields_to_reservations.php` - FCFS fields migration
- `database/seeders/` - Database seeders
  - `AdminSeeder.php` - Create default admin account
  - `BookSeeder.php` - Seed 100 sample books
- `routes/web.php` - Application routes
- `public/uploads/` - User uploaded files (avatars, request letters)
- `public/covers/` - Book cover images

## 📚 Features Overview

### 📖 Book Management

**Public:**
- Browse books at `/books`
- View book details at `/books/{id}`
- See book covers and descriptions
- Search dan filter buku

**Admin:**
- Manage books at `/admin/books`
- Add, edit, delete books
- Upload book cover images (JPG, PNG, max 2MB)
- Bulk operations

### 📅 Reservations (dengan FCFS Algorithm)

**Public:**
- Submit reservation requests at `/reservations/create`
- **View jadwal yang sudah terdaftar** pada tanggal yang dipilih
- **Real-time conflict detection** - otomatis cek bentrok waktu
- Provide personal details, book/event info, location data
- Upload request letters (PDF, max 5MB)
- Track reservasi di `/reservations/my-reservations`
- **View FCFS metrics**: Queue position, waiting time, turnaround time

**Admin:**
- View all reservations at `/admin/reservations`
- **View FCFS metrics** untuk setiap reservasi:
  - 🏁 Queue Position
  - ⏱️ Waiting Time (WT)
  - 📊 Turnaround Time (TAT)
  - 🚀 Start Time
- Approve, reject (dengan alasan), or mark as completed
- **Auto FCFS processing** saat approve reservasi
- Filter by status

**FCFS Commands:**
```bash
# Process queue untuk hari ini
php artisan fcfs:process

# Process queue untuk tanggal tertentu
php artisan fcfs:process --date=2025-12-15
```

**API Endpoints:**
- `GET /reservations/by-date?date=YYYY-MM-DD` - Get reservations for specific date
- `GET /reservations/booked-slots?date=YYYY-MM-DD` - Get booked time slots
- `GET /reservations/{id}/json` - Get reservation details as JSON

### ⭐ Reviews

**Public:**
- Submit book reviews at `/reviews/create`
- Reviews include sentiment analysis (positive/negative/neutral)

**Admin:**
- View all reviews at `/admin/reviews`
- Delete inappropriate reviews
- See sentiment analysis results

### User Management

**Public:**
- Register new account at `/register`
- Edit profile at `/profile`
- Upload profile picture
- Update extended profile fields (NIK, gender, education, occupation, etc.)

**Admin:**
- Manage users at `/admin/users`
- Create, edit, delete user accounts
- Assign admin/user roles
- View user profiles

### Mobile Library Schedule

**Public:**
- View schedule at `/schedule`
- Filter by location, booker, category
- See Google Maps links for locations

**Admin:**
- Generate schedules at `/admin/schedule`
- Preview optimized schedule with interval scheduling algorithm
- Commit schedule to database
- Algorithm selects non-overlapping time slots for maximum coverage

### 🚌 Mobile Library Schedule

**Public:**
- View schedule at `/schedule`
- Filter by location, booker, category
- See Google Maps links for locations
- Interactive map dengan Leaflet.js

**Admin:**
- Generate schedules at `/admin/schedule`
- Preview optimized schedule with interval scheduling algorithm
- Commit schedule to database
- Algorithm selects non-overlapping time slots for maximum coverage

### 📄 Static Pages

- **E-Resources** (`/e-resources`) - Browse digital book catalog with filters
- **Library Location** (`/library-location`) - Interactive map, address, contact info, hours
- **Literacy Agenda** (`/literacy-agenda`) - Upcoming literacy events
- **Literacy Detail** (`/literacy/{id}`) - Event details
- **Library Info** (`/info`) - General library information

## 🔐 Authentication & Authorization

The application uses **Laravel Breeze** for authentication with additional **role-based access control**:

- **Public routes:** Accessible to all users (browsing books, creating reservations)
- **Auth routes:** Require login (`/profile`, `/reservations/my-reservations`)
- **Admin routes:** Require login + admin role (`/admin/*`)

Admin middleware checks the `role` field on the User model.

## 📤 File Uploads

### Book Covers
- **Path**: `public/covers/`
- **Formats**: JPG, JPEG, PNG, GIF
- **Max size**: 2MB
- **Recommended**: 600x800px untuk best display

### User Avatars
- **Path**: `public/uploads/`
- **Formats**: JPG, JPEG, PNG, GIF
- **Max size**: 2MB
- **Recommended**: Square images (500x500px)

### Reservation Letters
- **Path**: `public/uploads/`
- **Formats**: PDF
- **Max size**: 5MB
- **Required**: Surat permohonan resmi

## 🗄️ Database Schema

### Key Tables:

**`users`** - User accounts
- `id`, `nik`, `fullname`, `email`, `password`
- `role` (admin/user)
- Extended profile: `jenis_kelamin`, `usia`, `pendidikan_terakhir`, `pekerjaan`, `alamat_tinggal`
- `avatar` untuk profile picture

**`books`** - Book catalog
- `id`, `title`, `author`, `publisher`, `publication_year`
- `isbn`, `category`, `description`, `stock`
- `cover` untuk book cover image

**`reservations`** - Reservation requests dengan **FCFS fields**
- Basic info: `id`, `full_name`, `category`, `occupation`, `address`, `phone_number`
- Reservation details: `reservation_date`, `visit_time`, `end_time`, `status`
- **FCFS fields**:
  - `arrival_time` - Waktu request masuk (AT)
  - `burst_time` - Durasi layanan dalam menit (BT)
  - `start_time` - Waktu mulai layanan (ST)
  - `completion_time` - Waktu selesai layanan (CT)
  - `waiting_time` - Waktu tunggu dalam menit (WT = ST - AT)
  - `turnaround_time` - Total waktu proses (TAT = CT - AT)
  - `queue_position` - Posisi dalam antrian
- `request_letter` - Upload surat permohonan
- `rejection_reason` - Alasan jika ditolak

**`reviews`** - Book reviews dengan sentiment analysis
- `id`, `book_id`, `user_id`, `rating`, `review_text`
- `sentiment` - positive/negative/neutral
- `created_at`, `updated_at`

**`mobile_library_schedule`** - Scheduled visits
- `id`, `location`, `booker`, `category`
- `visit_date`, `start_time`, `end_time`, `duration`
- `latitude`, `longitude` untuk maps
- `status`, `notes`

## 🧪 Testing

### Run Test Suite

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ReservationTest

# Run with coverage
php artisan test --coverage
```

### Manual Testing

**Test FCFS Algorithm:**
```bash
# Test dengan sample data
php test_fcfs_algorithm.php

# Verify FCFS calculations
php verify_fcfs.php

# Check reservations
php check_reservations.php
```

Feature tests cover:
- ✅ Authentication (registration, login, admin access)
- ✅ Book management (CRUD operations)
- ✅ Reservations (submission, admin approval)
- ✅ FCFS scheduling (queue processing, metrics calculation)

## 🔮 Future Enhancements

### High Priority
- [ ] Email notifications untuk reservation status changes
- [ ] Real-time notifications dengan WebSocket
- [ ] Export FCFS reports (PDF, Excel)
- [ ] Dashboard analytics dengan grafik WT dan TAT

### Medium Priority
- [ ] Advanced search dengan multiple filters
- [ ] Mobile app dengan React Native/Flutter
- [ ] Barcode scanning untuk books
- [ ] Integration dengan perpustakaan nasional API

### Low Priority
- [ ] Payment gateway untuk book purchases
- [ ] Book borrowing/lending tracking system
- [ ] Chatbot untuk customer support
- [ ] Machine learning untuk book recommendations

## 🔧 Troubleshooting

### Migration Errors

**Problem**: `SQLSTATE[HY000] [1049] Unknown database 'project_web'`

**Solution**:
```bash
# Create database first
mysql -u root -p
CREATE DATABASE project_web;
EXIT;

# Then run migrations
php artisan migrate
```

**Problem**: Migration fails with existing tables

**Solution**:
```bash
# Fresh migration (WARNING: destroys all data)
php artisan migrate:fresh

# Or rollback and re-migrate
php artisan migrate:rollback
php artisan migrate
```

### FCFS Processing Errors

**Problem**: FCFS tidak auto-process setelah approve

**Solution**:
```bash
# Manual process
php artisan fcfs:process --date=2025-12-09

# Check logs
tail -f storage/logs/laravel.log
```

**Problem**: "Table migrations doesn't exist"

**Solution**:
```bash
# Ensure MySQL is running
# Then run
php artisan migrate:install
php artisan migrate
```

### Asset Compilation Issues

If you get errors about missing `updated_at` on users table, run:

```bash
php artisan migrate
```

### File Upload Errors

Ensure the following directories are writable:

```bash
chmod -R 775 public/uploads
chmod -R 775 public/covers
chmod -R 775 storage
```

On Windows, ensure IIS/Apache has write permissions to these folders.

### Asset Compilation Issues

**Problem**: Vite assets don't load

**Solution**:
```bash
# Clear cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules
npm install

# Build assets
npm run build

# Or run dev server
npm run dev
```

### File Upload Errors

**Problem**: "The file could not be uploaded"

**Solution** (Windows):
```powershell
# Ensure directories exist and are writable
New-Item -ItemType Directory -Force -Path "public\uploads"
New-Item -ItemType Directory -Force -Path "public\covers"
New-Item -ItemType Directory -Force -Path "storage\app\public"
```

**Solution** (Linux/Mac):
```bash
# Create directories and set permissions
mkdir -p public/uploads public/covers storage/app/public
chmod -R 775 public/uploads public/covers storage
```

### Performance Issues

**Problem**: Slow page load

**Solution**:
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📚 Documentation

### Available Documentation Files:
- **README.md** (this file) - Main documentation
- **FCFS_ALGORITHM.md** - Detailed FCFS algorithm documentation
- **FCFS_QUICK_START.md** - Quick start guide for FCFS
- **FCFS_UI_FEATURES.md** - UI features documentation
- **DEPLOYMENT_GUIDE.md** - Production deployment guide
- **BOOK_SEEDER_README.md** - Book seeder documentation
- **EMAIL_TROUBLESHOOTING.md** - Email configuration guide

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11
- **Language**: PHP 8.2+
- **Database**: MySQL 5.7+
- **ORM**: Eloquent
- **Authentication**: Laravel Breeze
- **PDF Generation**: DomPDF
- **QR Code**: SimpleSoftwareIO/simple-qrcode, Endroid/qr-code

### Frontend
- **Template Engine**: Blade Templates
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js 3.x
- **Build Tool**: Vite 6.x
- **Maps**: Leaflet.js
- **Icons**: Heroicons, Font Awesome

### Development Tools
- **Package Manager**: Composer (PHP), NPM (Node.js)
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint
- **Debugging**: Laravel Pail, Tinker

## 🌐 API Endpoints

### Public API
- `GET /books` - List all books
- `GET /books/{id}` - Book details
- `GET /reservations/by-date?date=YYYY-MM-DD` - Reservations by date
- `GET /reservations/booked-slots?date=YYYY-MM-DD` - Booked time slots
- `GET /reservations/{id}/json` - Reservation details JSON

### Admin API (Requires Authentication)
- `POST /admin/reservations/{id}/status` - Update reservation status
- `POST /admin/reviews/{id}/delete` - Delete review
- `POST /admin/books` - Create book
- `PUT /admin/books/{id}` - Update book
- `DELETE /admin/books/{id}` - Delete book

## 📞 Support & Contact

**Library Contact:**
- **Email**: perpustakaandaerah@gmail.com
- **WhatsApp**: +62 811 1412 8989
- **Address**: Makassar, Sulawesi Selatan

**Technical Support:**
- Check logs: `storage/logs/laravel.log`
- GitHub Issues: (if repository is public)
- Email: (technical contact)

## 📄 License

This project is developed for **educational purposes** as part of library management system modernization.

## 🙏 Acknowledgments

- Laravel Framework Team
- Tailwind CSS Team
- All open source contributors
- Perpustakaan Daerah Makassar

---

**Version**: 1.0.0  
**Last Updated**: December 9, 2025  
**Status**: ✅ Production Ready
