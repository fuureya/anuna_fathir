# 🔄 FCFS (First Come First Served) Scheduling Algorithm

**Tanggal Implementasi:** 9 Desember 2025  
**Status:** ✅ **IMPLEMENTED**

---

## 📋 **Overview**

Implementasi algoritma **First Come First Served (FCFS)** untuk penjadwalan otomatis kunjungan Perpustakaan Keliling Panrita Kota Parepare. Algoritma ini menyusun jadwal berdasarkan urutan waktu kedatangan permintaan reservasi (Arrival Time).

---

## 🎯 **Tujuan**

1. **Penjadwalan Otomatis**: Sistem secara otomatis menghitung jadwal kunjungan berdasarkan urutan kedatangan
2. **Fair Scheduling**: First-come first-served memastikan penjadwalan yang adil
3. **Analisis Performa**: Menghitung Waiting Time (WT) dan Turnaround Time (TAT) untuk evaluasi sistem
4. **Optimasi Waktu**: Menghindari konflik jadwal dan mengoptimalkan penggunaan waktu

---

## 🔢 **Algoritma & Rumus**

### **Parameter FCFS:**

- **AT (Arrival Time)**: Waktu permintaan reservasi masuk ke sistem
- **RT (Requested Time)**: Waktu kunjungan yang diminta oleh user
- **BT (Burst Time)**: Durasi layanan dalam menit (default: 120 menit = 2 jam)
- **ST (Start Time)**: Waktu mulai layanan aktual
- **CT (Completion Time)**: Waktu selesai layanan
- **WT (Waiting Time)**: Waktu tunggu dari submit hingga mulai layanan
- **TAT (Turnaround Time)**: Total waktu dari submit hingga selesai layanan

### **Rumus Perhitungan:**

```
ST = max(AT, previous CT, RT if available)
CT = ST + BT
WT = ST - AT (in minutes)
TAT = CT - AT (in minutes)
```

### **Flowchart:**

```
[Mulai]
  ↓
[Input Data Permintaan Reservasi]
  ↓
[Simpan Data ke Antrian berdasarkan AT]
  ↓
[Apakah Masih Ada Permintaan Reservasi?] ──No──→ [Selesai]
  ↓ Yes
[Proses Perhitungan Jadwal Kunjungan]
  ↓
[Proses Penentuan Waktu Tunggu dan Penyelesaian (WT dan TAT)]
  ↓
[Simpan Jadwal ke Data Base]
  ↓
[Kirim Konfirmasi ke Pengguna]
  ↓
[Kembali ke pengecekan antrian]
```

---

## 🗂️ **Database Schema**

### **Kolom Baru di Tabel `reservations`:**

| Field | Type | Nullable | Default | Description |
|-------|------|----------|---------|-------------|
| `arrival_time` | TIMESTAMP | YES | NULL | Waktu permintaan masuk (AT) |
| `burst_time` | INTEGER | NO | 120 | Durasi layanan dalam menit (BT) |
| `start_time` | TIMESTAMP | YES | NULL | Waktu mulai layanan (ST) |
| `completion_time` | TIMESTAMP | YES | NULL | Waktu selesai layanan (CT) |
| `waiting_time` | INTEGER | YES | NULL | Waktu tunggu dalam menit (WT) |
| `turnaround_time` | INTEGER | YES | NULL | Total waktu proses (TAT) |
| `queue_position` | INTEGER | YES | NULL | Posisi dalam antrian |

### **Migration:**

```bash
php artisan migrate
# Runs: 2025_12_09_000001_add_fcfs_fields_to_reservations.php
```

---

## 🏗️ **Implementasi**

### **1. FCFS Scheduler Service**

**File:** `app/Services/FCFSScheduler.php`

**Methods:**
- `processQueue($date)`: Memproses antrian untuk tanggal tertentu
- `calculateTimes($reservation, $previousCompletionTime)`: Menghitung ST, CT, WT, TAT
- `findNextAvailableSlot($requestedTime, $date, $duration)`: Mencari slot kosong jika RT bentrok
- `getQueuePosition($reservation)`: Mendapatkan posisi antrian

**Key Features:**
- ✅ Sorting berdasarkan `arrival_time` ASC
- ✅ Sequential processing (FCFS order)
- ✅ Auto-calculate all FCFS metrics
- ✅ Transaction support (rollback on error)
- ✅ Logging untuk monitoring

### **2. Artisan Command**

**File:** `app/Console/Commands/ProcessFCFSQueue.php`

**Usage:**

```bash
# Process today's queue
php artisan fcfs:process

# Process specific date
php artisan fcfs:process --date=2025-12-15
```

**Output:**

```
🚀 Processing FCFS queue for date: 2025-12-15

✅ FCFS Processing Complete

┌─────────────────────────────┬───────────────────────┐
│ Metric                      │ Value                 │
├─────────────────────────────┼───────────────────────┤
│ Date                        │ 2025-12-15            │
│ Processed Reservations      │ 5                     │
│ Average Waiting Time        │ 125.5 minutes         │
│ Average Turnaround Time     │ 245.5 minutes         │
└─────────────────────────────┴───────────────────────┘

📊 Successfully processed 5 reservations!
```

### **3. Auto-Trigger saat Approve**

**File:** `app/Http/Controllers/Admin/ReservationController.php`

**Logic:**

```php
// When admin confirms reservation, FCFS automatically processes
if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
    $fcfsScheduler = new \App\Services\FCFSScheduler();
    $fcfsScheduler->processQueue($reservation->reservation_date);
}
```

**Benefits:**
- ✅ Automatic scheduling saat approve
- ✅ Real-time calculation
- ✅ No manual intervention needed

### **4. Capture Arrival Time**

**File:** `app/Http/Controllers/ReservationController.php`

**Logic:**

```php
Reservation::create([
    // ... other fields
    'arrival_time' => now(), // Record submission time
    'burst_time' => 120,     // Default 2 hours
]);
```

---

## 🧪 **Testing**

### **Test 1: Algorithm Verification**

```bash
php test_fcfs_algorithm.php
```

**Expected Output:**

```
╔════════════════════════════════════════════════════════════════════════════════════════╗
║      FCFS (First Come First Served) Scheduling Algorithm - Test Simulation           ║
╚════════════════════════════════════════════════════════════════════════════════════════╝

📋 Testing with 4 sample reservations
📅 Date: 2025-12-09

┌──────┬──────┬──────────────────────┬──────────┬──────────┬──────┬──────────┬──────────┬───────┬───────┐
│ Pos  │  ID  │       Name           │    AT    │    RT    │  BT  │    ST    │    CT    │   WT  │  TAT  │
├──────┼──────┼──────────────────────┼──────────┼──────────┼──────┼──────────┼──────────┼───────┼───────┤
│ 1    │ 1    │ SDN 1 Parepare       │ 08:00:00 │ 09:00:00 │ 120m │ 09:00:00 │ 11:00:00 │ 60m   │ 180m  │
│ 2    │ 2    │ SMP Negeri 5         │ 08:15:00 │ 10:00:00 │ 90m  │ 11:00:00 │ 12:30:00 │ 165m  │ 255m  │
│ 3    │ 3    │ Komunitas Baca       │ 08:30:00 │ 09:30:00 │ 60m  │ 12:30:00 │ 13:30:00 │ 240m  │ 300m  │
│ 4    │ 4    │ Puskesmas Kota       │ 09:00:00 │ 11:00:00 │ 120m │ 13:30:00 │ 15:30:00 │ 270m  │ 390m  │
└──────┴──────┴──────────────────────┴──────────┴──────────┴──────┴──────────┴──────────┴───────┴───────┘

✅ FCFS Algorithm Test Complete!
```

### **Test 2: Database Processing**

```bash
# Process reservations for specific date
php artisan fcfs:process --date=2025-12-15
```

### **Test 3: Manual Verification**

```sql
-- Check calculated FCFS values
SELECT 
    id,
    full_name,
    arrival_time,
    start_time,
    completion_time,
    waiting_time,
    turnaround_time,
    queue_position
FROM reservations
WHERE reservation_date = '2025-12-15'
ORDER BY arrival_time ASC;
```

---

## 📊 **Example Calculation**

### **Sample Data:**

| ID | Name | AT | RT | BT |
|----|------|----|----|-----|
| 1 | SDN 1 | 08:00 | 09:00 | 120 min |
| 2 | SMP 5 | 08:15 | 10:00 | 90 min |
| 3 | Komunitas | 08:30 | 09:30 | 60 min |
| 4 | Puskesmas | 09:00 | 11:00 | 120 min |

### **FCFS Processing:**

**Reservation 1:**
- AT = 08:00
- RT = 09:00 (requested)
- ST = 09:00 (use RT since no previous reservation)
- CT = 09:00 + 120m = 11:00
- WT = 09:00 - 08:00 = 60 minutes
- TAT = 11:00 - 08:00 = 180 minutes

**Reservation 2:**
- AT = 08:15
- RT = 10:00 (requested, but previous CT = 11:00)
- ST = 11:00 (must wait for previous to finish)
- CT = 11:00 + 90m = 12:30
- WT = 11:00 - 08:15 = 165 minutes
- TAT = 12:30 - 08:15 = 255 minutes

**Reservation 3:**
- AT = 08:30
- RT = 09:30 (requested, but previous CT = 12:30)
- ST = 12:30 (must wait)
- CT = 12:30 + 60m = 13:30
- WT = 12:30 - 08:30 = 240 minutes
- TAT = 13:30 - 08:30 = 300 minutes

**Reservation 4:**
- AT = 09:00
- RT = 11:00 (requested, but previous CT = 13:30)
- ST = 13:30 (must wait)
- CT = 13:30 + 120m = 15:30
- WT = 13:30 - 09:00 = 270 minutes
- TAT = 15:30 - 09:00 = 390 minutes

**Statistics:**
- Average WT = (60 + 165 + 240 + 270) / 4 = **183.75 minutes**
- Average TAT = (180 + 255 + 300 + 390) / 4 = **281.25 minutes**

---

## 🚀 **Deployment**

### **Step 1: Run Migration**

```bash
php artisan migrate
```

### **Step 2: Update Existing Reservations (Optional)**

```bash
# Update existing pending/confirmed reservations with arrival_time
php artisan tinker

>>> use App\Models\Reservation;
>>> Reservation::whereNull('arrival_time')
      ->whereIn('status', ['pending', 'confirmed'])
      ->update(['arrival_time' => DB::raw('created_at')]);
```

### **Step 3: Process Existing Queues**

```bash
# Process all dates with pending/confirmed reservations
php artisan fcfs:process --date=2025-12-10
php artisan fcfs:process --date=2025-12-11
# ... etc
```

### **Step 4: Verify Results**

```bash
# Check logs
tail -f storage/logs/laravel.log | grep FCFS

# Query database
SELECT * FROM reservations WHERE queue_position IS NOT NULL;
```

---

## 💡 **Integration dengan Sistem Existing**

### **1. Interval Scheduling Algorithm**

FCFS bekerja **bersamaan** dengan interval scheduling:

- **FCFS**: Assigns `start_time` dan `completion_time` untuk setiap reservasi
- **Interval Scheduling**: Optimizes non-overlapping schedules untuk `mobile_library_schedule`

**Flow:**

```
User Submit → arrival_time recorded
     ↓
Admin Approve → FCFS calculates ST, CT, WT, TAT
     ↓
Admin Generate Schedule → Interval Scheduling creates non-overlapping slots
     ↓
Public View Schedule → Shows optimized schedule
```

### **2. Compatibility**

✅ **Backward Compatible**: Existing reservations without `arrival_time` are skipped
✅ **Non-Intrusive**: FCFS processing doesn't break existing functionality
✅ **Optional**: Can be disabled by not calling `processQueue()`

---

## 📁 **Files Modified/Created**

### **Created Files:**

1. ✅ `database/migrations/2025_12_09_000001_add_fcfs_fields_to_reservations.php`
2. ✅ `app/Services/FCFSScheduler.php`
3. ✅ `app/Console/Commands/ProcessFCFSQueue.php`
4. ✅ `test_fcfs_algorithm.php`

### **Modified Files:**

5. ✅ `app/Models/Reservation.php` - Added FCFS fields to fillable/casts
6. ✅ `app/Http/Controllers/ReservationController.php` - Record arrival_time
7. ✅ `app/Http/Controllers/Admin/ReservationController.php` - Trigger FCFS on approve

---

## 📈 **Performance Metrics**

### **Time Complexity:**

- **processQueue()**: O(n²) - nested loop untuk conflict detection
- **calculateTimes()**: O(1) - simple arithmetic
- **findNextAvailableSlot()**: O(n × m) - n = iterations, m = existing reservations

### **Optimization:**

- ✅ Database transaction untuk consistency
- ✅ Batch processing per date
- ✅ Index pada `arrival_time` dan `reservation_date`

**Recommended Index:**

```sql
CREATE INDEX idx_reservations_fcfs 
ON reservations(reservation_date, arrival_time, status);
```

---

## 🔍 **Troubleshooting**

### **Issue 1: Arrival Time NULL**

**Symptom:** FCFS skips reservations

**Solution:**

```php
// Set arrival_time for existing reservations
Reservation::whereNull('arrival_time')
    ->update(['arrival_time' => DB::raw('NOW()')]);
```

### **Issue 2: High Waiting Time**

**Symptom:** WT > 500 minutes

**Cause:** Too many reservations on same date

**Solution:**

- Limit daily reservations
- Adjust operating hours
- Increase BT efficiency

### **Issue 3: FCFS Not Triggered**

**Symptom:** WT and TAT remain NULL

**Solution:**

```bash
# Manually trigger FCFS
php artisan fcfs:process --date=2025-12-15
```

---

## ✅ **Conclusion**

**Status:** ✅ **PRODUCTION READY**

**Features Implemented:**
- ✅ FCFS algorithm dengan 7 parameter (AT, RT, BT, ST, CT, WT, TAT)
- ✅ Auto-processing saat admin approve
- ✅ Artisan command untuk manual processing
- ✅ Queue position tracking
- ✅ Comprehensive logging
- ✅ Test script untuk verification

**Benefits:**
- ✅ Fair scheduling (first-come first-served)
- ✅ Automated time calculation
- ✅ Performance metrics (WT, TAT)
- ✅ Integration dengan existing interval scheduling

**Tested & Verified:** ✅  
**Ready for Production:** ✅

---

## 📚 **References**

- [FCFS Scheduling Algorithm - Wikipedia](https://en.wikipedia.org/wiki/Scheduling_(computing)#First_come,_first_served)
- Operating System Concepts - Silberschatz, Galvin, Gagne
- CPU Scheduling Algorithms - GeeksforGeeks

---

**Author:** AI Assistant  
**Date:** 9 Desember 2025  
**Version:** 1.0.0
