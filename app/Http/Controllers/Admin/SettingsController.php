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
            'logo_secondary' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_third' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_fourth' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|file|mimes:png,jpg,jpeg,ico,svg|max:2048',
            'register_hero_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'newsletter_text' => 'nullable|string',
            'contact_email' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'show_contact_phone' => 'nullable|boolean',
            'show_footer_support' => 'nullable|boolean',
            'registration_open' => 'nullable|boolean',
            'contact_location' => 'nullable|string',
            'social_facebook' => 'nullable|string',
            'social_twitter' => 'nullable|string',
            'social_instagram' => 'nullable|string',
            'social_linkedin' => 'nullable|string',
            'social_youtube' => 'nullable|string',
            'footer_support_text' => 'nullable|string',
            'delete_logo' => 'nullable|boolean',
            'delete_logo_secondary' => 'nullable|boolean',
            'delete_logo_third' => 'nullable|boolean',
            'delete_logo_fourth' => 'nullable|boolean',
            'delete_favicon' => 'nullable|boolean',
            'delete_register_hero_image' => 'nullable|boolean',
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

        if ($request->hasFile('logo_secondary')) {
            if (!empty($currentSettings['logo_secondary']) && str_starts_with($currentSettings['logo_secondary'], '/') && file_exists(public_path($currentSettings['logo_secondary']))) {
                @unlink(public_path($currentSettings['logo_secondary']));
            }
            $file = $request->file('logo_secondary');
            $filename = 'logo_secondary_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['logo_secondary'] = '/brand/' . $filename;
        } elseif ($request->input('delete_logo_secondary')) {
            if (!empty($currentSettings['logo_secondary']) && str_starts_with($currentSettings['logo_secondary'], '/') && file_exists(public_path($currentSettings['logo_secondary']))) {
                @unlink(public_path($currentSettings['logo_secondary']));
            }
            $validated['logo_secondary'] = '';
        } else {
            unset($validated['logo_secondary']);
        }

        if ($request->hasFile('logo_third')) {
            if (!empty($currentSettings['logo_third']) && str_starts_with($currentSettings['logo_third'], '/') && file_exists(public_path($currentSettings['logo_third']))) {
                @unlink(public_path($currentSettings['logo_third']));
            }
            $file = $request->file('logo_third');
            $filename = 'logo_third_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['logo_third'] = '/brand/' . $filename;
        } elseif ($request->input('delete_logo_third')) {
            if (!empty($currentSettings['logo_third']) && str_starts_with($currentSettings['logo_third'], '/') && file_exists(public_path($currentSettings['logo_third']))) {
                @unlink(public_path($currentSettings['logo_third']));
            }
            $validated['logo_third'] = '';
        } else {
            unset($validated['logo_third']);
        }

        if ($request->hasFile('logo_fourth')) {
            if (!empty($currentSettings['logo_fourth']) && str_starts_with($currentSettings['logo_fourth'], '/') && file_exists(public_path($currentSettings['logo_fourth']))) {
                @unlink(public_path($currentSettings['logo_fourth']));
            }
            $file = $request->file('logo_fourth');
            $filename = 'logo_fourth_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['logo_fourth'] = '/brand/' . $filename;
        } elseif ($request->input('delete_logo_fourth')) {
            if (!empty($currentSettings['logo_fourth']) && str_starts_with($currentSettings['logo_fourth'], '/') && file_exists(public_path($currentSettings['logo_fourth']))) {
                @unlink(public_path($currentSettings['logo_fourth']));
            }
            $validated['logo_fourth'] = '';
        } else {
            unset($validated['logo_fourth']);
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

        if ($request->hasFile('register_hero_image')) {
            if (!empty($currentSettings['register_hero_image']) && str_starts_with($currentSettings['register_hero_image'], '/') && file_exists(public_path($currentSettings['register_hero_image']))) {
                @unlink(public_path($currentSettings['register_hero_image']));
            }
            $file = $request->file('register_hero_image');
            $filename = 'register_hero_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $validated['register_hero_image'] = '/brand/' . $filename;
        } elseif ($request->input('delete_register_hero_image')) {
            if (!empty($currentSettings['register_hero_image']) && str_starts_with($currentSettings['register_hero_image'], '/') && file_exists(public_path($currentSettings['register_hero_image']))) {
                @unlink(public_path($currentSettings['register_hero_image']));
            }
            $validated['register_hero_image'] = '';
        } else {
            unset($validated['register_hero_image']);
        }

        unset(
            $validated['delete_logo'],
            $validated['delete_logo_secondary'],
            $validated['delete_logo_third'],
            $validated['delete_logo_fourth'],
            $validated['delete_favicon'],
            $validated['delete_register_hero_image'],
        );

        $merged = array_merge($currentSettings, $validated);
        $this->saveSettings($merged);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.')
            ->with('settings', $merged);
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
            'logo' => '/brand/logo2.png',
            'logo_secondary' => '/brand/govt.png',
            'logo_third' => '',
            'logo_fourth' => '',
            'favicon' => '',
            'register_hero_image' => '',
            'newsletter_text' => 'Subscribe to our newsletter',
            'contact_email' => 'info@foodsummit2026.org',
            'contact_phone' => '+975 1234 5678',
            'show_contact_phone' => true,
            'show_footer_support' => true,
            'registration_open' => true,
            'contact_location' => "Royal Institute for Governance and Strategic Studies\nThimphu, Bhutan",
            'social_facebook' => 'https://facebook.com/gcfss2026',
            'social_twitter' => 'https://twitter.com/gcfss2026',
            'social_instagram' => 'https://instagram.com/gcfss2026',
            'social_linkedin' => 'https://linkedin.com/company/gcfss2026',
            'social_youtube' => 'https://youtube.com/@gcfss2026',
            'footer_support_text' => '24/7 support provided by the Global Conscious Food Systems Summit event team.',
        ];
    }

    private function saveSettings(array $settings)
    {
        Storage::put('admin/settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    }
}
