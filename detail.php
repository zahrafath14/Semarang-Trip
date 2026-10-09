<?php
require_once __DIR__ . '/includes/bootstrap.php';

$slug   = trim($_GET['slug'] ?? '');
$wisata = $slug !== '' ? getWisataBySlug($pdo, $slug) : null;

$page_title = $wisata ? $wisata['nama_wisata'] : 'Destinasi Tidak Ditemukan';
$koordinat  = $wisata && $wisata['latitude'] && $wisata['longitude']
    ? urlencode($wisata['latitude'] . ',' . $wisata['longitude'])
    : null;

$info = $wisata ? [
    ['Jenis wisata',          getLabel('jenis_wisata', $wisata['jenis_wisata']),        ikonJenis($wisata['jenis_wisata'])],
    ['Kategori anggaran',     getLabel('budget', $wisata['budget_category']),           'wallet'],
    ['Suasana',               getLabel('suasana', $wisata['suasana']),                  'users'],
    ['Aktivitas',             getLabel('aktivitas', $wisata['aktivitas']),              'activity'],
    ['Fasilitas',             getLabel('fasilitas', $wisata['fasilitas']),              'building'],
    ['Waktu yang dianjurkan', getLabel('waktu_kunjungan', $wisata['waktu_kunjungan']),  'calendar'],
] : [];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main id="konten" class="page">
    <div class="container">

    <?php if (!$wisata): ?>

        <?php renderPartial('empty_state', [
            'title'        => 'Destinasi tidak ditemukan',
            'message'      => 'Data destinasi yang diminta tidak tersedia atau telah dihapus.',
            'action_label' => 'Kembali ke Beranda',
            'action_url'   => url('index.php'),
        ]); ?>

    <?php else: ?>

        <nav class="breadcrumb" aria-label="Jejak halaman">
            <a href="<?= e(url('index.php')) ?>">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="<?= e(url('index.php#daftar-wisata')) ?>">Daftar Wisata</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= e($wisata['nama_wisata']) ?></span>
        </nav>

        <header class="detail-hero">
            <?= wisataMedia($wisata, 'detail-media') ?>
            <div class="detail-hero-text">
                <ul class="tags">
                    <?php foreach (tagWisata($wisata) as $t): ?>
                        <li class="tag <?= e($t['kelas']) ?>"><?= e($t['teks']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <h1><?= e($wisata['nama_wisata']) ?></h1>
                <p class="rec-area"><?= ikon('pin', 17) ?><?= e($wisata['alamat']) ?></p>
            </div>
        </header>

        <div class="detail-layout">
            <div class="detail-main">
                <section class="panel">
                    <h2 class="panel-heading">Deskripsi</h2>
                    <p class="prose"><?= nl2br(e($wisata['deskripsi'])) ?></p>
                </section>

                <section class="panel">
                    <h2 class="panel-heading">Informasi destinasi</h2>
                    <dl class="info-grid">
                        <?php foreach ($info as [$label, $nilai, $ik]): ?>
                            <div>
                                <span class="fact-ico"><?= ikon($ik, 18) ?></span>
                                <dt><?= e($label) ?></dt>
                                <dd><?= e($nilai) ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </section>

                <section class="panel">
                    <h2 class="panel-heading">Lokasi</h2>
                    <?php if ($koordinat): ?>
                        <div class="map">
                            <iframe title="Peta lokasi <?= e($wisata['nama_wisata']) ?>"
                                    src="https://maps.google.com/maps?q=<?= $koordinat ?>&z=15&output=embed"
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <p><a class="link" href="https://www.google.com/maps/search/?api=1&query=<?= $koordinat ?>" target="_blank" rel="noopener">Buka di Google Maps</a></p>
                    <?php else: ?>
                        <p class="muted">Koordinat belum tersedia. Gunakan alamat di atas untuk pencarian lokasi.</p>
                    <?php endif; ?>
                </section>

                <p class="note">Sumber data: <?= e($wisata['sumber_data']) ?></p>
            </div>

            <aside class="detail-side">
                <div class="panel quick-card">
                    <div class="quick-row">
                        <span class="fact-ico"><?= ikon('ticket', 18) ?></span>
                        <div><p class="quick-label">Tiket masuk</p><p class="quick-value"><?= e($wisata['harga_tiket']) ?></p></div>
                    </div>
                    <div class="quick-row">
                        <span class="fact-ico"><?= ikon('clock', 18) ?></span>
                        <div><p class="quick-label">Jam operasional</p><p class="quick-value"><?= e($wisata['jam_buka']) ?></p></div>
                    </div>
                    <div class="quick-row">
                        <span class="fact-ico"><?= ikon('pin', 18) ?></span>
                        <div><p class="quick-label">Alamat</p><p class="quick-value"><?= e($wisata['alamat']) ?></p></div>
                    </div>

                    <a href="<?= e(urlPeta($wisata)) ?>" class="btn btn-primary btn-block" target="_blank" rel="noopener"><?= ikon('pin', 18) ?>Buka di Google Maps</a>
                    <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-outline btn-block">Konsultasi Baru</a>
                    <a href="<?= e(url('index.php#daftar-wisata')) ?>" data-back class="btn btn-secondary btn-block">Kembali</a>
                </div>
            </aside>
        </div>

    <?php endif; ?>

    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
