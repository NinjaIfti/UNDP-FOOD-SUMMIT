<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\EmailLog;
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
            'gender' => 'required|string|in:female,male,non_binary,prefer_not_to_say',
            'email' => 'required|email',
            // Phone is saved as: "<country code> <local number>", so allow '+' and spaces.
            'phone' => ['required', 'regex:/^\\+?[0-9 ]+$/', 'max:50'],
            'country' => 'required|string|max:255',
            'country_of_residence' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:255',
            'organization_type_other' => 'nullable|string|max:255',
            'organization_size' => 'nullable|string|max:255',
            'participation_budget_support' => 'nullable|string|in:Yes,No,Partially',
            'job_title' => 'required|string|max:255',
            'website' => 'nullable|max:500',
            'passport_number' => 'required|string|max:50',
            'passport_expiry' => 'required|date',
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
            'emergency_phone' => ['required', 'regex:/^\\+?[0-9 ]+$/', 'max:50'],
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
            $registration = SummitRegistration::create($data);

            $subject = 'Thank You for Your Registration - Global Conscious Food Systems Summit Bhutan 2026';
            // Escape user input before injecting into email HTML.
            $username = htmlspecialchars((string) ($data['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');
            if (trim($username) === '') {
                $username = 'Participant';
            }

            $html = <<<HTML
<div style="font-family: Arial, Helvetica, sans-serif; color: #111827; background: #ffffff; padding: 24px;">
    <div style="max-width: 760px; margin: 0 auto;">
        <p style="margin: 0 0 12px 0;">Dear {$username},</p>

        <p style="margin: 0 0 12px 0;">Warm greetings from the Conscious Food Systems Alliance,</p>

        <p style="margin: 0 0 12px 0;">
            Thank you for your registration - we are truly delighted that you have expressed interest in joining the inaugural Conscious Food Systems Summit, taking place from 31 August to 4 September in Paro, Bhutan.
        </p>

        <div style="margin: 16px 0; padding: 16px; border: 2px solid #f59e0b; background: #fffbeb;">
            <p style="margin: 0; font-weight: 700;">
                We are pleased to confirm that your registration application has been successfully received.
            </p>
        </div>

        <p style="margin: 0 0 10px 0;">
            To support you through the process, we would like to outline the next steps:
        </p>

        <div style="margin-top: 12px;">
            <div style="border: 1px solid #e5e7eb; background: #ffffff; padding: 14px; border-left: 6px solid #d21f1f; margin-bottom: 12px;">
                <p style="margin: 0; font-weight: 700;">Step 1 — Submit Your Registration Application (Completed)</p>
                <p style="margin: 8px 0 0 0; line-height: 1.55;">
                    You have completed this step by submitting the registration form with your personal details, selected participation package, and any optional preferences.
                </p>
            </div>

            <div style="border: 1px solid #e5e7eb; background: #ffffff; padding: 14px; border-left: 6px solid #d21f1f; margin-bottom: 12px;">
                <p style="margin: 0; font-weight: 700;">Step 2 — Review and Confirmation by Organizers</p>
                <p style="margin: 8px 0 0 0; line-height: 1.55;">
                    All applications are carefully reviewed by the organizing team. Please note that submission of the form does not automatically confirm participation.
                </p>
            </div>

            <div style="border: 1px solid #e5e7eb; background: #ffffff; padding: 14px; border-left: 6px solid #d21f1f; margin-bottom: 12px;">
                <p style="margin: 0; font-weight: 700;">Step 3 — Approval Notification and Payment Instructions</p>
                <p style="margin: 8px 0 0 0; line-height: 1.55;">
                    If your application is approved, you will receive a follow-up email confirming your participation along with instructions to proceed with payment.
                </p>
            </div>

            <div style="border: 1px solid #e5e7eb; background: #ffffff; padding: 14px; border-left: 6px solid #d21f1f; margin-bottom: 12px;">
                <p style="margin: 0; font-weight: 700;">Step 4 — Visa Coordination After Payment</p>
                <p style="margin: 8px 0 0 0; line-height: 1.55;">
                    Once your payment is completed, the organizers will initiate the visa process and prepare the required documentation for your travel to Bhutan. We encourage you to begin checking flight schedules, as your travel details will be required to process your visa. If all documents are submitted on time, the visa process typically takes around two weeks.
                </p>
            </div>

            <div style="border: 1px solid #e5e7eb; background: #ffffff; padding: 14px; border-left: 6px solid #d21f1f;">
                <p style="margin: 0; font-weight: 700;">Step 5 — Visa Approval and Travel Planning</p>
                <p style="margin: 8px 0 0 0; line-height: 1.55;">
                    As soon as your visa is approved, you will be notified. At that stage, you may proceed with booking your flights, accommodation, and final travel arrangements.
                </p>
            </div>
        </div>

        <div style="margin-top: 16px; padding: 14px; border: 1px solid #e5e7eb; background: #f9fafb;">
            <p style="margin: 0 0 8px 0; font-weight: 700;">
                We kindly invite you to regularly check the event website for updates and additional information:
            </p>
            <p style="margin: 0;">
                <a href="https://consciousfoodsummit.com/" target="_blank" style="color: #2563eb; text-decoration: none;">
                    https://consciousfoodsummit.com/
                </a>
            </p>
        </div>

        <div style="margin-top: 12px;">
            <p style="margin: 0 0 6px 0;">
                If you have any questions or require assistance at any stage, please feel free to contact us at:
            </p>
            <p style="margin: 0; font-weight: 700;">cofsa@undp.org</p>
        </div>

        <p style="margin: 16px 0 0 0;">
            We look forward to the possibility of welcoming you to Bhutan for this meaningful and transformative gathering.
        </p>

        <p style="margin: 12px 0 0 0;">With warm regards,</p>
        <p style="margin: 4px 0 0 0; font-weight: 700;">The Global Conscious Food Systems Summit Organizing Team</p>
    </div>
</div>
HTML;

            try {
                Mail::html($html, function ($mail) use ($data, $subject) {
                    $mail->to($data['email'])
                        ->subject($subject);
                });
                EmailLog::create([
                    'type' => 'registration',
                    'recipient_email' => $data['email'],
                    'summit_registration_id' => $registration->id,
                ]);
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
