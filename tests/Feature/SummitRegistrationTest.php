<?php

namespace Tests\Feature;

use App\Models\SummitRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SummitRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Avoid storage/app/admin/packages.json on disk hiding built-in tiers (needed for DashTicket price lookup).
        Storage::fake('local');
    }

    protected function validRegistrationData(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+97517123456',
            'country' => 'Bhutan',
            'country_of_residence' => 'Bhutan',
            'organization' => 'Test Org',
            'job_title' => 'Developer',
            'website' => '',
            'passport_number' => 'AB1234567',
            'passport_expiry' => '2030-12-31',
            'arrival_date' => '2026-03-15',
            'departure_date' => '2026-03-18',
            'price_tier' => 'standard',
            'field_visit' => false,
            'accessibility_needed' => 'no',
            'accessibility_details' => '',
            'dietary' => 'none',
            'dietary_other' => '',
            'heard_from' => [],
            'heard_from_other' => '',
            'cofsa_member' => 'no',
            'cofsa_newsletter' => 'yes',
            'emergency_name' => 'Jane Doe',
            'emergency_phone' => '+97517123457',
            'emergency_email' => 'jane@example.com',
            'emergency_relation' => 'Spouse',
            'confirm_tier' => false,
            'subsidized_price_justification' => '',
            'flexible_support_amount' => '',
        ];
    }

    public function test_registration_page_loads(): void
    {
        $response = $this->get('/register-summit');

        $response->assertStatus(200);
    }

    public function test_summit_registration_creates_record_and_calls_dashticket_api(): void
    {
        config(['services.dashticket.api_key' => 'test-dashticket-key']);

        Http::fake(function () {
            return Http::response([
                'status' => 'success',
                'message' => 'User registered',
                'user_uid' => 'test-uid-123',
            ], 201);
        });

        $data = $this->validRegistrationData();

        $response = $this->post(route('registration.store'), $data);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $this->assertDatabaseHas('summit_registrations', [
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        Http::assertSent(function ($request) use ($data) {
            $body = $request->data();
            return str_contains($request->url(), 'register-user')
                && $body['email'] === 'john.doe@example.com'
                && $body['name'] === 'John Doe'
                && $body['tag'] === 'from_UNDP'
                && (float) $body['price'] === 970.0
                && isset($body['password'])
                && strlen($body['password']) >= 8
                && $body['nationality'] === 'BT'
                && $body['phone'] === '+97517123456';
        });
    }

    public function test_registration_works_when_dashticket_not_configured(): void
    {
        config(['services.dashticket.api_key' => '']);

        $data = $this->validRegistrationData();

        $response = $this->post(route('registration.store'), $data);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('summit_registrations', ['email' => 'john.doe@example.com']);
    }
}
