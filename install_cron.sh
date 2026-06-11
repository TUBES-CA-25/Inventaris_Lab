#!/bin/bash

# Script Instalasi Otomatis Cron Job untuk Inventaris Lab (Linux/Ubuntu/CentOS)
# Jalankan script ini di terminal server Anda: ./install_cron.sh

# 1. Tentukan Path Project dan PHP
PROJECT_DIR=$(pwd)
PHP_PATH=$(which php)
CRON_SCRIPT="$PROJECT_DIR/cron_job.php"
LOG_FILE="$PROJECT_DIR/cron_output.log"

# Cek apakah PHP terinstall
if [ -z "$PHP_PATH" ]; then
    echo "[ERROR] PHP tidak ditemukan. Pastikan PHP sudah terinstall."
    exit 1
fi

# Cek keberadaan cron_job.php
if [ ! -f "$CRON_SCRIPT" ]; then
    echo "[ERROR] File cron_job.php tidak ditemukan di direktori ini."
    echo "Pastikan Anda menjalankan script ini dari root folder project."
    exit 1
fi

# Beri permission execute ke cron_job.php
chmod +x "$CRON_SCRIPT"

# 2. Siapkan Perintah Cron (Jalan setiap hari jam 05:00 Pagi)
# Format: 0 5 * * * /path/to/php /path/to/script >> /path/to/log 2>&1
CRON_CMD="0 5 * * * $PHP_PATH $CRON_SCRIPT >> $LOG_FILE 2>&1"

# 3. Cek apakah cron job sudah ada sebelumnya untuk menghindari duplikasi
EXISTING_CRON=$(crontab -l 2>/dev/null | grep "$CRON_SCRIPT")

if [ -n "$EXISTING_CRON" ]; then
    echo "[INFO] Cron job untuk script ini sudah ada. Tidak perlu menambahkan lagi."
    echo "Existing: $EXISTING_CRON"
else
    # 4. Tambahkan ke Crontab
    # Simpan crontab lama, tambahkan baris baru, lalu load kembali
    (crontab -l 2>/dev/null; echo "$CRON_CMD") | crontab -
    
    echo "[SUKSES] Cron job berhasil ditambahkan!"
    echo "Email otomatis akan dikirim setiap hari pukul 05:00 pagi."
    echo "Log output dapat dilihat di: $LOG_FILE"
    echo "Perintah yang ditambahkan:"
    echo "$CRON_CMD"
fi

echo "========================================"
echo "Test Run (Mencoba menjalankan script sekarang...)"
$PHP_PATH $CRON_SCRIPT
echo "========================================"
