<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AdminPaymentService
{
    protected const STORAGE_PATH = 'admin/payments.json';

    public function getAll(): array
    {
        if (! Storage::exists(self::STORAGE_PATH)) {
            return [];
        }

        return json_decode(Storage::get(self::STORAGE_PATH), true) ?? [];
    }

    public function get(int $registrationId): array
    {
        $all = $this->getAll();

        return $all[$registrationId] ?? ['status' => 'pending', 'amount' => null];
    }

    public function save(int $registrationId, array $data): void
    {
        $all = $this->getAll();
        $all[$registrationId] = array_merge($this->get($registrationId), $data);
        Storage::put(self::STORAGE_PATH, json_encode($all, JSON_PRETTY_PRINT));
    }

    public function storeDashTicketPassword(int $registrationId, string $password): void
    {
        $this->save($registrationId, [
            'dashticket_password' => $password,
        ]);
    }

    public function getDashTicketPassword(int $registrationId): ?string
    {
        $password = $this->get($registrationId)['dashticket_password'] ?? null;

        return is_string($password) && $password !== '' ? $password : null;
    }
}
