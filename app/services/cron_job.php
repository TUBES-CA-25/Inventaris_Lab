#!/usr/bin/env php
<?php
// app/services/cron_job.php
// Script ini digunakan untuk mengirim email notifikasi secara otomatis via Windows Task Scheduler.
// Tidak perlu browser atau login user.

// Pastikan dijalankan via CLI
if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui Command Line (CLI).");
}

echo "==========================================\n";
echo "   INVENTARIS LAB - AUTOMATED EMAIL JOB   \n";
echo "==========================================\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";

// Load Helper & Config
// Karena file ini ada di app/services/, kita perlu naik 2 level (../../) untuk ke root folder
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Notification_model.php';

echo "Memuat model dan database...\n";

try {
    $notify = new Notification_model();

    echo "Memeriksa notifikasi yang perlu dikirim...\n";
    $result = $notify->checkAndRunDaily();

    if ($result) {
        echo "[SUKSES] Notifikasi berhasil diproses dan dikirim (jika ada data).\n";
    } else {
        echo "[SKIP] Notifikasi hari ini sudah dijalankan sebelumnya.\n";
    }
} catch (Exception $e) {
    echo "[ERROR] Terjadi kesalahan: " . $e->getMessage() . "\n";
}

echo "==========================================\n";
echo "Done.\n";
