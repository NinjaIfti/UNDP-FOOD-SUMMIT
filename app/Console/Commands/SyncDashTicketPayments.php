<?php

namespace App\Console\Commands;

use App\Services\PaymentSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncDashTicketPayments extends Command
{
    protected $signature = 'payments:sync-dashticket';

    protected $description = 'Sync approved participant payments from DashTicket';

    public function handle(PaymentSyncService $paymentSyncService): int
    {
        $result = $paymentSyncService->syncFromDashTicket();

        if (! $result['ok']) {
            Log::warning('Scheduled DashTicket payment sync skipped', [
                'reason' => $result['message'],
            ]);
            $this->warn($result['message']);
            return self::SUCCESS;
        }

        Log::info('Scheduled DashTicket payment sync finished', $result);
        $this->info($result['message']);

        return self::SUCCESS;
    }
}
