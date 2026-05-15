<?php

namespace Tests\Feature;

use App\Models\SummitRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashTicketUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Role::findOrCreate('super-admin');
        config([
            'services.dashticket.api_key' => 'test-dashticket-key',
            'services.dashticket.url' => 'https://api.dashbhutan.com',
        ]);
    }

    public function test_upgrade_syncs_entitlements_and_stores_upgrade_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $registration = SummitRegistration::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '+97517123456',
            'organization' => 'UNDP',
            'job_title' => 'Officer',
            'country' => 'Bhutan',
            'country_of_residence' => 'Bhutan',
            'passport_number' => 'AB1234567',
            'passport_expiry' => '2030-12-31',
            'arrival_date' => '2026-03-15',
            'departure_date' => '2026-03-18',
            'price_tier' => 'standard',
            'price_tier_raw' => 'standard',
            'price_tier_name' => 'Standard Price',
            'field_visit' => false,
            'accessibility_needed' => 'no',
            'cofsa_member' => 'no',
            'cofsa_newsletter' => 'no',
            'emergency_name' => 'John Doe',
            'emergency_phone' => '+97517123457',
            'emergency_email' => 'john@example.com',
            'emergency_relation' => 'Spouse',
            'status' => SummitRegistration::STATUS_APPROVED,
        ]);

        Storage::put('admin/payments.json', json_encode([
            $registration->id => [
                'status' => 'paid',
                'amount' => 970,
                'dashticket_password' => 'SecurePass1!',
            ],
        ], JSON_PRETTY_PRINT));

        Http::fake([
            'https://api.dashbhutan.com/api/v1/integrations/register-user' => Http::response([
                'status' => 'success',
                'message' => 'User exists',
                'user' => ['uid' => 'u_test', 'email' => 'jane@example.com', 'name' => 'Jane Doe'],
            ], 200),
            'https://api.dashbhutan.com/api/v1/integrations/ticket-status*' => Http::response([
                'status' => 'success',
                'email' => 'jane@example.com',
                'ticket_purchased' => true,
                'upgrade_paid' => false,
            ], 200),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.participants.upgrade', $registration), [
            'price_tier_raw' => 'supporter',
            'field_visit' => true,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $registration->refresh();
        $this->assertSame('supporter', $registration->price_tier_raw);
        $this->assertTrue($registration->field_visit);

        $payments = json_decode(Storage::get('admin/payments.json'), true);
        $this->assertFalse($payments[$registration->id]['upgrade_paid']);
        $this->assertArrayHasKey('upgrade_synced_at', $payments[$registration->id]);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'register-user')) {
                return false;
            }

            $body = $request->data();

            return ($body['email'] ?? '') === 'jane@example.com'
                && ($body['add_on'] ?? false) === true
                && (float) ($body['price'] ?? 0) === 1550.0;
        });
    }
}
