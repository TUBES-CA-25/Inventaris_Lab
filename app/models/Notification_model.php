<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Notification_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
        // Tidak perlu require autoloader lagi karena sudah di init.php
    }

    /**
     * Kirim email generik
     */
    public function sendEmail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            // $mail->SMTPDebug = 2; // Aktifkan untuk debug jika error
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MAIL_PORT;

            // Recipients
            $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
            $mail->addAddress($to);

            // Attach Logo
            $logoPath = __DIR__ . '/../../public/img/logo bg hitam.svg';
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'logo_app', 'logo_app');
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Bisa log error di sini: $mail->ErrorInfo
            return false;
        }
    }

    /**
     * Cari peminjaman yang akan berakhir BESOK (H-1)
     * Status harus 'disetujui' (sedang dipinjam)
     */
    public function cekHampirHabis()
    {
        // Cari yang selisih tanggal pengembalian dengan hari ini = 0 s.d 3 hari
        // 0 = Hari ini (Jatuh Tempo), 1 = Besok, dst.
        $today = date('Y-m-d');
        $query = "SELECT p.*, d.nama_user, u.email 
                  FROM trx_peminjaman p
                  JOIN trx_user u ON p.id_user = u.id_user
                  JOIN trx_data_user d ON p.id_user = d.id_user
                  WHERE p.status = 'disetujui' 
                  AND DATEDIFF(p.tanggal_pengembalian, '$today') BETWEEN 0 AND 3";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Cari peminjaman yang SUDAH terlambat
     * Tanggal pengembalian < Hari Ini
     */
    public function cekTerlambat()
    {
        $query = "SELECT p.*, d.nama_user, u.email 
                  FROM trx_peminjaman p
                  JOIN trx_user u ON p.id_user = u.id_user
                  JOIN trx_data_user d ON p.id_user = d.id_user
                  WHERE p.status = 'disetujui' 
                  AND p.tanggal_pengembalian < CURDATE()";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Generate Professional HTML Email Template
     */
    private function getHtmlTemplate($title, $message, $details = [], $isWarning = false)
    {
        // Colors
        $primaryColor = "#0c1740"; // Dark Blue (Project Theme)
        $accentColor = $isWarning ? "#ef4444" : "#f59e0b"; // Red for Danger, Amber for Warning
        $bgColor = "#f3f4f6";
        $cardColor = "#ffffff";

        // Build Detail Rows
        $detailRows = "";
        foreach ($details as $label => $value) {
            $detailRows .= "
                <tr>
                    <td style='padding: 8px 0; color: #6b7280; font-size: 14px;'>{$label}</td>
                    <td style='padding: 8px 0; color: #111827; font-weight: 600; font-size: 14px; text-align: right;'>{$value}</td>
                </tr>
                <tr><td colspan='2' style='border-bottom: 1px solid #e5e7eb;'></td></tr>
            ";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 0; font-family: sans-serif; background-color: {$bgColor};'>
            <div style='max-width: 600px; margin: 0 auto; padding: 40px 20px;'>
                
                <!-- Logo / Header -->
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='cid:logo_app' alt='Inventaris Lab Logo' style='height: 50px; width: auto; display: inline-block;'>
                </div>
                <!-- Brand Name -->
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: {$primaryColor}; margin: 0; font-size: 24px; letter-spacing: -0.5px;'>INVENTARIS LAB</h1>
                </div>

                <!-- Card -->
                <div style='background-color: {$cardColor}; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);'>
                    
                    <!-- Status Bar -->
                    <div style='background-color: {$accentColor}; padding: 12px; text-align: center;'>
                        <span style='color: white; font-weight: bold; letter-spacing: 1px; font-size: 14px; text-transform: uppercase;'>
                            {$title}
                        </span>
                    </div>

                    <!-- Content -->
                    <div style='padding: 30px;'>
                        <p style='color: #374151; font-size: 16px; line-height: 1.6; margin-top: 0;'>
                            {$message}
                        </p>

                        <!-- Details Table -->
                        <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                            {$detailRows}
                        </table>

                        <!-- CTA Button (Optional link to app) -->
                        <div style='margin-top: 30px; text-align: center;'>
                            <a href='" . BASEURL . "' style='display: inline-block; background-color: {$primaryColor}; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 14px;'>
                                Buka Aplikasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div style='text-align: center; margin-top: 24px; color: #9ca3af; font-size: 12px;'>
                    <p>&copy; " . date('Y') . " Inventaris Lab. All rights reserved.</p>
                    <p>Sistem ini mengirim notifikasi otomatis, mohon tidak membalas email ini.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Proses pengiriman notifikasi masal
     */
    public function prosesNotifikasiOtomatis()
    {
        $countSent = 0;

        // 1. Notifikasi H-1 (Peringatan / Warning)
        $hampirHabis = $this->cekHampirHabis();
        foreach ($hampirHabis as $item) {
            $tgl_kembali = strtotime($item['tanggal_pengembalian']);
            $today = strtotime(date('Y-m-d'));
            $diff = $tgl_kembali - $today;
            $hari_sisa = floor($diff / (60 * 60 * 24));

            $subject = "⚠️ Peringatan Pengembalian - Inventaris Lab";

            if ($hari_sisa == 0) {
                $statusWaktu = "JATUH TEMPO HARI INI";
            } else {
                $statusWaktu = "{$hari_sisa} HARI LAGI";
            }

            $message = "Halo <b>{$item['nama_user']}</b>,<br>Ini adalah pengingat bahwa masa peminjaman barang Anda akan segera berakhir. Mohon persiapkan pengembalian barang tepat waktu.";

            $details = [
                "Judul Kegiatan" => $item['judul_kegiatan'],
                "Tanggal Kembali" => date('d M Y', strtotime($item['tanggal_pengembalian'])),
                "Sisa Waktu" => $statusWaktu
            ];

            $body = $this->getHtmlTemplate("Peringatan Pengembalian", $message, $details, false);

            if ($this->sendEmail($item['email'], $subject, $body)) {
                $countSent++;
            }
        }

        // 2. Notifikasi Terlambat (Bahaya / Danger)
        $terlambat = $this->cekTerlambat();
        foreach ($terlambat as $item) {
            $tgl_kembali = strtotime($item['tanggal_pengembalian']);
            $today = strtotime(date('Y-m-d'));
            $diff = $today - $tgl_kembali;
            $hari_telat = floor($diff / (60 * 60 * 24));

            $subject = "🚨 KETERLAMBATAN PENGEMBALIAN - Inventaris Lab";

            $message = "Halo <b>{$item['nama_user']}</b>,<br>Masa peminjaman barang Anda <b>SUDAH BERAKHIR</b>. Mohon segera kembalikan barang ke laboratorium untuk menghindari sanksi.";

            $details = [
                "Judul Kegiatan" => $item['judul_kegiatan'],
                "Tanggal Kembali" => date('d M Y', strtotime($item['tanggal_pengembalian'])),
                "Keterlambatan" => "{$hari_telat} Hari"
            ];

            $body = $this->getHtmlTemplate("Terlambat Mengembalikan", $message, $details, true);

            if ($this->sendEmail($item['email'], $subject, $body)) {
                $countSent++;
            }
        }

        return $countSent;
    }
    /**
     * Cek apakah notifikasi hari ini sudah dijalankan (System-Wide)
     * Menggunakan File Lock agar hanya jalan 1x sehari, siapapun yang login.
     */
    public function checkAndRunDaily()
    {
        $lockFile = __DIR__ . '/../../app/config/last_email_run.txt'; // Path ke file lock
        $today = date('Y-m-d');

        // 1. Cek file lock
        if (file_exists($lockFile)) {
            $lastRun = file_get_contents($lockFile);
            if (trim($lastRun) == $today) {
                // Sudah jalan hari ini, skip
                return false;
            }
        }

        // 2. Belum jalan hari ini, jalankan
        $this->prosesNotifikasiOtomatis();

        // 3. Update file lock
        file_put_contents($lockFile, $today);
        return true;
    }
}
