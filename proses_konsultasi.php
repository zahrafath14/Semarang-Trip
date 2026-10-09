<?php
/**
 * Memproses jawaban konsultasi: validasi, penyimpanan, penilaian, lalu pengalihan ke hasil.
 */
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('konsultasi.php'));
    exit;
}

// Validasi: setiap pertanyaan wajib dijawab dengan nilai yang tersedia pada opsi.
$jawaban = [];
foreach (getQuestions() as $q) {
    $value = trim($_POST[$q['key']] ?? '');
    if (!array_key_exists($value, $q['options'])) {
        header('Location: ' . url('konsultasi.php?error=belum_lengkap'));
        exit;
    }
    $jawaban[$q['key']] = $value;
}

// Sesi anonim untuk fitur riwayat tanpa login.
if (empty($_SESSION['session_id'])) {
    $_SESSION['session_id'] = bin2hex(random_bytes(16));
}

try {
    $konsultasiId = simpanKonsultasi($pdo, $_SESSION['session_id'], $jawaban);
} catch (Throwable $e) {
    error_log('Proses konsultasi gagal: ' . $e->getMessage());
    http_response_code(500);
    exit('Konsultasi tidak dapat diproses saat ini. Silakan coba kembali.');
}

header('Location: ' . url('hasil.php?konsultasi_id=' . $konsultasiId));
exit;
