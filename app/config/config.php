<?php
// Check if running in CLI or Web Server
if (php_sapi_name() === 'cli') {
    $httpHost = 'localhost'; // Fallback for CLI
} else {
    $httpHost = $_SERVER['HTTP_HOST'];
}
define('BASEURL', 'http://' . $httpHost . '/Inventaris_Lab1/public/');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'inventori_db');

// ID Encryption Keys
define('ID_ENCRYPTION_KEY', 'InventarisLabSecreetKey2024!!'); // 32 chars recommended for AES-256
define('ID_ENCRYPTION_IV', '1234567890123456'); // 16 chars for AES-256-CBC

// Session Timeout Duration (in seconds)
// 1800 seconds = 30 minutes
define('SESSION_TIMEOUT_DURATION', 1800);

// Development Mode - set to false in production
// Jika true, akan menampilkan detail error
define('DEVELOPMENT_MODE', true); // Change to false in production

// Email Configuration for PHPMailer
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'andiahsanashuri@gmail.com'); // TODO: Ganti dengan email Gmail Anda
define('MAIL_PASSWORD', 'jkdm otvp mwul jhbt');    // TODO: Ganti dengan App Password dari Google
define('MAIL_FROM_NAME', 'InventarisLab_Email');
define('VERIFICATION_LINK_EXPIRY', 24); // Durasi expired token dalam jam

// WhatsApp Gateway (Fonnte) Configuration
define('FONNTE_API_TOKEN', '3sLQ7VNQPxSibtHmirvN'); // TODO: Ganti dengan Token dari https://fonnte.com

// Application Versioning (for asset caching)
define('APP_VERSION', '1.0.1');