<?php
/**
 * Mesin sistem pakar, terpisah dari lapisan tampilan.
 *
 * 1. Basis pengetahuan : kumpulan aturan IF-THEN
 * 2. Forward chaining  : mencocokkan jawaban dengan aturan untuk menentukan kategori
 * 3. Penilaian         : menghitung skor kecocokan jawaban terhadap setiap destinasi
 */

/** Basis pengetahuan: setiap aturan berisi kondisi dan kategori hasil. */
function getRuleBase(): array
{
    return [
        ['nama_rule' => 'R1', 'kondisi' => ['jenis_wisata' => 'sejarah', 'aktivitas' => 'budaya_sejarah'], 'hasil' => 'Sejarah dan Budaya'],
        ['nama_rule' => 'R2', 'kondisi' => ['jenis_wisata' => 'alam', 'suasana' => 'tenang'],              'hasil' => 'Alam dan Ketenangan'],
        ['nama_rule' => 'R3', 'kondisi' => ['budget' => 'gratis', 'aktivitas' => 'bersantai'],             'hasil' => 'Wisata Hemat dan Santai'],
        ['nama_rule' => 'R4', 'kondisi' => ['jenis_wisata' => 'religi'],                                   'hasil' => 'Wisata Religi'],
        ['nama_rule' => 'R5', 'kondisi' => ['jenis_wisata' => 'buatan', 'aktivitas' => 'foto'],            'hasil' => 'Wisata Buatan dan Fotografi'],
        ['nama_rule' => 'R6', 'kondisi' => ['jenis_wisata' => 'edukasi', 'aktivitas' => 'edukasi'],        'hasil' => 'Edukasi dan Pengetahuan'],
        ['nama_rule' => 'R7', 'kondisi' => ['jenis_wisata' => 'kuliner'],                                  'hasil' => 'Wisata Kuliner'],
        ['nama_rule' => 'R8', 'kondisi' => ['aktivitas' => 'keluarga', 'fasilitas' => 'lengkap'],          'hasil' => 'Wisata Keluarga'],
    ];
}

/**
 * Forward chaining: dari fakta (jawaban) ke kesimpulan (kategori).
 * Aturan yang seluruh kondisinya terpenuhi dan paling spesifik (kondisi terbanyak) dipilih.
 */
function forwardChaining(array $jawaban): array
{
    $bestRule  = null;
    $bestCount = 0;

    foreach (getRuleBase() as $rule) {
        foreach ($rule['kondisi'] as $field => $value) {
            if (($jawaban[$field] ?? null) !== $value) {
                continue 2;
            }
        }
        $count = count($rule['kondisi']);
        if ($count > $bestCount) {
            $bestCount = $count;
            $bestRule  = $rule;
        }
    }

    if ($bestRule !== null) {
        return ['kategori' => $bestRule['hasil'], 'rule' => $bestRule['nama_rule']];
    }

    return ['kategori' => getLabel('jenis_wisata', $jawaban['jenis_wisata']), 'rule' => 'Default'];
}

/**
 * Kriteria penilaian beserta bobotnya.
 * 'kolom' adalah nama kolom pada tabel wisata yang dibandingkan.
 */
function getBobotKriteria(): array
{
    return [
        'jenis_wisata'    => ['label' => 'Jenis wisata',     'bobot' => 2, 'kolom' => 'jenis_wisata'],
        'budget'          => ['label' => 'Anggaran tiket',   'bobot' => 2, 'kolom' => 'budget_category'],
        'suasana'         => ['label' => 'Suasana',          'bobot' => 1, 'kolom' => 'suasana'],
        'aktivitas'       => ['label' => 'Aktivitas',        'bobot' => 1, 'kolom' => 'aktivitas'],
        'fasilitas'       => ['label' => 'Fasilitas',        'bobot' => 1, 'kolom' => 'fasilitas'],
        'waktu_kunjungan' => ['label' => 'Waktu kunjungan',  'bobot' => 1, 'kolom' => 'waktu_kunjungan'],
    ];
}

/**
 * Menghitung skor kecocokan satu destinasi terhadap jawaban pengguna.
 * Destinasi dengan waktu "sepanjang_hari" selalu cocok untuk kriteria waktu kunjungan.
 *
 * @return array{score:int, max_score:int, persentase:int, rincian:array}
 */
function hitungSkorWisata(array $jawaban, array $wisata): array
{
    $score   = 0;
    $max     = 0;
    $rincian = [];

    foreach (getBobotKriteria() as $key => $kriteria) {
        $nilaiWisata = $wisata[$kriteria['kolom']];

        $cocok = $nilaiWisata === $jawaban[$key];
        if ($key === 'waktu_kunjungan' && $nilaiWisata === 'sepanjang_hari') {
            $cocok = true;
        }

        $max += $kriteria['bobot'];
        if ($cocok) {
            $score += $kriteria['bobot'];
        }

        $rincian[] = [
            'kriteria'   => $kriteria['label'],
            'preferensi' => getLabel($key, $jawaban[$key]),
            'destinasi'  => getLabel($key, $nilaiWisata),
            'cocok'      => $cocok,
            'poin'       => $cocok ? $kriteria['bobot'] : 0,
            'bobot'      => $kriteria['bobot'],
        ];
    }

    return [
        'score'      => $score,
        'max_score'  => $max,
        'persentase' => (int) round($score / $max * 100),
        'rincian'    => $rincian,
    ];
}
