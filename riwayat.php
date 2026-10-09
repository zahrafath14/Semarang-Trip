<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title = 'Riwayat Konsultasi';
$riwayat    = !empty($_SESSION['session_id']) ? getRiwayat($pdo, $_SESSION['session_id']) : [];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main id="konten">
    <section class="page-hero page-hero-compact">
        <div class="container">
            <span class="chip"><?= ikon('history', 15) ?>Riwayat</span>
            <h1>Riwayat <span class="hl">konsultasi</span></h1>
            <p class="lead">Daftar konsultasi yang pernah dilakukan pada perangkat dan peramban ini.</p>
        </div>
    </section>

    <div class="container page">

        <?php if (!$riwayat): ?>

            <?php renderPartial('empty_state', [
                'title'        => 'Belum ada riwayat konsultasi',
                'message'      => 'Konsultasi pertama Anda akan tercatat di halaman ini.',
                'action_label' => 'Mulai Konsultasi',
                'action_url'   => url('konsultasi.php'),
            ]); ?>

        <?php else: ?>

            <ul class="history-list">
                <?php foreach ($riwayat as $r): ?>
                    <li class="panel history-item">
                        <div class="history-main">
                            <p class="history-date"><?= ikon('calendar', 16) ?><?= e(formatTanggal($r['tanggal'])) ?></p>
                            <ul class="tags">
                                <li class="tag tag-<?= e($r['jenis_wisata']) ?>"><?= e(getLabel('jenis_wisata', $r['jenis_wisata'])) ?></li>
                                <li class="tag tag-rose"><?= e(getLabel('budget', $r['budget'])) ?></li>
                                <li class="tag tag-sun"><?= e(getLabel('suasana', $r['suasana'])) ?></li>
                            </ul>
                        </div>
                        <div class="history-top">
                            <?php if ($r['top']): ?>
                                <p class="quick-label">Rekomendasi teratas</p>
                                <p class="history-name"><?= e($r['top']['nama_wisata']) ?></p>
                            <?php else: ?>
                                <p class="muted">Tidak tersedia</p>
                            <?php endif; ?>
                        </div>
                        <div class="history-score">
                            <?php if ($r['top']): ?>
                                <span class="score-pill"><?= (int) $r['top']['persentase'] ?>%</span>
                            <?php endif; ?>
                            <a class="btn btn-outline btn-sm" href="<?= e(url('hasil.php?konsultasi_id=' . (int) $r['id'])) ?>">Lihat hasil <?= ikon('chevron', 15) ?></a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="actions actions-end">
                <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-primary">Konsultasi Baru</a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
