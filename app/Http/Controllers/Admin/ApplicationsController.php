<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitRegistration;
use App\Services\DashTicketService;
use App\Services\PackageService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                'Nationality',
                'Country of residence',
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
                        $app->country_of_residence,
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

    public function exportData(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $tierMap = collect(PackageService::getTiers())
            ->mapWithKeys(fn ($t) => [(string) ($t['id'] ?? '') => (string) ($t['name'] ?? '')]);

        $rows = $query->get()->map(function ($app) use ($tierMap) {
            $raw = (string) ($app->price_tier_raw ?? $app->price_tier ?? '');
            $priceTierDisplay = (string) ($app->price_tier_name ?: ($tierMap[$raw] ?? $raw));

            return [
                'first_name' => $app->first_name,
                'last_name' => $app->last_name,
                'email' => $app->email,
                'country' => $app->country,
                'country_of_residence' => $app->country_of_residence,
                'organization' => $app->organization,
                'price_tier' => $priceTierDisplay,
                'status' => $app->status,
                'created_at' => $app->created_at ? $app->created_at->format('Y-m-d H:i:s') : '',
            ];
        })->values();

        return response()->json(['data' => $rows]);
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

        $plainPassword = $this->generateSecurePassword();
        $paymentLink = (string) (config('services.dashticket.payment_link') ?? '');

        $user = null;
        DB::transaction(function () use ($application, $plainPassword, &$user) {
            $user = User::create([
                'name' => trim($application->first_name . ' ' . $application->last_name),
                'email' => $application->email,
                'password' => Hash::make($plainPassword),
            ]);

            $application->update([
                'status' => SummitRegistration::STATUS_APPROVED,
                'user_id' => $user->id,
            ]);
        });

        $dashTicket = app(DashTicketService::class);
        if ($dashTicket->isConfigured()) {
            $dashPayload = $application->toArray();
            $dashPayload['price_tier'] = (string) ($application->price_tier_raw ?? $application->price_tier ?? '');
            $dashPayload['dashticket_password'] = $plainPassword;
            $dashTicket->registerUser($dashPayload);
        } else {
            Log::warning('DashTicket: Not configured; skipping DashTicket registration on approval', [
                'email' => $application->email ?? null,
            ]);
        }

        $safeName = htmlspecialchars(trim($application->first_name . ' ' . $application->last_name), ENT_QUOTES, 'UTF-8');
        if ($safeName === '') {
            $safeName = 'Participant';
        }
        $safeEmail = htmlspecialchars((string) $application->email, ENT_QUOTES, 'UTF-8');
        $safePassword = htmlspecialchars($plainPassword, ENT_QUOTES, 'UTF-8');
        $safePaymentLink = htmlspecialchars($paymentLink, ENT_QUOTES, 'UTF-8');

        $subject = 'Congratulations – You’re Invited to Join the Conscious Food Systems Summit Bhutan 2026';

        $html = <<<HTML
<div style="font-family: Arial, Helvetica, sans-serif; color: #111827; background: #ffffff; padding: 24px;">
    <div style="max-width: 760px; margin: 0 auto;">
        <div style="padding: 18px 18px; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 14px;">
            <p style="margin: 0; font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; font-weight: 700;">
                Global Conscious Food Systems Summit
            </p>
            <p style="margin: 6px 0 0 0; font-size: 20px; font-weight: 800; color: #0d385a;">
                Application Approved
            </p>
        </div>

        <p style="margin: 18px 0 10px 0;">Dear {$safeName},</p>
        <p style="margin: 0 0 12px 0; line-height: 1.6;">
            Warm greetings from the Organizing Team of the Global Conscious Food Systems Summit.
        </p>

        <div style="margin: 14px 0 0 0; padding: 16px; border: 2px solid #729430; background: #f0f7e8; border-radius: 12px;">
            <p style="margin: 0; font-weight: 800;">
                We are delighted to inform you that your application has been successfully approved to join the Global Conscious Food Systems Summit, to be held from 31 August to 4 September in Paro, Bhutan.
            </p>
            <p style="margin: 10px 0 0 0; font-weight: 700;">
                We warmly welcome you to this unique and transformative gathering.
            </p>
        </div>

        <div style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
            <div style="background: #0d385a; color: #ffffff; padding: 12px 16px;">
                <p style="margin: 0; font-weight: 800;">Payment Instructions</p>
            </div>
            <div style="background: #ffffff; padding: 14px 16px;">
                <p style="margin: 0; line-height: 1.6;">
                    To secure your place, we kindly invite you to proceed with the payment of your selected participation package.
                    Please access the payment platform using the link below and create your user account with the following credentials:
                </p>

                <div style="margin-top: 12px; padding: 12px; border: 1px dashed #cbd5e1; border-radius: 12px; background: #f8fafc;">
                    <p style="margin: 0 0 10px 0; font-weight: 800;">Login details</p>
                    <p style="margin: 0 0 6px 0;"><strong>Payment link:</strong> <a href="{$safePaymentLink}" target="_blank" style="color: #2563eb; text-decoration: none;">{$safePaymentLink}</a></p>
                    <p style="margin: 0 0 6px 0;"><strong>Email:</strong> {$safeEmail}</p>
                    <p style="margin: 0;"><strong>Password:</strong> {$safePassword}</p>
                </div>

                <div style="margin-top: 12px; padding: 12px; border-left: 6px solid #f59e0b; background: #fffbeb; border-radius: 10px;">
                    <p style="margin: 0; line-height: 1.6;">
                        Due to the high level of interest and limited availability, we kindly ask you to complete your payment within <strong>10 calendar days</strong> from receipt of this email.
                        Your place will be confirmed upon successful payment.
                    </p>
                </div>
            </div>
        </div>

        <div style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
            <div style="background: #111827; color: #ffffff; padding: 12px 16px;">
                <p style="margin: 0; font-weight: 800;">Visa Coordination</p>
            </div>
            <div style="background: #ffffff; padding: 14px 16px;">
                <p style="margin: 0; line-height: 1.6;">
                    Following the completion of your payment, our team will contact you to initiate the visa process and guide you through the required documentation.
                </p>
                <p style="margin: 12px 0 8px 0; font-weight: 800;">To ensure a smooth process, we recommend that you:</p>
                <ul style="margin: 0; padding-left: 18px; line-height: 1.7;">
                    <li>Have a valid passport ready (with at least 6 months validity)</li>
                    <li>Begin reviewing potential flight options, as your travel details will be required for visa processing. For guidance on travel to Bhutan, please refer to: <a href="https://www.consciousfoodsummit.com/your-stay-in-bhutan" target="_blank" style="color: #2563eb; text-decoration: none;">https://www.consciousfoodsummit.com/your-stay-in-bhutan</a> (Air Travel tab)</li>
                </ul>
            </div>
        </div>

        <div style="margin-top: 16px; padding: 14px; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 14px;">
            <p style="margin: 0 0 8px 0; font-weight: 800;">Event website</p>
            <p style="margin: 0;">
                <a href="https://www.consciousfoodsummit.com" target="_blank" style="color: #2563eb; text-decoration: none;">
                    https://www.consciousfoodsummit.com
                </a>
            </p>
        </div>

        <div style="margin-top: 14px;">
            <p style="margin: 0 0 6px 0;">
                Should you have any questions or require assistance, please do not hesitate to contact us at:
            </p>
            <p style="margin: 0; font-weight: 800;">cofsa@undp.org</p>
        </div>

        <p style="margin: 16px 0 0 0; line-height: 1.6;">
            We very much look forward to welcoming you to Bhutan and sharing this meaningful journey together.
        </p>
        <p style="margin: 12px 0 0 0;">With warm regards,</p>
        <p style="margin: 4px 0 0 0; font-weight: 800;">The Organizing Team of the Global Conscious Food Systems Summit</p>
    </div>
</div>
HTML;

        try {
            Mail::html($html, function ($mail) use ($application, $subject) {
                $mail->to($application->email)->subject($subject);
            });
        } catch (\Throwable $mailException) {
            Log::warning('Application approval email failed', [
                'email' => $application->email ?? null,
                'message' => $mailException->getMessage(),
            ]);
        }

        return back()->with('success', 'Application approved. User account created and approval email sent to ' . $application->email);
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

    public function destroy(SummitRegistration $summit_registration)
    {
        $application = $summit_registration;

        DB::transaction(function () use ($application) {
            if ($application->user_id) {
                User::where('id', $application->user_id)->delete();
            }

            $application->delete();
        });

        return back()->with('success', 'Application and linked user deleted permanently.');
    }

    protected function generateSecurePassword(): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lowercase = 'abcdefghjkmnpqrstuvwxyz';
        $digits = '23456789';
        $special = '!@#$%&*';

        $password = $uppercase[random_int(0, strlen($uppercase) - 1)]
            . $lowercase[random_int(0, strlen($lowercase) - 1)]
            . $digits[random_int(0, strlen($digits) - 1)]
            . $special[random_int(0, strlen($special) - 1)];

        $pool = $uppercase . $lowercase . $digits . $special;
        for ($i = 0; $i < 8; $i++) {
            $password .= $pool[random_int(0, strlen($pool) - 1)];
        }

        return str_shuffle($password);
    }
}
