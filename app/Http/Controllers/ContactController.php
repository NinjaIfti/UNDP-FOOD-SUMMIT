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
}
