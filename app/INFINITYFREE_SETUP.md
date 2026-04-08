# 🚀 Panduan Deploy ke InfinityFree (Hosting Gratis + SSL)

## ✅ Keunggulan InfinityFree

- ✅ **100% GRATIS** selamanya
- ✅ **SSL Certificate GRATIS** (HTTPS)
- ✅ **Unlimited Disk Space & Bandwidth**
- ✅ Support PHP 8.2 (Laravel compatible)
- ✅ MySQL Database
- ✅ cPanel & File Manager
- ✅ No ads pada website Anda

⚠️ **Keterbatasan:**
- Tidak ada SSH access (tapi cukup untuk project ini)
- Subdomain gratis (.infinityfreeapp.com) atau bisa pakai domain sendiri
- Hit limit: 50,000 hits/hari (cukup untuk library system)

---

## 📋 Step-by-Step Deploy ke InfinityFree

### STEP 1: Daftar Account InfinityFree

1. **Buka:** https://www.infinityfree.com
2. **Klik:** "Sign Up" atau "Create Account"
3. **Isi form:**
   - Email: zamanilham57@gmail.com (atau email Anda)
   - Password: Buat password kuat
4. **Verifikasi email** yang dikirim ke inbox Anda
5. **Login** ke Client Area

---

### STEP 2: Buat Hosting Account

1. **Di Client Area, klik:** "Create Account"
2. **Pilih subdomain gratis:**
   - Misal: `perpustakaanparepare.infinityfreeapp.com`
   - Atau: `pustaka-parepare.rf.gd` (alternatif domain gratis)
   - **TIPS:** Pilih nama yang mudah diingat
3. **Klik:** "Create Account"
4. **Tunggu 2-5 menit** sampai account aktif
5. **Catat informasi penting:**
   ```
   Username: epiz_xxxxx
   Main Domain: perpustakaanparepare.infinityfreeapp.com
   MySQL Host: sqlxxx.infinityfreeapp.com
   FTP Host: ftpupload.net
   ```

---

### STEP 3: Persiapan File di Laptop

#### 3.1 Compress Project Laravel

**Di Windows PowerShell:**

```powershell
# Masuk ke folder project
cd D:\project_web\laravel

# Hapus folder yang tidak perlu (agar file lebih kecil)
Remove-Item -Recurse -Force node_modules -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force vendor -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force storage\framework\cache\* -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force storage\logs\* -ErrorAction SilentlyContinue

# Compress menjadi ZIP
Compress-Archive -Path * -DestinationPath D:\project_web\laravel-perpustakaan.zip -Force
```

✅ **File `laravel-perpustakaan.zip` siap di** `D:\project_web\`

---

### STEP 4: Upload File ke InfinityFree

#### Opsi A: Via cPanel File Manager (MUDAH - REKOMENDASI!)

1. **Login cPanel:**
   - Dari Client Area → klik "Control Panel"
   - Atau langsung ke: https://cpanel.infinityfree.com

2. **Buka File Manager:**
   - Klik icon "File Manager" di cPanel
   - Masuk ke folder `htdocs`

3. **Upload ZIP:**
   - Klik tombol "Upload" di atas
   - Pilih file `laravel-perpustakaan.zip`
   - Tunggu sampai 100% (mungkin 5-10 menit tergantung internet)

4. **Extract ZIP:**
   - Kembali ke File Manager
   - Klik kanan file `laravel-perpustakaan.zip`
   - Pilih "Extract"
   - Pilih extract ke: `/htdocs`
   - Klik "Extract File(s)"
   - **Hapus file ZIP** setelah extract (klik kanan → Delete)

#### Opsi B: Via FTP (FileZilla)

**Download FileZilla:** https://filezilla-project.org

1. **Setting FTP di FileZilla:**
   ```
   Host: ftpupload.net
   Username: epiz_xxxxx (dari email InfinityFree)
   Password: (password hosting Anda)
   Port: 21
   ```

2. **Connect & Upload:**
   - Klik "Quickconnect"
   - Di panel kanan: masuk ke folder `/htdocs`
   - Di panel kiri: pilih folder laravel Anda
   - Drag & drop semua file ke kanan
   - Tunggu upload selesai (bisa 30-60 menit)

---

### STEP 5: Struktur Folder yang Benar

**⚠️ PENTING!** Folder struktur harus seperti ini:

```
htdocs/
├── .htaccess (buat file ini - lihat step 6)
├── index.php (dari folder public Laravel)
├── logo.png (dari folder public)
├── favicon.png (dari folder public)
├── manifest.json (dari folder public)
├── service-worker.js (dari folder public)
├── offline.html (dari folder public)
├── css/ (dari folder public)
├── images/ (dari folder public)
├── covers/ (dari folder public)
├── uploads/ (dari folder public)
└── laravel/ (folder Laravel di dalam htdocs)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    ├── composer.json
    └── artisan
```

**Cara Setting:**

1. **Di File Manager, di folder `htdocs/public`:**
   - Select semua file (index.php, logo.png, favicon.png, css, dll)
   - Klik "Move" di toolbar
   - Move to: `/htdocs`
   - Klik "Move File(s)"

2. **Rename folder:**
   - Folder `htdocs/` yang berisi `app`, `bootstrap`, dll
   - Rename menjadi `htdocs/laravel`

3. **Hapus folder `public` yang sudah kosong**

---

### STEP 6: Buat/Edit File .htaccess

**Di File Manager, folder `htdocs/`:**

1. **Klik "New File"**
2. **Nama file:** `.htaccess`
3. **Klik kanan → Edit**
4. **Paste kode ini:**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Force HTTPS (SSL)
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    
    # Handle Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

5. **Save Changes**

---

### STEP 7: Edit File index.php

**Di File Manager, file `htdocs/index.php`:**

1. **Klik kanan → Edit**
2. **Ubah path Laravel:**

**DARI:**
```php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

**JADI:**
```php
require __DIR__.'/laravel/vendor/autoload.php';
$app = require_once __DIR__.'/laravel/bootstrap/app.php';
```

3. **Save Changes**

---

### STEP 8: Buat Database MySQL

1. **Di cPanel, klik:** "MySQL Databases"

2. **Create New Database:**
   - Database Name: `perpustakaan` (akan jadi: `epiz_xxxxx_perpustakaan`)
   - Klik "Create Database"

3. **Create Database User:**
   - Username: `admindb` (akan jadi: `epiz_xxxxx_admindb`)
   - Password: Buat password kuat (CATAT!)
   - Klik "Create User"

4. **Add User to Database:**
   - User: `epiz_xxxxx_admindb`
   - Database: `epiz_xxxxx_perpustakaan`
   - Privileges: **ALL PRIVILEGES**
   - Klik "Add"

5. **Catat informasi database:**
   ```
   DB_HOST: sqlxxx.infinityfreeapp.com
   DB_DATABASE: epiz_xxxxx_perpustakaan
   DB_USERNAME: epiz_xxxxx_admindb
   DB_PASSWORD: (password yang Anda buat)
   ```

---

### STEP 9: Import Database

1. **Di cPanel, klik:** "phpMyAdmin"

2. **Login phpMyAdmin** (otomatis)

3. **Pilih database:** `epiz_xxxxx_perpustakaan` di sidebar kiri

4. **Klik tab "Import"**

5. **Choose File:**
   - Browse file `project_web.sql` dari laptop Anda
   - (File ada di: `D:\project_web\project_web\project_web.sql`)

6. **Klik "Go"** di bawah

7. **Tunggu sampai muncul:** "Import has been successfully finished"

---

### STEP 10: Edit File .env

**Di File Manager, file `htdocs/laravel/.env`:**

1. **Klik kanan → Edit**

2. **Update konfigurasi ini:**

```env
APP_NAME="Perpustakaan Keliling Parepare"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://perpustakaanparepare.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql123.infinityfreeapp.com
DB_PORT=3306
DB_DATABASE=epiz_12345678_perpustakaan
DB_USERNAME=epiz_12345678_admindb
DB_PASSWORD=password_database_anda

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=zamanilham57@gmail.com
MAIL_PASSWORD="olqc wqzv mgqa mvzc"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=zamanilham57@gmail.com
MAIL_FROM_NAME="Perpustakaan Keliling Parepare"
```

⚠️ **GANTI:**
- `APP_URL` → domain Anda
- `DB_HOST` → dari email InfinityFree
- `DB_DATABASE` → `epiz_xxxxx_perpustakaan`
- `DB_USERNAME` → `epiz_xxxxx_admindb`
- `DB_PASSWORD` → password database Anda

3. **Save Changes**

---

### STEP 11: Generate APP_KEY

**APP_KEY harus unique untuk security!**

#### Cara 1: Online Generator (MUDAH)

1. **Buka:** https://generate-random.org/laravel-key-generator
2. **Klik "Generate"**
3. **Copy key** (contoh: `base64:abcd1234...`)
4. **Paste ke `.env`:**
   ```env
   APP_KEY=base64:ySKBSkMx6xbKELHCSMEL3M6Oi0s9rIp11234567890=
   ```

#### Cara 2: PHP Code (via File Manager)

1. **Buat file baru:** `htdocs/generate-key.php`
2. **Isi dengan:**
   ```php
   <?php
   echo 'base64:'.base64_encode(random_bytes(32));
   ?>
   ```
3. **Buka di browser:** `https://perpustakaanparepare.infinityfreeapp.com/generate-key.php`
4. **Copy key yang muncul**
5. **Paste ke `.env`**
6. **HAPUS file `generate-key.php`** (security!)

---

### STEP 12: Install Composer Dependencies

⚠️ **InfinityFree tidak ada SSH**, jadi install di laptop dulu!

**Di PowerShell laptop:**

```powershell
cd D:\project_web\laravel

# Install dependencies
composer install --no-dev --optimize-autoloader

# Compress folder vendor
Compress-Archive -Path vendor -DestinationPath D:\vendor.zip -Force
```

**Upload `vendor.zip` ke hosting:**

1. **Via File Manager:** Upload ke `htdocs/laravel/`
2. **Extract:** Klik kanan → Extract → Extract to `/htdocs/laravel`
3. **Hapus `vendor.zip`**

---

### STEP 13: Set File Permissions

**Di File Manager:**

1. **Folder `storage/`:**
   - Klik kanan folder `htdocs/laravel/storage`
   - Klik "Change Permissions"
   - Centang semua checkbox (777)
   - Centang "Recurse into subdirectories"
   - Klik "Change Permissions"

2. **Folder `bootstrap/cache/`:**
   - Klik kanan `htdocs/laravel/bootstrap/cache`
   - Change Permissions → 777

---

### STEP 14: Aktifkan SSL (HTTPS) - GRATIS!

1. **Di Client Area InfinityFree:**
   - Klik account hosting Anda
   - Scroll ke bawah

2. **SSL Certificate Status:**
   - Biasanya **otomatis aktif** dalam 24 jam
   - Status: "Active" dengan icon gembok hijau ✅

3. **Jika belum aktif (manual):**
   - Klik "Manage"
   - Tab "SSL"
   - Klik "Enable SSL"
   - Pilih "Free SSL by InfinityFree"
   - Tunggu 5-10 menit

4. **Test SSL:**
   - Buka: `https://perpustakaanparepare.infinityfreeapp.com`
   - Lihat gembok hijau di address bar ✅

---

### STEP 15: Optimize Laravel

**Buat file `htdocs/optimize.php`:**

```php
<?php
// Temporary file untuk optimize Laravel (HAPUS setelah digunakan!)

chdir(__DIR__ . '/laravel');

// Clear & cache config
echo "Clearing config...\n";
shell_exec('php artisan config:clear');
echo "Caching config...\n";
shell_exec('php artisan config:cache');

// Clear & cache routes
echo "Clearing routes...\n";
shell_exec('php artisan route:clear');
echo "Caching routes...\n";
shell_exec('php artisan route:cache');

// Clear & cache views
echo "Clearing views...\n";
shell_exec('php artisan view:clear');
echo "Caching views...\n";
shell_exec('php artisan view:cache');

echo "\n✅ Optimization completed!\n";
echo "⚠️ HAPUS file ini setelah selesai untuk keamanan!\n";
?>
```

**Jalankan:**
1. Buka: `https://perpustakaanparepare.infinityfreeapp.com/optimize.php`
2. Tunggu muncul "✅ Optimization completed!"
3. **HAPUS file `optimize.php`** (security!)

---

### STEP 16: Testing Website

#### Test 1: Homepage
- Buka: `https://perpustakaanparepare.infinityfreeapp.com`
- ✅ Harus muncul halaman utama dengan logo

#### Test 2: Login
- Buka: `https://perpustakaanparepare.infinityfreeapp.com/login`
- ✅ Login sebagai admin/user

#### Test 3: Database Connection
- Cek halaman koleksi buku
- ✅ Harus muncul data buku dari database

#### Test 4: Email (Approval)
- Login sebagai admin
- Approve sebuah reservasi
- ✅ Cek email inbox → harus terkirim email dengan QR code

#### Test 5: QR Code Verification
- Buka email approval
- Klik link verifikasi atau scan QR
- ✅ Muncul detail reservasi

#### Test 6: PWA Installation (PALING PENTING!)

**Android:**
1. Buka di Chrome: `https://perpustakaanparepare.infinityfreeapp.com`
2. Tunggu 10 detik
3. ✅ Muncul tombol "Install Aplikasi" (bottom-right) atau banner install
4. Klik Install
5. ✅ Icon muncul di Home Screen
6. Buka dari icon → fullscreen tanpa browser UI

**iPhone:**
1. Buka di Safari
2. Tap tombol Share (kotak dengan panah)
3. "Add to Home Screen"
4. ✅ Icon muncul di Home Screen

**Desktop:**
1. Buka di Chrome/Edge
2. ✅ Icon install di address bar (kanan)
3. Klik Install
4. Aplikasi buka di window terpisah

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error

**Penyebab:**
- File permissions salah
- APP_KEY tidak di-set
- Path di index.php salah

**Solusi:**
1. Cek permissions: `storage` dan `bootstrap/cache` → 777
2. Cek `.env` → APP_KEY harus ada
3. Cek `index.php` → path ke `/laravel/vendor/autoload.php`

### Database Connection Error

**Cek:**
- `.env` → DB_HOST, DB_DATABASE, DB_USERNAME benar
- phpMyAdmin → database sudah diimport
- Database user sudah di-assign ke database

### PWA Tidak Bisa Install

**Penyebab:**
- SSL belum aktif (masih HTTP)

**Solusi:**
1. Tunggu SSL aktif (max 24 jam)
2. Force HTTPS di `.htaccess` (sudah ada di step 6)
3. Test di incognito mode
4. Clear cache browser

### Email Tidak Terkirim

**Cek:**
- `.env` → MAIL_USERNAME dan MAIL_PASSWORD benar
- Gmail App Password masih valid
- Test kirim email manual via PHP

### CSS/JS Tidak Load

**Penyebab:**
- Path asset salah

**Solusi:**
- Di file Blade, gunakan:
  ```php
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  ```
- Jangan gunakan path absolute: `/css/style.css`

---

## 📊 Monitoring & Maintenance

### Check Hosting Resource (di Client Area)

- **Disk Usage:** Unlimited (tapi jangan abuse)
- **Bandwidth:** Unlimited
- **Hits:** Max 50,000/hari (auto reset)
- **Email:** Max 10 email/jam dari script PHP

### Backup Database

**Rutin backup database (via phpMyAdmin):**
1. Login phpMyAdmin
2. Pilih database
3. Tab "Export"
4. Format: SQL
5. Download

### Update Data Buku

**Via phpMyAdmin:**
- Tab "SQL" → jalankan query INSERT/UPDATE
- Atau import file SQL baru

---

## 🎉 Selamat! Website Sudah Online!

**URL Website Anda:**
```
https://perpustakaanparepare.infinityfreeapp.com
```

**Yang Bisa Dilakukan:**
- ✅ Diakses dari mana saja (HP, laptop, tablet)
- ✅ Install PWA sebagai aplikasi
- ✅ Staff scan QR code untuk verifikasi
- ✅ Email otomatis terkirim ke pengunjung
- ✅ Offline mode untuk halaman yang sudah pernah dibuka
- ✅ HTTPS (SSL) - Aman dan dipercaya

**Tidak Perlu:**
- ❌ `php artisan serve` lagi
- ❌ Buka laptop/server lokal
- ❌ Bayar hosting (100% gratis!)

---

## 🚀 Upgrade ke Custom Domain (Opsional)

**Jika mau domain sendiri (misal: `perpustakaanparepare.com`):**

1. **Beli domain** (Niagahoster, Hostinger, Namecheap)
2. **Di Client Area InfinityFree:**
   - Tab "Addon Domains"
   - Add domain Anda
3. **Setting DNS di registrar domain:**
   - A Record: IP dari InfinityFree (lihat di email)
   - CNAME: www → domain utama
4. **Tunggu propagasi** (2-48 jam)
5. **SSL otomatis aktif** untuk domain baru

---

## 📞 Bantuan

**InfinityFree Support:**
- Forum: https://forum.infinityfree.com
- Knowledge Base: https://forum.infinityfree.com/docs

**Laravel Issues:**
- Cek `storage/logs/laravel.log` via File Manager
- Aktifkan debug sementara: `APP_DEBUG=true` di `.env`

---

**🎓 Dibuat untuk: Perpustakaan Keliling Kota Parepare**

**Stack:**
- ✅ InfinityFree Hosting (Gratis)
- ✅ SSL Certificate (Gratis)
- ✅ Laravel 11
- ✅ PWA (Progressive Web App)
- ✅ QR Code System
- ✅ Gmail SMTP Email

**Estimasi Waktu Deploy: 1-2 Jam**
**Biaya: Rp 0,- (100% GRATIS)**

🚀 **Selamat Deploy!**
