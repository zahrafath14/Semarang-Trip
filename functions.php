<?php
/**
 * Fungsi bantu umum untuk tampilan.
 */

/** Escape output HTML. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** URL absolut terhadap BASE_URL. */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL berkas aset (css, js, images). */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/** Memuat potongan tampilan dari includes/partials. */
function renderPartial(string $name, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require __DIR__ . '/partials/' . $name . '.php';
}

/** Format tanggal berbahasa Indonesia, contoh: 08 Oktober 2026, 14:30. */
function formatTanggal(string $datetime): string
{
    static $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y, H:i', $ts);
}

/** Menampilkan gambar destinasi, atau blok berwarna dengan ikon bila gambar belum tersedia. */
function wisataMedia(array $wisata, string $class = 'media'): string
{
    $file = basename($wisata['gambar'] ?? '');
    $path = dirname(__DIR__) . '/assets/images/' . $file;

    if ($file !== '' && $file !== 'placeholder.jpg' && is_file($path)) {
        return '<img class="' . e($class) . '" src="' . e(asset('images/' . $file)) . '" alt="'
            . e($wisata['nama_wisata']) . '" loading="lazy">';
    }

    return '<div class="' . e($class) . ' media-empty tone-' . e($wisata['jenis_wisata']) . '" role="img" aria-label="'
        . e($wisata['nama_wisata']) . '">'
        . ikon(ikonJenis($wisata['jenis_wisata']), 44)
        . '<span>' . e($wisata['nama_wisata']) . '</span></div>';
}

/* ---------------------------------------------------------
 * Pembantu tampilan tambahan (desain baru)
 * --------------------------------------------------------- */

/** Ikon SVG sebaris (gaya garis). Warna mengikuti currentColor. */
function ikon(string $nama, int $ukuran = 20): string
{
    static $path = [
        'home'     => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h5v-6h4v6h5V9.5"/>',
        'chat'     => '<path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12Z"/>',
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'map'      => '<path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3Z"/><path d="M9 3v15M15 6v15"/>',
        'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'pin'      => '<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'ticket'   => '<path d="M3 9a2 2 0 0 0 0 6v3h18v-3a2 2 0 0 1 0-6V6H3Z"/><path d="M13 6v12" stroke-dasharray="2 3"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'wallet'   => '<path d="M3 7a2 2 0 0 1 2-2h13v4"/><path d="M3 7v11a2 2 0 0 0 2 2h14V9H5a2 2 0 0 1-2-2Z"/><circle cx="16" cy="14.5" r="1"/>',
        'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><path d="M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.4c2 .7 3.5 2.6 3.5 5.6"/>',
        'activity' => '<path d="M3 12h4l3-8 4 16 3-8h4"/>',
        'building' => '<rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 8h.01M15 8h.01M9 12h.01M15 12h.01M10 21v-4h4v4"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'landmark' => '<path d="M3 10 12 4l9 6H3Z"/><path d="M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 21h18"/>',
        'star'     => '<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9Z"/>',
        'trophy'   => '<path d="M8 4h8v6a4 4 0 0 1-8 0Z"/><path d="M8 6H4v1a4 4 0 0 0 4 4M16 6h4v1a4 4 0 0 1-4 4M12 14v4M8 21h8M10 18h4"/>',
        'refresh'  => '<path d="M20 11a8 8 0 0 0-14.5-4M4 5v3.5h3.5"/><path d="M4 13a8 8 0 0 0 14.5 4M20 19v-3.5h-3.5"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'chevron'  => '<path d="m9 6 6 6-6 6"/>',
        'bulb'     => '<path d="M9 18h6M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3Z"/>',
        'history'  => '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5M12 8v4l3 2"/>',
        'check'    => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'        => '<path d="M6 6l12 12M18 6 6 18"/>',
        'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/>',
        'sparkle'  => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>',
        'tree'     => '<path d="M12 21v-6"/><path d="M12 3 6 11h3l-4 6h14l-4-6h3Z"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
    ];

    return '<svg class="ikon" width="' . $ukuran . '" height="' . $ukuran . '" viewBox="0 0 24 24" fill="none" '
        . 'stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . ($path[$nama] ?? $path['info']) . '</svg>';
}

/** Nama ikon untuk tiap jenis wisata. */
function ikonJenis(string $jenis): string
{
    return [
        'alam' => 'tree', 'sejarah' => 'landmark', 'budaya' => 'landmark', 'religi' => 'building',
        'buatan' => 'sparkle', 'edukasi' => 'bulb', 'kuliner' => 'star',
    ][$jenis] ?? 'pin';
}

/** Membuang keterangan dalam kurung, mis. "(perkiraan, perlu verifikasi)". */
function ringkasData(?string $teks): string
{
    $bersih = preg_replace('/\s*\([^)]*\)/u', '', (string) $teks);
    return trim($bersih) !== '' ? trim($bersih) : 'Belum ada data';
}

/** Nama kecamatan dari alamat, contoh: "..., Semarang Tengah, Kota Semarang" -> "Semarang Tengah". */
function areaWisata(?string $alamat): string
{
    $bagian = array_values(array_filter(array_map('trim', explode(',', (string) $alamat)), function ($b) {
        return $b !== '' && strcasecmp($b, 'Kota Semarang') !== 0;
    }));
    return $bagian ? end($bagian) : 'Semarang';
}

/** Label tingkat kecocokan berdasarkan persentase. */
function labelKecocokan(int $persen): string
{
    if ($persen >= 80) return 'Sangat Sesuai';
    if ($persen >= 60) return 'Sesuai';
    if ($persen >= 40) return 'Cukup Sesuai';
    return 'Kurang Sesuai';
}

/** URL Google Maps dari koordinat, atau pencarian berdasarkan nama bila koordinat kosong. */
function urlPeta(array $wisata): string
{
    if (!empty($wisata['latitude']) && !empty($wisata['longitude'])) {
        $q = urlencode($wisata['latitude'] . ',' . $wisata['longitude']);
    } else {
        $q = urlencode($wisata['nama_wisata'] . ' Semarang');
    }
    return 'https://www.google.com/maps/search/?api=1&query=' . $q;
}

/** Tiga label ringkas untuk kartu: jenis, aktivitas, suasana (dengan kelas warna). */
function tagWisata(array $wisata): array
{
    return [
        ['teks' => getLabel('jenis_wisata', $wisata['jenis_wisata']), 'kelas' => 'tag-' . $wisata['jenis_wisata']],
        ['teks' => getLabel('aktivitas_singkat', $wisata['aktivitas']), 'kelas' => 'tag-rose'],
        ['teks' => getLabel('suasana', $wisata['suasana']), 'kelas' => 'tag-sun'],
    ];
}
