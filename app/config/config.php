<?php

define('BASEURL', 'http://localhost/Inventaris_Lab1/public/');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'inventori_db5');

// ID Encryption Keys
define('ID_ENCRYPTION_KEY', 'InventarisLabSecreetKey2024!!'); // 32 chars recommended for AES-256
define('ID_ENCRYPTION_IV', '1234567890123456'); // 16 chars for AES-256-CBC

// Session Timeout Duration (in seconds)
// 1800 seconds = 30 minutes
define('SESSION_TIMEOUT_DURATION', 1800);
