<?php
$current = basename($_SERVER['PHP_SELF']);

// "Hasil" membuka konsultasi terbaru pada sesi ini; bila belum ada, membuka riwayat.
$terakhir = !empty($_SESSION['session_id']) ? getKonsultasiTerakhirId($pdo, $_SESSION['session_id']) : null;
$urlHasil = $terakhir ? url('hasil.php?konsultasi_id=' . $terakhir) : url('riwayat.php');

$menu = [
    ['label' => 'Beranda',       'ikon' => 'home',  'href' => url('index.php'),                   'aktif' => $current === 'index.php' && empty($_GET['q'])],
    ['label' => 'Konsultasi',    'ikon' => 'chat',  'href' => url('konsultasi.php'),              'aktif' => $current === 'konsultasi.php'],
    ['label' => 'Hasil',         'ikon' => 'grid',  'href' => $urlHasil,                          'aktif' => in_array($current, ['hasil.php', 'riwayat.php'], true)],
    ['label' => 'Daftar Wisata', 'ikon' => 'map',   'href' => url('index.php#daftar-wisata'),     'aktif' => $current === 'detail.php' || ($current === 'index.php' && !empty($_GET['q']))],
    ['label' => 'Tentang',       'ikon' => 'info',  'href' => url('index.php#tentang'),           'aktif' => false],
];
?>
<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= e(url('index.php')) ?>" class="brand" aria-label="<?= e(APP_NAME) ?> - Beranda">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 40 44" width="34" height="38" fill="none">
                    <path d="M20 2v6M17 8h6" stroke="#1769e0" stroke-width="2" stroke-linecap="round"/>
                    <path d="M13 40 20 8l7 32" stroke="#1769e0" stroke-width="2.4" stroke-linejoin="round"/>
                    <path d="M15.2 30h9.6M16.6 22h6.8M18.2 15h3.6" stroke="#1769e0" stroke-width="2" stroke-linecap="round"/>
                    <path d="M13 40 24.5 22M27 40 15.5 22" stroke="#1769e0" stroke-width="1.5"/>
                    <path d="M6 42h28" stroke="#1769e0" stroke-width="2.4" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="brand-text">Semarang<b>Trip</b></span>
        </a>

        <button type="button" class="navbar-toggle" id="navbarToggle" aria-expanded="false" aria-controls="navbarMenu" aria-label="Buka menu">
            <?= ikon('menu', 22) ?>
        </button>

        <div class="navbar-menu" id="navbarMenu">
            <nav class="nav-links" aria-label="Navigasi utama">
                <?php foreach ($menu as $m): ?>
                    <a href="<?= e($m['href']) ?>"<?= $m['aktif'] ? ' class="active" aria-current="page"' : '' ?>>
                        <?= ikon($m['ikon'], 19) ?><span><?= e($m['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <form class="nav-search" action="<?= e(url('index.php')) ?>#daftar-wisata" method="get" role="search">
                <?= ikon('search', 19) ?>
                <label class="sr-only" for="cariWisata">Cari wisata di Semarang</label>
                <input type="search" id="cariWisata" name="q" placeholder="Cari wisata di Semarang..." value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off">
            </form>
        </div>
    </div>
</header>
