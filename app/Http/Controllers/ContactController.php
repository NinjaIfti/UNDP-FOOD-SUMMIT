<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        return back()->with('success', 'Message sent successfully.');
    }

    public function newsletter(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('newsletter', 'Thank you for subscribing.');
    }

    public function registration(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:50',
            'country' => 'nullable|string|max:255',
            'organization' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'website' => 'nullable|max:500',
            'passport_number' => 'required|string|max:50',
            'passport_expiry' => 'nullable|date',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'price_tier' => 'required|string|in:tier1,tier2,tier3',
            'field_visit' => 'boolean',
            'accessibility_needed' => 'required|string|in:yes,no',
            'accessibility_details' => 'nullable|string|max:1000',
            'dietary' => 'required|string',
            'dietary_other' => 'nullable|string|max:255',
            'heard_from' => 'nullable|array',
            'heard_from_other' => 'nullable|string|max:255',
            'additional_members' => 'nullable|array',
            'cofsa_member' => 'required|string|in:yes,no',
            'cofsa_newsletter' => 'required|string|in:yes,no',
            'emergency_name' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:50',
            'emergency_email' => 'required|email',
            'emergency_relation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        return back()->with('success', 'Your registration application has been submitted successfully. Our team will review it and get back to you shortly.');
    }
}
