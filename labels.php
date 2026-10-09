<?php
/**
 * Label tampilan untuk setiap nilai enum di basis data.
 * Menjadi sumber tunggal untuk opsi form, hasil, dan detail.
 */
function getLabels(): array
{
    static $labels = [
        'jenis_wisata' => [
            'alam'    => 'Alam',
            'sejarah' => 'Sejarah',
            'budaya'  => 'Budaya',
            'religi'  => 'Religi',
            'buatan'  => 'Buatan',
            'edukasi' => 'Edukasi',
            'kuliner' => 'Kuliner',
        ],
        'budget' => [
            'gratis' => 'Gratis',
            'murah'  => 'Di bawah Rp25.000',
            'sedang' => 'Rp25.000 - Rp50.000',
            'mahal'  => 'Di atas Rp50.000',
        ],
        'suasana' => [
            'tenang' => 'Tenang',
            'sedang' => 'Sedang',
            'ramai'  => 'Ramai',
        ],
        'aktivitas' => [
            'bersantai'      => 'Bersantai',
            'foto'           => 'Fotografi',
            'edukasi'        => 'Edukasi',
            'rekreasi'       => 'Rekreasi',
            'keluarga'       => 'Wisata keluarga',
            'budaya_sejarah' => 'Menikmati budaya dan sejarah',
        ],
        'fasilitas' => [
            'lengkap' => 'Fasilitas lengkap',
            'standar' => 'Fasilitas standar',
            'minim'   => 'Fasilitas minimal',
        ],
        'waktu_kunjungan' => [
            'pagi'           => 'Pagi',
            'siang'          => 'Siang',
            'sore'           => 'Sore',
            'malam'          => 'Malam',
            'sepanjang_hari' => 'Sepanjang hari',
        ],
        // Label singkat untuk ringkasan di kartu dan panel preferensi.
        'budget_singkat' => [
            'gratis' => 'Gratis',
            'murah'  => 'Murah',
            'sedang' => 'Sedang',
            'mahal'  => 'Mahal',
        ],
        'aktivitas_singkat' => [
            'bersantai'      => 'Santai',
            'foto'           => 'Foto',
            'edukasi'        => 'Edukasi',
            'rekreasi'       => 'Rekreasi',
            'keluarga'       => 'Keluarga',
            'budaya_sejarah' => 'Budaya / Sejarah',
        ],
        'fasilitas_singkat' => [
            'lengkap' => 'Lengkap',
            'standar' => 'Standar',
            'minim'   => 'Minimal',
        ],
    ];
    return $labels;
}

/** Mengambil label; mengembalikan nilai asli bila tidak ditemukan. */
function getLabel(string $field, ?string $value): string
{
    return getLabels()[$field][$value] ?? (string) $value;
}
