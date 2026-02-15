<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ApplicationsController extends Controller
{
    public function index(Request $request)
    {
        $query = SummitRegistration::query()->orderByDesc('created_at');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => SummitRegistration::where('status', SummitRegistration::STATUS_PENDING)->count(),
            'approved' => SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->count(),
            'rejected' => SummitRegistration::where('status', SummitRegistration::STATUS_REJECTED)->count(),
            'waitlisted' => SummitRegistration::where('status', SummitRegistration::STATUS_WAITLISTED)->count(),
        ];

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $applications,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(SummitRegistration $summit_registration)
    {
        return Inertia::render('Admin/Applications/Show', [
            'application' => $summit_registration,
        ]);
    }

    public function approve(SummitRegistration $summit_registration)
    {
        $application = $summit_registration;

        if ($application->status === SummitRegistration::STATUS_APPROVED) {
            return back()->with('error', 'Application is already approved.');
        }

        if (User::where('email', $application->email)->exists()) {
            return back()->with('error', 'A user with this email already exists.');
        }

        DB::transaction(function () use ($application) {
            $user = User::create([
                'name' => trim($application->first_name . ' ' . $application->last_name),
                'email' => $application->email,
                'password' => Hash::make(Str::random(32)),
            ]);

            $application->update([
                'status' => SummitRegistration::STATUS_APPROVED,
                'user_id' => $user->id,
            ]);

            Password::sendResetLink(['email' => $user->email]);
        });

        return back()->with('success', 'Application approved. User account created and password reset link sent to ' . $application->email);
    }

    public function reject(SummitRegistration $summit_registration)
    {
        $summit_registration->update(['status' => SummitRegistration::STATUS_REJECTED]);
        return back()->with('success', 'Application rejected.');
    }

    public function waitlist(SummitRegistration $summit_registration)
    {
        $summit_registration->update(['status' => SummitRegistration::STATUS_WAITLISTED]);
        return back()->with('success', 'Application moved to waitlist.');
    }

    public function revert(SummitRegistration $summit_registration)
    {
        $application = $summit_registration;
        if ($application->status !== SummitRegistration::STATUS_APPROVED) {
            $application->update(['status' => SummitRegistration::STATUS_PENDING]);
            return back()->with('success', 'Application reverted to pending.');
        }

        if ($application->user_id) {
            return back()->with('error', 'Cannot revert approved application with existing user account.');
        }

        $application->update(['status' => SummitRegistration::STATUS_PENDING]);
        return back()->with('success', 'Application reverted to pending.');
    }
}
