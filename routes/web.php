<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.store');

Route::get('/', function () {
    return Inertia::render('Home/Index');
});

Route::get('/programme', fn () => Inertia::render('Programme/Index'));
Route::get('/get-involved', fn () => Inertia::render('GetInvolved/Index'));

Route::get('/register-summit', fn () => Inertia::render('Register/Index'));
Route::post('/register-summit', [ContactController::class, 'registration'])->name('registration.store');

Route::get('/submissions', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Call for Submissions']));
Route::get('/media-kit', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Media Kit']));
Route::get('/support', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Support the Event']));


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
