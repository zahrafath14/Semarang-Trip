<?php
/**
 * Titik masuk bersama untuk seluruh halaman:
 * sesi, konfigurasi, koneksi database, fungsi bantu, dan mesin sistem pakar.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/labels.php';
require_once __DIR__ . '/questions.php';
require_once __DIR__ . '/expert_system.php';
require_once __DIR__ . '/repository.php';
