<?php
/**
 * Akses data (query basis data). Seluruh query memakai prepared statement.
 */

/** Daftar destinasi untuk ditampilkan di beranda. */
function getWisataUnggulan(PDO $pdo, int $limit = 4): array
{
    $stmt = $pdo->prepare(
        'SELECT id, nama_wisata, slug, jenis_wisata, harga_tiket, gambar
         FROM wisata ORDER BY id ASC LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function countWisata(PDO $pdo): int
{
    return (int) $pdo->query('SELECT COUNT(*) FROM wisata')->fetchColumn();
}

function getWisataBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM wisata WHERE slug = :slug LIMIT 1');
    $stmt->execute([':slug' => $slug]);
    return $stmt->fetch() ?: null;
}

/** Konsultasi milik sesi yang sedang aktif; null bila tidak ditemukan. */
function getKonsultasi(PDO $pdo, int $id, string $sessionId): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM konsultasi WHERE id = :id AND session_id = :sid LIMIT 1');
    $stmt->execute([':id' => $id, ':sid' => $sessionId]);
    return $stmt->fetch() ?: null;
}

/** Jawaban pengguna dari baris konsultasi, sesuai kriteria penilaian. */
function ambilJawaban(array $konsultasi): array
{
    return array_intersect_key($konsultasi, getBobotKriteria());
}

/** Hasil penilaian seluruh destinasi, diurutkan dari skor tertinggi. */
function getHasilKonsultasi(PDO $pdo, int $konsultasiId): array
{
    $stmt = $pdo->prepare(
        'SELECT hk.score, hk.persentase, w.*
         FROM hasil_konsultasi hk
         JOIN wisata w ON w.id = hk.wisata_id
         WHERE hk.konsultasi_id = :id
         ORDER BY hk.score DESC, w.nama_wisata ASC'
    );
    $stmt->execute([':id' => $konsultasiId]);
    return $stmt->fetchAll();
}

/** Riwayat konsultasi satu sesi beserta rekomendasi teratas masing-masing. */
function getRiwayat(PDO $pdo, string $sessionId): array
{
    $stmt = $pdo->prepare('SELECT * FROM konsultasi WHERE session_id = :sid ORDER BY tanggal DESC');
    $stmt->execute([':sid' => $sessionId]);
    $daftar = $stmt->fetchAll();

    $stmtTop = $pdo->prepare(
        'SELECT hk.persentase, w.nama_wisata, w.slug
         FROM hasil_konsultasi hk
         JOIN wisata w ON w.id = hk.wisata_id
         WHERE hk.konsultasi_id = :id
         ORDER BY hk.score DESC, w.nama_wisata ASC
         LIMIT 1'
    );

    foreach ($daftar as &$k) {
        $stmtTop->execute([':id' => $k['id']]);
        $k['top'] = $stmtTop->fetch() ?: null;
    }
    unset($k);

    return $daftar;
}

/**
 * Menyimpan konsultasi dan skor seluruh destinasi dalam satu transaksi.
 *
 * @return int ID konsultasi baru
 * @throws RuntimeException bila data destinasi kosong
 */
function simpanKonsultasi(PDO $pdo, string $sessionId, array $jawaban): int
{
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO konsultasi (session_id, jenis_wisata, budget, suasana, aktivitas, fasilitas, waktu_kunjungan)
             VALUES (:session_id, :jenis_wisata, :budget, :suasana, :aktivitas, :fasilitas, :waktu_kunjungan)'
        );
        $stmt->execute([
            ':session_id'      => $sessionId,
            ':jenis_wisata'    => $jawaban['jenis_wisata'],
            ':budget'          => $jawaban['budget'],
            ':suasana'         => $jawaban['suasana'],
            ':aktivitas'       => $jawaban['aktivitas'],
            ':fasilitas'       => $jawaban['fasilitas'],
            ':waktu_kunjungan' => $jawaban['waktu_kunjungan'],
        ]);
        $konsultasiId = (int) $pdo->lastInsertId();

        $semuaWisata = $pdo->query('SELECT * FROM wisata')->fetchAll();
        if (!$semuaWisata) {
            throw new RuntimeException('Data destinasi belum tersedia.');
        }

        $stmtHasil = $pdo->prepare(
            'INSERT INTO hasil_konsultasi (konsultasi_id, wisata_id, score, persentase)
             VALUES (:konsultasi_id, :wisata_id, :score, :persentase)'
        );
        foreach ($semuaWisata as $wisata) {
            $skor = hitungSkorWisata($jawaban, $wisata);
            $stmtHasil->execute([
                ':konsultasi_id' => $konsultasiId,
                ':wisata_id'     => $wisata['id'],
                ':score'         => $skor['score'],
                ':persentase'    => $skor['persentase'],
            ]);
        }

        $pdo->commit();
        return $konsultasiId;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

/** Seluruh destinasi, dapat difilter dengan kata kunci (nama, deskripsi, alamat, jenis). */
function getWisataSemua(PDO $pdo, string $kata = ''): array
{
    $sql = 'SELECT id, nama_wisata, slug, deskripsi, jenis_wisata, suasana, aktivitas, harga_tiket, alamat, jam_buka, latitude, longitude, gambar
            FROM wisata';
    $param = [];

    if ($kata !== '') {
        $sql .= ' WHERE nama_wisata LIKE :k1 OR deskripsi LIKE :k2 OR alamat LIKE :k3 OR jenis_wisata LIKE :k4';
        $like  = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $kata) . '%';
        $param = [':k1' => $like, ':k2' => $like, ':k3' => $like, ':k4' => $like];
    }

    $stmt = $pdo->prepare($sql . ' ORDER BY nama_wisata ASC');
    $stmt->execute($param);
    return $stmt->fetchAll();
}

/** ID konsultasi terbaru milik sesi, atau null bila belum pernah konsultasi. */
function getKonsultasiTerakhirId(PDO $pdo, string $sessionId): ?int
{
    $stmt = $pdo->prepare('SELECT id FROM konsultasi WHERE session_id = :sid ORDER BY tanggal DESC, id DESC LIMIT 1');
    $stmt->execute([':sid' => $sessionId]);
    $id = $stmt->fetchColumn();
    return $id !== false ? (int) $id : null;
}
