# 📧 Konfigurasi SMTP Email

Dokumentasi lengkap konfigurasi SMTP untuk sistem notifikasi email pada aplikasi Perpustakaan Keliling Parepare.

---

## 📋 Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Konfigurasi Environment](#konfigurasi-environment)
3. [Provider SMTP yang Didukung](#provider-smtp-yang-didukung)
4. [Integrasi di Sistem](#integrasi-di-sistem)
5. [Template Email](#template-email)
6. [Alur Pengiriman Email](#alur-pengiriman-email)
7. [Troubleshooting](#troubleshooting)

---

## 📌 Pendahuluan

Sistem ini menggunakan **Laravel Mail** dengan driver SMTP untuk mengirim notifikasi email kepada pengguna. Email dikirim secara otomatis ketika:

- ✅ Reservasi **disetujui** oleh admin
- ❌ Reservasi **ditolak** oleh admin

---

## ⚙️ Konfigurasi Environment

### File: `.env`

```env
# ====================
# KONFIGURASI EMAIL SMTP
# ====================

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-app-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Perpustakaan Keliling Parepare"
```

### Penjelasan Konfigurasi

| Variable | Keterangan | Contoh |
|----------|------------|--------|
| `MAIL_MAILER` | Driver email yang digunakan | `smtp` |
| `MAIL_HOST` | Server SMTP provider | `smtp.gmail.com` |
| `MAIL_PORT` | Port SMTP (587 untuk TLS, 465 untuk SSL) | `587` |
| `MAIL_USERNAME` | Email pengirim / username SMTP | `perpus@gmail.com` |
| `MAIL_PASSWORD` | App Password (bukan password email biasa) | `xxxx xxxx xxxx xxxx` |
| `MAIL_ENCRYPTION` | Jenis enkripsi (`tls` atau `ssl`) | `tls` |
| `MAIL_FROM_ADDRESS` | Alamat email pengirim yang ditampilkan | `perpus@gmail.com` |
| `MAIL_FROM_NAME` | Nama pengirim yang ditampilkan | `Perpustakaan Keliling` |

---

## 📡 Provider SMTP yang Didukung

### 1. Gmail SMTP (Recommended)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-app-password"
MAIL_ENCRYPTION=tls
```

**Langkah Setup Gmail:**
1. Buka [Google Account Security](https://myaccount.google.com/security)
2. Aktifkan **2-Step Verification**
3. Buat **App Password**:
   - Pergi ke Security → App passwords
   - Pilih app: Mail
   - Pilih device: Other (Custom name)
   - Beri nama: "Laravel App"
   - Copy password yang digenerate (16 karakter)
4. Gunakan password tersebut di `MAIL_PASSWORD`

### 2. Mailtrap (Development/Testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

### 3. SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### 4. Mailgun

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.mailgun.org
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
```

---

## 🔗 Integrasi di Sistem

### Struktur File

```
app/
├── Mail/
│   ├── ReservationApproved.php    # Mailable untuk email persetujuan
│   └── ReservationRejected.php    # Mailable untuk email penolakan
├── Http/Controllers/Admin/
│   └── ReservationController.php  # Controller yang mengirim email
└── Helpers/
    └── QRCodeHelper.php           # Generate QR Code untuk attachment

resources/views/emails/
├── reservation-approved.blade.php  # Template email persetujuan
└── reservation-rejected.blade.php  # Template email penolakan

config/
└── mail.php                        # Konfigurasi mail Laravel
```

### Class Mailable

#### 1. ReservationApproved.php

```php
<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ReservationApproved extends Mailable
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservasi Perpustakaan Disetujui - ' . $this->reservation->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-approved',
        );
    }

    public function attachments(): array
    {
        // Attach QR Code untuk verifikasi
        $qrDataUri = \App\Helpers\QRCodeHelper::generateReservationQR($this->reservation);
        // ... generate attachment
    }
}
```

#### 2. ReservationRejected.php

```php
<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Mail\Mailable;

class ReservationRejected extends Mailable
{
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservasi Perpustakaan Ditolak - ' . $this->reservation->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-rejected',
        );
    }
}
```

### Pengiriman Email di Controller

```php
// File: app/Http/Controllers/Admin/ReservationController.php

use App\Mail\ReservationApproved;
use App\Mail\ReservationRejected;
use Illuminate\Support\Facades\Mail;

public function updateStatus(Request $request, Reservation $reservation)
{
    $newStatus = $request->status;
    $reservation->status = $newStatus;
    $reservation->save();

    // Kirim email persetujuan
    if ($newStatus === 'confirmed') {
        Mail::to($reservation->email)->send(new ReservationApproved($reservation));
        Log::info("Approval email sent to {$reservation->email}");
    }
    
    // Kirim email penolakan
    if ($newStatus === 'rejected') {
        Mail::to($reservation->email)->send(new ReservationRejected($reservation));
        Log::info("Rejection email sent to {$reservation->email}");
    }
}
```

---

## 📝 Template Email

### 1. Email Persetujuan (`reservation-approved.blade.php`)

**Fitur:**
- ✅ Header dengan branding perpustakaan
- ✅ Detail reservasi lengkap
- ✅ Informasi FCFS Metrics (Posisi, Waktu Tunggu, TAT)
- ✅ QR Code untuk verifikasi
- ✅ Instruksi kunjungan
- ✅ Informasi kontak

**Data yang dikirim:**
```php
$reservation->full_name        // Nama pemohon
$reservation->email            // Email
$reservation->occupation       // Instansi/Event
$reservation->category         // Kategori layanan
$reservation->reservation_date // Tanggal kunjungan
$reservation->visit_time       // Waktu mulai
$reservation->end_time         // Waktu selesai
$reservation->address          // Alamat/Lokasi
$reservation->queue_position   // Posisi antrian FCFS
$reservation->waiting_time     // Waktu tunggu (menit)
$reservation->turnaround_time  // TAT (menit)
```

### 2. Email Penolakan (`reservation-rejected.blade.php`)

**Fitur:**
- ❌ Notifikasi penolakan
- 📋 Detail reservasi yang ditolak
- 💬 Alasan penolakan (jika ada)
- 📞 Informasi kontak untuk pertanyaan

**Data yang dikirim:**
```php
$reservation->full_name        // Nama pemohon
$reservation->occupation       // Instansi/Event
$reservation->reservation_date // Tanggal yang diminta
$reservation->rejection_reason // Alasan penolakan
```

---

## 🔄 Alur Pengiriman Email

```
┌─────────────────┐
│  Admin Panel    │
│  Kelola Reservasi│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Admin klik      │
│ "Update" status │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────┐
│ ReservationController           │
│ @updateStatus()                 │
│                                 │
│ 1. Update status di database    │
│ 2. Process FCFS (jika approved) │
│ 3. Kirim email notifikasi       │
└────────┬────────────────────────┘
         │
         ▼
    ┌────┴────┐
    │ Status? │
    └────┬────┘
         │
    ┌────┴────┬──────────┐
    ▼         ▼          ▼
┌───────┐ ┌───────┐ ┌───────┐
│Approved│ │Rejected│ │Pending│
└───┬───┘ └───┬───┘ └───────┘
    │         │         (no email)
    ▼         ▼
┌─────────┐ ┌─────────┐
│Mail::to │ │Mail::to │
│->send() │ │->send() │
│Approved │ │Rejected │
└────┬────┘ └────┬────┘
     │           │
     ▼           ▼
┌─────────────────────┐
│    SMTP Server      │
│  (smtp.gmail.com)   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   User Email Inbox  │
│  📧 Notifikasi      │
└─────────────────────┘
```

---

## 🔧 Troubleshooting

### Error: Connection could not be established

**Penyebab:** Server tidak bisa connect ke SMTP server.

**Solusi:**
```bash
# Cek koneksi ke SMTP
telnet smtp.gmail.com 587

# Pastikan port tidak diblokir firewall
# Untuk cloud hosting, buka port 587 di security group
```

### Error: Authentication failed

**Penyebab:** Username/password salah atau menggunakan password biasa (bukan App Password).

**Solusi:**
1. Pastikan menggunakan **App Password**, bukan password email biasa
2. Cek apakah email sudah mengaktifkan 2-Step Verification
3. Generate ulang App Password

### Error: 419 CSRF Token Mismatch

**Penyebab:** Session expired saat mengirim email.

**Solusi:** Sudah ditangani dengan menambahkan try-catch dan logging.

### Testing Email

```bash
# Test kirim email via artisan tinker
php artisan tinker

>>> use App\Mail\ReservationApproved;
>>> use App\Models\Reservation;
>>> use Illuminate\Support\Facades\Mail;

>>> $reservation = Reservation::find(1);
>>> Mail::to('test@example.com')->send(new ReservationApproved($reservation));
```

### Script Test Email

```php
// File: test_send_email.php
<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Mail\ReservationApproved;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

$reservation = Reservation::where('status', 'confirmed')->first();

if ($reservation) {
    Mail::to('your-test-email@gmail.com')->send(new ReservationApproved($reservation));
    echo "Email sent successfully!\n";
} else {
    echo "No confirmed reservation found.\n";
}
```

### Clear Cache Setelah Ubah Konfigurasi

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📊 Log Email

Email yang terkirim dicatat di log Laravel:

```
storage/logs/laravel.log
```

**Contoh log:**
```
[2026-01-13 10:30:45] local.INFO: Approval email sent to user@example.com for reservation #123
[2026-01-13 10:35:12] local.INFO: Rejection email sent to user@example.com for reservation #124
```

---

## ✅ Checklist Konfigurasi

- [ ] Set `MAIL_MAILER=smtp` di `.env`
- [ ] Set `MAIL_HOST` sesuai provider (Gmail: `smtp.gmail.com`)
- [ ] Set `MAIL_PORT=587` untuk TLS
- [ ] Set `MAIL_USERNAME` dengan email pengirim
- [ ] Set `MAIL_PASSWORD` dengan **App Password** (16 karakter)
- [ ] Set `MAIL_ENCRYPTION=tls`
- [ ] Set `MAIL_FROM_ADDRESS` dengan email pengirim
- [ ] Set `MAIL_FROM_NAME` dengan nama yang ditampilkan
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Test kirim email

---

## 📚 Referensi

- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Gmail SMTP Settings](https://support.google.com/mail/answer/7126229)
- [Create App Password Google](https://support.google.com/accounts/answer/185833)

---

*Dokumentasi ini dibuat untuk Sistem Perpustakaan Keliling Parepare*
*Terakhir diupdate: Januari 2026*
