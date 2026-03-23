<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $blogSettings = Storage::exists('admin/blog-settings.json')
            ? json_decode(Storage::get('admin/blog-settings.json'), true)
            : ['blog_enabled' => false];

        $siteSettings = $this->getSiteSettings();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'newsletter' => fn () => $request->session()->get('newsletter'),
            ],
            'blog_enabled' => $blogSettings['blog_enabled'] ?? false,
            'site_settings' => $siteSettings,
            'admin_permissions' => $request->user() ? $this->getAdminPermissions($request->user()) : [],
        ];
    }

    protected function getAdminPermissions($user): array
    {
        if (!$user || !method_exists($user, 'hasPermissionTo')) {
            return [];
        }

        $keys = [
            'homepage',
            'programme-experience',
            'your-bhutan-stay',
            'get-involved',
            'faqs',
            'blog',
            'applications',
            'payments',
            'packages',
            'communications',
            'users',
            'settings',
        ];

        if ($user->hasRole('super-admin')) {
            return array_fill_keys($keys, true);
        }

        $permissions = [];
        foreach ($keys as $key) {
            $permissions[$key] = $user->hasPermissionTo($key);
        }

        return $permissions;
    }

    protected function getSiteSettings(): array
    {
        if (Storage::exists('admin/settings.json')) {
            $s = json_decode(Storage::get('admin/settings.json'), true);
            return [
                'logo' => $s['logo'] ?? '/brand/logo2.png',
                'logo_secondary' => $s['logo_secondary'] ?? '/brand/govt.png',
                'logo_third' => $s['logo_third'] ?? '',
                'logo_fourth' => $s['logo_fourth'] ?? '',
                'favicon' => $s['favicon'] ?? '',
                'register_hero_image' => $s['register_hero_image'] ?? '',
                'newsletter_text' => $s['newsletter_text'] ?? 'Subscribe to our newsletter',
                'contact_email' => $s['contact_email'] ?? 'info@foodsummit2026.org',
                'contact_phone' => $s['contact_phone'] ?? '+975 1234 5678',
                'show_contact_phone' => $s['show_contact_phone'] ?? true,
                'show_footer_support' => $s['show_footer_support'] ?? true,
                'contact_location' => $s['contact_location'] ?? "Royal Institute for Governance and Strategic Studies\nThimphu, Bhutan",
                'social_facebook' => $s['social_facebook'] ?? '',
                'social_twitter' => $s['social_twitter'] ?? '',
                'social_instagram' => $s['social_instagram'] ?? '',
                'social_linkedin' => $s['social_linkedin'] ?? '',
                'social_youtube' => $s['social_youtube'] ?? '',
                'footer_support_text' => $s['footer_support_text'] ?? '24/7 support provided by the Global Conscious Food Systems Summit event team.',
            ];
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
            'contact_location' => "Royal Institute for Governance and Strategic Studies\nThimphu, Bhutan",
            'social_facebook' => '',
            'social_twitter' => '',
            'social_instagram' => '',
            'social_linkedin' => '',
            'social_youtube' => '',
            'footer_support_text' => '24/7 support provided by the Global Conscious Food Systems Summit event team.',
        ];
    }
}
