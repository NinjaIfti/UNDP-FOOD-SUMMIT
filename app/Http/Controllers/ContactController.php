<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\SummitRegistration;
use App\Services\DashTicketService;
use App\Services\PackageService;
use Illuminate\Http\Request;
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
        $activePackageIds = array_map(fn ($p) => (string) $p['id'], PackageService::getActiveTiers());

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'country' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:255',
            'organization_type_other' => 'nullable|string|max:255',
            'organization_size' => 'nullable|string|max:255',
            'participation_budget_support' => 'nullable|string|in:Yes,No,Partially',
            'job_title' => 'required|string|max:255',
            'website' => 'nullable|max:500',
            'passport_number' => 'required|string|max:50',
            'passport_expiry' => 'nullable|date',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'price_tier' => ['required', 'string', 'in:' . implode(',', $activePackageIds)],
            'confirm_tier' => 'accepted',
            'subsidized_price_justification' => 'nullable|string|max:2000',
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
        ]);

        if (!empty($validated['subsidized_price_justification']) && str_word_count(strip_tags((string) $validated['subsidized_price_justification'])) > 120) {
            return back()->withErrors([
                'subsidized_price_justification' => 'Please keep the Subsidised Price justification to a maximum of 120 words.',
            ])->withInput();
        }

        $data = collect($validated)->except(['photo'])->toArray();
        $data['field_visit'] = (bool) ($data['field_visit'] ?? false);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('registrations', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        SummitRegistration::create($data);

        $dashTicket = app(DashTicketService::class);
        if ($dashTicket->isConfigured()) {
            $dashTicket->registerUser($data);
        }

        return back()->with('success', 'Thank you for registering with the GCFSS, our team will get back to you soon!');
    }
}
