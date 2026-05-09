<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\EmailQueueProcessor;

class ProcessEmailQueue extends BaseCommand
{
    protected $group       = 'Email';
    protected $name        = 'email:process';
    protected $description = 'Process email queue via Cron Job';
    protected $usage       = 'email:process [batch_size]';
    protected $arguments   = [
        'batch_size' => 'Number of emails to process per run (default 10)',
    ];

    public function run(array $params)
    {
        $batchSize = $params[0] ?? 10;
        
        CLI::write('[' . date('Y-m-d H:i:s') . '] Processing email queue (batch: ' . $batchSize . ')...', 'yellow');
        
        try {
            $processor = new EmailQueueProcessor();
            $processed = $processor->process((int)$batchSize);
            
            if ($processed > 0) {
                CLI::write('[' . date('Y-m-d H:i:s') . '] Successfully processed ' . $processed . ' emails', 'green');
            } else {
                CLI::write('[' . date('Y-m-d H:i:s') . '] No pending emails to process', 'light_gray');
            }
        } catch (\Exception $e) {
            CLI::error('[' . date('Y-m-d H:i:s') . '] Error processing email queue: ' . $e->getMessage());
        }
    }
}

