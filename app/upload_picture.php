<?php
session_start();
include 'db_con.php';  // Pastikan Anda sudah melakukan koneksi ke database

// Cek apakah file diunggah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    // Dapatkan informasi file yang diunggah
    $file = $_FILES['profile_picture'];

    // Cek jika file diupload dengan benar
    if ($file['error'] == 0) {
        // Tentukan lokasi folder penyimpanan file
        $uploadDir = 'uploads/';
        
        // Pastikan folder 'uploads' ada, jika tidak buat folder tersebut
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);  // Membuat folder jika belum ada
        }

        // Tentukan nama file dan ekstensi
        $fileName = uniqid('profile_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        // Validasi ekstensi file (hanya gambar yang diperbolehkan)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            // Validasi ukuran file (misalnya maksimal 2MB)
            $maxFileSize = 2 * 1024 * 1024;  // 2MB
            if ($file['size'] <= $maxFileSize) {
                // Pindahkan file ke folder 'uploads'
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    // Simpan path foto profil ke database
                    $userId = $_SESSION['userid']; // Asumsikan ID pengguna disimpan di sesi
                    $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('si', $filePath, $userId);

                    if ($stmt->execute()) {
                        echo "<script>
                                alert('Foto profil berhasil diunggah!');
                                window.location.href = 'view_profile.php';
                            </script>";
                    } else {
                        echo "<script>
                                alert('Terjadi kesalahan saat menyimpan data ke database.');
                            </script>";
                    }
                } else {
                    echo "<script>
                            alert('Terjadi kesalahan saat mengunggah file.');
                        </script>";
                }
            } else {
                echo "<script>
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    </script>";
            }
        } else {
            echo "<script>
                    alert('Hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.');
                </script>";
        }
    } else {
        echo "<script>
                alert('Tidak ada file yang diunggah atau terjadi kesalahan.');
            </script>";
    }
}
?>
