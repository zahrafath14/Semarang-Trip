<?php
/**
 * Kaki dokumen. Variabel opsional sebelum dimuat:
 *   $page_scripts -> daftar berkas JS tambahan (relatif terhadap assets/js/)
 */
$page_scripts = $page_scripts ?? [];
?>
<footer class="footer">
    <div class="container footer-grid">
        <div>
            <p class="footer-brand">Semarang<b>Trip</b></p>
            <p>Sistem pakar rekomendasi destinasi wisata di Kota Semarang berdasarkan preferensi dan anggaran pengunjung.</p>
        </div>
        <div>
            <p class="footer-title">Navigasi</p>
            <ul class="footer-links">
                <li><a href="<?= e(url('index.php')) ?>">Beranda</a></li>
                <li><a href="<?= e(url('konsultasi.php')) ?>">Konsultasi</a></li>
                <li><a href="<?= e(url('riwayat.php')) ?>">Riwayat</a></li>
                <li><a href="<?= e(url('index.php#daftar-wisata')) ?>">Daftar Wisata</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-title">Metode</p>
            <p>Forward chaining untuk penentuan kategori dan pembobotan atribut untuk perhitungan tingkat kecocokan.</p>
        </div>
    </div>
    <div class="container footer-bottom">
        &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. Proyek sistem pakar.
    </div>
</footer>

<script src="<?= e(asset('js/script.js')) ?>"></script>
<?php foreach ($page_scripts as $script): ?>
    <script src="<?= e(asset('js/' . $script)) ?>"></script>
<?php endforeach; ?>
</body>
</html>
