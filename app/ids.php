<?php
// Fungsi untuk mencatat serangan ke file log
function log_attack($attack_type, $details) {
    // Jalur absolut untuk memastikan file log ditulis di lokasi yang benar
    $log_file = __DIR__ . '/attack_log.txt'; 
    $timestamp = date("Y-m-d H:i:s"); // Waktu serangan
    $log_entry = "[$timestamp] $attack_type - $details\n";

    // Mencoba menulis ke file log, mencatat error jika gagal
    if (file_put_contents($log_file, $log_entry, FILE_APPEND) === false) {
        error_log("Failed to write to log file: $log_file");
    }
}

// Fungsi untuk mendeteksi serangan XSS
function detect_xss($data) {
    $patterns = [
        "/<script.?>.?<\/script>/is", // Menangkap tag <script>
        "/<.?on\w+\s=\s*['\"].?['\"].?>/is" // Menangkap atribut event handler (misalnya onclick, onmouseover)
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $data)) {
            log_attack('XSS', $data); // Mencatat serangan XSS
            return true; // Serangan terdeteksi
        }
    }
    return false; // Tidak ada serangan
}

// Fungsi untuk mendeteksi serangan SQL Injection
function detect_sqli($data) {
    $patterns = [
        "/union\s+select\s+/is", // Menangkap query UNION SELECT
        "/select\s+.*?\s+from\s+/is", // Menangkap query SELECT ... FROM
        "/drop\s+table/is", // Menangkap query DROP TABLE
        "/--/is", // Menangkap komentar SQL (-- atau ;--)
        "/;--/is"
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $data)) {
            log_attack('SQLi', $data); // Mencatat serangan SQL Injection
            return true; // Serangan terdeteksi
        }
    }
    return false; // Tidak ada serangan
}

// Fungsi untuk memeriksa semua input yang diterima
function check_all_inputs() {
    // Memeriksa parameter GET
    foreach ($_GET as $key => $value) {
        if (detect_xss($value) || detect_sqli($value)) {
            echo "Malicious input detected in GET parameter: $key";
            exit; // Menghentikan eksekusi jika serangan terdeteksi
        }
    }

    // Memeriksa parameter POST
    foreach ($_POST as $key => $value) {
        if (detect_xss($value) || detect_sqli($value)) {
            echo "Malicious input detected in POST parameter: $key";
            exit; // Menghentikan eksekusi jika serangan terdeteksi
        }
    }

    // Memeriksa parameter COOKIE
    foreach ($_COOKIE as $key => $value) {
        if (detect_xss($value) || detect_sqli($value)) {
            echo "Malicious input detected in COOKIE parameter: $key";
            exit; // Menghentikan eksekusi jika serangan terdeteksi
        }
    }
}

// Mengaktifkan logging error PHP untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Memeriksa semua input pada awal eksekusi aplikasi
check_all_inputs();
?>