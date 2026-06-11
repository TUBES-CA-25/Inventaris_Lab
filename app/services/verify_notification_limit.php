<?php
// app/services/verify_notification_limit.php

require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/core/Database.php';
require_once __DIR__ . '/../models/Notification_model.php';

echo "=== VERIFICATION START ===\n";

$db = new Database;

// 1. Verify Column Exists
echo "[CHECK] Checking database schema...\n";
$db->query("SHOW COLUMNS FROM trx_peminjaman LIKE 'last_notification_sent'");
$col = $db->single();

if ($col) {
    echo "[PASS] Column 'last_notification_sent' exists.\n";
} else {
    echo "[FAIL] Column 'last_notification_sent' DOES NOT exist.\n";
    exit;
}

// 2. Test Notification Logic (Dry Run Effect)
// We won't actually send emails here easily without mocking, but we can call the method
// and see if it crashes.
echo "[CHECK] Running Notification_model->checkAndRunDaily()...\n";

try {
    $notify = new Notification_model();
    // This will try to send emails if there are any eligible loans.
    // If no loans are eligible (or no mail server), it might return 0 or fail silently.
    // We are mostly checking for SQL errors or Logic errors.
    $result = $notify->checkAndRunDaily();
    echo "[PASS] Method execution completed. Result (Count Sent): " . $result . "\n";
} catch (Exception $e) {
    echo "[FAIL] Method execution failed: " . $e->getMessage() . "\n";
}

echo "=== VERIFICATION END ===\n";
