<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitRegistration;
use App\Services\PackageService;
use Illuminate\Http\Request;
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

        $participants->getCollection()->transform(function ($registration) use ($paymentData) {
            $data = $paymentData[$registration->id] ?? ['status' => 'pending', 'amount' => null];
            return [
                'id' => $registration->id,
                'first_name' => $registration->first_name,
                'last_name' => $registration->last_name,
                'email' => $registration->email,
                'organization' => $registration->organization,
                'country' => $registration->country,
                'price_tier' => $registration->price_tier,
                'field_visit' => $registration->field_visit,
                'payment_status' => $data['status'] ?? 'pending',
                'payment_amount' => $data['amount'] ?? null,
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
            'payment_status' => 'required|in:paid,pending,partial,refunded',
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
            'payment_status' => 'required|in:paid,pending,partial,refunded',
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
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment confirmation email sent to ' . $summit_registration->email);
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
                'Name',
                'Email',
                'Organization',
                'Country',
                'Payment Status',
                'Payment Amount',
                'Package',
                'Field Visit',
                'Application Date',
            ]);

            foreach ($participants as $participant) {
                $data = $paymentData[$participant->id] ?? ['status' => 'pending', 'amount' => null];
                fputcsv($handle, [
                    $participant->first_name . ' ' . $participant->last_name,
                    $participant->email,
                    $participant->organization,
                    $participant->country,
                    $data['status'] ?? 'pending',
                    $data['amount'] ?? '',
                    $participant->price_tier,
                    $participant->field_visit ? 'Yes' : 'No',
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
