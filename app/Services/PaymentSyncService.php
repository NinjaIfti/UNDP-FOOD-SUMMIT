<?php

namespace App\Services;

use App\Models\SummitRegistration;
use Illuminate\Support\Facades\Log;
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
        $sponsoredSkipped = 0;
        $refundedConflicts = 0;
        $notFoundCount = 0;
        $lookupErrors = 0;
        $checked = 0;

        foreach ($approved as $registration) {
            if (PackageService::isSponsoredTier($registration->price_tier_raw ?? $registration->price_tier)) {
                $sponsoredSkipped++;
                continue;
            }

            $current = $paymentData[$registration->id] ?? ['status' => 'pending'];
            $wasPaid = ($current['status'] ?? 'pending') === 'paid';
            $isRefunded = ($current['status'] ?? 'pending') === 'refunded';

            $checked++;
            $status = $dashTicket->getTicketStatus($registration->email);

            // Throttle so a large batch of sequential lookups doesn't trip DashTicket's rate limiting.
            usleep(100_000);

            if ($status === null) {
                $lookupErrors++;
                continue;
            }

            if ($status['found'] === false) {
                $notFoundCount++;
            }

            $ticketPurchased = ($status['ticket_purchased'] ?? false) === true;

            if ($isRefunded) {
                // Refund is a deliberate admin decision; never auto-flip it back to paid.
                // But if DashTicket still shows the ticket as paid, that's a real conflict
                // worth knowing about instead of silently disappearing every sync.
                if ($ticketPurchased) {
                    $refundedConflicts++;
                    Log::warning('DashTicket: registration marked refunded locally but ticket is paid upstream', [
                        'registration_id' => $registration->id,
                        'email' => $registration->email,
                    ]);
                }
                continue;
            }

            $now = now()->toISOString();
            $patch = [];

            if ($ticketPurchased && ! $wasPaid) {
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

        Log::info('DashTicket payment sync summary', [
            'checked' => $checked,
            'updated_count' => $updatedCount,
            'sponsored_skipped' => $sponsoredSkipped,
            'refunded_conflicts' => $refundedConflicts,
            'not_found_on_dashticket' => $notFoundCount,
            'lookup_errors' => $lookupErrors,
        ]);

        $messageParts = [$updatedCount > 0
            ? "{$updatedCount} participant(s) marked as paid from DashTicket."
            : 'No new paid tickets found.'];

        if ($refundedConflicts > 0) {
            $messageParts[] = "{$refundedConflicts} refunded record(s) show as paid on DashTicket — flagged in logs for review.";
        }

        if ($lookupErrors > 0) {
            $messageParts[] = "{$lookupErrors} lookup(s) failed and will retry next sync.";
        }

        return [
            'ok' => true,
            'updated_count' => $updatedCount,
            'sponsored_skipped' => $sponsoredSkipped,
            'refunded_conflicts' => $refundedConflicts,
            'not_found_count' => $notFoundCount,
            'lookup_errors' => $lookupErrors,
            'message' => 'Sync complete. ' . implode(' ', $messageParts),
        ];
    }

}
