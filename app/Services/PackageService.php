<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PackageService
{
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
            return array_values(array_filter(
                is_array($tiers) ? $tiers : [],
                static fn ($tier) => is_array($tier) && ! self::isRemovedLegacyTier($tier)
            ));
        }

        return [
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
        ];
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
}
