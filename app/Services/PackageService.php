<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PackageService
{
    public static function getTiers(): array
    {
        if (Storage::exists('admin/packages.json')) {
            return json_decode(Storage::get('admin/packages.json'), true) ?? [];
        }

        return [
            [
                'id' => '1',
                'name' => 'Standard Package',
                'description' => 'Full summit access with meals and materials',
                'price' => 1500,
                'active' => true,
            ],
            [
                'id' => '2',
                'name' => 'Premium Package',
                'description' => 'Includes networking events and exclusive sessions',
                'price' => 2500,
                'active' => true,
            ],
            [
                'id' => '3',
                'name' => 'VIP Package',
                'description' => 'Complete experience with private meetings and luxury accommodation',
                'price' => 4500,
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
