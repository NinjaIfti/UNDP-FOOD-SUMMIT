<?php

namespace App\Services;

use App\Models\SummitRegistration;
use Illuminate\Support\Facades\Log;

class DashTicketUpgradeService
{
    public function __construct(
        protected DashTicketService $dashTicket,
        protected AdminPaymentService $payments,
    ) {}

    /**
     * Update local entitlements and re-sync to DashTicket (delta upgrade when already paid).
     *
     * @return array{ok: bool, message: string, upgrade_status?: string|null}
     */
    public function applyUpgrade(SummitRegistration $registration, array $validated): array
    {
        if ($registration->status !== SummitRegistration::STATUS_APPROVED) {
            return ['ok' => false, 'message' => 'Only approved participants can be upgraded.'];
        }

        if (PackageService::isSponsoredTier($registration->price_tier_raw ?? $registration->price_tier)) {
            return ['ok' => false, 'message' => 'Sponsored participants are not registered in DashTicket and cannot be upgraded through this flow.'];
        }

        if (! $this->dashTicket->isConfigured()) {
            return ['ok' => false, 'message' => 'DashTicket API key is not configured.'];
        }

        $tierMap = collect(PackageService::getTiers())
            ->keyBy(fn ($t) => (string) ($t['id'] ?? ''));

        $tierRaw = $validated['price_tier_raw'] ?? $registration->price_tier_raw ?? $registration->price_tier;
        $tier = $tierMap->get((string) $tierRaw);

        $update = [
            'field_visit' => (bool) ($validated['field_visit'] ?? $registration->field_visit),
        ];

        if (! empty($tierRaw)) {
            $update['price_tier_raw'] = $tierRaw;
            $update['price_tier'] = $tierRaw;
            $update['price_tier_name'] = $tier['name'] ?? $registration->price_tier_name;
        }

        if (array_key_exists('admin_override_price', $validated)) {
            $update['admin_override_price'] = $validated['admin_override_price'];
        }

        $registration->update($update);

        $registration = $registration->fresh();
        $password = $this->payments->getDashTicketPassword($registration->id);

        if ($password === null) {
            $password = $this->dashTicket->generatePasswordForStorage();
            $this->payments->storeDashTicketPassword($registration->id, $password);
            Log::warning('DashTicket upgrade: no stored password; generated new password for re-registration', [
                'registration_id' => $registration->id,
                'email' => $registration->email,
            ]);
        }

        $payload = $registration->toArray();
        $payload['price_tier'] = (string) ($registration->price_tier_raw ?? $registration->price_tier ?? '');
        $payload['dashticket_password'] = $password;

        if (! empty($registration->admin_override_price)) {
            $payload['admin_override_price'] = (float) $registration->admin_override_price;
        }

        if (! $this->dashTicket->registerUser($payload)) {
            return [
                'ok' => false,
                'message' => 'Failed to sync entitlements with DashTicket. Check logs for details.',
            ];
        }

        $status = $this->dashTicket->getTicketStatus($registration->email);
        $upgradeStatus = $this->resolveUpgradeStatusLabel($status);

        if ($status !== null && array_key_exists('upgrade_paid', $status)) {
            $this->payments->save($registration->id, [
                'upgrade_paid' => (bool) $status['upgrade_paid'],
                'upgrade_synced_at' => now()->toISOString(),
            ]);
        }

        $message = $upgradeStatus
            ? "Entitlements synced. DashTicket status: {$upgradeStatus}."
            : 'Entitlements synced with DashTicket successfully.';

        return [
            'ok' => true,
            'message' => $message,
            'upgrade_status' => $upgradeStatus,
        ];
    }

    public function resolveUpgradeStatusLabel(?array $ticketStatus): ?string
    {
        if ($ticketStatus === null) {
            return null;
        }

        if (! array_key_exists('upgrade_paid', $ticketStatus)) {
            return ($ticketStatus['ticket_purchased'] ?? false) ? 'Ticket confirmed' : null;
        }

        if (($ticketStatus['upgrade_paid'] ?? false) === true) {
            return 'Upgrade confirmed';
        }

        return 'Upgrade pending payment';
    }
}
