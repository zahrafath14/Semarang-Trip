# SemarangTrip Expert

Sistem pakar rekomendasi destinasi wisata di Kota Semarang berdasarkan preferensi dan anggaran pengunjung (PHP native dan MySQL).

## Menjalankan di XAMPP

1. Salin folder `semarang-trip` ke direktori `htdocs`.
2. Aktifkan Apache dan MySQL.
3. Impor `database/semarang_trip.sql` melalui phpMyAdmin.
4. Buka `http://localhost/semarang-trip/index.php`.

Jika nama folder proyek berbeda, ubah `BASE_URL` pada `config/app.php`.

## Struktur

```
semarang-trip/
├── index.php                  Beranda
├── konsultasi.php             Form preferensi (per pertanyaan)
├── proses_konsultasi.php      Validasi, penyimpanan, dan penilaian
├── hasil.php                  Hasil rekomendasi
├── detail.php                 Detail destinasi
├── riwayat.php                Riwayat konsultasi per sesi
├── config/
│   ├── app.php                Nama aplikasi dan BASE_URL
│   └── database.php           Koneksi PDO
├── includes/
│   ├── bootstrap.php          Titik masuk bersama seluruh halaman
│   ├── functions.php          Fungsi bantu tampilan
│   ├── labels.php             Label tampilan (sumber tunggal)
│   ├── questions.php          Definisi pertanyaan konsultasi
│   ├── expert_system.php      Aturan, forward chaining, penilaian
│   ├── repository.php         Seluruh query basis data
│   ├── header.php, navbar.php, footer.php
│   └── partials/              Komponen tampilan yang dipakai ulang
├── assets/
│   ├── css/style.css          Satu stylesheet terstruktur dengan token desain
│   └── js/                    script.js (umum), konsultasi.js (form)
└── database/semarang_trip.sql
```

## Akun admin awal

Username `admin`, kata sandi `admin123`. Ganti sebelum sistem digunakan secara nyata.

## Catatan

Data destinasi (harga, jam buka, alamat, koordinat) masih berupa data awal dan perlu diverifikasi. Untuk menampilkan foto, simpan berkas pada `assets/images/` dan isi kolom `gambar` pada tabel `wisata`.
