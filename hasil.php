<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title   = 'Hasil Rekomendasi';
$konsultasiId = (int) ($_GET['konsultasi_id'] ?? 0);
$konsultasi   = $konsultasiId > 0 ? getKonsultasi($pdo, $konsultasiId, $_SESSION['session_id'] ?? '') : null;

$utama     = null;
$rekomendasi = [];

if ($konsultasi) {
    $jawaban  = ambilJawaban($konsultasi);
    $kategori = forwardChaining($jawaban);
    $hasil    = getHasilKonsultasi($pdo, $konsultasiId);

    $utama       = $hasil[0] ?? null;
    $rekomendasi = array_slice($hasil, 0, 5);
    $skor        = $utama ? hitungSkorWisata($jawaban, $utama) : null;

    // Ringkasan preferensi untuk panel samping: [label, nilai singkat, ikon]
    $preferensi = [
        ['Jenis Wisata',     getLabel('jenis_wisata', $jawaban['jenis_wisata']),          'landmark'],
        ['Budget',           getLabel('budget_singkat', $jawaban['budget']),              'wallet'],
        ['Suasana',          getLabel('suasana', $jawaban['suasana']),                    'users'],
        ['Aktivitas',        getLabel('aktivitas_singkat', $jawaban['aktivitas']),        'activity'],
        ['Fasilitas',        getLabel('fasilitas_singkat', $jawaban['fasilitas']),        'building'],
        ['Waktu Kunjungan',  getLabel('waktu_kunjungan', $jawaban['waktu_kunjungan']),    'clock'],
    ];
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main id="konten">

<?php if (!$konsultasi || !$utama): ?>

    <div class="container page">
        <?php renderPartial('empty_state', [
            'title'        => 'Hasil konsultasi tidak ditemukan',
            'message'      => 'Konsultasi belum dilakukan atau sesi sudah berakhir. Mulai konsultasi baru untuk memperoleh rekomendasi.',
            'action_label' => 'Mulai Konsultasi',
            'action_url'   => url('konsultasi.php'),
        ]); ?>
    </div>

<?php else: ?>

    <section class="page-hero">
        <div class="container hero-grid">
            <div class="hero-text">
                <span class="chip"><?= ikon('grid', 15) ?>Hasil rekomendasi</span>
                <h1>Rekomendasi Tempat Wisata di <span class="hl">Kota Semarang</span></h1>
                <p class="lead">Berikut tempat wisata yang paling sesuai dengan preferensimu berdasarkan hasil konsultasi. Semakin tinggi persentase, semakin sesuai. Dihasilkan pada <?= e(formatTanggal($konsultasi['tanggal'])) ?>.</p>
            </div>
            <?php renderPartial('hero_art'); ?>
        </div>
    </section>

    <div class="container result-layout">

        <aside class="panel pref-panel" aria-labelledby="pref-title">
            <header class="pref-head">
                <span class="avatar"><?= ikon('user', 26) ?></span>
                <div>
                    <h2 id="pref-title">Preferensi Kamu</h2>
                    <p class="muted">Pilihan yang kamu isi pada konsultasi</p>
                </div>
            </header>

            <dl class="pref-list">
                <?php foreach ($preferensi as [$label, $nilai, $ik]): ?>
                    <div>
                        <dt><?= ikon($ik, 18) ?><?= e($label) ?></dt>
                        <dd><?= e($nilai) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>

            <div class="category-box">
                <p class="category-label">Kategori hasil</p>
                <p class="category-name"><?= e($kategori['kategori']) ?></p>
                <p class="muted">Ditentukan oleh aturan <?= e($kategori['rule']) ?> melalui forward chaining.</p>
            </div>

            <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-outline btn-block"><?= ikon('refresh', 18) ?>Ulangi Konsultasi</a>

            <div class="tips">
                <p class="tips-title"><?= ikon('bulb', 17) ?>Tips</p>
                <p>Kamu bisa mengubah preferensi dan melakukan konsultasi ulang untuk mendapatkan rekomendasi yang berbeda.</p>
            </div>
        </aside>

        <section class="panel rec-panel" aria-labelledby="rec-title">
            <header class="rec-head">
                <span class="rec-trophy"><?= ikon('trophy', 30) ?></span>
                <div>
                    <h2 id="rec-title">Daftar Rekomendasi</h2>
                    <p class="muted">Tempat wisata yang paling sesuai dengan preferensimu</p>
                </div>
                <span class="count-pill"><?= ikon('star', 15) ?>Menampilkan <b><?= count($rekomendasi) ?></b> rekomendasi terbaik</span>
            </header>

            <div class="rec-grid">
                <?php foreach ($rekomendasi as $i => $w): ?>
                    <?php renderPartial('wisata_card', ['wisata' => $w, 'rank' => $i + 1, 'persen' => (int) $w['persentase']]); ?>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <div class="container result-extra">
        <section class="panel" aria-labelledby="rincian-title">
            <h2 id="rincian-title" class="panel-heading">Rincian penilaian: <?= e($utama['nama_wisata']) ?></h2>
            <p class="muted">Skor <?= (int) $skor['score'] ?> dari <?= (int) $skor['max_score'] ?> poin untuk rekomendasi peringkat pertama.</p>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Kriteria</th>
                            <th scope="col">Preferensi Anda</th>
                            <th scope="col">Destinasi</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="num">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skor['rincian'] as $r): ?>
                            <tr>
                                <td><?= e($r['kriteria']) ?></td>
                                <td><?= e($r['preferensi']) ?></td>
                                <td><?= e($r['destinasi']) ?></td>
                                <td><span class="badge <?= $r['cocok'] ? 'badge-ok' : 'badge-no' ?>"><?= $r['cocok'] ? 'Sesuai' : 'Tidak sesuai' ?></span></td>
                                <td class="num"><?= (int) $r['poin'] ?> / <?= (int) $r['bobot'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr><th scope="row" colspan="4">Total skor</th><td class="num"><?= (int) $skor['score'] ?> / <?= (int) $skor['max_score'] ?></td></tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <div class="actions actions-end">
            <a href="<?= e(url('riwayat.php')) ?>" class="btn btn-secondary"><?= ikon('history', 18) ?>Lihat Riwayat</a>
            <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-primary"><?= ikon('refresh', 18) ?>Konsultasi Ulang</a>
        </div>
    </div>

<?php endif; ?>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
