<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitRegistration;
use App\Services\PackageService;
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
        $query = $this->buildFilteredQuery($request);

        $applications = $query->paginate(15)->withQueryString();
        $tierMap = collect(PackageService::getTiers())
            ->mapWithKeys(fn ($t) => [(string) ($t['id'] ?? '') => (string) ($t['name'] ?? '')]);
        $applications->getCollection()->transform(function ($app) use ($tierMap) {
            $raw = (string) ($app->price_tier_raw ?? $app->price_tier ?? '');
            $app->price_tier_display = (string) ($app->price_tier_name ?: ($tierMap[$raw] ?? $raw));
            return $app;
        });

        $stats = [
            'pending' => SummitRegistration::where('status', SummitRegistration::STATUS_PENDING)->count(),
            'approved' => SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->count(),
            'rejected' => SummitRegistration::where('status', SummitRegistration::STATUS_REJECTED)->count(),
            'waitlisted' => SummitRegistration::where('status', SummitRegistration::STATUS_WAITLISTED)->count(),
            'heard_from' => $this->buildHeardFromStats(),
        ];

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $applications,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    protected function buildFilteredQuery(Request $request)
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

        return $query;
    }

    protected function buildHeardFromStats(): array
    {
        $all = SummitRegistration::whereNotNull('heard_from')->pluck('heard_from');

        $totals = [];

        foreach ($all as $sources) {
            if (!is_array($sources)) {
                continue;
            }

            foreach ($sources as $source) {
                if (!isset($totals[$source])) {
                    $totals[$source] = 0;
                }

                $totals[$source]++;
            }
        }

        return $totals;
    }

    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $filename = 'applications-' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'First name',
                'Last name',
                'Email',
                'Phone',
                'Country',
                'Organization',
                'Job title',
                'Price tier',
                'Status',
                'Arrival date',
                'Departure date',
                'Heard from',
                'Heard from other',
                'Created at',
            ]);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $app) {
                    fputcsv($handle, [
                        $app->id,
                        $app->first_name,
                        $app->last_name,
                        $app->email,
                        $app->phone,
                        $app->country,
                        $app->organization,
                        $app->job_title,
                        $app->price_tier,
                        $app->status,
                        optional($app->arrival_date)->format('Y-m-d'),
                        optional($app->departure_date)->format('Y-m-d'),
                        is_array($app->heard_from) ? implode('; ', $app->heard_from) : '',
                        $app->heard_from_other,
                        $app->created_at ? $app->created_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    public function show(SummitRegistration $summit_registration)
    {
        $application = $summit_registration->toArray();
        $rawTier = (string) ($application['price_tier_raw'] ?? $application['price_tier'] ?? '');
        $tierMap = collect(PackageService::getTiers())
            ->keyBy(fn ($t) => (string) ($t['id'] ?? ''));
        $tier = $tierMap->get($rawTier);
        $application['price_tier_display'] = (string) (($application['price_tier_name'] ?? '') ?: (string) ($tier['name'] ?? $rawTier));
        $tierPrice = $tier['price'] ?? null;
        if (!empty($application['flexible_support_amount'])) {
            $tierPrice = $application['flexible_support_amount'];
        }
        $application['price_tier_amount'] = $tierPrice !== null && $tierPrice !== '' ? (float) $tierPrice : null;
        if (!empty($application['photo'])) {
            $application['photo_url'] = asset($application['photo']);
        }

        return Inertia::render('Admin/Applications/Show', [
            'application' => $application,
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
