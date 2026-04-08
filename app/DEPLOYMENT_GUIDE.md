# 📘 Panduan Deploy Perpustakaan Keliling Parepare

## ✅ Checklist Sebelum Deploy

### 1. Persiapan File
- [ ] Semua file sudah siap (termasuk logo.png dan favicon.png di folder public)
- [ ] Database sudah terisi dengan data admin dan buku

### 2. Pilih Hosting
Rekomendasi hosting untuk Laravel + PWA:

**Hosting Indonesia:**
- **Niagahoster** - Mulai 10rb/bulan (Baby Plan)
- **Hostinger** - Mulai 15rb/bulan
- **Dewaweb** - Cloud hosting
- **IDCloudHost** - Support Laravel

**Gratis (untuk testing):**
- **InfinityFree** - Gratis unlimited
- **000WebHost** - Gratis 300MB

⚠️ **WAJIB: Hosting harus support SSL (HTTPS)** untuk PWA!

---

## 🚀 Langkah Deploy

### Step 1: Upload File ke Hosting

**Via cPanel File Manager atau FTP:**
1. Compress folder `laravel` menjadi ZIP
2. Upload ke folder `public_html` (atau `www`, `htdocs`)
3. Extract file ZIP
4. Pindahkan isi folder `public` ke root `public_html`
5. Folder struktur akhir:
   ```
   public_html/
   ├── index.php (dari folder public)
   ├── logo.png
   ├── favicon.png
   ├── manifest.json
   ├── service-worker.js
   ├── offline.html
   ├── css/
   ├── images/
   ├── app/ (folder Laravel di luar public_html)
   ├── bootstrap/
   ├── config/
   ├── resources/
   └── ...
   ```

### Step 2: Konfigurasi Database

**Di cPanel phpMyAdmin:**
1. Buat database baru (misal: `u123456_perpustakaan`)
2. Buat user database
3. Import file `project_web.sql` ke database baru
4. Catat: nama database, username, password

### Step 3: Update File .env

**Edit file `.env` di hosting:**
```env
APP_URL=https://namadomain.com  # ⚠️ GANTI!

DB_DATABASE=u123456_perpustakaan  # ⚠️ GANTI!
DB_USERNAME=u123456_user  # ⚠️ GANTI!
DB_PASSWORD=password123  # ⚠️ GANTI!
```

### Step 4: Generate APP_KEY

**Via Terminal SSH (jika ada) atau cPanel Terminal:**
```bash
php artisan key:generate
```

**Atau manual:**
1. Buka https://generate-random.org/laravel-key-generator
2. Copy key yang dihasilkan
3. Paste ke `.env` → `APP_KEY=base64:...`

### Step 5: Set Permissions (PENTING!)

**Via Terminal SSH:**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 777 storage/framework
chmod -R 777 storage/logs
chmod -R 755 public/covers
chmod -R 755 public/uploads
```

**Via cPanel File Manager:**
- Klik kanan folder → Change Permissions
- storage: 755
- bootstrap/cache: 755
- storage/framework: 777
- storage/logs: 777

### Step 6: Update index.php (jika perlu)

**Edit `public_html/index.php`:**
Sesuaikan path jika folder Laravel di luar public_html:
```php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

### Step 7: Optimize Laravel

**Via Terminal:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 8: Aktifkan SSL (HTTPS)

**Di cPanel → SSL/TLS:**
1. Pilih "Let's Encrypt SSL" (gratis)
2. Centang domain
3. Install Certificate
4. Force HTTPS di `.htaccess`:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 🧪 Testing Setelah Deploy

### 1. Test Website
- [ ] Buka https://namadomain.com
- [ ] Test login admin dan user
- [ ] Test reservasi

### 2. Test Email
- [ ] Approve reservasi sebagai admin
- [ ] Cek inbox email → harus ada email approval + QR code

### 3. Test QR Code
- [ ] Buka email approval
- [ ] Scan QR code atau klik link verifikasi
- [ ] Pastikan muncul detail reservasi

### 4. Test PWA Installation

**Android (Chrome):**
1. Buka https://namadomain.com di Chrome
2. Tunggu muncul tombol "Install Aplikasi" (bottom-right)
3. Klik → Install
4. Icon muncul di Home Screen
5. Buka dari icon → fullscreen tanpa address bar ✅

**iPhone (Safari):**
1. Buka https://namadomain.com di Safari
2. Tap tombol Share (kotak dengan panah ke atas)
3. Scroll → "Add to Home Screen"
4. Tap "Add"
5. Icon muncul di Home Screen ✅

**Desktop (Chrome/Edge):**
1. Buka https://namadomain.com
2. Klik icon install di address bar (kanan)
3. Klik "Install"
4. Aplikasi terbuka di window terpisah ✅

### 5. Test Offline Mode
- [ ] Buka website → install PWA
- [ ] Disconnect WiFi/data
- [ ] Buka PWA dari icon
- [ ] Halaman yang pernah dibuka masih bisa ditampilkan
- [ ] Halaman baru muncul "Tidak Ada Koneksi" ✅

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error
**Penyebab:**
- Permission storage salah
- APP_KEY belum diset
- File .env salah

**Solusi:**
```bash
chmod -R 777 storage
php artisan key:generate
php artisan config:clear
```

### PWA Tidak Bisa Diinstall
**Penyebab:**
- SSL belum aktif (masih HTTP)
- Service Worker tidak terdaftar

**Solusi:**
1. Pastikan pakai HTTPS (kunci hijau di address bar)
2. Buka DevTools → Application → Service Workers
3. Refresh halaman

### Email Tidak Terkirim
**Cek:**
- `.env` → MAIL_USERNAME dan MAIL_PASSWORD benar
- Gmail App Password masih valid
- Hosting tidak block port 587

### QR Code Tidak Muncul di Email
**Cek:**
- Library endroid/qr-code terinstall
- Jalankan: `composer require endroid/qr-code`

---

## 📊 Monitoring

### Setelah Deploy, Monitor:
1. **Storage space** - Folder covers dan uploads
2. **Database size** - Reservasi dan reviews
3. **Email quota** - Gmail SMTP limit 500 email/hari
4. **SSL expiry** - Let's Encrypt expire 90 hari (auto-renew)

---

## 🎉 Setelah Deploy Sukses

Website Anda bisa:
- ✅ Diakses dari HP/tablet/laptop mana saja
- ✅ Diinstall sebagai aplikasi (PWA)
- ✅ Digunakan petugas untuk scan QR code
- ✅ Mengirim email otomatis ke pengunjung
- ✅ Bekerja offline (halaman yang sudah pernah dibuka)

**Tidak perlu buka `php artisan serve` lagi!** 🚀

---

## 📞 Support

Jika ada masalah:
1. Cek error log: `storage/logs/laravel.log`
2. Aktifkan debug sementara: `.env` → `APP_DEBUG=true`
3. Cek browser console untuk error JavaScript
4. Test di incognito mode (cache issue)

---

**Dibuat untuk: Perpustakaan Keliling Kota Parepare**
**Teknologi: Laravel 11 + PWA + QR Code + Email SMTP**
