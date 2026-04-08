# 📅 FITUR AUTO-SCHEDULE & FILTER JADWAL LEWAT

## 🎯 **Problem Statement**

**Issue #1: Jadwal Tidak Muncul di Jadwal Pusling Admin**
- Saat admin approve reservasi, status berubah ke `confirmed`
- Namun jadwal **tidak otomatis muncul** di halaman `/admin/schedule`
- Harus manual "Generate Schedule" lagi via Preview → Commit

**Issue #2: Jadwal yang Sudah Lewat Masih Muncul**
- Jadwal pusling yang sudah selesai (end_time < now) masih ditampilkan
- Membuat halaman jadwal penuh dengan jadwal lama yang tidak relevan
- User bingung mana jadwal aktif dan mana yang sudah lewat

---

## ✅ **Solusi yang Diimplementasikan**

### **1. Auto-Create MobileLibrarySchedule saat Approve**

**File Modified:** `app/Http/Controllers/Admin/ReservationController.php`

**Perubahan:**
```php
// BEFORE: Hanya FCFS processing
if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
    $fcfsScheduler->processQueue($reservation->reservation_date);
}

// AFTER: FCFS + Auto-create schedule
if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
    $fcfsScheduler->processQueue($reservation->reservation_date);
    
    // 🆕 Auto-create MobileLibrarySchedule
    if ($reservation->visit_time) {
        MobileLibrarySchedule::create([
            'reservation_id' => $reservation->id,
            'scheduled_date' => $reservation->reservation_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}
```

**Benefit:**
- ✅ **Instant visibility**: Jadwal langsung muncul di `/admin/schedule` dan `/schedule`
- ✅ **No manual work**: Tidak perlu "Generate Schedule" lagi
- ✅ **Prevent duplicates**: Check existing schedule sebelum create

---

### **2. Filter Jadwal yang Sudah Lewat (Past Schedules)**

**File Modified:** 
- `app/Http/Controllers/ScheduleController.php` (Public)
- `app/Http/Controllers/Admin/ScheduleController.php` (Admin)

**Perubahan:**

#### **Public Schedule (Users)**
```php
// BEFORE: Tampilkan semua jadwal
$query = MobileLibrarySchedule::query()
    ->orderBy('scheduled_date');

// AFTER: Hanya jadwal upcoming/ongoing
$query = MobileLibrarySchedule::query()
    ->where('end_time', '>=', now()) // 🆕 Filter past schedules
    ->orderBy('scheduled_date');
```

#### **Admin Schedule (dengan opsi show_all)**
```php
// BEFORE: Tampilkan semua jadwal
$schedules = MobileLibrarySchedule::query()
    ->orderByDesc('scheduled_date');

// AFTER: Default upcoming, bisa toggle show_all
$schedules = MobileLibrarySchedule::query()
    ->when(!$showAll, fn($q) => $q->where('end_time', '>=', now()))
    ->orderByDesc('scheduled_date');
```

**Benefit:**
- ✅ **Clean UI**: Hanya jadwal relevan yang ditampilkan
- ✅ **Auto-hide expired**: Jadwal lama otomatis tersembunyi
- ✅ **Admin flexibility**: Admin bisa toggle show all jika perlu review history

---

## 🚀 **Cara Kerja**

### **Flow: Approve Reservasi → Auto-Schedule**

```
1. Admin buka /admin/reservations
   └─ Klik "Approve" pada reservasi

2. Backend (Admin/ReservationController@updateStatus):
   ├─ Update status = 'confirmed'
   ├─ Process FCFS queue (calculate WT, TAT, etc)
   └─ 🆕 Create MobileLibrarySchedule entry
       ├─ reservation_id: [ID reservasi]
       ├─ scheduled_date: [Tanggal reservasi]
       ├─ start_time: [reservation_date + visit_time]
       └─ end_time: [start_time + duration_minutes]

3. Jadwal langsung muncul di:
   ├─ /admin/schedule (Admin panel)
   └─ /schedule (Public view)
```

### **Flow: Filter Jadwal Lewat**

```
User akses /schedule (Public)
   └─ Query: WHERE end_time >= NOW()
       ├─ ✅ Show: Jadwal yang belum selesai
       └─ ❌ Hide: Jadwal yang sudah lewat

Admin akses /admin/schedule
   ├─ Default: WHERE end_time >= NOW()
   └─ Toggle "Show All": Tampilkan semua termasuk history
```

---

## 📊 **Testing Results**

### **Test Script:** `test_auto_schedule.php`

```bash
php test_auto_schedule.php
```

**Output:**
```
✅ AUTO-CREATE SCHEDULE: WORKING!
   - Reservation ID: 1
   - MobileLibrarySchedule created automatically
   
📊 Schedule Statistics:
   - Upcoming/Ongoing: 0
   - Past (Hidden): 5
   - Total: 5

✅ FILTER: WORKING!
```

---

## 🎨 **UI Changes**

### **1. Public Schedule (`/schedule`)**

**Added Info Banner:**
```blade
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
    <p class="text-blue-700">📅 <strong>Menampilkan jadwal yang akan datang dan sedang berlangsung</strong></p>
    <p class="text-sm text-blue-600 mt-1">Jadwal yang sudah selesai akan otomatis disembunyikan</p>
</div>
```

**Before:** Menampilkan semua jadwal (termasuk yang sudah lewat berbulan-bulan)  
**After:** Hanya menampilkan jadwal upcoming/ongoing

---

### **2. Admin Schedule (`/admin/schedule`)**

**Added Toggle Button:**
```blade
@if($showAll)
    <a href="?date={{ $date }}">
        ✅ Menampilkan Semua (Termasuk Lewat)
    </a>
@else
    <a href="?date={{ $date }}&show_all=1">
        📅 Hanya Upcoming
    </a>
@endif
```

**Functionality:**
- **Default:** Hanya upcoming schedules (clean view)
- **Toggle "Show All":** Tampilkan termasuk history (untuk audit/reporting)

---

## 🔍 **Edge Cases Handled**

### **1. Duplicate Prevention**
```php
// Check if schedule already exists
$existingSchedule = MobileLibrarySchedule::where('reservation_id', $reservation->id)->first();

if (!$existingSchedule) {
    // Create new schedule
}
```

**Scenario:** Admin approve → cancel → approve lagi  
**Result:** Tidak create duplicate schedule

---

### **2. Missing visit_time**
```php
if ($reservation->visit_time) {
    // Only create schedule if visit_time is set
}
```

**Scenario:** Reservasi diapprove tapi belum ada visit_time  
**Result:** Skip auto-create, tunggu admin set visit_time dulu

---

### **3. Error Handling**
```php
try {
    MobileLibrarySchedule::create([...]);
    Log::info("Auto-created schedule");
} catch (\Exception $e) {
    Log::error("Failed to create schedule: " . $e->getMessage());
    // Don't fail the status update
}
```

**Scenario:** Database error saat create schedule  
**Result:** Status update tetap berhasil, admin bisa manual generate schedule nanti

---

## 📁 **Files Modified**

### **Controllers:**
1. ✅ `app/Http/Controllers/Admin/ReservationController.php`
   - Added: Auto-create MobileLibrarySchedule on approve
   - Added: Import `MobileLibrarySchedule` model
   - Added: Import `Carbon` for datetime parsing

2. ✅ `app/Http/Controllers/ScheduleController.php`
   - Modified: Added `where('end_time', '>=', now())` filter

3. ✅ `app/Http/Controllers/Admin/ScheduleController.php`
   - Modified: Added `$showAll` parameter
   - Modified: Conditional filter based on `$showAll`

### **Views:**
4. ✅ `resources/views/schedule/index.blade.php`
   - Added: Info banner explaining filter behavior

5. ✅ `resources/views/admin/schedule/index.blade.php`
   - Added: Toggle button for show_all parameter

### **Test Scripts:**
6. ✅ `test_auto_schedule.php`
   - Test auto-create functionality
   - Test filter statistics

---

## 🎯 **Expected Behavior**

### **Scenario 1: Admin Approve Reservasi Baru**
```
1. Admin approve reservasi dengan visit_time = "10:00"
2. System:
   ✅ Update status = confirmed
   ✅ Process FCFS queue
   ✅ Create MobileLibrarySchedule entry
   ✅ Send approval email
3. Result:
   ✅ Jadwal langsung muncul di /admin/schedule
   ✅ Jadwal langsung muncul di /schedule (public)
```

---

### **Scenario 2: User Lihat Jadwal Public**
```
1. User akses /schedule
2. System:
   ✅ Query schedules WHERE end_time >= NOW()
   ✅ Hanya tampilkan jadwal upcoming/ongoing
3. Result:
   ✅ User melihat jadwal yang relevan saja
   ❌ Jadwal lama otomatis tersembunyi
```

---

### **Scenario 3: Admin Review History**
```
1. Admin akses /admin/schedule
2. Click "✅ Menampilkan Semua (Termasuk Lewat)"
3. System:
   ✅ Query ALL schedules (no filter)
4. Result:
   ✅ Admin bisa lihat semua jadwal termasuk history
```

---

## 📝 **Migration Required?**

**NO** - Tidak ada perubahan database schema. Semua fitur menggunakan tabel existing:
- `reservations` (sudah ada)
- `mobile_library_schedule` (sudah ada)

---

## 🚀 **Deployment Checklist**

### **Development:**
- [x] Update controllers
- [x] Update views
- [x] Test auto-create functionality
- [x] Test filter functionality
- [x] Verify no syntax errors (`php artisan route:list`)

### **Production:**
```bash
# 1. Pull latest code
git pull origin main

# 2. Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Test
# - Approve a reservation
# - Check /admin/schedule
# - Check /schedule
```

---

## 🐛 **Troubleshooting**

### **Issue: Jadwal tidak auto-create**
**Symptom:** Admin approve tapi jadwal tidak muncul

**Check:**
```bash
# 1. Check logs
tail -f storage/logs/laravel.log | grep "Auto-created"

# 2. Check database
php artisan tinker
> App\Models\MobileLibrarySchedule::latest()->first();

# 3. Manual test
php test_auto_schedule.php
```

**Solution:**
```php
// Manual create if needed
php artisan tinker
> $r = App\Models\Reservation::find(123);
> App\Models\MobileLibrarySchedule::create([
    'reservation_id' => $r->id,
    'scheduled_date' => $r->reservation_date,
    'start_time' => $r->reservation_date . ' ' . $r->visit_time,
    'end_time' => Carbon::parse($r->reservation_date . ' ' . $r->visit_time)->addMinutes(120),
]);
```

---

### **Issue: Filter tidak bekerja**
**Symptom:** Jadwal lama masih muncul

**Check:**
```bash
# Test query langsung
php artisan tinker
> App\Models\MobileLibrarySchedule::where('end_time', '>=', now())->count();
> App\Models\MobileLibrarySchedule::where('end_time', '<', now())->count();
```

**Expected:**
- Upcoming count: Should match what's shown on /schedule
- Past count: Should be hidden from public view

---

## 📚 **Related Documentation**

- **FCFS Algorithm:** `FCFS_ALGORITHM.md`
- **Schedule Generation:** `BUGFIX_JADWAL_PUSLING.md`
- **Deployment Guide:** `DEPLOYMENT_GUIDE.md`

---

## ✅ **Status**

**Created:** 2025-12-10  
**Tested:** ✅ Working  
**Production Ready:** ✅ Yes  
**Breaking Changes:** ❌ No  

---

**Happy Scheduling! 📅✨**
