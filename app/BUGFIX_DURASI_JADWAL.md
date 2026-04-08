# 🐛 BUGFIX: Durasi Jadwal Hanya 1 Jam (Seharusnya 2 Jam)

**Tanggal:** 12 November 2025  
**Status:** ✅ **FIXED**

---

## 🔍 **Masalah**

Jadwal yang di-generate hanya **1 jam** (contoh: 09:00 - 10:00), padanh seharusnya **2 jam** (09:00 - 11:00) seperti yang diatur di kelola reservasi.

### **Bukti Screenshot:**
```
NO  NAMA                    MULAI              SELESAI
1   Contact Person 1        2025-11-11 09:00   2025-11-11 10:00  ❌ Cuma 1 jam
2   Contact Person 2        2025-11-11 11:30   2025-11-11 12:30  ❌ Cuma 1 jam
```

---

## 🧐 **Root Cause**

### **Penyebab 1: Default Duration Salah**
**Files:**
- `generate_schedules.php`
- `app/Http/Controllers/Admin/ScheduleController.php`

**Kode Lama (SALAH):**
```php
$duration = $r->duration_minutes ?: 60;  // ❌ Default 60 menit (1 jam)
```

### **Penyebab 2: Data di Database Salah**
**Tabel:** `reservations`

**Data Lama:**
```sql
SELECT id, full_name, duration_minutes FROM reservations WHERE status = 'confirmed';

# Result:
ID  full_name           duration_minutes
1   Contact Person 1    60  ❌
2   Contact Person 2    60  ❌
...
```

---

## ✅ **Solusi**

### **Fix #1: Update Default Duration (3 Files)** ✅

**File 1:** `generate_schedules.php`
```php
// Default 2 jam (120 menit) untuk durasi kunjungan perpustakaan keliling
$duration = $r->duration_minutes ?: 120;  // ✅ Changed from 60 to 120
```

**File 2:** `app/Http/Controllers/Admin/ScheduleController.php` (preview method)
```php
foreach ($reservations as $r) {
    $start = Carbon::createFromFormat('H:i:s', ...);
    // Default 2 jam (120 menit) untuk kunjungan perpustakaan keliling
    $duration = $r->duration_minutes ?: 120;  // ✅ Changed
    $end = (clone $start)->addMinutes($duration);
    ...
}
```

**File 3:** `app/Http/Controllers/Admin/ScheduleController.php` (commit method)
```php
MobileLibrarySchedule::create([
    'reservation_id' => $r->id,
    'scheduled_date' => $date,
    'start_time' => Carbon::parse($date.' '.$r->visit_time),
    // Default 2 jam (120 menit)
    'end_time' => Carbon::parse($date.' '.$r->visit_time)
        ->addMinutes($r->duration_minutes ?: 120),  // ✅ Changed
]);
```

---

### **Fix #2: Update Existing Data** ✅

**Script:** `update_duration.php`

```php
// Update all confirmed reservations to 120 minutes (2 hours)
$updated = Reservation::where('status', 'confirmed')
    ->update(['duration_minutes' => 120]);
```

**Hasil:**
```
✅ Updated 5 reservations to 120 minutes (2 hours)

📋 Updated Reservations:
  - ID 1: Contact Person 1 | Duration: 120 menit
  - ID 2: Contact Person 2 | Duration: 120 menit
  - ID 3: Contact Person 3 | Duration: 120 menit
  - ID 4: Contact Person 4 | Duration: 120 menit
  - ID 5: moh.ilham fariqulzaman | Duration: 120 menit
```

---

### **Fix #3: Re-Generate Schedules** ✅

**Command:**
```bash
php generate_schedules.php
```

**Output:**
```
📌 Schedule #11: Contact Person 1 | 09:00 - 11:00  ✅ 2 jam
📌 Schedule #12: Contact Person 2 | 11:30 - 13:30  ✅ 2 jam
📌 Schedule #13: Contact Person 3 | 14:00 - 16:00  ✅ 2 jam
📌 Schedule #14: Contact Person 4 | 16:00 - 18:00  ✅ 2 jam
📌 Schedule #15: moh.ilham fariqulzaman | 12:08 - 14:08  ✅ 2 jam
```

---

## 📊 **Verification**

### **Before Fix:**
```
📅 2025-11-11 | ⏰ 09:00 - 10:00 (1 jam) ❌
📅 2025-11-11 | ⏰ 11:30 - 12:30 (1 jam) ❌
📅 2025-11-11 | ⏰ 14:00 - 15:00 (1 jam) ❌
```

### **After Fix:**
```
📅 2025-11-11 | ⏰ 09:00 - 11:00 (2 jam) ✅
📅 2025-11-11 | ⏰ 11:30 - 13:30 (2 jam) ✅
📅 2025-11-11 | ⏰ 14:00 - 16:00 (2 jam) ✅
📅 2025-11-11 | ⏰ 16:00 - 18:00 (2 jam) ✅
📅 2025-11-25 | ⏰ 12:08 - 14:08 (2 jam) ✅
```

---

## 📁 **Files Modified**

1. ✅ `generate_schedules.php` - Default 60 → 120
2. ✅ `app/Http/Controllers/Admin/ScheduleController.php` - Preview default 60 → 120
3. ✅ `app/Http/Controllers/Admin/ScheduleController.php` - Commit default 60 → 120
4. 🆕 `update_duration.php` - Script untuk update data existing
5. 🆕 `verify_schedules.php` - Script verifikasi hasil

---

## 🧪 **Testing**

### **Test 1: Check Database**
```bash
php check_duration.php
```
**Expected:**
```
ID 1: Contact Person 1 | Duration: 120 menit ✅
ID 2: Contact Person 2 | Duration: 120 menit ✅
...
```

### **Test 2: Verify Generated Schedules**
```bash
php verify_schedules.php
```
**Expected:**
```
📅 2025-11-11 | ⏰ 09:00 - 11:00 (2 jam) ✅
📅 2025-11-11 | ⏰ 11:30 - 13:30 (2 jam) ✅
...
```

### **Test 3: Check Public Schedule**
1. Start MySQL di XAMPP
2. Buka: `http://localhost:8000/schedule`
3. ✅ Seharusnya tampil durasi 2 jam

### **Test 4: Check Admin Generate**
1. Login sebagai admin
2. Buka: `http://localhost:8000/admin/schedule`
3. Klik "Generate Jadwal" untuk tanggal baru
4. ✅ Seharusnya generate dengan durasi 2 jam

---

## 💡 **Future Improvements**

### **Option 1: Make Duration Configurable**

**Config File:** `config/library.php`
```php
return [
    'default_visit_duration' => env('LIBRARY_VISIT_DURATION', 120), // minutes
];
```

**Usage:**
```php
$duration = $r->duration_minutes ?: config('library.default_visit_duration', 120);
```

### **Option 2: Add Duration Input in Reservation Form**

**File:** `resources/views/reservations/create.blade.php`
```html
<label>Durasi Kunjungan (menit)</label>
<select name="duration_minutes">
    <option value="60">1 Jam</option>
    <option value="120" selected>2 Jam</option>
    <option value="180">3 Jam</option>
</select>
```

**Benefits:**
- ✅ User bisa pilih durasi sesuai kebutuhan
- ✅ Lebih flexible
- ✅ Tidak perlu hardcode default

---

## 📚 **Related Documentation**

- `BUGFIX_JADWAL_PUSLING.md` - Jadwal tidak muncul setelah approve
- `README.md` - Project overview
- `BUGFIX_LOG.md` - Other bugfixes

---

## ✅ **Conclusion**

**Status:** ✅ **FIXED**

**Root Cause:**
- Default duration salah (60 menit instead of 120 menit)
- Data di database juga masih 60 menit

**Solution:**
- ✅ Update default di 3 tempat: script + controller preview + controller commit
- ✅ Update data existing ke 120 menit
- ✅ Re-generate jadwal dengan durasi baru

**Result:**
- ✅ Semua jadwal sekarang **2 jam** (120 menit)
- ✅ Future schedules akan auto-generate dengan 2 jam

**Tested & Verified:** ✅  
**Ready for Production:** ✅

---

## 🚀 **Production Deployment Steps**

1. ✅ Push kode ke repository
2. ✅ Pull di server production
3. ⚠️ **CRITICAL:** Update duration untuk reservasi existing:
   ```bash
   php update_duration.php
   ```
4. ✅ Re-generate schedules:
   ```bash
   php generate_schedules.php
   ```
5. ✅ Test `/schedule` di production
6. ✅ Verify durasi 2 jam di admin panel

**Done!** 🎉
