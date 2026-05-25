<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\EmailQueueProcessor;

/**
 * EmailQueueController
 *
 * Endpoint internal untuk memproses email queue via HTTP request.
 * Digunakan sebagai alternatif cron job — bisa dipanggil dari:
 *   - Windows Task Scheduler (via curl/wget)
 *   - External cron service (cron-job.org, EasyCron, dll)
 *   - Manual trigger via CLI: php spark email:process
 *
 * Security: Endpoint dilindungi dengan secret key (CRON_SECRET di .env)
 * Route: POST /api/email-queue/process
 */
class EmailQueueController extends BaseController
{
    /**
     * Proses email queue via HTTP endpoint
     * Dilindungi dengan CRON_SECRET dari .env
     */
    public function process()
    {
        // Hanya izinkan POST atau GET (untuk kompatibilitas dengan scheduler)
        if (!in_array($this->request->getMethod(), ['post', 'get'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Method not allowed.',
            ])->setStatusCode(405);
        }

        // Validasi secret key
        $cronSecret = env('CRON_SECRET', '');
        if (empty($cronSecret)) {
            // Jika CRON_SECRET tidak di-set, tolak semua request dari luar
            $clientIp = $this->request->getIPAddress();
            $localIps = ['127.0.0.1', '::1', 'localhost'];
            if (!in_array($clientIp, $localIps, true)) {
                log_message('warning', "EmailQueue: Akses ditolak dari IP {$clientIp} — CRON_SECRET belum dikonfigurasi.");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized. Set CRON_SECRET di .env untuk menggunakan endpoint ini dari luar.',
                ])->setStatusCode(403);
            }
        } else {
            // Cek secret key dari header atau query param
            $providedSecret = $this->request->getHeaderLine('X-Cron-Secret')
                ?: $this->request->getGet('secret')
                ?: $this->request->getPost('secret');

            if ($providedSecret !== $cronSecret) {
                log_message('warning', 'EmailQueue: Akses ditolak — secret key tidak valid.');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ])->setStatusCode(403);
            }
        }

        // Jalankan processor
        try {
            $batchSize = (int) ($this->request->getGet('batch') ?: $this->request->getPost('batch') ?: 10);
            $batchSize = max(1, min($batchSize, 50)); // clamp 1–50

            $processor = new EmailQueueProcessor();
            $processed = $processor->process($batchSize);

            log_message('info', "EmailQueue via HTTP: {$processed} email diproses.");

            return $this->response->setJSON([
                'success'   => true,
                'processed' => $processed,
                'message'   => $processed > 0
                    ? "{$processed} email berhasil diproses."
                    : 'Tidak ada email pending.',
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'EmailQueue HTTP error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses email queue.',
            ])->setStatusCode(500);
        }
    }
}
