<?php
/**
 * Kartu destinasi.
 * @var array    $wisata  baris tabel wisata
 * @var int|null $rank    peringkat (opsional, hasil konsultasi)
 * @var int|null $persen  persentase kecocokan (opsional, hasil konsultasi)
 */
$rank   = $rank ?? null;
$persen = $persen ?? null;
$detail = url('detail.php?slug=' . urlencode($wisata['slug']));
?>
<article class="rec-card<?= $rank === 1 ? ' is-top' : '' ?>">
    <div class="rec-media">
        <?= wisataMedia($wisata, 'rec-img') ?>
        <?php if ($rank !== null): ?>
            <span class="rank-badge" aria-label="Peringkat <?= (int) $rank ?>">#<?= (int) $rank ?></span>
        <?php endif; ?>
        <?php if ($persen !== null): ?>
            <span class="match-badge" role="img" aria-label="Kecocokan <?= (int) $persen ?> persen, <?= e(labelKecocokan((int) $persen)) ?>">
                <strong><?= (int) $persen ?>%</strong>
                <small><?= e(labelKecocokan((int) $persen)) ?></small>
            </span>
        <?php endif; ?>
    </div>

    <div class="rec-body">
        <ul class="tags">
            <?php foreach (tagWisata($wisata) as $t): ?>
                <li class="tag <?= e($t['kelas']) ?>"><?= e($t['teks']) ?></li>
            <?php endforeach; ?>
        </ul>

        <h3 class="rec-title"><a href="<?= e($detail) ?>"><?= e($wisata['nama_wisata']) ?></a></h3>
        <p class="rec-area"><?= ikon('pin', 15) ?><?= e(areaWisata($wisata['alamat'])) ?></p>
        <p class="rec-desc"><?= e($wisata['deskripsi']) ?></p>

        <dl class="rec-facts">
            <div>
                <span class="fact-ico"><?= ikon('ticket', 18) ?></span>
                <dt>Harga Tiket</dt>
                <dd title="<?= e($wisata['harga_tiket']) ?>"><?= e(ringkasData($wisata['harga_tiket'])) ?></dd>
            </div>
            <div>
                <span class="fact-ico"><?= ikon('clock', 18) ?></span>
                <dt>Jam Buka</dt>
                <dd title="<?= e($wisata['jam_buka']) ?>"><?= e(ringkasData($wisata['jam_buka'])) ?></dd>
            </div>
        </dl>

        <div class="rec-actions">
            <a href="<?= e($detail) ?>" class="btn btn-primary btn-block">Lihat Detail <?= ikon('chevron', 16) ?></a>
            <a href="<?= e(urlPeta($wisata)) ?>" class="btn btn-icon" target="_blank" rel="noopener" aria-label="Buka lokasi <?= e($wisata['nama_wisata']) ?> di Google Maps">
                <?= ikon('pin', 19) ?>
            </a>
        </div>
    </div>
</article>
