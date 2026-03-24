<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\SummitRegistration;
use App\Services\DashTicketService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactSubmission::create($validated);

        return back()->with('success', 'Message sent successfully.');
    }

    public function newsletter(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('newsletter', 'Thank you for subscribing.');
    }

    public function registration(Request $request)
    {
        $activeTiers = PackageService::getActiveTiers();
        $flexibleTierIds = [];
        foreach ($activeTiers as $tier) {
            $id = (string) ($tier['id'] ?? '');
            $name = strtolower((string) ($tier['name'] ?? ''));
            if ($id !== '' && str_contains($name, 'flexible')) {
                $flexibleTierIds[] = $id;
            }
        }
        $flexibleTierIds = array_values(array_unique(array_merge($flexibleTierIds, ['flexible', 'flexible_any', 'flexible_hardcoded'])));
        $flexibleAmountRule = 'nullable|numeric|gte:970';
        if (!empty($flexibleTierIds)) {
            $flexibleAmountRule = 'nullable|required_if:price_tier,' . implode(',', $flexibleTierIds) . '|numeric|gte:970';
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'country' => 'required|string|max:255',
            'country_of_residence' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:255',
            'organization_type_other' => 'nullable|string|max:255',
            'organization_size' => 'nullable|string|max:255',
            'participation_budget_support' => 'nullable|string|in:Yes,No,Partially',
            'job_title' => 'required|string|max:255',
            'website' => 'nullable|max:500',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'price_tier' => 'required|string|max:100',
            'confirm_tier' => 'nullable|boolean',
            'subsidized_price_justification' => 'nullable|string|max:2000',
            'flexible_support_amount' => $flexibleAmountRule,
            'field_visit' => 'boolean',
            'accessibility_needed' => 'required|string|in:yes,no',
            'accessibility_details' => 'nullable|string|max:1000',
            'dietary' => 'required|string',
            'dietary_other' => 'nullable|string|max:255',
            'heard_from' => 'nullable|array',
            'heard_from.*' => 'nullable|string',
            'heard_from_other' => 'nullable|string|max:255',
            'cofsa_member' => 'required|string|in:yes,no',
            'cofsa_newsletter' => 'required|string|in:yes,no',
            'emergency_name' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:50',
            'emergency_email' => 'required|email',
            'emergency_relation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ], [
            'flexible_support_amount.gte' => 'Amount must be at least USD 970',
            'flexible_support_amount.gt' => 'Amount must be greater than USD 970',
        ]);

        if (!empty($validated['subsidized_price_justification']) && str_word_count(strip_tags((string) $validated['subsidized_price_justification'])) > 120) {
            return back()->withErrors([
                'subsidized_price_justification' => 'Please keep the Subsidised Price justification to a maximum of 120 words.',
            ])->withInput();
        }

        $data = collect($validated)->except(['photo'])->toArray();
        $submittedPriceTier = (string) ($validated['price_tier'] ?? '');
        $matchedTier = collect(PackageService::getTiers())->first(
            fn ($t) => (string) ($t['id'] ?? '') === $submittedPriceTier
        );
        $submittedTierName = (string) ($matchedTier['name'] ?? $submittedPriceTier);
        $data['field_visit'] = (bool) ($data['field_visit'] ?? false);
        if (!isset($data['organization']) || $data['organization'] === null) {
            $data['organization'] = 'Independent';
        }
        // Keep compatibility with current DB schema where passport_number is still NOT NULL.
        if (!isset($data['passport_number']) || $data['passport_number'] === null) {
            $data['passport_number'] = '';
        }
        // DB column length is 20; keep admin-created IDs safe for insert.
        if (isset($data['price_tier'])) {
            $data['price_tier'] = substr((string) $data['price_tier'], 0, 20);
        }
        $data['price_tier_raw'] = $submittedPriceTier;
        $data['price_tier_name'] = $submittedTierName;
        $data['confirm_tier'] = (bool) ($data['confirm_tier'] ?? false);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('registrations', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        try {
            SummitRegistration::create($data);

            $dashTicket = app(DashTicketService::class);
            if ($dashTicket->isConfigured()) {
                $dashPayload = $data;
                $dashPayload['price_tier'] = $submittedPriceTier;
                $dashTicket->registerUser($dashPayload);
            }

            $tierName = $submittedTierName;

            $customAmount = $validated['flexible_support_amount'] ?? null;
            $customAmount = $customAmount !== null && $customAmount !== '' ? (string) $customAmount : '';

            $message = "Thank you, {$data['first_name']} {$data['last_name']}, for registering for the Conscious Food Systems Summit.\n\n";
            $message .= "Your selected ticket option: {$tierName}\n";
            if ($customAmount !== '') {
                $message .= "Custom flexible amount (USD): {$customAmount}\n";
            }
            $message .= "\nOur team will review your submission and get back to you soon.\n";

            try {
                Mail::raw($message, function ($mail) use ($data) {
                    $mail->to($data['email'])
                        ->subject('Registration received - Conscious Food Systems Summit');
                });
            } catch (\Throwable $mailException) {
                Log::warning('Registration confirmation email failed', [
                    'email' => $data['email'] ?? null,
                    'message' => $mailException->getMessage(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Registration submit failed', [
                'email' => $data['email'] ?? null,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'form' => 'Submission failed. Please check the required fields and try again.',
            ])->withInput();
        }

        return back()->with('success', 'Thank you for registering with the GCFSS, our team will get back to you soon!');
    }
}
