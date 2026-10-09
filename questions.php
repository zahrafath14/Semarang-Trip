<?php
/**
 * Daftar pertanyaan konsultasi.
 * Opsi jawaban diambil dari labels.php agar selalu sinkron dengan basis data.
 * Nilai "sepanjang_hari" hanya atribut destinasi, bukan pilihan pengguna.
 */
function getQuestions(): array
{
    $labels = getLabels();

    $waktu = $labels['waktu_kunjungan'];
    unset($waktu['sepanjang_hari']);

    return [
        [
            'key'     => 'jenis_wisata',
            'title'   => 'Jenis wisata apa yang Anda inginkan?',
            'desc'    => 'Pilih satu jenis destinasi yang paling sesuai.',
            'options' => $labels['jenis_wisata'],
        ],
        [
            'key'     => 'budget',
            'title'   => 'Berapa anggaran tiket masuk yang Anda rencanakan?',
            'desc'    => 'Pilih rentang harga tiket masuk per orang.',
            'options' => $labels['budget'],
        ],
        [
            'key'     => 'suasana',
            'title'   => 'Suasana seperti apa yang Anda sukai?',
            'desc'    => 'Pilih tingkat keramaian yang nyaman bagi Anda.',
            'options' => $labels['suasana'],
        ],
        [
            'key'     => 'aktivitas',
            'title'   => 'Aktivitas apa yang menjadi tujuan utama?',
            'desc'    => 'Pilih satu aktivitas utama selama berkunjung.',
            'options' => $labels['aktivitas'],
        ],
        [
            'key'     => 'fasilitas',
            'title'   => 'Seberapa penting kelengkapan fasilitas?',
            'desc'    => 'Pilih tingkat fasilitas yang Anda butuhkan.',
            'options' => $labels['fasilitas'],
        ],
        [
            'key'     => 'waktu_kunjungan',
            'title'   => 'Kapan Anda berencana berkunjung?',
            'desc'    => 'Pilih waktu kunjungan yang paling memungkinkan.',
            'options' => $waktu,
        ],
    ];
}
