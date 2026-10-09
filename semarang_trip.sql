-- =========================================================
-- DATABASE: semarang_trip
-- Sistem Pakar Rekomendasi Tempat Wisata di Kota Semarang
-- =========================================================
-- Cara import:
-- 1. Buka phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Buat database baru "semarang_trip" ATAU langsung import file ini
--    (file ini sudah membuat database sendiri)
-- =========================================================

CREATE DATABASE IF NOT EXISTS semarang_trip CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE semarang_trip;

-- ---------------------------------------------------------
-- TABEL: wisata
-- Menyimpan data master tempat wisata + atribut yang dipakai
-- oleh mesin sistem pakar (jenis, budget, suasana, dll)
-- ---------------------------------------------------------
CREATE TABLE wisata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_wisata VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    deskripsi TEXT NOT NULL,

    -- Atribut untuk pencocokan sistem pakar (harus sinkron dengan pilihan di konsultasi.php)
    jenis_wisata ENUM('alam','sejarah','budaya','religi','buatan','edukasi','kuliner') NOT NULL,
    budget_category ENUM('gratis','murah','sedang','mahal') NOT NULL, -- gratis, <25rb, 25rb-50rb, >50rb
    suasana ENUM('tenang','sedang','ramai') NOT NULL,
    aktivitas ENUM('bersantai','foto','edukasi','rekreasi','keluarga','budaya_sejarah') NOT NULL,
    fasilitas ENUM('lengkap','standar','minim') NOT NULL,
    waktu_kunjungan ENUM('pagi','siang','sore','malam','sepanjang_hari') NOT NULL,

    -- Data informasi umum
    harga_tiket VARCHAR(100) DEFAULT 'Belum ada data', -- placeholder jelas, mudah diedit admin
    alamat VARCHAR(255) DEFAULT 'Belum ada data',
    jam_buka VARCHAR(150) DEFAULT 'Belum ada data',
    latitude DECIMAL(10,7) DEFAULT NULL,
    longitude DECIMAL(10,7) DEFAULT NULL,
    gambar VARCHAR(255) DEFAULT 'placeholder.jpg', -- nama file di assets/images/
    sumber_data VARCHAR(255) DEFAULT 'Data awal project (perlu verifikasi)',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- TABEL: rules
-- Rule IF-THEN untuk forward chaining (opsional dikelola admin,
-- tahap awal logic utama tetap ditulis di PHP agar sederhana)
-- ---------------------------------------------------------
CREATE TABLE rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_rule VARCHAR(100) NOT NULL,
    jenis_wisata VARCHAR(50) DEFAULT NULL,
    budget_category VARCHAR(50) DEFAULT NULL,
    suasana VARCHAR(50) DEFAULT NULL,
    aktivitas VARCHAR(50) DEFAULT NULL,
    fasilitas VARCHAR(50) DEFAULT NULL,
    waktu_kunjungan VARCHAR(50) DEFAULT NULL,
    bobot INT NOT NULL DEFAULT 1, -- bobot poin saat kecocokan (dipakai proses scoring)
    hasil VARCHAR(100) DEFAULT NULL, -- label kategori rekomendasi, misal: sejarah_edukasi
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- TABEL: konsultasi
-- Menyimpan jawaban user setiap sesi konsultasi
-- ---------------------------------------------------------
CREATE TABLE konsultasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    jenis_wisata VARCHAR(50) NOT NULL,
    budget VARCHAR(50) NOT NULL,
    suasana VARCHAR(50) NOT NULL,
    aktivitas VARCHAR(50) NOT NULL,
    fasilitas VARCHAR(50) NOT NULL,
    waktu_kunjungan VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- TABEL: hasil_konsultasi
-- Menyimpan hasil scoring per wisata untuk tiap sesi konsultasi
-- ---------------------------------------------------------
CREATE TABLE hasil_konsultasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    konsultasi_id INT NOT NULL,
    wisata_id INT NOT NULL,
    score INT NOT NULL,
    persentase DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (konsultasi_id) REFERENCES konsultasi(id) ON DELETE CASCADE,
    FOREIGN KEY (wisata_id) REFERENCES wisata(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- TABEL: admins
-- ---------------------------------------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- disimpan dengan password_hash()
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun admin default -> username: admin | password: admin123
-- Hash di bawah dihasilkan dari password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ---------------------------------------------------------
-- DATA CONTOH WISATA
-- Catatan: harga_tiket, jam_buka, alamat, koordinat bersifat
-- PLACEHOLDER / perkiraan umum agar mudah diedit lebih lanjut.
-- Silakan verifikasi & sesuaikan data aslinya sebelum presentasi.
-- ---------------------------------------------------------
INSERT INTO wisata
(nama_wisata, slug, deskripsi, jenis_wisata, budget_category, suasana, aktivitas, fasilitas, waktu_kunjungan, harga_tiket, alamat, jam_buka, latitude, longitude, gambar, sumber_data)
VALUES
('Lawang Sewu', 'lawang-sewu',
 'Bangunan bersejarah peninggalan zaman kolonial Belanda yang dulunya merupakan kantor perusahaan kereta api. Terkenal dengan arsitektur unik dan ratusan pintu/jendela besar.',
 'sejarah', 'murah', 'tenang', 'budaya_sejarah', 'lengkap', 'sore',
 'Rp10.000 - Rp30.000 (perkiraan, perlu verifikasi)', 'Jl. Pemuda, Sekayu, Semarang Tengah, Kota Semarang',
 '07.00 - 21.00 (perkiraan, perlu verifikasi)', -6.9832000, 110.4108000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Sam Poo Kong', 'sam-poo-kong',
 'Klenteng bersejarah yang menjadi bukti perjalanan Laksamana Cheng Ho ke Semarang. Memiliki arsitektur khas Tionghoa dengan nuansa merah yang khas.',
 'religi', 'murah', 'sedang', 'budaya_sejarah', 'lengkap', 'pagi',
 'Rp10.000 - Rp30.000 (perkiraan, perlu verifikasi)', 'Jl. Simongan No.129, Bongsari, Semarang Barat, Kota Semarang',
 '08.00 - 20.00 (perkiraan, perlu verifikasi)', -6.9889000, 110.3986000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Kota Lama Semarang', 'kota-lama-semarang',
 'Kawasan kota tua dengan deretan bangunan bergaya Eropa peninggalan Belanda. Cocok untuk berjalan santai sambil menikmati suasana klasik dan berfoto.',
 'sejarah', 'gratis', 'ramai', 'foto', 'standar', 'sore',
 'Gratis (khusus area terbuka, perlu verifikasi)', 'Kawasan Kota Lama, Tanjung Mas, Semarang Utara, Kota Semarang',
 'Sepanjang hari (perkiraan, perlu verifikasi)', -6.9676000, 110.4283000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Goa Kreo', 'goa-kreo',
 'Objek wisata alam berupa goa dan waduk yang dihuni banyak kera liar. Suasana asri dan sejuk, cocok untuk yang ingin menikmati alam.',
 'alam', 'murah', 'tenang', 'rekreasi', 'standar', 'pagi',
 'Rp5.000 - Rp15.000 (perkiraan, perlu verifikasi)', 'Kandri, Gunungpati, Kota Semarang',
 '07.00 - 17.00 (perkiraan, perlu verifikasi)', -7.0664000, 110.3494000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Masjid Agung Jawa Tengah', 'masjid-agung-jawa-tengah',
 'Masjid megah dengan perpaduan arsitektur Jawa, Islam, dan Romawi. Memiliki menara Al Husna yang dapat dinaiki untuk melihat pemandangan kota.',
 'religi', 'gratis', 'sedang', 'edukasi', 'lengkap', 'sepanjang_hari',
 'Gratis, menara berbayar terpisah (perlu verifikasi)', 'Jl. Gajah Raya, Sambirejo, Gayamsari, Kota Semarang',
 'Sepanjang hari (perkiraan, perlu verifikasi)', -6.9930000, 110.4478000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Semarang Zoo', 'semarang-zoo',
 'Kebun binatang keluarga dengan koleksi satwa yang cukup lengkap. Cocok untuk wisata edukasi dan rekreasi bersama keluarga.',
 'edukasi', 'sedang', 'ramai', 'keluarga', 'lengkap', 'pagi',
 'Rp25.000 - Rp50.000 (perkiraan, perlu verifikasi)', 'Jl. Sisingamangaraja, Kalisegoro, Gunungpati, Kota Semarang',
 '08.00 - 16.00 (perkiraan, perlu verifikasi)', -7.0392000, 110.4103000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Museum Ranggawarsita', 'museum-ranggawarsita',
 'Museum yang menyimpan berbagai koleksi sejarah dan budaya Jawa Tengah, mulai dari zaman prasejarah hingga masa kini.',
 'edukasi', 'murah', 'tenang', 'edukasi', 'standar', 'siang',
 'Rp5.000 - Rp15.000 (perkiraan, perlu verifikasi)', 'Jl. Abdulrahman Saleh No.1, Kalibanteng Kulon, Semarang Barat',
 '08.00 - 15.00 (perkiraan, perlu verifikasi)', -6.9791000, 110.3814000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Kampung Pelangi', 'kampung-pelangi',
 'Perkampungan unik dengan rumah-rumah berwarna-warni yang instagramable. Sangat cocok untuk kegiatan foto-foto.',
 'buatan', 'gratis', 'ramai', 'foto', 'minim', 'sore',
 'Gratis (perlu verifikasi)', 'Kelurahan Randusari, Semarang Selatan, Kota Semarang',
 'Sepanjang hari (perkiraan, perlu verifikasi)', -6.9920000, 110.4185000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Pantai Marina', 'pantai-marina',
 'Pantai yang cukup populer di Semarang, cocok untuk bersantai menikmati suasana sore sambil melihat pemandangan laut.',
 'alam', 'gratis', 'sedang', 'bersantai', 'standar', 'sore',
 'Gratis - Rp10.000 (perkiraan, perlu verifikasi)', 'Jl. Yos Sudarso, Tawangsari, Semarang Utara, Kota Semarang',
 'Sepanjang hari (perkiraan, perlu verifikasi)', -6.9436000, 110.4064000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)'),

('Brown Canyon', 'brown-canyon',
 'Bekas area pertambangan yang membentuk lanskap unik menyerupai ngarai/canyon. Populer sebagai lokasi foto dengan pemandangan tanah berwarna coklat.',
 'alam', 'gratis', 'sedang', 'foto', 'minim', 'siang',
 'Gratis - Rp10.000 (perkiraan, perlu verifikasi)', 'Rowosari, Tembalang, Kota Semarang',
 '08.00 - 17.00 (perkiraan, perlu verifikasi)', -7.0580000, 110.4590000, 'placeholder.jpg', 'Data awal project (perlu verifikasi)');
