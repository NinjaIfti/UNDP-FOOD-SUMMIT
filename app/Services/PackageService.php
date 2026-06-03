<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PackageService
{
    public const SPONSORED_TIER_ID = 'sponsored';

    public static function isSponsoredTier(?string $tierId): bool
    {
        return $tierId !== null
            && $tierId !== ''
            && (string) $tierId === self::SPONSORED_TIER_ID;
    }

    private static function isRemovedLegacyTier(array $tier): bool
    {
        $name = strtolower(trim((string) ($tier['name'] ?? '')));
        if ($name === '') return false;

        // Legacy tiers that should no longer appear in registration:
        // "Standard Package", "Premium Package", "VIP Package +"
        return str_starts_with($name, 'standard package')
            || str_starts_with($name, 'premium package')
            || str_starts_with($name, 'vip package');
    }

    public static function getTiers(): array
    {
        if (Storage::exists('admin/packages.json')) {
            $tiers = json_decode(Storage::get('admin/packages.json'), true) ?? [];
            $tiers = array_values(array_filter(
                is_array($tiers) ? $tiers : [],
                static fn ($tier) => is_array($tier) && ! self::isRemovedLegacyTier($tier)
            ));

            return self::ensureSponsoredTierPresent($tiers);
        }

        return self::ensureSponsoredTierPresent([
            [
                'id' => 'standard',
                'name' => 'Standard Price',
                'description' => 'The regular participation fee that supports the core costs of convening the Summit.',
                'price' => 970,
                'active' => true,
            ],
            [
                'id' => 'subsidised',
                'name' => 'Subsidised Price',
                'description' => 'For participants working with limited financial resources whose organizations may not have dedicated budgets for international events but whose participation would be critical.',
                'price' => 580,
                'active' => true,
            ],
            [
                'id' => 'supporter',
                'name' => 'Supporter Price',
                'description' => 'For participants who are able to contribute more and help make the Summit accessible to a wider diversity of voices.',
                'price' => 1550,
                'active' => true,
            ],
            [
                'id' => 'flexible',
                'name' => 'Flexible Supporter Price — Above USD 970',
                'description' => 'For participants who are willing and able to contribute beyond the Standard Price, this option allows you to choose an amount above USD 970 based on your capacity and commitment to supporting an inclusive Summit.',
                'price' => 970,
                'active' => true,
            ],
            [
                'id' => 'flexible_any',
                'name' => 'Flexible Supporter Price — Choose any amount above USD 970',
                'description' => 'For participants who are willing and able to contribute beyond the Standard Price. Select any amount above USD 970 based on your capacity and commitment to supporting an inclusive Summit.',
                'price' => 970,
                'active' => true,
            ],
            [
                'id' => 'flexible_hardcoded',
                'name' => 'Flexible Supporter Price — Above USD 970',
                'description' => 'Hardcoded flexible tier on the registration form.',
                'price' => 970,
                'active' => true,
            ],
            self::defaultSponsoredTier(),
        ]);
    }

    protected static function defaultSponsoredTier(): array
    {
        return [
            'id' => self::SPONSORED_TIER_ID,
            'name' => 'Sponsored Participant (by approval only)',
            'description' => 'For participants whose registration is fully sponsored by UNDP. Selection requires organizer approval. No ticket payment or DashTicket account is created — your record is kept in our system only.',
            'price' => 0,
            'active' => true,
        ];
    }

    protected static function ensureSponsoredTierPresent(array $tiers): array
    {
        $hasSponsored = collect($tiers)->contains(
            fn ($tier) => is_array($tier) && (string) ($tier['id'] ?? '') === self::SPONSORED_TIER_ID
        );

        if (! $hasSponsored) {
            $tiers[] = self::defaultSponsoredTier();
        }

        return $tiers;
    }

    public static function getActiveTiers(): array
    {
        return array_values(array_filter(
            self::getTiers(),
            fn ($t) => $t['active'] ?? true
        ));
    }

    public static function getPriceForTier(?string $tierId): ?float
    {
        if ($tierId === null || $tierId === '') {
            return null;
        }
        $tiers = self::getTiers();
        foreach ($tiers as $tier) {
            if ((string) ($tier['id'] ?? '') === (string) $tierId) {
                $price = $tier['price'] ?? null;
                return $price !== null ? (float) $price : null;
            }
        }

        return null;
    }

    /** Immersive experience add-on (USD), shown on registration form. */
    public const IMMERSIVE_EXPERIENCE_ADDON = 95.0;

    /**
     * Expected payment amount from registration tier, override, flexible amount, and add-on.
     *
     * @param  \App\Models\SummitRegistration|array<string, mixed>  $registration
     */
    public static function resolvePaymentAmountForRegistration($registration): ?float
    {
        $tierId = (string) (
            (is_array($registration) ? ($registration['price_tier_raw'] ?? $registration['price_tier'] ?? '') : null)
            ?? $registration->price_tier_raw
            ?? $registration->price_tier
            ?? ''
        );

        if (self::isSponsoredTier($tierId)) {
            return 0.0;
        }

        $adminOverride = is_array($registration)
            ? ($registration['admin_override_price'] ?? null)
            : $registration->admin_override_price;

        if ($adminOverride !== null && $adminOverride !== '' && (float) $adminOverride > 0) {
            return (float) $adminOverride;
        }

        $flexibleAmount = is_array($registration)
            ? ($registration['flexible_support_amount'] ?? null)
            : $registration->flexible_support_amount;

        $price = self::getPriceForTier($tierId);

        $flexibleTierIds = ['flexible', 'flexible_any', 'flexible_hardcoded'];
        foreach (self::getActiveTiers() as $tier) {
            $id = (string) ($tier['id'] ?? '');
            $name = strtolower((string) ($tier['name'] ?? ''));
            if ($id !== '' && str_contains($name, 'flexible')) {
                $flexibleTierIds[] = $id;
            }
        }
        $flexibleTierIds = array_values(array_unique($flexibleTierIds));

        if (in_array($tierId, $flexibleTierIds, true) && $flexibleAmount !== null && $flexibleAmount !== '') {
            $custom = (float) $flexibleAmount;
            if ($custom >= 970) {
                $price = $custom;
            }
        }

        if ($price === null) {
            return null;
        }

        $fieldVisit = is_array($registration)
            ? filter_var($registration['field_visit'] ?? false, FILTER_VALIDATE_BOOLEAN)
            : (bool) $registration->field_visit;

        if ($fieldVisit) {
            $price += self::IMMERSIVE_EXPERIENCE_ADDON;
        }

        return $price;
    }

    /**
     * Use stored payment JSON amount when valid; otherwise derive from registration.
     */
    public static function displayPaymentAmount($registration, ?float $storedAmount): ?float
    {
        $resolved = self::resolvePaymentAmountForRegistration($registration);

        if ($storedAmount !== null && $storedAmount !== '' && (float) $storedAmount > 0) {
            return (float) $storedAmount;
        }

        return $resolved;
    }
}
