# 📚 Book Seeder Documentation

**Created:** 12 November 2025  
**File:** `database/seeders/BookSeeder.php`

---

## 📊 **Statistik Data Buku**

### **Total Buku:** 39 buku

### **Kategori:**
- 📖 **Novel**: 14 buku
- 📚 **Ensiklopedia**: 10 buku  
- 🎨 **Komik**: 10 buku
- 👤 **Biografi**: 5 buku

### **Audience:**
- 👥 **General**: 27 buku (dewasa)
- 👶 **Children**: 8 buku (anak-anak)
- 🎓 **Students**: 4 buku (pelajar)

---

## 📖 **Daftar Buku yang Di-Seed**

### **Fiksi Indonesia:**
1. Laskar Pelangi - Andrea Hirata (20 eksemplar)
2. Bumi Manusia - Pramoedya Ananta Toer (10 eksemplar)
3. Ronggeng Dukuh Paruk - Ahmad Tohari (9 eksemplar)
4. Cantik Itu Luka - Eka Kurniawan (12 eksemplar)
5. Ayat-Ayat Cinta - Habiburrahman El Shirazy (15 eksemplar)
6. Sang Pemimpi - Andrea Hirata (14 eksemplar)
7. Tenggelamnya Kapal Van Der Wijck - Hamka (10 eksemplar)
8. Perahu Kertas - Dee Lestari (18 eksemplar)

### **Fiksi Internasional:**
1. Harry Potter and the Philosopher's Stone - J.K. Rowling (10 eksemplar)
2. To Kill a Mockingbird - Harper Lee (8 eksemplar)
3. 1984 - George Orwell (12 eksemplar)
4. The Little Prince - Antoine de Saint-Exupéry (14 eksemplar)

### **Pengembangan Diri & Motivasi:**
1. Filosofi Teras - Henry Manampiring (22 eksemplar)
2. Atomic Habits - James Clear (25 eksemplar)
3. Mindset: Psikologi Sukses - Carol S. Dweck (16 eksemplar)
4. The 7 Habits of Highly Effective People - Stephen R. Covey (19 eksemplar)
5. Sapiens: A Brief History of Humankind - Yuval Noah Harari (18 eksemplar)

### **Buku Anak-Anak:**
1. Kancil dan Buaya - Tim Penulis Bhuana (30 eksemplar)
2. Si Pitung: Pahlawan Betawi - Tim Penulis Gramedia (20 eksemplar)
3. Petualangan Sherina - Jujur Prananto (15 eksemplar)
4. Keluarga Cemara - Arswendo Atmowiloto (18 eksemplar)

### **Komik & Manga:**
1. Si Juki: Kumpulan Strip - Faza Meonk (25 eksemplar)
2. Naruto Vol. 1 - Masashi Kishimoto (28 eksemplar)
3. One Piece Vol. 1 - Eiichiro Oda (30 eksemplar)
4. Doraemon Vol. 1 - Fujiko F. Fujio (35 eksemplar)

### **Agama & Spiritual:**
1. Al-Quran dan Terjemahan - Kementerian Agama RI (50 eksemplar)
2. Sejarah Peradaban Islam - Badri Yatim (11 eksemplar)
3. Tafsir Ibnu Katsir - Ibnu Katsir (12 eksemplar)
4. Kitab Tauhid - Muhammad bin Abdul Wahhab (20 eksemplar)

### **Sains & Teknologi:**
1. Brief Answers to the Big Questions - Stephen Hawking (13 eksemplar)
2. Homo Deus: Masa Depan Umat Manusia - Yuval Noah Harari (15 eksemplar)
3. Ensiklopedia Biologi - Tim Penulis (7 eksemplar)

### **Ekonomi & Bisnis:**
1. Rich Dad Poor Dad - Robert T. Kiyosaki (28 eksemplar)
2. The Lean Startup - Eric Ries (17 eksemplar)

### **Pelajaran Sekolah:**
1. Matematika untuk SMA - Tim Guru Indonesia (25 eksemplar)
2. Fisika untuk SMA Kelas X - Marthen Kanginan (40 eksemplar)
3. Kimia untuk SMA Kelas X - Michael Purba (40 eksemplar)
4. Bahasa Indonesia untuk SMA - Tim Edukatif (45 eksemplar)
5. Sejarah Indonesia Modern - Ricklefs, M.C. (15 eksemplar)

---

## 🚀 **Cara Menjalankan Seeder**

### **Opsi 1: Seed Semua Tabel**
```bash
php artisan db:seed
```

### **Opsi 2: Seed Hanya Buku**
```bash
php artisan db:seed --class=BookSeeder
```

### **Opsi 3: Fresh Migration + Seed**
```bash
php artisan migrate:fresh --seed
```
⚠️ **WARNING:** Ini akan menghapus semua data dan create ulang!

---

## 🔧 **Customize Seeder**

### **Menambah Buku Baru:**

Edit file `database/seeders/BookSeeder.php`:

```php
[
    'judul' => 'Judul Buku',
    'penulis' => 'Nama Penulis',
    'penerbit' => 'Nama Penerbit',
    'tahun_terbit' => '2024',
    'ISBN' => '9786024512345',
    'kategori' => 'Novel', // Novel, Biografi, Ensiklopedia, Komik
    'genre' => 'Fiction', // Fiction, Non-Fiction, Education, Religion
    'deskripsi' => 'Deskripsi singkat buku...',
    'jumlah_eksemplar' => 10,
    'cover_image' => 'cover-filename.jpg',
    'audience_category' => 'general', // general, children, students
    'created_at' => now(),
],
```

### **Menghapus Data Sebelum Seed:**

Uncomment baris ini di `BookSeeder.php`:

```php
// Clear existing books first (optional)
DB::table('books')->truncate();
```

---

## 📁 **Database Schema**

Tabel `books` memiliki kolom:
- `id` - Primary key
- `judul` - Judul buku
- `penulis` - Nama penulis
- `penerbit` - Nama penerbit
- `tahun_terbit` - Tahun terbit
- `ISBN` - Nomor ISBN
- `kategori` - Kategori buku (Novel, Biografi, Ensiklopedia, Komik)
- `genre` - Genre (Fiction, Non-Fiction, Education, Religion)
- `deskripsi` - Deskripsi singkat
- `jumlah_eksemplar` - Jumlah stok
- `cover_image` - Nama file cover
- `audience_category` - Target audience (general, children, students)
- `created_at` - Timestamp created
- `updated_at` - Timestamp updated

---

## 🧪 **Verifikasi Data**

### **Check Total Books:**
```bash
php verify_books.php
```

### **Via Tinker:**
```bash
php artisan tinker
```
```php
// Count total books
App\Models\Book::count();

// Count by category
App\Models\Book::select('kategori', DB::raw('COUNT(*) as count'))
    ->groupBy('kategori')
    ->get();

// Latest 5 books
App\Models\Book::latest()->take(5)->get(['judul', 'penulis']);
```

### **Via Browser:**
```
http://localhost:8000/books
```

---

## 📝 **Notes**

### **File Cover:**
- Cover images belum ada secara fisik
- Nama file sudah di-define di seeder
- Bisa upload cover nanti via admin panel
- Default placeholder akan ditampilkan jika file tidak ada

### **Stock Management:**
- `jumlah_eksemplar` = total stok buku
- Bisa di-update via admin panel
- Track peminjaman via sistem reservasi

### **ISBN:**
- Semua buku punya ISBN valid
- Format: 13 digit (9786024512345)
- Untuk verifikasi dan katalogisasi

---

## 🎯 **Use Cases**

### **Untuk Perpustakaan Keliling:**
✅ Buku pelajaran (SMA) - stock banyak (40-45)  
✅ Buku anak-anak populer - stock banyak (20-35)  
✅ Novel Indonesia - stock medium (10-20)  
✅ Al-Quran - stock banyak (50)  
✅ Komik populer - stock banyak (25-35)  

### **Untuk Pengunjung:**
✅ Variasi kategori lengkap  
✅ Ada buku anak, remaja, dewasa  
✅ Ada buku pelajaran sekolah  
✅ Ada buku agama  
✅ Ada komik hiburan  

---

## 🚀 **Production Deployment**

1. ✅ Push seeder ke repository
2. ✅ Pull di server production
3. ✅ Run seeder:
   ```bash
   php artisan db:seed --class=BookSeeder
   ```
4. ⚠️ Upload cover images ke `public/covers/`
5. ✅ Verify via admin panel: `/admin/books`

---

## 📚 **Related Files**

- `app/Models/Book.php` - Book model
- `database/migrations/*_create_books_table.php` - Migration
- `app/Http/Controllers/BookController.php` - Public controller
- `app/Http/Controllers/Admin/BookController.php` - Admin controller
- `verify_books.php` - Verification script

---

## ✅ **Status**

**Created:** ✅  
**Tested:** ✅  
**Seeded:** ✅ 39 books  
**Ready for Production:** ✅

---

**Happy Reading! 📚**
