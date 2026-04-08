# 🚀 FCFS Quick Start Guide

## 📋 Checklist Implementasi

### ✅ **Step 1: Run Migration**

```bash
# Jalankan migration untuk menambahkan kolom FCFS
php artisan migrate
```

**Expected Output:**
```
Migration table created successfully.
Migrating: 2025_12_09_000001_add_fcfs_fields_to_reservations
Migrated:  2025_12_09_000001_add_fcfs_fields_to_reservations
```

---

### ✅ **Step 2: Update Existing Reservations**

```bash
# Update reservasi existing dengan arrival_time
php update_fcfs_fields.php
```

**Expected Output:**
```
╔════════════════════════════════════════════════════════════════╗
║       Update Existing Reservations with FCFS Fields           ║
╚════════════════════════════════════════════════════════════════╝

📋 Found 5 reservations without arrival_time

  ✓ ID 1: Contact Person 1 | AT: 2025-11-11 09:00:00
  ✓ ID 2: Contact Person 2 | AT: 2025-11-11 11:30:00
  ...

✅ Update completed successfully!
```

---

### ✅ **Step 3: Test Algorithm**

```bash
# Test FCFS algorithm dengan sample data
php test_fcfs_algorithm.php
```

**Expected Output:**
```
╔════════════════════════════════════════════════════════════════╗
║      FCFS Scheduling Algorithm - Test Simulation              ║
╚════════════════════════════════════════════════════════════════╝

┌──────┬──────┬────────────────┬─────────┬─────────┬──────┬─────────┬─────────┬───────┬───────┐
│ Pos  │  ID  │     Name       │   AT    │   RT    │  BT  │   ST    │   CT    │   WT  │  TAT  │
└──────┴──────┴────────────────┴─────────┴─────────┴──────┴─────────┴─────────┴───────┴───────┘

✅ FCFS Algorithm Test Complete!
```

---

### ✅ **Step 4: Process FCFS Queue**

```bash
# Process untuk tanggal tertentu
php artisan fcfs:process --date=2025-12-15

# Atau process untuk hari ini
php artisan fcfs:process
```

**Expected Output:**
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

---

### ✅ **Step 5: Verify Results**

```bash
# Verifikasi perhitungan FCFS
php verify_fcfs.php
```

**Expected Output:**
```
╔════════════════════════════════════════════════════════════════╗
║              Verify FCFS Processing Results                   ║
╚════════════════════════════════════════════════════════════════╝

📅 Date: 2025-12-15
────────────────────────────────────────────────────────────────
ID    | Name               | AT       | ST       | CT       | Status
────────────────────────────────────────────────────────────────
1     | SDN 1 Parepare     | 08:00:00 | 09:00:00 | 11:00:00 | ✅
2     | SMP Negeri 5       | 08:15:00 | 11:00:00 | 12:30:00 | ✅
...

✅ FCFS verification complete! All calculations are correct.
```

---

## 🔄 **Daily Usage Flow**

### **Scenario 1: User Submit Reservasi**

1. User mengisi form reservasi
2. System auto-record `arrival_time = now()`
3. System set `burst_time = 120` (default 2 jam)
4. Status = `pending`

**No action needed!** ✅

---

### **Scenario 2: Admin Approve Reservasi**

1. Admin buka `/admin/reservations`
2. Admin klik "Approve" pada reservasi
3. System **otomatis** menjalankan FCFS processing
4. System calculate: ST, CT, WT, TAT, Queue Position
5. Email konfirmasi terkirim dengan jadwal

**Automatic!** ✅

---

### **Scenario 3: Manual Processing (Optional)**

Jika perlu re-process atau batch processing:

```bash
# Process specific date
php artisan fcfs:process --date=2025-12-20

# Process multiple dates
php artisan fcfs:process --date=2025-12-20
php artisan fcfs:process --date=2025-12-21
php artisan fcfs:process --date=2025-12-22
```

---

## 📊 **Monitoring & Analytics**

### **Check Queue Status**

```sql
SELECT 
    reservation_date,
    COUNT(*) as total,
    AVG(waiting_time) as avg_wt,
    AVG(turnaround_time) as avg_tat
FROM reservations
WHERE queue_position IS NOT NULL
GROUP BY reservation_date
ORDER BY reservation_date;
```

### **Check Logs**

```bash
# Monitor FCFS processing
tail -f storage/logs/laravel.log | grep FCFS

# Check specific date
cat storage/logs/laravel.log | grep "2025-12-15" | grep FCFS
```

---

## 🐛 **Troubleshooting**

### **Issue: Migration Error**

**Error:** `Table doesn't exist`

**Solution:**
```bash
# Make sure MySQL is running
# Check database connection in .env
php artisan migrate:status
php artisan migrate
```

---

### **Issue: FCFS Not Processing**

**Error:** `No reservations found to process`

**Cause:** No reservations have `arrival_time`

**Solution:**
```bash
# Update existing reservations
php update_fcfs_fields.php

# Then process again
php artisan fcfs:process --date=2025-12-15
```

---

### **Issue: Wrong Calculations**

**Error:** WT or TAT incorrect

**Solution:**
```bash
# Verify calculations
php verify_fcfs.php

# Re-process if needed
php artisan fcfs:process --date=2025-12-15
```

---

## 📁 **File Locations**

```
laravel/
├── app/
│   ├── Console/Commands/
│   │   └── ProcessFCFSQueue.php          # Artisan command
│   ├── Services/
│   │   └── FCFSScheduler.php             # FCFS logic
│   └── Models/
│       └── Reservation.php                # Updated model
├── database/migrations/
│   └── 2025_12_09_000001_add_fcfs_fields_to_reservations.php
├── test_fcfs_algorithm.php                # Algorithm test
├── update_fcfs_fields.php                 # Update existing data
├── verify_fcfs.php                        # Verify results
└── FCFS_ALGORITHM.md                      # Full documentation
```

---

## 🎯 **Next Steps**

1. ✅ **Test in Development**
   - Run all scripts
   - Verify calculations
   - Check logs

2. ✅ **Update Views (Optional)**
   - Show WT/TAT in admin panel
   - Display queue position to users
   - Add FCFS metrics to dashboard

3. ✅ **Schedule Automation (Optional)**
   - Add to Laravel Scheduler
   - Auto-process nightly
   - Send reports

4. ✅ **Deploy to Production**
   - Run migration
   - Update existing data
   - Monitor performance

---

## 📞 **Support**

**Documentation:** `FCFS_ALGORITHM.md`

**Commands:**
- `php artisan fcfs:process --help`
- `php test_fcfs_algorithm.php`
- `php verify_fcfs.php`

**Logs:** `storage/logs/laravel.log`

---

✅ **FCFS Implementation Complete!**

Ready to use! 🚀
