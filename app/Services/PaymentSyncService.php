<?php

namespace App\Services;

use App\Models\SummitRegistration;
use Illuminate\Support\Facades\Storage;

class PaymentSyncService
{
    public function __construct(
        protected AdminPaymentService $payments,
    ) {}

    public function syncFromDashTicket(): array
    {
        $dashTicket = app(DashTicketService::class);

        if (! $dashTicket->isConfigured()) {
            return [
                'ok' => false,
                'updated_count' => 0,
                'message' => 'DashTicket API key is not configured.',
            ];
        }

        $approved = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->get();
        $paymentData = $this->payments->getAll();
        $updatedCount = 0;

        foreach ($approved as $registration) {
            if (PackageService::isSponsoredTier($registration->price_tier_raw ?? $registration->price_tier)) {
                continue;
            }

            $current = $paymentData[$registration->id] ?? ['status' => 'pending'];
            $wasPaid = ($current['status'] ?? 'pending') === 'paid';

            if (in_array($current['status'] ?? 'pending', ['refunded'], true)) {
                continue;
            }

            $status = $dashTicket->getTicketStatus($registration->email);

            if ($status === null) {
                continue;
            }

            $now = now()->toISOString();
            $patch = [];

            if (($status['ticket_purchased'] ?? false) === true && ! $wasPaid) {
                $resolvedAmount = PackageService::resolvePaymentAmountForRegistration($registration);

                $patch = array_merge($patch, [
                    'status' => 'paid',
                    'amount' => $resolvedAmount,
                    'updated_at' => $now,
                    'synced_from_dashticket' => true,
                    'synced_at' => $now,
                ]);
                $updatedCount++;
            }

            if (array_key_exists('upgrade_paid', $status)) {
                $storedUpgrade = $current['upgrade_paid'] ?? null;
                if ($storedUpgrade !== $status['upgrade_paid']) {
                    $patch['upgrade_paid'] = (bool) $status['upgrade_paid'];
                    $patch['upgrade_synced_at'] = $now;
                    if ($wasPaid) {
                        $updatedCount++;
                    }
                }
            }

            if ($wasPaid && ($current['status'] ?? '') === 'paid') {
                $storedAmount = $current['amount'] ?? null;
                if ($storedAmount === null || $storedAmount === '' || (float) $storedAmount <= 0) {
                    $resolved = PackageService::resolvePaymentAmountForRegistration($registration);
                    if ($resolved !== null && $resolved > 0) {
                        $patch['amount'] = $resolved;
                    }
                }
            }

            if ($patch !== []) {
                $paymentData[$registration->id] = array_merge($current, $patch);
            }
        }

        Storage::put('admin/payments.json', json_encode($paymentData, JSON_PRETTY_PRINT));

        $message = $updatedCount > 0
            ? "Sync complete. {$updatedCount} participant(s) marked as paid from DashTicket."
            : 'Sync complete. No new paid tickets found.';

        return [
            'ok' => true,
            'updated_count' => $updatedCount,
            'message' => $message,
        ];
    }

}
