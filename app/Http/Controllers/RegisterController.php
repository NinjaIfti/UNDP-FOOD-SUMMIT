<?php

namespace App\Http\Controllers;

use App\Services\PackageService;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function index()
    {
        if (! $this->isRegistrationOpen()) {
            return Inertia::render('Register/Closed');
        }

        $packages = PackageService::getActiveTiers();
        $yourStaySettings = [];

        try {
            if (Storage::disk('local')->exists('admin/yourstay-settings.json')) {
                $decoded = json_decode(Storage::disk('local')->get('admin/yourstay-settings.json'), true);
                if (is_array($decoded)) {
                    $yourStaySettings = $decoded;
                }
            }
        } catch (\Throwable $e) {
            $yourStaySettings = [];
        }

        return Inertia::render('Register/Index', [
            'packages' => $packages,
            'yourStaySettings' => $yourStaySettings,
        ]);
    }

    protected function isRegistrationOpen(): bool
    {
        if (! Storage::exists('admin/settings.json')) {
            return true;
        }

        $settings = json_decode(Storage::get('admin/settings.json'), true);

        return ($settings['registration_open'] ?? true) !== false;
    }
}
