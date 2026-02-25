<?php
// app/services/migration_add_notification_column.php

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/core/Database.php';

class Migration
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function run()
    {
        echo "Mengakses Database...\n";

        // Check columns in trx_peminjaman
        $this->db->query("SHOW COLUMNS FROM trx_peminjaman LIKE 'last_notification_sent'");
        $result = $this->db->single();

        if ($result) {
            echo "[INFO] Kolom 'last_notification_sent' sudah ada. Tidak perlu migrasi.\n";
            return;
        }

        echo "[PROCESS] Menambahkan kolom 'last_notification_sent' ke tabel 'trx_peminjaman'...\n";

        try {
            $query = "ALTER TABLE trx_peminjaman ADD COLUMN last_notification_sent DATETIME NULL DEFAULT NULL AFTER status";
            $this->db->query($query);
            $this->db->execute();
            echo "[SUCCESS] Kolom 'last_notification_sent' berhasil ditambahkan.\n";
        } catch (Exception $e) {
            echo "[ERROR] Gagal menambahkan kolom: " . $e->getMessage() . "\n";
        }
    }
}

$migration = new Migration();
$migration->run();
