<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\SummitRegistration;
use App\Services\DashTicketService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $participants = $query->paginate(20)->withQueryString();
        $paymentData = $this->getAllPaymentData();

        $tierMap = collect(PackageService::getTiers())
            ->keyBy(fn ($t) => (string) ($t['id'] ?? ''));

        $participants->getCollection()->transform(function ($registration) use ($paymentData, $tierMap) {
            $data = $paymentData[$registration->id] ?? ['status' => 'pending', 'amount' => null];
            $rawTier = (string) ($registration->price_tier_raw ?? $registration->price_tier ?? '');
            $tier = $tierMap->get($rawTier);
            $tierName = $registration->price_tier_name
                ?? ($tier['name'] ?? null)
                ?? $registration->price_tier;
            $upgradeStatus = null;
            if (array_key_exists('upgrade_paid', $data)) {
                $upgradeStatus = ($data['upgrade_paid'] ?? false) ? 'confirmed' : 'pending';
            }

            return [
                'id' => $registration->id,
                'first_name' => $registration->first_name,
                'last_name' => $registration->last_name,
                'email' => $registration->email,
                'organization' => $registration->organization,
                'country' => $registration->country,
                'price_tier' => $tierName,
                'price_tier_raw' => $rawTier,
                'admin_override_price' => $registration->admin_override_price,
                'field_visit' => $registration->field_visit,
                'payment_status' => $data['status'] ?? 'pending',
                'payment_amount' => PackageService::displayPaymentAmount(
                    $registration,
                    isset($data['amount']) ? (float) $data['amount'] : null
                ),
                'upgrade_status' => $upgradeStatus,
                'created_at' => $registration->created_at?->format('M d, Y'),
            ];
        });

        // Filter by payment status if specified
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $participants->setCollection(
                $participants->getCollection()->filter(function ($participant) use ($request) {
                    return $participant['payment_status'] === $request->payment_status;
                })
            );
        }

        $allApproved = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->get();
        $allPaymentData = $this->getAllPaymentData();

        $paidCount = 0;
        $pendingCount = 0;

        foreach ($allApproved as $registration) {
            $data = $allPaymentData[$registration->id] ?? ['status' => 'pending'];
            $status = $data['status'] ?? 'pending';
            if ($status === 'paid') {
                $paidCount++;
            } elseif ($status === 'pending') {
                $pendingCount++;
            }
        }

        $stats = [
            'total_approved' => $allApproved->count(),
            'paid' => $paidCount,
            'pending' => $pendingCount,
        ];

        return Inertia::render('Admin/Payments', [
            'participants' => $participants,
            'stats' => $stats,
            'packages' => PackageService::getActiveTiers(),
        ]);
    }

    public function updatePaymentStatus(Request $request, SummitRegistration $summit_registration)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:paid,pending,partial,refunded,sponsored',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        $this->savePaymentData($summit_registration->id, [
            'status' => $validated['payment_status'],
            'amount' => $validated['payment_amount'] ?? null,
            'updated_at' => now()->toISOString(),
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment status updated successfully.');
    }

    public function store(Request $request)
    {
        $activePackageIds = array_map(fn ($p) => (string) $p['id'], PackageService::getActiveTiers());

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'organization' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'price_tier' => ['required', 'string', 'in:' . implode(',', $activePackageIds)],
            'field_visit' => 'boolean',
            'payment_status' => 'required|in:paid,pending,partial,refunded,sponsored',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        $registration = SummitRegistration::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'organization' => $validated['organization'],
            'country' => $validated['country'],
            'price_tier' => $validated['price_tier'],
            'field_visit' => $validated['field_visit'] ?? false,
            'status' => SummitRegistration::STATUS_APPROVED,
        ]);

        $this->savePaymentData($registration->id, [
            'status' => $validated['payment_status'],
            'amount' => $validated['payment_amount'] ?? null,
            'created_at' => now()->toISOString(),
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Manual registration added successfully.');
    }

    public function resendEmail(SummitRegistration $summit_registration)
    {
        $reg = $summit_registration;
        $paymentLink = config('services.dashticket.payment_link', '');
        $name = trim($reg->first_name . ' ' . $reg->last_name);
        $subject = 'Payment Reminder – Global Conscious Food Systems Summit';

        $html = <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; color: #1a1a1a;">
    <h2 style="color: #2d5f3f; margin-bottom: 8px;">Payment Reminder</h2>
    <p style="margin: 0 0 16px 0;">Dear {$name},</p>
    <p style="margin: 0 0 16px 0;">
        This is a friendly reminder that your application to the <strong>Global Conscious Food Systems Summit</strong>
        has been approved. To secure your place, please complete your payment at the link below.
    </p>
    <div style="text-align: center; margin: 24px 0;">
        <a href="{$paymentLink}" style="background-color: #2d5f3f; color: #ffffff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block;">
            Complete Payment
        </a>
    </div>
    <p style="margin: 16px 0 0 0; font-size: 13px; color: #555;">
        If you have any questions, please contact us at <strong>cofsa@undp.org</strong>.
    </p>
    <p style="margin: 12px 0 0 0;">With warm regards,</p>
    <p style="margin: 4px 0 0 0; font-weight: 700;">The Organizing Team of the Global Conscious Food Systems Summit</p>
</div>
HTML;

        try {
            Mail::html($html, function ($mail) use ($reg, $subject) {
                $mail->to($reg->email)->subject($subject);
            });
            EmailLog::create([
                'type' => 'reminder',
                'recipient_email' => $reg->email,
                'summit_registration_id' => $reg->id,
            ]);
            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment reminder email sent to ' . $reg->email);
        } catch (\Throwable $e) {
            Log::warning('Resend payment email failed', [
                'email' => $reg->email,
                'message' => $e->getMessage(),
            ]);
            return redirect()->route('admin.payments.index')
                ->with('error', 'Failed to send reminder email to ' . $reg->email);
        }
    }

    public function syncFromDashTicket()
    {
        if (! app(DashTicketService::class)->isConfigured()) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'DashTicket API key is not configured.');
        }

        $phpBinary = env('PHP_CLI_PATH', '/usr/bin/php');

        Process::path(base_path())
            ->timeout(600)
            ->start([$phpBinary, 'artisan', 'payments:sync-dashticket']);

        return redirect()->route('admin.payments.index')
            ->with('success', 'DashTicket sync started in the background. Refresh in a minute to see updates.');
    }

    public function export(Request $request)
    {
        $query = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $participants = $query->get();
        $paymentData = $this->getAllPaymentData();
        
        // Filter by payment status if specified (payment data is in JSON, so filter after fetching)
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $participants = $participants->filter(function ($participant) use ($request, $paymentData) {
                $data = $paymentData[$participant->id] ?? ['status' => 'pending'];
                return ($data['status'] ?? 'pending') === $request->payment_status;
            });
        }

        $filename = 'payments-' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($participants, $paymentData) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Nationality',
                'Country of Residence',
                'Organization',
                'Organization Type',
                'Organization Type (Other)',
                'Organization Size',
                'Job Title',
                'Website',
                'Package',
                'Flexible Support Amount',
                'Immersive Experience (Add-on)',
                'Participation Budget Support',
                'Passport Number',
                'Passport Expiry',
                'Arrival Date',
                'Departure Date',
                'Dietary Requirements',
                'Dietary (Other)',
                'Accessibility Needed',
                'Accessibility Details',
                'Heard From',
                'Heard From (Other)',
                'CoFSA Member',
                'CoFSA Newsletter',
                'Emergency Contact Name',
                'Emergency Contact Phone',
                'Emergency Contact Email',
                'Emergency Contact Relation',
                'Payment Status',
                'Payment Amount',
                'Application Date',
            ]);

            foreach ($participants as $participant) {
                $data = $paymentData[$participant->id] ?? ['status' => 'pending', 'amount' => null];
                fputcsv($handle, [
                    $participant->first_name,
                    $participant->last_name,
                    $participant->email,
                    $participant->phone,
                    $participant->country,
                    $participant->country_of_residence,
                    $participant->organization,
                    $participant->organization_type,
                    $participant->organization_type_other,
                    $participant->organization_size,
                    $participant->job_title,
                    $participant->website,
                    $participant->price_tier,
                    $participant->flexible_support_amount,
                    $participant->field_visit ? 'Yes' : 'No',
                    $participant->participation_budget_support,
                    $participant->passport_number,
                    optional($participant->passport_expiry)->format('Y-m-d'),
                    optional($participant->arrival_date)->format('Y-m-d'),
                    optional($participant->departure_date)->format('Y-m-d'),
                    $participant->dietary,
                    $participant->dietary_other,
                    $participant->accessibility_needed ? 'Yes' : 'No',
                    $participant->accessibility_details,
                    is_array($participant->heard_from) ? implode('; ', $participant->heard_from) : ($participant->heard_from ?? ''),
                    $participant->heard_from_other,
                    $participant->cofsa_member ? 'Yes' : 'No',
                    $participant->cofsa_newsletter ? 'Yes' : 'No',
                    $participant->emergency_name,
                    $participant->emergency_phone,
                    $participant->emergency_email,
                    $participant->emergency_relation,
                    $data['status'] ?? 'pending',
                    $data['amount'] ?? '',
                    $participant->created_at?->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    protected function getPaymentData($registrationId): array
    {
        if (Storage::exists('admin/payments.json')) {
            $payments = json_decode(Storage::get('admin/payments.json'), true) ?? [];
            return $payments[$registrationId] ?? ['status' => 'pending', 'amount' => null];
        }
        return ['status' => 'pending', 'amount' => null];
    }

    protected function getAllPaymentData(): array
    {
        if (Storage::exists('admin/payments.json')) {
            return json_decode(Storage::get('admin/payments.json'), true) ?? [];
        }
        return [];
    }

    protected function savePaymentData($registrationId, array $data): void
    {
        $payments = $this->getAllPaymentData();
        $payments[$registrationId] = $data;
        Storage::put('admin/payments.json', json_encode($payments, JSON_PRETTY_PRINT));
    }
}
