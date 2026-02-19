<?php

namespace App\Http\Controllers;

use App\Services\PackageService;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function index()
    {
        $packages = PackageService::getActiveTiers();

        return Inertia::render('Register/Index', [
            'packages' => $packages,
        ]);
    }
}
