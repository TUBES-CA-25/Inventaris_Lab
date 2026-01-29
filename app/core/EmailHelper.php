<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper
{

    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        // SMTP Configuration
        $this->mailer->isSMTP();
        $this->mailer->Host = MAIL_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = MAIL_USERNAME;
        $this->mailer->Password = MAIL_PASSWORD;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = MAIL_PORT;

        // Sender Info
        $this->mailer->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
        $this->mailer->isHTML(true);
    }

    /**
     * Send verification email to user
     * 
     * @param string $email User's email address
     * @param string $name User's name
     * @param string $token Verification token
     * @return bool True if email sent successfully, false otherwise
     */
    public function sendVerificationEmail($email, $name, $token)
    {
        try {
            // Generate verification link - using path parameter instead of query string
            $verificationLink = BASEURL . "Auth/verify/" . urlencode($token);

            // Email recipient
            $this->mailer->addAddress($email, $name);

            // Email subject
            $this->mailer->Subject = 'Verifikasi Email - Inventaris Lab';

            // Email body (HTML)
            $this->mailer->Body = $this->getVerificationEmailTemplate($name, $verificationLink);

            // Plain text alternative
            $this->mailer->AltBody = "Halo $name,\n\n"
                . "Terima kasih telah mendaftar di Sistem Inventaris Lab.\n\n"
                . "Silakan klik link berikut untuk verifikasi email Anda:\n"
                . "$verificationLink\n\n"
                . "Link ini akan expired dalam " . VERIFICATION_LINK_EXPIRY . " jam.\n\n"
                . "Jika Anda tidak merasa mendaftar, abaikan email ini.\n\n"
                . "Salam,\nTim Inventaris Lab";

            // Send email
            $this->mailer->send();
            return true;

        } catch (Exception $e) {
            // Log error if needed
            error_log("Email verification failed: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    /**
     * Get HTML template for verification email
     * 
     * @param string $name User's name
     * @param string $link Verification link
     * @return string HTML email template
     */
    private function getVerificationEmailTemplate($name, $link)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: #ffffff;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 30px 20px;
                }
                .content h2 {
                    color: #667eea;
                    font-size: 20px;
                    margin-top: 0;
                }
                .button {
                    display: inline-block;
                    padding: 14px 30px;
                    margin: 20px 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    text-align: center;
                }
                .button:hover {
                    opacity: 0.9;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                .warning {
                    background: #fff3cd;
                    border-left: 4px solid #ffc107;
                    padding: 12px;
                    margin: 20px 0;
                    font-size: 14px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📧 Verifikasi Email Anda</h1>
                </div>
                <div class='content'>
                    <h2>Halo, $name!</h2>
                    <p>Terima kasih telah mendaftar di <strong>Sistem Inventaris Lab</strong>.</p>
                    <p>Untuk menyelesaikan proses registrasi, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
                    
                    <div style='text-align: center;'>
                        <a href='$link' class='button'>✓ Verifikasi Email Sekarang</a>
                    </div>
                    
                    <p>Atau salin dan tempel link berikut ke browser Anda:</p>
                    <p style='word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 13px;'>
                        $link
                    </p>
                    
                    <div class='warning'>
                        <strong>⚠️ Penting:</strong> Link verifikasi ini akan <strong>expired dalam " . VERIFICATION_LINK_EXPIRY . " jam</strong>.
                    </div>
                    
                    <p style='margin-top: 20px; font-size: 14px; color: #666;'>
                        Jika Anda tidak merasa mendaftar akun ini, silakan abaikan email ini dan tidak ada tindakan lebih lanjut yang diperlukan.
                    </p>
                </div>
                <div class='footer'>
                    <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                    <p>&copy; " . date('Y') . " Inventaris Lab. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
