<?php

namespace Tests\Feature;

use App\Models\SummitRegistration;
use App\Models\User;
use App\Services\PackageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SponsoredApprovalEmailBuilder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SponsoredApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Mail::fake();
        Role::findOrCreate('super-admin');
        config(['services.dashticket.api_key' => 'test-dashticket-key']);
    }

    public function test_sponsored_approval_skips_user_and_dashticket(): void
    {
        Http::fake();

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $registration = SummitRegistration::create([
            'first_name' => 'Sam',
            'last_name' => 'Sponsor',
            'email' => 'sam.sponsor@example.com',
            'phone' => '+97517123456',
            'organization' => 'UNDP',
            'job_title' => 'Advisor',
            'country' => 'Bhutan',
            'country_of_residence' => 'Bhutan',
            'passport_number' => 'AB1234567',
            'passport_expiry' => '2030-12-31',
            'arrival_date' => '2026-03-15',
            'departure_date' => '2026-03-18',
            'price_tier' => PackageService::SPONSORED_TIER_ID,
            'price_tier_raw' => PackageService::SPONSORED_TIER_ID,
            'price_tier_name' => 'Sponsored Participant (by approval only)',
            'field_visit' => false,
            'accessibility_needed' => 'no',
            'cofsa_member' => 'no',
            'cofsa_newsletter' => 'no',
            'emergency_name' => 'Jane Doe',
            'emergency_phone' => '+97517123457',
            'emergency_email' => 'jane@example.com',
            'emergency_relation' => 'Spouse',
            'status' => SummitRegistration::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.applications.approve', $registration)
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $registration->refresh();
        $this->assertSame(SummitRegistration::STATUS_APPROVED, $registration->status);
        $this->assertNull($registration->user_id);
        $this->assertNull(User::where('email', 'sam.sponsor@example.com')->first());

        Http::assertNothingSent();

        $this->assertDatabaseHas('email_logs', [
            'type' => 'approval',
            'recipient_email' => 'sam.sponsor@example.com',
            'summit_registration_id' => $registration->id,
        ]);

        $payments = json_decode(Storage::get('admin/payments.json'), true);
        $this->assertSame('sponsored', $payments[$registration->id]['status']);
        $this->assertSame(0, $payments[$registration->id]['amount']);
    }

    public function test_sponsored_registration_sends_standard_welcome_email(): void
    {
        Mail::fake();

        $data = [
            'first_name' => 'Sam',
            'last_name' => 'Sponsor',
            'gender' => 'male',
            'email' => 'sam.sponsor@example.com',
            'phone' => '+97517123456',
            'country' => 'Bhutan',
            'country_of_residence' => 'Bhutan',
            'organization' => 'UNDP',
            'job_title' => 'Officer',
            'website' => '',
            'passport_number' => 'AB1234567',
            'passport_expiry' => '2030-12-31',
            'arrival_date' => '2026-03-15',
            'departure_date' => '2026-03-18',
            'price_tier' => PackageService::SPONSORED_TIER_ID,
            'field_visit' => false,
            'accessibility_needed' => 'no',
            'accessibility_details' => '',
            'dietary' => 'none',
            'dietary_other' => '',
            'heard_from' => [],
            'heard_from_other' => '',
            'cofsa_member' => 'no',
            'cofsa_newsletter' => 'no',
            'emergency_name' => 'Jane Doe',
            'emergency_phone' => '+97517123457',
            'emergency_email' => 'jane@example.com',
            'emergency_relation' => 'Spouse',
            'confirm_tier' => false,
            'subsidized_price_justification' => '',
            'flexible_support_amount' => '',
        ];

        $response = $this->post(route('registration.store'), $data);

        $response->assertRedirect();

        $registration = SummitRegistration::where('email', 'sam.sponsor@example.com')->first();
        $this->assertNotNull($registration);

        $this->assertDatabaseHas('email_logs', [
            'type' => 'registration',
            'recipient_email' => 'sam.sponsor@example.com',
            'summit_registration_id' => $registration->id,
        ]);
    }

    public function test_sponsored_approval_email_matches_organizer_template(): void
    {
        $html = SponsoredApprovalEmailBuilder::html('Jane Doe');

        $this->assertStringContainsString('Form VISA2025', $html);
        $this->assertStringContainsString('neema.tamang@bhutanmindfulness.com', $html);
        $this->assertStringContainsString('pema.tshogyal@bhutanmindfulness.com', $html);
        $this->assertStringContainsString('28 August to 6 September', $html);
        $this->assertStringContainsString('General conditions for entry into Bhutan', $html);
    }

    public function test_sponsored_tier_is_available_in_packages(): void
    {
        $tier = collect(PackageService::getActiveTiers())->first(
            fn ($t) => ($t['id'] ?? '') === PackageService::SPONSORED_TIER_ID
        );

        $this->assertNotNull($tier);
        $this->assertSame('Sponsored Participant (by approval only)', $tier['name']);
        $this->assertSame(0.0, (float) $tier['price']);
    }
}
