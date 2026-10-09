<?php
/**
 * Koneksi database (PDO).
 * Variabel $pdo tersedia setelah file ini dimuat.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'semarang_trip');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit('Layanan tidak tersedia. Pastikan server MySQL aktif dan basis data "semarang_trip" telah diimpor.');
}
