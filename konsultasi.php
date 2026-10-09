<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title   = 'Konsultasi';
$page_scripts = ['konsultasi.js'];
$questions    = getQuestions();
$total        = count($questions);
$hasError     = ($_GET['error'] ?? '') === 'belum_lengkap';
$ikonSoal     = ['jenis_wisata' => 'landmark', 'budget' => 'wallet', 'suasana' => 'users', 'aktivitas' => 'activity', 'fasilitas' => 'building', 'waktu_kunjungan' => 'calendar'];
$namaSingkat  = ['jenis_wisata' => 'Jenis wisata', 'budget' => 'Anggaran', 'suasana' => 'Suasana', 'aktivitas' => 'Aktivitas', 'fasilitas' => 'Fasilitas', 'waktu_kunjungan' => 'Waktu kunjungan'];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main id="konten">
    <section class="page-hero page-hero-compact">
        <div class="container">
            <span class="chip"><?= ikon('chat', 15) ?>Konsultasi</span>
            <h1>Ceritakan wisata <span class="hl">impianmu</span></h1>
            <p class="lead">Jawab seluruh pertanyaan berikut untuk memperoleh peringkat destinasi yang sesuai.</p>
        </div>
    </section>

    <div class="container consult-layout">

        <aside class="panel consult-steps" aria-label="Daftar pertanyaan">
            <h2 class="panel-title">Langkah konsultasi</h2>
            <ol class="step-list" id="stepList">
                <?php foreach ($questions as $i => $q): ?>
                    <li data-step="<?= $i + 1 ?>">
                        <span class="step-dot"><?= ikon($ikonSoal[$q['key']] ?? 'info', 17) ?></span>
                        <span><?= e($namaSingkat[$q['key']] ?? $q['title']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </aside>

        <div>
            <?php if ($hasError): ?>
                <div class="alert alert-error" role="alert">Konsultasi belum lengkap. Lengkapi seluruh pertanyaan, kemudian kirim kembali.</div>
            <?php endif; ?>

            <form action="<?= e(url('proses_konsultasi.php')) ?>" method="post" id="konsultasiForm" class="panel wizard" novalidate>

                <div class="progress-head">
                    <span id="stepLabel">Pertanyaan 1 dari <?= $total ?></span>
                </div>
                <div class="progress" id="progress" role="progressbar" aria-label="Kemajuan pengisian" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-fill" id="progressFill"></div>
                </div>

                <?php foreach ($questions as $i => $q): ?>
                    <fieldset class="wizard-step">
                        <legend tabindex="-1"><?= e($q['title']) ?></legend>
                        <p class="muted"><?= e($q['desc']) ?></p>

                        <div class="options">
                            <?php foreach ($q['options'] as $value => $label): ?>
                                <label class="option">
                                    <input type="radio" name="<?= e($q['key']) ?>" value="<?= e($value) ?>" required>
                                    <span class="option-mark" aria-hidden="true"></span>
                                    <span class="option-label"><?= e($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <p class="field-error" role="alert" hidden>Pilih salah satu jawaban untuk melanjutkan.</p>
                    </fieldset>
                <?php endforeach; ?>

                <div class="wizard-nav">
                    <button type="button" class="btn btn-secondary" id="btnBack" disabled>Sebelumnya</button>
                    <button type="button" class="btn btn-primary" id="btnNext">Lanjutkan</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit" hidden>Tampilkan Rekomendasi</button>
                </div>
            </form>
        </div>

    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
