# Email Troubleshooting Guide

## ✅ Konfigurasi Email Sudah Benar

Email sudah dikonfigurasi dengan benar:
- **MAIL_MAILER**: smtp
- **MAIL_HOST**: smtp.gmail.com
- **MAIL_PORT**: 587
- **MAIL_USERNAME**: zamanilham57@gmail.com
- **MAIL_PASSWORD**: olqc wqzv mgqa mvzc (App Password)
- **MAIL_ENCRYPTION**: tls
- **MAIL_FROM**: zamanilham57@gmail.com

## ⚠️ Error "Too Many Connections" (421)

### Penyebab:
Gmail membatasi jumlah koneksi SMTP dalam periode waktu tertentu untuk mencegah spam.

### Solusi:

#### 1. **Tunggu 5-10 Menit**
Gmail akan reset limit setelah beberapa menit. Coba kirim email lagi setelah menunggu.

#### 2. **Gunakan Email Queue (Recommended untuk Production)**

Update `.env`:
```env
QUEUE_CONNECTION=database
```

Jalankan migration untuk queue:
```bash
php artisan queue:table
php artisan migrate
```

Update controller untuk queue email:
```php
// Dari:
Mail::to($reservation->email)->send(new ReservationApproved($reservation));

// Menjadi:
Mail::to($reservation->email)->queue(new ReservationApproved($reservation));
```

Jalankan queue worker:
```bash
php artisan queue:work
```

#### 3. **Tambahkan Delay Antar Email**

Jika mengirim banyak email sekaligus, tambahkan delay:
```php
Mail::to($reservation->email)
    ->later(now()->addSeconds(5), new ReservationApproved($reservation));
```

#### 4. **Increase Gmail Limits**

- Gunakan **Google Workspace** (bukan Gmail gratis) untuk limit lebih besar
- Atau gunakan layanan email transactional seperti:
  - **Mailtrap** (testing)
  - **SendGrid** (free 100 emails/day)
  - **Mailgun** (free 5000 emails/month)
  - **Amazon SES** (murah, $0.10 per 1000 emails)

## 🧪 Testing Email

### Test Manual:
```bash
php test_send_email.php
```

### Test via Tinker:
```php
php artisan tinker

// Test email
$reservation = App\Models\Reservation::find(7);
Mail::to('kuluzen@gmail.com')->send(new App\Mail\ReservationApproved($reservation));
```

### Preview Email di Browser:
```php
// routes/web.php (untuk development saja)
Route::get('/test-email', function() {
    $reservation = App\Models\Reservation::first();
    return new App\Mail\ReservationApproved($reservation);
});
```

Akses: `http://localhost:8000/test-email`

## 📧 Cek Email Terkirim

1. **Cek Inbox** di `kuluzen@gmail.com`
2. **Cek Spam/Junk Folder**
3. **Cek Logs** di `storage/logs/laravel.log`

## 🔍 Debug Error

Tambahkan log di controller:
```php
try {
    Mail::to($reservation->email)->send(new ReservationApproved($reservation));
    Log::info('Email sent successfully to: ' . $reservation->email);
} catch (\Exception $e) {
    Log::error('Email failed: ' . $e->getMessage());
    Log::error('Stack: ' . $e->getTraceAsString());
}
```

## 💡 Solusi Sementara untuk Development

Gunakan **Mailtrap** untuk testing tanpa kirim email asli:

1. Daftar di https://mailtrap.io (gratis)
2. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

3. Email akan masuk ke inbox Mailtrap (tidak ke email asli)

## 📊 Status Saat Ini

- ✅ Konfigurasi SMTP Gmail sudah benar
- ✅ Email template sudah fix (field names corrected)
- ⚠️ Gmail limiting connections (tunggu 5-10 menit)
- ✅ Controller sudah handle error dengan try-catch
- ✅ Success message tetap muncul meski email gagal

## 🚀 Production Recommendation

Untuk production di InfinityFree:

1. **Jangan gunakan Gmail SMTP** (terbatas dan sering blocked)
2. **Gunakan layanan email transactional**:
   - SendGrid (100 free emails/day)
   - Mailgun (5000 free emails/month)
   - Amazon SES (sangat murah)

3. **Setup Queue** untuk email async:
```bash
# Update .env
QUEUE_CONNECTION=database

# Jalankan queue worker di background
nohup php artisan queue:work --daemon &
```

## 📝 Next Steps

1. **Tunggu 10 menit** lalu coba approve reservasi lagi
2. **Cek spam folder** di kuluzen@gmail.com
3. **Pertimbangkan menggunakan Mailtrap** untuk development
4. **Setup queue** sebelum deploy ke production
