<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();

        return Inertia::render('Admin/Settings', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
            'newsletter_text' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_location' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'delete_logo' => 'nullable|boolean',
            'delete_favicon' => 'nullable|boolean',
        ]);

        $currentSettings = $this->getSettings();

        if ($request->hasFile('logo')) {
            if (!empty($currentSettings['logo']) && str_starts_with($currentSettings['logo'], '/') && file_exists(public_path($currentSettings['logo']))) {
                @unlink(public_path($currentSettings['logo']));
            }
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['logo'] = '/brand/' . $filename;
        } elseif ($request->input('delete_logo')) {
            if (!empty($currentSettings['logo']) && str_starts_with($currentSettings['logo'], '/') && file_exists(public_path($currentSettings['logo']))) {
                @unlink(public_path($currentSettings['logo']));
            }
            $validated['logo'] = '';
        } else {
            unset($validated['logo']);
        }

        if ($request->hasFile('favicon')) {
            if (!empty($currentSettings['favicon']) && str_starts_with($currentSettings['favicon'], '/') && file_exists(public_path($currentSettings['favicon']))) {
                @unlink(public_path($currentSettings['favicon']));
            }
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['favicon'] = '/brand/' . $filename;
        } elseif ($request->input('delete_favicon')) {
            if (!empty($currentSettings['favicon']) && str_starts_with($currentSettings['favicon'], '/') && file_exists(public_path($currentSettings['favicon']))) {
                @unlink(public_path($currentSettings['favicon']));
            }
            $validated['favicon'] = '';
        } else {
            unset($validated['favicon']);
        }

        unset($validated['delete_logo'], $validated['delete_favicon']);

        $merged = array_merge($currentSettings, $validated);
        $this->saveSettings($merged);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function updateGeneral(Request $request)
    {
        return $this->update($request);
    }

    public function updateFooter(Request $request)
    {
        return $this->update($request);
    }

    private function getSettings()
    {
        if (Storage::exists('admin/settings.json')) {
            return json_decode(Storage::get('admin/settings.json'), true);
        }

        return [
            'logo' => '',
            'favicon' => '',
            'newsletter_text' => 'Subscribe to our newsletter',
            'contact_email' => 'info@foodsummit2026.org',
            'contact_phone' => '+975 1234 5678',
            'contact_location' => "Royal Institute for Governance and Strategic Studies\nThimphu, Bhutan",
            'social_facebook' => 'https://facebook.com/gcfss2026',
            'social_twitter' => 'https://twitter.com/gcfss2026',
            'social_instagram' => 'https://instagram.com/gcfss2026',
            'social_linkedin' => 'https://linkedin.com/company/gcfss2026',
            'social_youtube' => 'https://youtube.com/@gcfss2026',
        ];
    }

    private function saveSettings(array $settings)
    {
        Storage::put('admin/settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    }
}
