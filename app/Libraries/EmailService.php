<?php

namespace App\Libraries;

use App\Models\EmailQueueModel;

class EmailService
{
    protected $config;
    protected $mailer;
    protected $queueModel;

    public function __construct()
    {
        $this->ensureAutoload();
        $this->config     = config('Email');
        $this->queueModel = new EmailQueueModel();
        $this->mailer     = new \PHPMailer\PHPMailer\PHPMailer(true);
        $this->configure();
    }

    protected function queueEmail(string $toEmail, string $subject, string $body): bool
    {
        try {
            $this->queueModel->insert([
                'recipient'  => $toEmail,
                'subject'    => $subject,
                'body'       => $body,
                'is_sent'    => EmailQueueModel::STATUS_PENDING,
                'fail_count' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            log_message('info', "Email queued: {$toEmail} - {$subject}");
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Failed to queue email: ' . $e->getMessage());
            return false;
        }
    }

    protected function ensureAutoload(): void
    {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $paths = [
                defined('ROOTPATH') ? ROOTPATH . 'vendor/autoload.php' : null,
                __DIR__ . '/../../vendor/autoload.php',
            ];
            foreach ($paths as $path) {
                if ($path && file_exists($path)) {
                    require_once $path;
                    break;
                }
            }
        }
    }

    protected function configure(): void
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->config->SMTPHost;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->config->SMTPUser;
            $this->mailer->Password   = $this->config->SMTPPass;
            $this->mailer->SMTPSecure = $this->config->SMTPCrypto === 'ssl'
                ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port    = $this->config->SMTPPort;
            $this->mailer->Timeout = $this->config->SMTPTimeout;
            $this->mailer->CharSet = $this->config->charset;
            $this->mailer->setFrom($this->config->fromEmail, $this->config->fromName);
        } catch (\Exception $e) {
            log_message('error', 'EmailService config error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // Public API
    // =========================================================================

    public function sendOtpRegister(string $toEmail, string $toName, string $otp, string $verificationLink): bool
    {
        // OTP langsung di subject agar terlihat tanpa harus buka email
        return $this->queueEmail(
            $toEmail,
            $otp . ' adalah kode verifikasi Anda',
            $this->templateOtp(
                $toName,
                $otp,
                'Verifikasi Akun',
                'Gunakan kode ini untuk menyelesaikan verifikasi akun Anda di Website Desa Bonto Marannu.',
                $verificationLink,
                'Verifikasi Sekarang',
                'Jika Anda tidak mendaftar di Website Desa Bonto Marannu, abaikan saja email ini.'
            )
        );
    }

    public function sendOtpReset(string $toEmail, string $toName, string $otp, ?string $directLink = null): bool
    {
        $link = $directLink ?? base_url('/verify-reset');
        // OTP langsung di subject agar terlihat tanpa harus buka email
        return $this->queueEmail(
            $toEmail,
            $otp . ' adalah kode reset password Anda',
            $this->templateOtp(
                $toName,
                $otp,
                'Reset Password',
                'Gunakan kode ini untuk mereset password akun Anda. Masukkan kode tersebut di halaman verifikasi, atau klik tombol di bawah untuk langsung membuat password baru.',
                $link,
                'Buat Password Baru',
                'Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.'
            )
        );
    }

    public function sendNotification(
        string $toEmail,
        string $toName,
        string $title,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null,
        ?string $letterTitle = null,
        ?string $letterType = null
    ): bool {
        return $this->queueEmail(
            $toEmail,
            $title,
            $this->templateNotification($toName, $title, $message, $actionUrl, $letterTitle, $letterType)
        );
    }

    // =========================================================================
    // Shared Helpers
    // =========================================================================

    private function greenButton(string $url, string $label): string
    {
        $u = htmlspecialchars($url);
        $l = htmlspecialchars($label);
        return <<<HTML
<a href="{$u}" target="_blank"
   style="display:inline-block;background-color:#1a73e8;color:#ffffff;text-decoration:none;
          font-family:'Google Sans','Poppins',Arial,sans-serif;font-size:14px;font-weight:500;
          padding:10px 24px;border-radius:4px;">
  {$l}
</a>
HTML;
        // Catatan: warna #1a73e8 (biru Google) diganti sesuai brand hijau di bawah
    }

    private function wrap(string $body): string
    {
        $year    = date('Y');
        $logo    = 'https://plain-apac-prod-public.komododecks.com/202605/25/pcrPYVNNGSOlX9CqP97R/image.png';
        $site    = 'Website Desa Bonto Marannu';

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
  </style>
</head>
<body style="margin:0;padding:0;background:#f1f3f4;font-family:'Poppins',Arial,sans-serif;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
       style="background:#f1f3f4;padding:24px 16px 32px;">
  <tr>
    <td align="center">
      <!-- Card -->
      <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="520"
             style="max-width:520px;width:100%;background:#ffffff;border-radius:8px;border:1px solid #e0e0e0;">

        <!-- Logo -->
        <tr>
          <td style="padding:28px 40px 0 40px;text-align:center;">
            <img src="{$logo}" alt="{$site}" width="64" height="64"
                 style="display:inline-block;border-radius:8px;width:64px;height:64px;object-fit:contain;">
          </td>
        </tr>

        <!-- Body -->
        {$body}

        <!-- Footer -->
        <tr>
          <td style="padding:20px 40px 28px 40px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#80868b;font-family:'Poppins',Arial,sans-serif;line-height:1.6;">
              Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
            </p>
            <p style="margin:6px 0 0 0;font-size:12px;color:#80868b;font-family:'Poppins',Arial,sans-serif;">
              &copy; {$year} {$site}
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;
    }

    // =========================================================================
    // Templates
    // =========================================================================

    /**
     * Template tunggal untuk OTP (register & reset) — Google-style minimal
     */
    private function templateOtp(
        string $name,
        string $otp,
        string $title,
        string $description,
        string $buttonUrl,
        string $buttonLabel,
        string $disclaimer
    ): string {
        $nameEsc  = htmlspecialchars($name);
        $otpEsc   = htmlspecialchars($otp);
        $titleEsc = htmlspecialchars($title);
        $descEsc  = htmlspecialchars($description);
        $disclEsc = htmlspecialchars($disclaimer);
        $urlEsc   = htmlspecialchars($buttonUrl);
        $lblEsc   = htmlspecialchars($buttonLabel);

        $body = <<<HTML

        <!-- Title -->
        <tr>
          <td style="padding:24px 40px 0 40px;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:#202124;
                       font-family:'Poppins',Arial,sans-serif;">
              {$titleEsc}
            </h1>
          </td>
        </tr>

        <!-- Description -->
        <tr>
          <td style="padding:12px 40px 0 40px;">
            <p style="margin:0;font-size:14px;color:#3c4043;line-height:1.7;
                      font-family:'Poppins',Arial,sans-serif;">
              Halo <strong>{$nameEsc}</strong>,
            </p>
            <p style="margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;
                      font-family:'Poppins',Arial,sans-serif;">
              {$descEsc}
            </p>
          </td>
        </tr>

        <!-- OTP Code — besar, tanpa box -->
        <tr>
          <td style="padding:28px 40px 8px 40px;text-align:center;">
            <p style="margin:0;font-size:42px;font-weight:600;color:#202124;letter-spacing:8px;
                      font-family:'Poppins',Arial,sans-serif;">
              {$otpEsc}
            </p>
            <p style="margin:6px 0 0 0;font-size:13px;color:#80868b;font-family:'Poppins',Arial,sans-serif;">
              Kode ini berlaku selama 1 jam.
            </p>
          </td>
        </tr>

        <!-- Button -->
        <tr>
          <td style="padding:24px 40px 0 40px;text-align:center;">
            <a href="{$urlEsc}" target="_blank"
               style="display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;
                      font-family:'Poppins',Arial,sans-serif;font-size:14px;font-weight:500;
                      padding:10px 28px;border-radius:4px;">
              {$lblEsc}
            </a>
          </td>
        </tr>

        <!-- Divider -->
        <tr>
          <td style="padding:24px 40px 0 40px;">
            <div style="height:1px;background:#e0e0e0;"></div>
          </td>
        </tr>

        <!-- Disclaimer -->
        <tr>
          <td style="padding:16px 40px 0 40px;">
            <p style="margin:0;font-size:12px;color:#80868b;line-height:1.6;
                      font-family:'Poppins',Arial,sans-serif;">
              {$disclEsc}
            </p>
          </td>
        </tr>

HTML;
        return $this->wrap($body);
    }

    /**
     * Template notifikasi — Google-style minimal
     */
    private function templateNotification(
        string $name,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $letterTitle = null,
        ?string $letterType = null
    ): string {
        $nameEsc    = htmlspecialchars($name);
        $titleEsc   = htmlspecialchars($title);
        $messageEsc = htmlspecialchars($message);

        // Baris perihal — ditampilkan di bawah message jika ada judul surat/pengaduan
        $perihalHtml = '';
        if ($letterTitle) {
            $perihalEsc = htmlspecialchars($letterTitle);
            $typeLabel  = $letterType ? htmlspecialchars($letterType) : 'Perihal';
            $perihalHtml = <<<HTML
        <tr>
          <td style="padding:12px 40px 0 40px;">
            <p style="margin:0;font-size:12px;color:#80868b;font-family:'Poppins',Arial,sans-serif;
                       text-transform:uppercase;letter-spacing:0.5px;">
              {$typeLabel}
            </p>
            <p style="margin:2px 0 0 0;font-size:14px;font-weight:600;color:#202124;
                       font-family:'Poppins',Arial,sans-serif;">
              {$perihalEsc}
            </p>
          </td>
        </tr>
HTML;
        }

        $btnHtml = '';
        if ($actionUrl) {
            $urlEsc  = htmlspecialchars($actionUrl);
            $btnHtml = <<<HTML
        <tr>
          <td style="padding:24px 40px 0 40px;text-align:center;">
            <a href="{$urlEsc}" target="_blank"
               style="display:inline-block;background-color:#16a34a;color:#ffffff;text-decoration:none;
                      font-family:'Poppins',Arial,sans-serif;font-size:14px;font-weight:500;
                      padding:10px 28px;border-radius:4px;">
              Lihat Selengkapnya
            </a>
          </td>
        </tr>
HTML;
        }

        $divider = $actionUrl ? <<<HTML
        <tr>
          <td style="padding:24px 40px 0 40px;">
            <div style="height:1px;background:#e0e0e0;"></div>
          </td>
        </tr>
HTML : '';

        $body = <<<HTML

        <!-- Title -->
        <tr>
          <td style="padding:24px 40px 0 40px;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:#202124;
                       font-family:'Poppins',Arial,sans-serif;">
              {$titleEsc}
            </h1>
          </td>
        </tr>

        <!-- Message -->
        <tr>
          <td style="padding:12px 40px 0 40px;">
            <p style="margin:0;font-size:14px;color:#3c4043;line-height:1.7;
                      font-family:'Poppins',Arial,sans-serif;">
              Halo <strong>{$nameEsc}</strong>,
            </p>
            <p style="margin:8px 0 0 0;font-size:14px;color:#3c4043;line-height:1.7;
                      font-family:'Poppins',Arial,sans-serif;">
              {$messageEsc}
            </p>
          </td>
        </tr>

        <!-- Perihal (jika ada) -->
        {$perihalHtml}

        {$btnHtml}

        {$divider}

        <tr><td style="padding-bottom:4px;"></td></tr>

HTML;
        return $this->wrap($body);
    }
}
