<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SummitRegistration;
use App\Services\DashTicketUpgradeService;
use App\Services\PackageService;
use Illuminate\Http\Request;

class ParticipantUpgradeController extends Controller
{
    public function store(Request $request, SummitRegistration $summit_registration)
    {
        $activePackageIds = array_map(
            fn ($p) => (string) $p['id'],
            PackageService::getActiveTiers()
        );

        $validated = $request->validate([
            'price_tier_raw' => ['nullable', 'string', 'in:' . implode(',', $activePackageIds)],
            'admin_override_price' => 'nullable|numeric|min:0',
            'field_visit' => 'boolean',
        ]);

        if ($request->has('admin_override_price') && ($validated['admin_override_price'] ?? '') === '') {
            $validated['admin_override_price'] = null;
        }

        $validated['field_visit'] = $request->boolean('field_visit');

        $result = app(DashTicketUpgradeService::class)->applyUpgrade($summit_registration, $validated);

        $redirect = $request->input('redirect', 'dashboard');
        $route = $redirect === 'payments'
            ? 'admin.payments.index'
            : 'admin.dashboard';

        if (! $result['ok']) {
            return redirect()->route($route)->with('error', $result['message']);
        }

        return redirect()->route($route)->with('success', $result['message']);
    }
}
