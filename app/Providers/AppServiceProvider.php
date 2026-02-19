<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        View::composer('app', function ($view) {
            $favicon = '';
            if (Storage::exists('admin/settings.json')) {
                $s = json_decode(Storage::get('admin/settings.json'), true);
                $favicon = $s['favicon'] ?? '';
            }
            $view->with('faviconUrl', $favicon ?: null);
        });
    }
}
