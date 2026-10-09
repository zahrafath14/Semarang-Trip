<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title  = 'Rekomendasi Destinasi Wisata Semarang';
$kata        = trim($_GET['q'] ?? '');
$daftar      = getWisataSemua($pdo, $kata);
$totalWisata = countWisata($pdo);
$kriteria    = getBobotKriteria();
$maxSkor     = array_sum(array_column($kriteria, 'bobot'));

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main id="konten">

    <section class="page-hero">
        <div class="container hero-grid">
            <div class="hero-text">
                <span class="chip"><?= ikon('sparkle', 15) ?>Sistem pakar wisata Semarang</span>
                <h1>Temukan wisata di <span class="hl">Kota Semarang</span> yang paling cocok untukmu</h1>
                <p class="lead">Jawab enam pertanyaan singkat tentang jenis wisata, anggaran, dan suasana. Sistem pakar menyusun peringkat destinasi beserta rincian penilaiannya.</p>
                <div class="actions">
                    <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-primary btn-lg">Mulai Konsultasi <?= ikon('chevron', 18) ?></a>
                    <a href="#daftar-wisata" class="btn btn-outline btn-lg">Lihat Daftar Wisata</a>
                </div>

                <dl class="hero-facts">
                    <div><dt>Variabel preferensi</dt><dd><?= count($kriteria) ?></dd></div>
                    <div><dt>Skor maksimum</dt><dd><?= (int) $maxSkor ?></dd></div>
                    <div><dt>Destinasi terdata</dt><dd><?= (int) $totalWisata ?></dd></div>
                </dl>
            </div>

            <?php renderPartial('hero_art'); ?>
        </div>
    </section>

    <section class="section" id="tentang">
        <div class="container">
            <div class="section-head">
                <h2>Cara kerja konsultasi</h2>
                <p>Tiga tahap dari pengisian preferensi hingga hasil rekomendasi.</p>
            </div>
            <ol class="steps">
                <li>
                    <span class="step-ico"><?= ikon('chat', 24) ?></span>
                    <h3>Mengisi preferensi</h3>
                    <p>Menjawab enam pertanyaan mengenai jenis wisata, anggaran, suasana, aktivitas, fasilitas, dan waktu kunjungan.</p>
                </li>
                <li>
                    <span class="step-ico"><?= ikon('activity', 24) ?></span>
                    <h3>Analisis oleh sistem</h3>
                    <p>Jawaban dicocokkan dengan aturan sistem pakar dan atribut setiap destinasi untuk menghitung skor kecocokan.</p>
                </li>
                <li>
                    <span class="step-ico"><?= ikon('trophy', 24) ?></span>
                    <h3>Membaca hasil</h3>
                    <p>Sistem menampilkan destinasi dengan kecocokan tertinggi beserta rincian kriteria yang terpenuhi.</p>
                </li>
            </ol>

            <div class="feature-grid">
                <div class="feature">
                    <h3>Berbasis preferensi dan anggaran</h3>
                    <p>Peringkat disusun dari jawabanmu, bukan daftar umum yang sama untuk semua pengunjung.</p>
                </div>
                <div class="feature">
                    <h3>Penilaian yang transparan</h3>
                    <p>Setiap rekomendasi disertai rincian kriteria yang sesuai dan poin yang diperoleh.</p>
                </div>
                <div class="feature">
                    <h3>Informasi destinasi terstruktur</h3>
                    <p>Harga tiket, alamat, jam operasional, fasilitas, dan lokasi pada peta tersedia di satu halaman.</p>
                </div>
                <div class="feature">
                    <h3>Riwayat konsultasi</h3>
                    <p>Hasil konsultasi sebelumnya dapat dibuka kembali pada perangkat yang sama tanpa perlu masuk akun.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-tint" id="daftar-wisata">
        <div class="container">
            <div class="section-head section-head-row">
                <div>
                    <h2>Daftar wisata di Kota Semarang</h2>
                    <p>
                        <?php if ($kata !== ''): ?>
                            <?= count($daftar) ?> hasil untuk &ldquo;<?= e($kata) ?>&rdquo;.
                            <a class="link" href="<?= e(url('index.php#daftar-wisata')) ?>">Tampilkan semua</a>
                        <?php else: ?>
                            Seluruh destinasi yang tersedia dalam basis data sistem.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if (!$daftar): ?>
                <?php renderPartial('empty_state', [
                    'title'        => $kata !== '' ? 'Wisata tidak ditemukan' : 'Data destinasi belum tersedia',
                    'message'      => $kata !== '' ? 'Coba kata kunci lain, misalnya nama tempat, kecamatan, atau jenis wisata.' : 'Impor berkas database/semarang_trip.sql untuk menampilkan destinasi.',
                    'action_label' => $kata !== '' ? 'Tampilkan semua wisata' : 'Mulai Konsultasi',
                    'action_url'   => $kata !== '' ? url('index.php#daftar-wisata') : url('konsultasi.php'),
                ]); ?>
            <?php else: ?>
                <div class="card-grid">
                    <?php foreach ($daftar as $wisata): ?>
                        <?php renderPartial('wisata_card', ['wisata' => $wisata]); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="cta">
        <div class="container cta-inner">
            <div>
                <h2>Tentukan destinasi yang sesuai dengan kebutuhanmu</h2>
                <p>Konsultasi membutuhkan waktu kurang dari dua menit.</p>
            </div>
            <a href="<?= e(url('konsultasi.php')) ?>" class="btn btn-inverse btn-lg">Mulai Konsultasi</a>
        </div>
    </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
