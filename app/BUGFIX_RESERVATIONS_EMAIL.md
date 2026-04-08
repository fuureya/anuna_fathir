## 🐛 Bug Fix Summary - November 11, 2025

### Issue #1: Halaman "Reservasi Saya" Kosong

**Problem:**
- User baru reservasi tapi tidak muncul di halaman "Reservasi Saya"
- Halaman menampilkan "Belum Ada Reservasi" meskipun sudah ada data

**Root Cause:**
1. Kolom `email` baru ditambahkan ke tabel `reservations`
2. Reservasi lama (ID 1-6) tidak punya email (empty string)
3. Query di `myReservations()` menggunakan logic `orWhere` yang salah:
   ```php
   // SALAH - orWhere di luar closure akan return semua data
   ->where(function($q) use ($user) {
       if ($user && $user->email) {
           $q->where('email', $user->email);
       }
   })
   ->orWhere('phone_number', $user->phone ?? '')
   ```

4. User login dengan email berbeda dari email di reservasi
   - User login: `admin@gmail.com`
   - Reservasi email: `kuluzen@gmail.com`

**Solution:**
1. ✅ Fix query logic di `ReservationController::myReservations()`:
   ```php
   ->where(function($q) use ($user) {
       if ($user && $user->email) {
           $q->where('email', $user->email)
             ->orWhere('phone_number', $user->phone ?? '');
       }
   })
   ```

2. ✅ Fix `orderBy` - ganti `created_at` dengan `id` (karena tabel tidak punya timestamps)

3. ✅ Update reservasi lama dengan email default untuk data consistency

**Test Steps:**
1. Login dengan email yang sesuai dengan reservasi
2. Atau buat reservasi baru dengan email user yang login
3. Akses `/reservations/my-reservations`
4. Seharusnya muncul list reservasi dengan status badge

---

### Issue #2: Email Tidak Terkirim

**Problem:**
- Admin approve reservasi, muncul alert "email telah dikirim"
- Tapi user tidak menerima email di inbox

**Root Cause:**
1. ❌ Gmail SMTP Error 421: "too many connections"
   - Gmail membatasi koneksi SMTP untuk mencegah spam
   - Testing berulang kali menyebabkan limit tercapai

2. ❌ Email template punya field yang salah:
   - `$reservation->phone` → harusnya `$reservation->phone_number`
   - `$reservation->visit_date` → harusnya `$reservation->reservation_date`
   - `$reservation->visitor_count` → field tidak exist
   - `$reservation->purpose` → field tidak exist

**Solution:**
1. ✅ Fix email template `reservation-approved.blade.php`:
   - Ganti semua field dengan nama yang benar sesuai database
   - Tambahkan null check dan Carbon formatting

2. ✅ Improve error handling di `Admin\ReservationController`:
   ```php
   $emailSent = false;
   $emailError = null;
   
   try {
       Mail::to($reservation->email)->send(new ReservationApproved($reservation));
       $emailSent = true;
   } catch (\Exception $e) {
       Log::error('Failed to send email: ' . $e->getMessage());
       $emailError = $e->getMessage();
   }
   
   // Show appropriate message to admin
   if ($emailSent) {
       $message .= ' dan email notifikasi telah dikirim';
   } elseif (str_contains($emailError, '421')) {
       $message .= '. ⚠️ Email gagal (Gmail limit). Tunggu 10 menit.';
   }
   ```

3. ✅ Tambahkan logging untuk debug:
   - Log success: `Log::info('Email sent to ...')`
   - Log error: `Log::error('Failed to send email: ...')`

4. 📝 Created `EMAIL_TROUBLESHOOTING.md` dengan solusi:
   - Tunggu 10 menit (Gmail akan reset limit)
   - Gunakan email queue (recommended untuk production)
   - Ganti ke layanan email transactional (SendGrid, Mailgun, SES)
   - Setup Mailtrap untuk development testing

**Temporary Workaround:**
- Tunggu 10-15 menit sebelum approve reservasi lagi
- Atau ubah MAIL config ke Mailtrap untuk testing

**Production Recommendation:**
```env
# Jangan gunakan Gmail untuk production
# Gunakan layanan email transactional:

# Option 1: SendGrid (100 free emails/day)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key

# Option 2: Setup Queue untuk email async
QUEUE_CONNECTION=database
# Lalu jalankan: php artisan queue:work
```

**Test Email Manually:**
```bash
# Test 1: Send via script
php test_send_email.php

# Test 2: Preview email di browser
# Tambah route:
Route::get('/test-email', function() {
    $reservation = App\Models\Reservation::find(7);
    return new App\Mail\ReservationApproved($reservation);
});
# Akses: http://localhost:8000/test-email

# Test 3: Via Tinker
php artisan tinker
>>> $r = App\Models\Reservation::find(7);
>>> Mail::to('kuluzen@gmail.com')->send(new App\Mail\ReservationApproved($r));
```

---

### Files Modified:

1. **app/Http/Controllers/ReservationController.php**
   - Fix `myReservations()` query logic
   - Fix `orderBy` from `created_at` to `id`

2. **app/Http/Controllers/Admin/ReservationController.php**
   - Improve error handling dan logging
   - Better user feedback untuk email status

3. **resources/views/emails/reservation-approved.blade.php**
   - Fix field names: `phone_number`, `reservation_date`
   - Remove non-existent fields: `visitor_count`, `purpose`
   - Add Carbon formatting dan null checks

4. **New Files:**
   - `EMAIL_TROUBLESHOOTING.md` - Comprehensive email guide
   - `test_send_email.php` - Manual email testing script
   - `debug_my_reservations.php` - Debug query logic

---

### Testing Checklist:

- [ ] Login dengan user yang punya email matching reservation
- [ ] Create reservasi baru dengan email user
- [ ] Check halaman "Reservasi Saya" - should show reservation(s)
- [ ] Tunggu 10 menit dari last email attempt
- [ ] Admin approve reservasi
- [ ] Check logs: `tail -f storage/logs/laravel.log`
- [ ] Check email inbox (dan spam folder)
- [ ] Verify email content tampil dengan benar
- [ ] Test QR code di email dapat di-scan

---

### Status:

- ✅ Issue #1 (My Reservations Empty): **FIXED**
- ⚠️ Issue #2 (Email Not Sent): **FIXED (code)**, waiting for Gmail limit reset
- ✅ Documentation: **COMPLETE**
- ✅ Error Handling: **IMPROVED**
- ✅ Logging: **ADDED**

**Next Action:**
1. Tunggu 10 menit
2. Atau gunakan Mailtrap untuk testing
3. Atau deploy dengan email queue enabled
