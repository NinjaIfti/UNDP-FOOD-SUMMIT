<?php

use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CommunicationsController;
use App\Http\Controllers\Admin\ContactSubmissionsController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.store');

Route::get('/', function () {
    $defaults = [
        'hero_title' => 'Global Conscious Food Systems Summit',
        'hero_date' => '31 August – 4 September 2026',
        'hero_location' => 'Bhutan',
        'hero_description' => 'Let\'s co-create a food future rooted in wisdom, nature connection, compassion, and deep collaboration.',
        'countdown_date' => '2026-08-31T09:00:00',
        'summit_intro_title' => 'A New Kind of Food Systems Summit',
        'summit_intro_text1' => 'This landmark global gathering aims to facilitate high-level dialogue, knowledge exchange, and partnership-building around advancing conscious, inclusive, and sustainable food systems. Key themes include food consciousness, agroecology, and the role of spirituality and culture in supporting nature-friendly agriculture.',
        'summit_intro_text2' => 'The Summit will convene 350–500 national and global changemakers—governments, indigenous communities, farmers, youth leaders, civil society, the private sector, and academia—prioritizing gender balance, inclusion, and meaningful participation of underrepresented voices.',
        'coorganizers_title' => 'Co-organizers',
        'video1_title' => 'Summit Video',
        'video1_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'video2_title' => 'HPM Video',
        'video2_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'conscious_food_title' => 'Conscious Food Systems: Not just what we eat, but how we grow, share and value food.',
        'conscious_food_description' => 'Food systems are at the heart of pressing global challenges: climate change, environmental pollution, biodiversity decline, and land degradation. The need for a bold and innovative path, harnessing the power of consciousness, mindfulness, and ethical decision-making, has never been more urgent.',
        'conscious_food_cofsa_text' => 'The Conscious Food Systems Alliance (CoFSA), convened by UNDP, is a movement of food, agriculture, and consciousness practitioners united around a common goal: to support people from across food and agriculture systems in integrating inner development, mindfulness and ethical practices into their work—fostering transformation from the inside out.',
        'outcomes_title' => 'Activating the Heart of Food Systems Transformation',
        'outcomes_description' => 'This landmark gathering will advance a new paradigm for food systems transformation—one that recognizes the vital role of culture, values, spirituality, happiness and inner development in catalyzing systemic change.',
        'bhutan_title' => 'Bhutan: An Inspiration and Innovator in Sustainable Transformation',
        'bhutan_text1' => 'Bhutan offers a uniquely inspiring setting for this summit. Guided by the holistic philosophy of Gross National Happiness, and through forward-looking initiatives, such as the Gelephu Mindfulness City and its 100% organic agriculture goal, Bhutan exemplifies how consciousness and sustainability can shape national development.',
        'bhutan_text2' => 'The Summit will advance Bhutan\'s National Food Systems Transformation Pathways by cultivating a conscious inner mindset, while also highlighting Bhutan\'s leadership in driving conscious food systems transformation globally.',
        'events_title' => 'Events Plan',
        'events_subtitle' => '31 August – 4 September 2026 · Thimphu, Bhutan',
    ];
    
    if (Storage::exists('admin/homepage-settings.json')) {
        $settings = json_decode(Storage::get('admin/homepage-settings.json'), true);
    } else {
        $settings = $defaults;
    }

    return Inertia::render('Home/Index', ['settings' => $settings]);
});

Route::get('/faq', [AdminController::class, 'faqPublic']);

Route::get('/programme', [AdminController::class, 'programmePublic']);
Route::get('/get-involved', [AdminController::class, 'getInvolvedPublic']);
Route::get('/your-stay-in-bhutan', [AdminController::class, 'yourStayPublic']);
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/register-summit', [\App\Http\Controllers\RegisterController::class, 'index']);
Route::post('/register-summit', [ContactController::class, 'registration'])->name('registration.store');

Route::get('/submissions', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Call for Submissions']));
Route::get('/media-kit', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Media Kit']));
Route::get('/support', fn () => Inertia::render('Placeholders/ComingSoon', ['title' => 'Support the Event']));

// Admin routes
Route::get('/admin-portal-access', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin-portal-access', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin-portal-access/forgot-password', [AdminController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin-portal-access/forgot-password', [AdminController::class, 'sendResetLink'])->name('admin.password.email');
Route::get('/admin-portal-access/reset-password/{token}', [AdminController::class, 'showResetPasswordForm'])->name('admin.password.reset');
Route::post('/admin-portal-access/reset-password', [AdminController::class, 'resetPassword'])->name('admin.password.store');

Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::middleware('admin.permission:homepage')->group(function () {
        Route::get('/homepage', [AdminController::class, 'homepage'])->name('homepage');
        Route::post('/homepage', [AdminController::class, 'updateHomepage'])->name('homepage.update');
    });

    Route::middleware('admin.permission:your-bhutan-stay')->group(function () {
        Route::get('/your-stay', [AdminController::class, 'yourStay'])->name('your-stay');
        Route::post('/your-stay', [AdminController::class, 'updateYourStay'])->name('your-stay.update');
    });

    Route::middleware('admin.permission:programme-experience')->group(function () {
        Route::get('/programme', [AdminController::class, 'programme'])->name('programme');
        Route::post('/programme', [AdminController::class, 'updateProgramme'])->name('programme.update');
    });

    Route::middleware('admin.permission:faqs')->group(function () {
        Route::get('/faq', [AdminController::class, 'faq'])->name('faq');
        Route::post('/faq', [AdminController::class, 'updateFaq'])->name('faq.update');
    });

    Route::middleware('admin.permission:get-involved')->group(function () {
        Route::get('/get-involved', [AdminController::class, 'getInvolved'])->name('get-involved');
        Route::post('/get-involved', [AdminController::class, 'updateGetInvolved'])->name('get-involved.update');
    });

    Route::middleware('admin.permission:blog')->group(function () {
        Route::resource('blog', AdminBlogController::class)->except(['show']);
        Route::post('/blog-settings', [AdminBlogController::class, 'updateSettings'])->name('blog.settings');
    });

    Route::middleware('admin.permission:applications')->group(function () {
        Route::get('/applications', [ApplicationsController::class, 'index'])->name('applications.index');
        Route::get('/applications/export', [ApplicationsController::class, 'export'])->name('applications.export');
        Route::get('/applications/{summit_registration}', [ApplicationsController::class, 'show'])->name('applications.show');
        Route::post('/applications/{summit_registration}/approve', [ApplicationsController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{summit_registration}/reject', [ApplicationsController::class, 'reject'])->name('applications.reject');
        Route::post('/applications/{summit_registration}/waitlist', [ApplicationsController::class, 'waitlist'])->name('applications.waitlist');
        Route::post('/applications/{summit_registration}/revert', [ApplicationsController::class, 'revert'])->name('applications.revert');
    });

    Route::middleware('admin.permission:packages')->group(function () {
        Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
        Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
        Route::put('/packages/{id}', [PackagesController::class, 'update'])->name('packages.update');
        Route::post('/packages/{id}/toggle', [PackagesController::class, 'toggle'])->name('packages.toggle');
        Route::delete('/packages/{id}', [PackagesController::class, 'destroy'])->name('packages.destroy');
    });

    Route::middleware('admin.permission:communications')->group(function () {
        Route::get('/communications', [CommunicationsController::class, 'index'])->name('communications.index');
        Route::post('/communications', [CommunicationsController::class, 'store'])->name('communications.store');
        Route::put('/communications/{id}', [CommunicationsController::class, 'update'])->name('communications.update');
        Route::post('/communications/{id}/duplicate', [CommunicationsController::class, 'duplicate'])->name('communications.duplicate');
        Route::delete('/communications/{id}', [CommunicationsController::class, 'destroy'])->name('communications.destroy');
        Route::get('/contact-submissions', [ContactSubmissionsController::class, 'index'])->name('contact-submissions.index');
        Route::get('/contact-submissions/{contact_submission}', [ContactSubmissionsController::class, 'show'])->name('contact-submissions.show');
        Route::post('/contact-submissions/{contact_submission}/mark-as-read', [ContactSubmissionsController::class, 'markAsRead'])->name('contact-submissions.mark-as-read');
        Route::post('/contact-submissions/{contact_submission}/mark-as-unread', [ContactSubmissionsController::class, 'markAsUnread'])->name('contact-submissions.mark-as-unread');
        Route::delete('/contact-submissions/{contact_submission}', [ContactSubmissionsController::class, 'destroy'])->name('contact-submissions.destroy');
    });

    Route::middleware('admin.permission:payments')->group(function () {
        Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentsController::class, 'store'])->name('payments.store');
        Route::get('/payments/export', [PaymentsController::class, 'export'])->name('payments.export');
        Route::post('/payments/{summit_registration}/resend-email', [PaymentsController::class, 'resendEmail'])->name('payments.resend-email');
        Route::put('/payments/{summit_registration}/status', [PaymentsController::class, 'updatePaymentStatus'])->name('payments.update-status');
    });

    Route::middleware('admin.permission:users')->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\Admin\UsersController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('admin.permission:settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.update-general');
        Route::post('/settings/footer', [SettingsController::class, 'updateFooter'])->name('settings.update-footer');
    });

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
