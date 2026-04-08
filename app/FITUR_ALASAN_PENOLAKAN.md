# 📝 Fitur Alasan Penolakan Reservasi

## 📋 Deskripsi
Fitur ini memungkinkan admin untuk memberikan alasan ketika menolak reservasi, sehingga user dapat memahami mengapa reservasi mereka ditolak. Alasan penolakan akan ditampilkan di halaman "Reservasi Saya" dan dikirim melalui email.

## 🎯 Use Case
Saat ada 2 atau lebih reservasi dengan waktu yang sama dan masih berstatus "pending", admin hanya dapat menerima salah satu reservasi. Untuk reservasi yang ditolak, admin wajib memberikan alasan penolakan agar user mengerti dan dapat membuat reservasi baru di waktu yang berbeda.

## ✨ Fitur Utama

### 1. **Modal Dialog Konfirmasi** (Admin)
- Ketika admin memilih status "Tolak" pada reservasi, akan muncul modal dialog
- Modal meminta admin untuk menuliskan alasan penolakan (maksimal 500 karakter)
- Terdapat character counter yang menampilkan jumlah karakter (X/500)
- Tombol "Konfirmasi" disabled sampai alasan diisi
- Tombol "Batal" akan mereset status kembali ke "Terima"

### 2. **Email Notifikasi Penolakan**
- Email otomatis dikirim ke user ketika reservasi ditolak
- Email berisi:
  - Status penolakan dengan tema merah
  - Detail reservasi (nama, tanggal, waktu, lokasi, dll)
  - **Alasan penolakan** dalam kotak kuning yang mencolok
  - Langkah-langkah selanjutnya untuk user
  - Tombol CTA untuk membuat reservasi baru
- Template: `resources/views/emails/reservation-rejected.blade.php`

### 3. **Tampilan di Halaman User**
- User dapat melihat alasan penolakan di halaman "Reservasi Saya"
- Alasan ditampilkan dalam kotak kuning dengan ikon peringatan
- Styling konsisten dengan email notification
- Hanya muncul untuk reservasi dengan status "Ditolak" yang memiliki alasan

## 🛠️ Implementasi Teknis

### Database
**Migration:** `2025_11_11_124247_add_rejection_reason_to_reservations_table.php`
```php
$table->text('rejection_reason')->nullable()->after('status');
```

**Model:** `app/Models/Reservation.php`
```php
protected $fillable = [
    // ... existing fields
    'rejection_reason',
];
```

### Backend

**Controller:** `app/Http/Controllers/Admin/ReservationController.php`

**Validation:**
```php
$validated = $request->validate([
    'status' => 'required|in:pending,confirmed,rejected',
    'rejection_reason' => 'nullable|string|max:500',
]);
```

**Logic:**
- Jika status = "rejected": simpan `rejection_reason` dan kirim email `ReservationRejected`
- Jika status = "confirmed": kirim email `ReservationApproved`
- Jika status berubah dari "rejected" ke "confirmed/pending": hapus `rejection_reason`

**Email Sending:**
```php
if ($reservation->wasChanged('status')) {
    if ($reservation->status === 'rejected') {
        // Kirim email penolakan
        Mail::to($reservation->email)->send(new ReservationRejected($reservation));
    } elseif ($reservation->status === 'confirmed') {
        // Kirim email persetujuan
        Mail::to($reservation->email)->send(new ReservationApproved($reservation));
    }
}
```

**Error Handling:**
- Deteksi Gmail rate limit (error 421)
- Log error ke `storage/logs/laravel.log`
- Tetap update status meski email gagal
- Pesan sukses menyebutkan jenis email yang dikirim

### Frontend

#### Admin Interface
**File:** `resources/views/admin/reservations/index.blade.php`

**Modal HTML:**
```html
<div id="rejection-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3>Konfirmasi Penolakan</h3>
        <p>Anda akan menolak reservasi: <strong id="modal-reservation-name"></strong></p>
        <label>Alasan Penolakan (wajib):</label>
        <textarea id="modal-rejection-reason" maxlength="500" rows="4"></textarea>
        <div id="char-count">0/500</div>
        <div class="modal-actions">
            <button id="modal-cancel">Batal</button>
            <button id="modal-confirm" disabled>Konfirmasi Penolakan</button>
        </div>
    </div>
</div>
```

**JavaScript Logic:**
1. Event listener pada select status
2. Jika status = "rejected": tampilkan modal
3. Character counter update real-time
4. Validasi: tombol konfirmasi disabled jika textarea kosong
5. Submit: copy alasan ke hidden textarea dan submit form
6. Cancel: reset status ke "Terima"

#### User Interface
**File:** `resources/views/reservations/my-reservations.blade.php`

**Display Block:**
```blade
@if($reservation->status === 'rejected' && $reservation->rejection_reason)
    <div class="rejection-reason-box">
        <div class="reason-header">
            <svg>...</svg>
            <strong>Alasan Penolakan:</strong>
        </div>
        <p class="reason-text">{{ $reservation->rejection_reason }}</p>
    </div>
@endif
```

**CSS Styling:**
```css
.rejection-reason-box {
    background: #fef3c7;        /* Yellow background */
    border: 1px solid #fbbf24;  /* Golden border */
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
}

.reason-header {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #92400e;              /* Dark brown text */
    margin-bottom: 10px;
}

.reason-text {
    color: #78350f;              /* Brown text */
    font-size: 14px;
    line-height: 1.6;
    padding-left: 28px;
}
```

### Email Template
**File:** `resources/views/emails/reservation-rejected.blade.php`

**Struktur:**
1. **Header** (merah): "❌ Reservasi Perpustakaan Ditolak"
2. **Status Badge**: "✗ DITOLAK" dengan background merah
3. **Detail Section**: 6 field informasi reservasi
4. **Reason Box** (kuning): Menampilkan alasan penolakan
5. **Info Box** (biru): 4 langkah yang bisa dilakukan user
6. **CTA Button**: Link ke form reservasi baru

**Key HTML:**
```html
<div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
    <p style="font-weight: bold; color: #92400e; margin: 0 0 10px 0;">
        📝 Alasan Penolakan:
    </p>
    <p style="color: #78350f; margin: 0; line-height: 1.6;">
        {{ $reservation->rejection_reason }}
    </p>
</div>
```

## 📊 Workflow Lengkap

### Scenario: Admin Menolak Reservasi

1. **Admin membuka halaman Manage Reservations**
2. **Admin menemukan 2 reservasi pending dengan waktu sama:**
   - Reservasi A: Perpustakaan Keliling ke SDN 1 (14:00-16:00)
   - Reservasi B: Perpustakaan Keliling ke SDN 2 (14:00-16:00)

3. **Admin memilih untuk menerima Reservasi A:**
   - Klik dropdown status → pilih "Terima"
   - Klik "Update Status"
   - Email persetujuan terkirim ke user A

4. **Admin menolak Reservasi B:**
   - Klik dropdown status → pilih "Tolak"
   - **Modal muncul** meminta alasan penolakan
   - Admin mengetik: *"Waktu bentrok dengan reservasi lain yang sudah dikonfirmasi untuk SDN 1 pada 14:00-16:00. Silakan pilih waktu lain."*
   - Character counter menampilkan: 139/500
   - Klik "Konfirmasi Penolakan"
   - Form submit otomatis

5. **System Processing:**
   - Database: `status` = "rejected", `rejection_reason` = alasan yang ditulis
   - Email: Kirim `ReservationRejected` ke email user B
   - Success message: "Status berhasil diupdate. Email penolakan telah dikirim."

6. **User B menerima notifikasi:**
   - **Email masuk** dengan template penolakan
   - Email menampilkan alasan dengan jelas dalam kotak kuning
   - User membaca alasan dan memahami kenapa ditolak

7. **User B cek "Reservasi Saya":**
   - Status badge menampilkan "❌ Ditolak"
   - **Kotak kuning muncul** di bawah detail reservasi
   - Menampilkan alasan: "Waktu bentrok dengan reservasi lain..."
   - User bisa membuat reservasi baru dengan waktu berbeda

## 🔒 Validasi & Keamanan

### Server-Side Validation
- **Maksimal 500 karakter** untuk rejection_reason
- **Nullable** - alasan hanya wajib saat status = rejected
- **String validation** untuk mencegah injection

### Client-Side Validation (JavaScript)
- Modal tidak bisa di-submit tanpa mengisi alasan
- Character counter mencegah input lebih dari 500 karakter
- Form validation mencegah submit jika textarea kosong

### Error Handling
- **Email gagal terkirim**: Status tetap update, error di-log
- **Gmail rate limit**: Terdeteksi dan di-log dengan pesan khusus
- **Network error**: Ditangani dengan graceful degradation

## 📧 Email Configuration

**File:** `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=zamanilham57@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=zamanilham57@gmail.com
MAIL_FROM_NAME="Perpustakaan Keliling"
```

**Note:** Gunakan Gmail App Password, bukan password akun biasa.

## 🎨 Design System

### Color Palette
- **Rejection Theme**: Red (#dc2626, #fee2e2)
- **Reason Box**: Yellow/Amber (#fef3c7, #fbbf24, #92400e, #78350f)
- **Info Box**: Blue (#dbeafe, #3b82f6, #1e3a8a)
- **Success Theme**: Green (#10b981, #d1fae5)

### Typography
- **Headers**: 16-24px, bold
- **Body**: 14-15px, medium
- **Labels**: 12px, regular
- **Reason Text**: 14px, line-height 1.6

### Spacing
- **Padding**: 15px (boxes), 10px (headers)
- **Margin**: 15px (vertical), 10px (gaps)
- **Border Radius**: 8px (boxes), 6px (buttons)

## 🧪 Testing Checklist

### Admin Side
- [ ] Modal muncul saat pilih status "Tolak"
- [ ] Character counter berfungsi (0/500)
- [ ] Tombol konfirmasi disabled jika textarea kosong
- [ ] Tombol konfirmasi enabled setelah ketik alasan
- [ ] Tombol "Batal" reset status ke "Terima"
- [ ] Tombol "Konfirmasi" submit form dengan alasan
- [ ] Success message menyebutkan "email penolakan"
- [ ] Alasan tersimpan di database
- [ ] Email terkirim ke user

### User Side
- [ ] Email penolakan masuk ke inbox
- [ ] Email menampilkan alasan dalam kotak kuning
- [ ] Halaman "Reservasi Saya" menampilkan status "❌ Ditolak"
- [ ] Kotak alasan penolakan muncul di bawah detail
- [ ] Alasan ditampilkan dengan format yang benar
- [ ] User dapat membuat reservasi baru

### Edge Cases
- [ ] Reservasi tanpa email: Log error, tidak crash
- [ ] Gmail rate limit: Error di-log, status tetap update
- [ ] Alasan > 500 karakter: Di-trim atau ditolak
- [ ] Reservasi lama tanpa alasan: Tidak error (nullable)
- [ ] Status berubah dari rejected ke confirmed: Alasan terhapus

## 📝 Contoh Alasan Penolakan yang Baik

1. **Bentrok waktu:**
   > "Waktu bentrok dengan reservasi lain yang sudah dikonfirmasi untuk SDN 1 pada 14:00-16:00. Silakan pilih waktu lain yang tersedia."

2. **Dokumen tidak lengkap:**
   > "Surat permohonan tidak dapat dibaca dengan jelas. Mohon upload ulang surat permohonan yang lebih jelas atau dalam format PDF."

3. **Lokasi tidak terjangkau:**
   > "Lokasi terlalu jauh dari rute perpustakaan keliling (>50km). Mohon pilih lokasi alternatif yang lebih dekat atau hubungi kami untuk konsultasi."

4. **Waktu tidak sesuai:**
   > "Waktu yang dipilih di luar jam operasional perpustakaan keliling (08:00-16:00). Silakan pilih waktu antara 08:00-16:00."

5. **Kapasitas penuh:**
   > "Kuota kunjungan untuk tanggal tersebut sudah penuh. Silakan pilih tanggal lain atau hubungi kami untuk reschedule."

## 🔧 Maintenance

### Database Cleanup (Optional)
Jika ingin menghapus alasan penolakan untuk reservasi yang sudah lama:

```sql
-- Hapus alasan penolakan untuk reservasi > 6 bulan yang lalu
UPDATE reservations 
SET rejection_reason = NULL 
WHERE status = 'rejected' 
AND reservation_date < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Log Monitoring
Check error logs untuk email yang gagal terkirim:

```bash
tail -f storage/logs/laravel.log | grep "Failed to send"
```

## 📚 Related Files

**Backend:**
- `app/Models/Reservation.php`
- `app/Http/Controllers/Admin/ReservationController.php`
- `app/Mail/ReservationRejected.php`
- `database/migrations/2025_11_11_124247_add_rejection_reason_to_reservations_table.php`

**Frontend:**
- `resources/views/admin/reservations/index.blade.php`
- `resources/views/reservations/my-reservations.blade.php`
- `resources/views/emails/reservation-rejected.blade.php`

**Configuration:**
- `.env` (MAIL_* settings)

## 🎉 Kesimpulan

Fitur ini meningkatkan transparansi dan komunikasi antara admin dan user. User tidak lagi bingung kenapa reservasi mereka ditolak, dan dapat segera mengambil tindakan yang tepat (misalnya membuat reservasi baru di waktu lain). Admin juga lebih mudah mengelola konflik jadwal dengan memberikan penjelasan yang jelas.

**Benefits:**
- ✅ User experience lebih baik (transparency)
- ✅ Mengurangi pertanyaan follow-up dari user
- ✅ Admin dapat berkomunikasi secara efisien
- ✅ Audit trail untuk keputusan penolakan
- ✅ Professional & user-friendly
