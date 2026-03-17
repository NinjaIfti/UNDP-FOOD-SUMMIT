<?php

namespace App\Http\Controllers;

use App\Models\SummitRegistration;
use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'super-admin', 'cms-admin'])) {
            return redirect()->route('admin.dashboard');
        }

        return Inertia::render('Admin/Login', [
            'status' => session('status'),
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->hasAnyRole(['admin', 'super-admin', 'cms-admin'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showForgotPasswordForm()
    {
        return Inertia::render('Admin/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->hasRole('admin')) {
            return back()->withErrors(['email' => 'We can\'t find an admin account with that email address.']);
        }

        $token = Password::broker()->getRepository()->create($user);
        $user->notify(new AdminResetPasswordNotification($token));

        return back()->with('status', 'Password reset link sent! Check your email.');
    }

    public function showResetPasswordForm(Request $request)
    {
        return Inertia::render('Admin/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->hasRole('admin')) {
            throw ValidationException::withMessages([
                'email' => ['We can\'t find an admin account with that email address.'],
            ]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('status', 'Password reset successfully. You can now log in.');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function dashboard()
    {
        $totalApplications = SummitRegistration::count();
        $approved = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->count();
        $rejected = SummitRegistration::where('status', SummitRegistration::STATUS_REJECTED)->count();
        $paymentData = $this->getDashboardPaymentData();
        $paidCount = 0;
        foreach (SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)->pluck('id') as $id) {
            if (($paymentData[$id]['status'] ?? '') === 'paid') {
                $paidCount++;
            }
        }

        $pendingCount = SummitRegistration::where('status', SummitRegistration::STATUS_PENDING)->count();
        $thisWeekStart = now()->startOfWeek();
        $applicationsThisWeek = SummitRegistration::where('created_at', '>=', $thisWeekStart)->count();
        $approvedThisWeek = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)
            ->where('updated_at', '>=', $thisWeekStart)->count();
        $rejectedThisWeek = SummitRegistration::where('status', SummitRegistration::STATUS_REJECTED)
            ->where('updated_at', '>=', $thisWeekStart)->count();
        $paidThisWeek = 0;
        foreach ($paymentData as $regId => $data) {
            if (($data['status'] ?? '') === 'paid' && isset($data['updated_at'])) {
                if (strtotime($data['updated_at']) >= $thisWeekStart->timestamp) {
                    $paidThisWeek++;
                }
            }
        }

        $metrics = [
            [
                'title' => 'Total Applications (EOIs)',
                'value' => (string) $totalApplications,
                'change' => '+' . $applicationsThisWeek . ' this week',
                'icon' => 'users',
                'color' => 'from-[#2d5f3f] to-[#4a7c59]',
            ],
            [
                'title' => 'Approved Participants',
                'value' => (string) $approved,
                'change' => '+' . $approvedThisWeek . ' this week',
                'icon' => 'check',
                'color' => 'from-[#4a7c59] to-[#6b9773]',
            ],
            [
                'title' => 'Rejected Participants',
                'value' => (string) $rejected,
                'change' => '+' . $rejectedThisWeek . ' this week',
                'icon' => 'x',
                'color' => 'from-[#c53030] to-[#a82828]',
            ],
            [
                'title' => 'Total Tickets Sold',
                'value' => (string) $paidCount,
                'change' => '+' . $paidThisWeek . ' this week',
                'icon' => 'ticket',
                'color' => 'from-[#d4af37] to-[#b8962f]',
            ],
        ];

        $registrationData = $this->buildRegistrationChartData();
        $emailActivity = [
            'approval_sent' => 0,
            'opened' => 0,
            'payment_clicked' => 0,
            'reminder_sent' => 0,
        ];

        $paidParticipants = SummitRegistration::where('status', SummitRegistration::STATUS_APPROVED)
            ->get()
            ->filter(function ($reg) use ($paymentData) {
                return ($paymentData[$reg->id]['status'] ?? '') === 'paid';
            })
            ->map(function ($reg) use ($paymentData) {
                $data = $paymentData[$reg->id] ?? [];
                return [
                    'id' => $reg->id,
                    'first_name' => $reg->first_name,
                    'last_name' => $reg->last_name,
                    'email' => $reg->email,
                    'organization' => $reg->organization,
                    'country' => $reg->country,
                    'price_tier' => $reg->price_tier,
                    'field_visit' => (bool) $reg->field_visit,
                    'payment_amount' => $data['amount'] ?? null,
                    'payment_date' => isset($data['updated_at']) ? date('M j, Y', strtotime($data['updated_at'])) : null,
                ];
            })
            ->values()
            ->all();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metrics,
            'registrationData' => $registrationData,
            'emailActivity' => $emailActivity,
            'paidParticipants' => $paidParticipants,
        ]);
    }

    protected function getDashboardPaymentData(): array
    {
        if (Storage::exists('admin/payments.json')) {
            return json_decode(Storage::get('admin/payments.json'), true) ?? [];
        }
        return [];
    }

    protected function buildRegistrationChartData(): array
    {
        $weeks = 10;
        $items = [];
        $end = now();
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $start = $end->copy()->subWeeks($i)->startOfWeek();
            $weekEnd = $start->copy()->endOfWeek();
            $count = SummitRegistration::whereBetween('created_at', [$start, $weekEnd])->count();
            $items[] = [
                'date' => $start->format('M j'),
                'count' => $count,
            ];
        }
        return $items;
    }

    public function homepage()
    {
        $settings = $this->getHomepageSettings();
        return Inertia::render('Admin/Homepage', ['settings' => $settings]);
    }

    public function updateHomepage(Request $request)
    {
        // Get all request data including dynamic speaker image fields
        $allData = $request->all();
        
        // Build dynamic validation rules for speaker images
        $validationRules = [
            'hero_title' => 'nullable|string',
            'hero_tagline' => 'nullable|string',
            'hero_date' => 'nullable|string',
            'hero_location' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'countdown_date' => 'nullable|date',
            'summit_intro_title' => 'nullable|string',
            'summit_intro_text1' => 'nullable|string',
            'summit_intro_text2' => 'nullable|string',
            'coorganizers_title' => 'nullable|string',
            'coorganizers_subtitle' => 'nullable|string',
            'govt_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'undp_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cofsa_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'coorganizer_logo_4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'coorganizer_logo_5' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_govt_logo' => 'nullable|boolean',
            'delete_undp_logo' => 'nullable|boolean',
            'delete_cofsa_logo' => 'nullable|boolean',
            'delete_coorganizer_logo_4' => 'nullable|boolean',
            'delete_coorganizer_logo_5' => 'nullable|boolean',
            'video1_title' => 'nullable|string',
            'video1_url' => 'nullable|string',
            'video2_title' => 'nullable|string',
            'video2_url' => 'nullable|string',
            'cta_section_title' => 'nullable|string',
            'cta_section_subtitle' => 'nullable|string',
            'cta_actions' => 'nullable|array',
            'speakers_section_title' => 'nullable|string',
            'speakers_section_subtitle' => 'nullable|string',
            'speakers' => 'nullable|array',
            'conscious_food_title' => 'nullable|string',
            'conscious_food_description' => 'nullable|string',
            'conscious_food_cofsa_text' => 'nullable|string',
            'conscious_food_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'outcomes_title' => 'nullable|string',
            'outcomes_description' => 'nullable|string',
            'outcomes_list' => 'nullable|array',
            'bhutan_title' => 'nullable|string',
            'bhutan_text1' => 'nullable|string',
            'bhutan_text2' => 'nullable|string',
            'bhutan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'events_title' => 'nullable|string',
            'events_subtitle' => 'nullable|string',
            'events_days' => 'nullable|array',
        ];
        
        // Add validation for dynamic speaker image fields
        foreach ($allData as $key => $value) {
            if (preg_match('/^speaker_\d+_image$/', $key)) {
                $validationRules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            }
            if (preg_match('/^speaker_\d+_delete_image$/', $key)) {
                $validationRules[$key] = 'nullable|boolean';
            }
            if (preg_match('/^hero_slide_\d+$/', $key)) {
                $validationRules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120';
            }
        }
        
        $validationRules['hero_slides'] = 'nullable|array';
        $validationRules['hero_slides.*'] = 'nullable|string';
        
        $validated = $request->validate($validationRules);

        $currentSettings = $this->getHomepageSettings();

        // Handle logo uploads
        if ($request->hasFile('govt_logo')) {
            if (isset($currentSettings['govt_logo']) && file_exists(public_path($currentSettings['govt_logo']))) {
                unlink(public_path($currentSettings['govt_logo']));
            }
            $file = $request->file('govt_logo');
            $filename = 'govt_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brand'), $filename);
            $validated['govt_logo'] = '/brand/' . $filename;
        }

        if ($request->hasFile('undp_logo')) {
            if (isset($currentSettings['undp_logo']) && file_exists(public_path($currentSettings['undp_logo']))) {
                unlink(public_path($currentSettings['undp_logo']));
            }
            $file = $request->file('undp_logo');
            $filename = 'undp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brand'), $filename);
            $validated['undp_logo'] = '/brand/' . $filename;
        }

        if ($request->hasFile('cofsa_logo')) {
            if (isset($currentSettings['cofsa_logo']) && file_exists(public_path($currentSettings['cofsa_logo']))) {
                unlink(public_path($currentSettings['cofsa_logo']));
            }
            $file = $request->file('cofsa_logo');
            $filename = 'cofsa_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brand'), $filename);
            $validated['cofsa_logo'] = '/brand/' . $filename;
        }

        if ($request->hasFile('coorganizer_logo_4')) {
            if (isset($currentSettings['coorganizer_logo_4']) && $currentSettings['coorganizer_logo_4'] && file_exists(public_path($currentSettings['coorganizer_logo_4']))) {
                unlink(public_path($currentSettings['coorganizer_logo_4']));
            }
            $file = $request->file('coorganizer_logo_4');
            $filename = 'coorganizer4_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brand'), $filename);
            $validated['coorganizer_logo_4'] = '/brand/' . $filename;
        }

        if ($request->hasFile('coorganizer_logo_5')) {
            if (isset($currentSettings['coorganizer_logo_5']) && $currentSettings['coorganizer_logo_5'] && file_exists(public_path($currentSettings['coorganizer_logo_5']))) {
                unlink(public_path($currentSettings['coorganizer_logo_5']));
            }
            $file = $request->file('coorganizer_logo_5');
            $filename = 'coorganizer5_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brand'), $filename);
            $validated['coorganizer_logo_5'] = '/brand/' . $filename;
        }

        // Handle logo deletions
        if ($request->input('delete_govt_logo') && isset($currentSettings['govt_logo'])) {
            if (file_exists(public_path($currentSettings['govt_logo']))) {
                unlink(public_path($currentSettings['govt_logo']));
            }
            $validated['govt_logo'] = '/brand/govt.png';
        }

        if ($request->input('delete_undp_logo') && isset($currentSettings['undp_logo'])) {
            if (file_exists(public_path($currentSettings['undp_logo']))) {
                unlink(public_path($currentSettings['undp_logo']));
            }
            $validated['undp_logo'] = '/brand/undp.png';
        }

        if ($request->input('delete_cofsa_logo') && isset($currentSettings['cofsa_logo'])) {
            if (file_exists(public_path($currentSettings['cofsa_logo']))) {
                unlink(public_path($currentSettings['cofsa_logo']));
            }
            $validated['cofsa_logo'] = '/brand/cofsa.png';
        }

        if ($request->input('delete_coorganizer_logo_4') && isset($currentSettings['coorganizer_logo_4'])) {
            if ($currentSettings['coorganizer_logo_4'] && file_exists(public_path($currentSettings['coorganizer_logo_4']))) {
                unlink(public_path($currentSettings['coorganizer_logo_4']));
            }
            $validated['coorganizer_logo_4'] = '';
        }

        if ($request->input('delete_coorganizer_logo_5') && isset($currentSettings['coorganizer_logo_5'])) {
            if ($currentSettings['coorganizer_logo_5'] && file_exists(public_path($currentSettings['coorganizer_logo_5']))) {
                unlink(public_path($currentSettings['coorganizer_logo_5']));
            }
            $validated['coorganizer_logo_5'] = '';
        }

        // Remove delete flags from validated data
        unset(
            $validated['delete_govt_logo'],
            $validated['delete_undp_logo'],
            $validated['delete_cofsa_logo'],
            $validated['delete_coorganizer_logo_4'],
            $validated['delete_coorganizer_logo_5'],
        );

        // Conscious Food section background image (store path string only in JSON)
        $consciousFoodImage = $currentSettings['conscious_food_image'] ?? '/brand/img41.jpg';
        if ($request->hasFile('conscious_food_image')) {
            if (!empty($consciousFoodImage) && str_starts_with($consciousFoodImage, '/brand/') && $consciousFoodImage !== '/brand/img41.jpg' && file_exists(public_path($consciousFoodImage))) {
                @unlink(public_path($consciousFoodImage));
            }
            $file = $request->file('conscious_food_image');
            $filename = 'conscious_food_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand'))) {
                mkdir(public_path('brand'), 0755, true);
            }
            $file->move(public_path('brand'), $filename);
            $consciousFoodImage = '/brand/' . $filename;
        }
        unset($validated['conscious_food_image']);
        $validated['conscious_food_image'] = $consciousFoodImage;

        // Why Bhutan section image (store path string only in JSON)
        $bhutanImage = $currentSettings['bhutan_image'] ?? '/brand/img41.jpg';
        if (!is_string($bhutanImage) || $bhutanImage === '') {
            $bhutanImage = '/brand/img41.jpg';
        }
        if ($request->hasFile('bhutan_image')) {
            $file = $request->file('bhutan_image');
            if (is_array($file)) {
                $file = $file[0] ?? null;
            }
            if ($file && $file->isValid()) {
                $pathForDisk = ltrim($bhutanImage, '/');
                if ($bhutanImage !== '/brand/img41.jpg' && str_starts_with($bhutanImage, '/brand/') && file_exists(public_path($pathForDisk))) {
                    @unlink(public_path($pathForDisk));
                }
                $filename = 'bhutan_' . time() . '.' . $file->getClientOriginalExtension();
                if (!is_dir(public_path('brand'))) {
                    mkdir(public_path('brand'), 0755, true);
                }
                $file->move(public_path('brand'), $filename);
                $bhutanImage = '/brand/' . $filename;
            }
        }
        unset($validated['bhutan_image']);
        $validated['bhutan_image'] = $bhutanImage;

        // Handle speaker image uploads
        if (isset($validated['speakers']) && is_array($validated['speakers'])) {
            foreach ($validated['speakers'] as $index => &$speaker) {
                $fileKey = 'speaker_' . $index . '_image';
                $deleteKey = 'speaker_' . $index . '_delete_image';
                
                // Get current speaker image from settings if exists
                if (!isset($speaker['image']) && isset($currentSettings['speakers'][$index]['image'])) {
                    $speaker['image'] = $currentSettings['speakers'][$index]['image'];
                }
                
                // Handle image upload
                if ($request->hasFile($fileKey)) {
                    // Delete old image if exists
                    if (isset($speaker['image']) && $speaker['image'] && file_exists(public_path($speaker['image']))) {
                        @unlink(public_path($speaker['image']));
                    }
                    
                    $file = $request->file($fileKey);
                    $filename = 'speaker_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    
                    // Ensure brand directory exists
                    if (!file_exists(public_path('brand'))) {
                        mkdir(public_path('brand'), 0755, true);
                    }
                    
                    $file->move(public_path('brand'), $filename);
                    $speaker['image'] = '/brand/' . $filename;
                }
                
                // Handle image deletion
                if ($request->input($deleteKey)) {
                    if (isset($speaker['image']) && $speaker['image'] && file_exists(public_path($speaker['image']))) {
                        @unlink(public_path($speaker['image']));
                    }
                    $speaker['image'] = '';
                }
                
                // Remove temporary fields
                unset($speaker['imageFile'], $speaker['deleteImage']);
            }
        }
        
        // Handle hero slide image uploads
        if (isset($validated['hero_slides']) && is_array($validated['hero_slides'])) {
            foreach ($validated['hero_slides'] as $index => &$slide) {
                $fileKey = 'hero_slide_' . $index;
                
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $filename = 'hero_slide_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    
                    // Ensure brand directory exists
                    if (!file_exists(public_path('brand'))) {
                        mkdir(public_path('brand'), 0755, true);
                    }
                    
                    $file->move(public_path('brand'), $filename);
                    $slide = '/brand/' . $filename;
                } elseif (empty($slide) || !is_string($slide)) {
                    // Keep existing slide if no new upload and slide is empty/invalid
                    if (isset($currentSettings['hero_slides'][$index])) {
                        $slide = $currentSettings['hero_slides'][$index];
                    }
                }
            }
        } elseif (!isset($currentSettings['hero_slides'])) {
            // Set default slides if none exist
            $validated['hero_slides'] = ['/brand/img55.jpg', '/brand/img34.jpg', '/brand/img32.jpg', '/brand/img30.jpg', '/brand/img22.jpg'];
        }
        
        // Remove all speaker image upload fields from validated data (they're already processed)
        foreach ($validated as $key => $value) {
            if (preg_match('/^speaker_\d+_(image|delete_image)$/', $key)) {
                unset($validated[$key]);
            }
            if (preg_match('/^hero_slide_\d+$/', $key)) {
                unset($validated[$key]);
            }
        }

        $merged = array_merge($currentSettings, $validated);

        Storage::put('admin/homepage-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'Homepage updated successfully!');
    }

    private function getHomepageSettings()
    {
        if (Storage::exists('admin/homepage-settings.json')) {
            return json_decode(Storage::get('admin/homepage-settings.json'), true);
        }

        return [
            'hero_title' => 'Global Conscious Food Systems Summit',
            'hero_tagline' => 'Activating the heart',
            'hero_date' => '31 August – 4 September 2026',
            'hero_location' => 'Bhutan',
            'hero_description' => 'Let\'s co-create a food future rooted in wisdom, nature connection, compassion, and deep collaboration.',
            'countdown_date' => '2026-08-31T09:00:00',
            'hero_slides' => ['/brand/img55.jpg', '/brand/img34.jpg', '/brand/img32.jpg', '/brand/img30.jpg', '/brand/img22.jpg'],
            'summit_intro_title' => 'A New Kind of Food Systems Summit',
            'summit_intro_text1' => 'This landmark global gathering aims to facilitate high-level dialogue, knowledge exchange, and partnership-building around advancing conscious, inclusive, and sustainable food systems. Key themes include food consciousness, agroecology, and the role of spirituality and culture in supporting nature-friendly agriculture.',
            'summit_intro_text2' => 'The Summit will convene 350–500 national and global changemakers—governments, indigenous communities, farmers, youth leaders, civil society, the private sector, and academia—prioritizing gender balance, inclusion, and meaningful participation of underrepresented voices.',
            'coorganizers_title' => 'Co-organizers',
            'coorganizers_subtitle' => 'Our work and impact would not be possible without the dedicated financial support of numerous individuals, foundations and companies who are committed to its success.',
            'govt_logo' => '/brand/govt.png',
            'undp_logo' => '/brand/undp.png',
            'cofsa_logo' => '/brand/cofsa.png',
            'coorganizer_logo_4' => '',
            'coorganizer_logo_5' => '',
            'video1_title' => 'Summit Video',
            'video1_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'video2_title' => 'HPM Video',
            'video2_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'cta_section_title' => 'Call to Actions',
            'cta_section_subtitle' => 'There are many ways to get involved with the Summit. Choose how you\'d like to participate.',
            'cta_actions' => [
                [
                    'title' => 'Register Here!',
                    'desc' => 'Secure your spot at the first-ever Global Conscious Food Systems Summit in Bhutan.',
                    'href' => '/register-summit',
                    'cta' => 'Register Now',
                    'icon' => 'ticket',
                    'color' => 'bg-accent-2',
                    'textColor' => 'text-primary-1',
                    'external' => false,
                ],
                [
                    'title' => 'Call for Submissions',
                    'desc' => 'Contribute your experience — respond to our Call for Submissions and help shape the Summit.',
                    'href' => 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf',
                    'cta' => 'Submit Now',
                    'icon' => 'document',
                    'color' => 'bg-primary-2',
                    'textColor' => 'text-white',
                    'external' => true,
                ],
                [
                    'title' => 'Help Us Spread the Word!',
                    'desc' => 'Download our Communication Kit and help us disseminate the event across your networks.',
                    'href' => '/media-kit',
                    'cta' => 'Get the Kit',
                    'icon' => 'megaphone',
                    'color' => 'bg-accent-1',
                    'textColor' => 'text-white',
                    'external' => false,
                ],
                [
                    'title' => 'Have Any Questions?',
                    'desc' => 'Check out the FAQs for answers to common questions about the Summit, travel, and registration.',
                    'href' => '/faq',
                    'cta' => 'View FAQs',
                    'icon' => 'question',
                    'color' => 'bg-primary-1',
                    'textColor' => 'text-white',
                    'external' => false,
                ],
            ],
            'speakers_section_title' => 'Featured Speakers',
            'speakers_section_subtitle' => 'Leading voices in conscious food systems transformation',
            'speakers' => [
                ['name' => 'Speaker One', 'role' => 'Policy & Governance', 'bio' => 'Leading expert in food policy and governance with over 20 years of experience.', 'image' => '/brand/img22.jpg'],
                ['name' => 'Speaker Two', 'role' => 'Agroecology', 'bio' => 'Pioneer in agroecological practices and sustainable farming systems.', 'image' => '/brand/img29.jpg'],
                ['name' => 'Speaker Three', 'role' => 'Indigenous Knowledge', 'bio' => 'Advocate for indigenous food systems and traditional agricultural wisdom.', 'image' => '/brand/img30.jpg'],
                ['name' => 'Speaker Four', 'role' => 'Youth & Movement Building', 'bio' => 'Youth leader driving food systems transformation across communities.', 'image' => '/brand/img31.jpg'],
                ['name' => 'Speaker Five', 'role' => 'Food Systems', 'bio' => 'Researcher focused on conscious and regenerative food systems.', 'image' => '/brand/img32.jpg'],
                ['name' => 'Speaker Six', 'role' => 'Sustainability', 'bio' => 'Environmental strategist working on sustainable food supply chains.', 'image' => '/brand/img33.jpg'],
            ],
            'conscious_food_title' => 'Conscious Food Systems: Not just what we eat, but how we grow, share and value food.',
            'conscious_food_description' => 'Food systems are at the heart of pressing global challenges: climate change, environmental pollution, biodiversity decline, and land degradation. The need for a bold and innovative path, harnessing the power of consciousness, mindfulness, and ethical decision-making, has never been more urgent.',
            'conscious_food_cofsa_text' => 'The Conscious Food Systems Alliance (CoFSA), convened by UNDP, is a movement of food, agriculture, and consciousness practitioners united around a common goal: to support people from across food and agriculture systems in integrating inner development, mindfulness and ethical practices into their work—fostering transformation from the inside out.',
            'conscious_food_image' => '/brand/img41.jpg',
            'outcomes_title' => 'Activating the Heart of Food Systems Transformation',
            'outcomes_description' => 'This landmark gathering will advance a new paradigm for food systems transformation—one that recognizes the vital role of culture, values, spirituality, happiness and inner development in catalyzing systemic change.',
            'outcomes_list' => [
                'High-level dialogue on conscious food systems',
                'Knowledge exchange and innovation showcase',
                'Partnership-building across sectors',
                'Bhutan leadership platform for transformation',
                'Collective action and investment mobilization',
            ],
            'bhutan_title' => 'Bhutan: An Inspiration and Innovator in Sustainable Transformation',
            'bhutan_text1' => 'Bhutan offers a uniquely inspiring setting for this summit. Guided by the holistic philosophy of Gross National Happiness, and through forward-looking initiatives, such as the Gelephu Mindfulness City and its 100% organic agriculture goal, Bhutan exemplifies how consciousness and sustainability can shape national development.',
            'bhutan_text2' => 'The Summit will advance Bhutan\'s National Food Systems Transformation Pathways by cultivating a conscious inner mindset, while also highlighting Bhutan\'s leadership in driving conscious food systems transformation globally.',
            'bhutan_image' => '/brand/img41.jpg',
            'events_title' => 'Events Plan',
            'events_subtitle' => '31 August – 4 September 2026 · Thimphu, Bhutan',
            'events_days' => [
                [
                    'date' => '31 Aug',
                    'label' => 'Day 1',
                    'title' => 'Opening & Welcome',
                    'schedule' => [
                        ['time' => '08:00 – 09:00', 'title' => 'Registration & Welcome Coffee', 'type' => 'break', 'speaker' => ''],
                        ['time' => '09:00 – 09:30', 'title' => 'Opening Ceremony', 'type' => 'event', 'speaker' => 'H.E. Prime Minister of Bhutan'],
                        ['time' => '09:30 – 10:30', 'title' => 'Keynote: Rethinking Global Food Systems', 'type' => 'speaker', 'speaker' => 'Dr. Agnes Kalibata'],
                    ],
                ],
                [
                    'date' => '1 Sep',
                    'label' => 'Day 2',
                    'title' => 'Plenary Sessions',
                    'schedule' => [
                        ['time' => '09:00 – 10:30', 'title' => 'Keynote: Gross National Happiness & Food Sovereignty', 'type' => 'speaker', 'speaker' => 'Dasho Karma Ura'],
                    ],
                ],
            ],
        ];
    }

    public function speakersPublic()
    {
        $settings = $this->getHomepageSettings();
        return Inertia::render('Speakers/Index', ['settings' => $settings]);
    }

    public function faqPublic()
    {
        $settings = $this->getFaqSettings();
        return Inertia::render('Faq/Index', ['settings' => $settings]);
    }

    public function faq()
    {
        $settings = $this->getFaqSettings();
        return Inertia::render('Admin/Faq', ['settings' => $settings]);
    }

    public function updateFaq(Request $request)
    {
        $validated = $request->validate([
            'faq_section_title' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string',
            'faqs.*.answer' => 'nullable|string',
        ]);

        $merged = array_merge($this->getFaqSettings(), $validated);
        Storage::put('admin/faq-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'FAQs updated successfully.');
    }

    private function getFaqSettings()
    {
        if (Storage::exists('admin/faq-settings.json')) {
            return json_decode(Storage::get('admin/faq-settings.json'), true);
        }

        return [
            'faq_section_title' => 'Frequently Asked Questions',
            'faqs' => [
                ['question' => 'When and where is the Summit?', 'answer' => 'The Global Conscious Food Systems Summit will take place from 31 August to 4 September 2026 in Thimphu, Bhutan.'],
                ['question' => 'Who can attend?', 'answer' => 'The Summit convenes 350–500 national and global changemakers—governments, indigenous communities, farmers, youth leaders, civil society, the private sector, and academia. Registration will open soon.'],
                ['question' => 'How can I contribute?', 'answer' => 'You can contribute by responding to our Call for Submissions, sharing your experience, or helping spread the word using our Communication Kit.'],
            ],
        ];
    }

    public function getInvolved()
    {
        $settings = $this->getGetInvolvedSettings();
        return Inertia::render('Admin/GetInvolved', ['settings' => $settings]);
    }

    public function updateGetInvolved(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string',
            'hero_subtitle' => 'nullable|string',
            'hero_intro' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'hero_slides' => 'nullable|array',
            'hero_slides.*' => 'nullable|string',
            'spread_title' => 'nullable|string',
            'spread_body' => 'nullable|string',
            'spread_pending' => 'nullable|string',
            'partners_title' => 'nullable|string',
            'partners_body' => 'nullable|string',
            'partners_pending' => 'nullable|string',
            'partners_logos' => 'nullable|array',
            'partners_logos.*' => 'nullable|string',
            'financial_title' => 'nullable|string',
            'financial_body' => 'nullable|string',
            'categories_section_title' => 'nullable|string',
            'categories_section_subtitle' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*.title' => 'nullable|string',
            'categories.*.description' => 'nullable|string',
            'categories.*.icon' => 'nullable|string|in:presentation,document',
            'cta_paragraph1' => 'nullable|string',
            'cta_paragraph2' => 'nullable|string',
            'cta_paragraph3' => 'nullable|string',
            'cta_cards' => 'nullable|array',
            'cta_cards.*.href' => 'nullable|string',
            'cta_cards.*.title' => 'nullable|string',
            'cta_cards.*.subtitle' => 'nullable|string',
            'cta_cards.*.style' => 'nullable|string|in:primary,secondary',
        ]);

        $current = $this->getGetInvolvedSettings();

        // Hero main image
        $heroImage = $current['hero_image'] ?? '/brand/img34.jpg';
        if ($request->hasFile('hero_image')) {
            if (!empty($heroImage) && str_starts_with($heroImage, '/') && file_exists(public_path($heroImage))) {
                @unlink(public_path($heroImage));
            }
            $file = $request->file('hero_image');
            $filename = 'getinvolved_hero_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand/getinvolved'))) {
                mkdir(public_path('brand/getinvolved'), 0755, true);
            }
            $file->move(public_path('brand/getinvolved'), $filename);
            $heroImage = '/brand/getinvolved/' . $filename;
        }

        // Hero slides
        $existingSlides = $validated['hero_slides'] ?? $current['hero_slides'] ?? [];
        if (!is_array($existingSlides)) {
            $existingSlides = [];
        }
        $heroSlides = array_values(array_filter($existingSlides, static fn ($value) => is_string($value) && $value !== ''));

        foreach ([1, 2, 3] as $i) {
            $key = 'hero_slide_' . $i . '_file';
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = 'getinvolved_hero_slide_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                if (!is_dir(public_path('brand/getinvolved'))) {
                    mkdir(public_path('brand/getinvolved'), 0755, true);
                }
                $file->move(public_path('brand/getinvolved'), $filename);
                $heroSlides[] = '/brand/getinvolved/' . $filename;
            }
        }

        // Partners logos
        $existingPartnerLogos = $validated['partners_logos'] ?? $current['partners_logos'] ?? [];
        if (!is_array($existingPartnerLogos)) {
            $existingPartnerLogos = [];
        }
        $partnerLogos = array_values(array_filter($existingPartnerLogos, static fn ($value) => is_string($value) && $value !== ''));

        foreach ([1, 2, 3, 4, 5, 6] as $i) {
            $key = 'partner_logo_' . $i . '_file';
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = 'getinvolved_partner_logo_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                if (!is_dir(public_path('brand/getinvolved/partners'))) {
                    mkdir(public_path('brand/getinvolved/partners'), 0755, true);
                }
                $file->move(public_path('brand/getinvolved/partners'), $filename);
                $partnerLogos[] = '/brand/getinvolved/partners/' . $filename;
            }
        }

        $merged = array_merge(
            $current,
            $validated,
            [
                'hero_image' => $heroImage,
                'hero_slides' => array_values($heroSlides),
                'partners_logos' => array_values($partnerLogos),
            ]
        );
        Storage::put('admin/get-involved-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'Get Involved page updated successfully.');
    }

    private function getGetInvolvedSettings()
    {
        if (Storage::exists('admin/get-involved-settings.json')) {
            return json_decode(Storage::get('admin/get-involved-settings.json'), true);
        }

        return [
            'hero_title' => 'Call for Submissions',
            'hero_subtitle' => 'We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from 31 August – 4 September 2026.',
            'hero_intro' => 'CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.',
            'hero_image' => '/brand/img34.jpg',
            'hero_slides' => [],
            'spread_title' => 'Spread the word!',
            'spread_body' => 'PENDING: Communication Toolkit will be provided by COFSA soon.',
            'spread_pending' => '',
            'partners_title' => 'Together with our partners',
            'partners_body' => 'This Summit is made possible through strong partnerships.',
            'partners_pending' => 'PENDING: COFSA WILL GIVE YOU ALL THE PARTNER LOGOS',
            'partners_logos' => [],
            'financial_title' => 'Financial support to expand Summit impact',
            'financial_body' => 'At the Global Conscious Food Systems Summxit 2026, our priority is to expand the impact and accessibility of this gathering to all sectors and stakeholders, especially grassroots champions, small-scale producers, and those working actively in the field to shape the future of food systems.\n\nWe believe that diverse participation strengthens collective learning, fosters inclusive dialogue, and amplifies transformative action.\n\nTo make this vision a reality, we are seeking financial support from institutions, partners, and donors who share our commitment to equitable access and meaningful engagement.\n\nFinancial contributions can be made through sponsorship, partnerships, or directed grant support. For more information on how your organization or institution can support this initiative—including available sponsorship tiers, recognition opportunities, and impact highlights—please contact the Summit team at Thomas.Legrand@undp.org.',
            'categories_section_title' => 'Submission Categories',
            'categories_section_subtitle' => 'Applicants may submit one or more proposals under the following categories:',
            'categories' => [
                ['title' => 'Whole Session', 'description' => 'For applicants who propose to design and facilitate a full session during the Summit, workshops should be interactive and experiential, and focus on the themes illustrated below (see "Submission Themes"). The sessions will be 1.15 hrs length.', 'icon' => 'presentation'],
                ['title' => 'Case Study', 'description' => 'For individuals or organizations not yet ready to propose a full interactive session, there is the option to submit a case study, experience, or initiative for inclusion in a panel-style or collective session with other contributors. Case studies should illustrate practical experiences, lessons learned, or emerging insights related to conscious food systems. The CoFSA team will propose how to integrate this in a session.', 'icon' => 'document'],
            ],
            'cta_paragraph1' => 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.',
            'cta_paragraph2' => 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.',
            'cta_paragraph3' => 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.',
            'cta_cards' => [
                ['href' => 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', 'title' => 'Submit Your Application', 'subtitle' => 'Complete the application form by 31st March', 'style' => 'primary'],
                ['href' => 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', 'title' => 'Full Submission Guidelines', 'subtitle' => 'View details, eligibility criteria and guidelines', 'style' => 'secondary'],
            ],
        ];
    }

    public function getInvolvedPublic()
    {
        $settings = $this->getGetInvolvedSettings();
        return Inertia::render('GetInvolved/Index', ['settings' => $settings]);
    }

    public function programme()
    {
        $settings = $this->getProgrammeSettings();
        return Inertia::render('Admin/Programme', ['settings' => $settings]);
    }

    public function programmePublic()
    {
        // Base Programme/Experience page content
        $programmeSettings = $this->getProgrammeSettings();

        // Reuse Events Plan configuration from Homepage settings
        $homepageSettings = $this->getHomepageSettings();

        $settings = array_merge($programmeSettings, [
            'events_title' => $homepageSettings['events_title'] ?? 'Events Plan',
            'events_subtitle' => $homepageSettings['events_subtitle'] ?? '31 August – 4 September 2026 · Thimphu, Bhutan',
            'events_days' => $homepageSettings['events_days'] ?? [],
        ]);

        return Inertia::render('Programme/Index', ['settings' => $settings]);
    }

    public function yourStayPublic()
    {
        $settings = $this->getYourStaySettings();
        return Inertia::render('YourStay/Index', ['settings' => $settings]);
    }

    public function updateProgramme(Request $request)
    {
        $validationRules = [
            // Section 1 – A Transformative Summit Experience
            'transform_title' => 'nullable|string',
            'transform_p1' => 'nullable|string',
            'transform_p2' => 'nullable|string',
            'transform_p3' => 'nullable|string',
            'transform_p4' => 'nullable|string',

            // Section 1a – Call for Submissions
            'cfs_title' => 'nullable|string',
            'cfs_body' => 'nullable|string',
            'cfs_apply' => 'nullable|string',
            'cfs_button' => 'nullable|string',
            'cfs_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',

            // Section 2 – Core Themes intro
            'core_title' => 'nullable|string',
            'core_intro' => 'nullable|string',

            // Section 3 – Building a Global Movement
            'global_title' => 'nullable|string',
            'global_p1' => 'nullable|string',
            'global_p2' => 'nullable|string',
            'global_p3' => 'nullable|string',
            'global_p4' => 'nullable|string',
            'global_quote' => 'nullable|string',
            'global_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'transform_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'transform_slides' => 'nullable|array',
            'transform_slides.*' => 'nullable|string',

            // Section 1 – Programme highlights
            'programme_highlights_subtitle' => 'nullable|string',
            'programme_highlights' => 'nullable|array',
            'programme_highlights.*.title' => 'nullable|string',
            'programme_highlights.*.body' => 'nullable|string',

            // Section 3 – cards
            'global_cards' => 'nullable|array',
            'global_cards.*.title' => 'nullable|string',
            'global_cards.*.body' => 'nullable|string',

            // Theme cards
            'themes' => 'nullable|array',
            'themes.*.number' => 'nullable|integer',
            'themes.*.title' => 'nullable|string',
            'themes.*.description' => 'nullable|string',
            'themes.*.contact' => 'nullable|string',
            'themes.*.lead' => 'nullable|string',
        ];
        foreach (array_keys($request->all()) as $key) {
            if (preg_match('/^theme_\d+_image$/', $key)) {
                $validationRules[$key] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            }
            if (preg_match('/^theme_\d+_delete_image$/', $key)) {
                $validationRules[$key] = 'nullable|boolean';
            }
        }
        $validated = $request->validate($validationRules);

        $currentSettings = $this->getProgrammeSettings();
        $themes = $validated['themes'] ?? $currentSettings['themes'] ?? [];
        $globalCards = $validated['global_cards'] ?? $currentSettings['global_cards'] ?? [];

        $deletePublicFile = static function (?string $path): void {
            if (!$path || !is_string($path)) return;
            if (str_starts_with($path, '/storage/')) {
                $rel = ltrim(substr($path, strlen('/storage/')), '/');
                if ($rel !== '') {
                    Storage::disk('public')->delete($rel);
                }
                return;
            }
            if (str_starts_with($path, '/') && file_exists(public_path($path))) {
                @unlink(public_path($path));
            }
        };

        $storePublicFile = static function ($file, string $dir, string $prefix): string {
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            Storage::disk('public')->putFileAs($dir, $file, $filename);
            return '/storage/' . trim($dir, '/') . '/' . $filename;
        };

        // Section 1 header image
        $transformImage = $currentSettings['transform_image'] ?? '/brand/img34.jpg';
        if ($request->hasFile('transform_image')) {
            $deletePublicFile($transformImage);
            $transformImage = $storePublicFile($request->file('transform_image'), 'experience', 'programme_transform');
        }

        $existingSlides = $validated['transform_slides'] ?? $currentSettings['transform_slides'] ?? [];
        if (!is_array($existingSlides)) {
            $existingSlides = [];
        }
        $transformSlides = array_values(array_filter($existingSlides, static fn ($value) => is_string($value) && $value !== ''));

        foreach ([1, 2, 3] as $i) {
            $key = 'transform_slide_' . $i . '_file';
            if ($request->hasFile($key)) {
                $transformSlides[] = $storePublicFile($request->file($key), 'experience', 'programme_transform_slide_' . $i);
            }
        }

        foreach ($themes as $index => &$theme) {
            if (!isset($theme['image']) && isset($currentSettings['themes'][$index]['image'])) {
                $theme['image'] = $currentSettings['themes'][$index]['image'];
            }
            if ($request->hasFile("theme_{$index}_image")) {
                $deletePublicFile($theme['image'] ?? null);
                $theme['image'] = $storePublicFile($request->file("theme_{$index}_image"), 'themes', 'theme_' . $index);
            }
            if ($request->input("theme_{$index}_delete_image")) {
                $deletePublicFile($theme['image'] ?? null);
                $theme['image'] = '';
            }
        }

        // Section 1a image handling
        $cfsImage = $currentSettings['cfs_image'] ?? '/brand/img34.jpg';
        if ($request->hasFile('cfs_image')) {
            $deletePublicFile($cfsImage);
            $cfsImage = $storePublicFile($request->file('cfs_image'), 'experience', 'cfs');
        }

        // Section 3 image handling
        $globalImage = $currentSettings['global_image'] ?? '/brand/img30.jpg';
        if ($request->hasFile('global_image')) {
            $deletePublicFile($globalImage);
            $globalImage = $storePublicFile($request->file('global_image'), 'experience', 'global');
        }

        $merged = array_merge(
            $currentSettings,
            [
                // Section 1
                'transform_title' => $validated['transform_title'] ?? ($currentSettings['transform_title'] ?? null),
                'transform_p1' => $validated['transform_p1'] ?? ($currentSettings['transform_p1'] ?? null),
                'transform_p2' => $validated['transform_p2'] ?? ($currentSettings['transform_p2'] ?? null),
                'transform_p3' => $validated['transform_p3'] ?? ($currentSettings['transform_p3'] ?? null),
                'transform_p4' => $validated['transform_p4'] ?? ($currentSettings['transform_p4'] ?? null),
                'transform_image' => $transformImage,
                'transform_slides' => array_values($transformSlides),
                'programme_highlights_subtitle' => $validated['programme_highlights_subtitle'] ?? ($currentSettings['programme_highlights_subtitle'] ?? null),
                'programme_highlights' => array_values($validated['programme_highlights'] ?? ($currentSettings['programme_highlights'] ?? [])),

                // Section 1a
                'cfs_title' => $validated['cfs_title'] ?? ($currentSettings['cfs_title'] ?? null),
                'cfs_body' => $validated['cfs_body'] ?? ($currentSettings['cfs_body'] ?? null),
                'cfs_apply' => $validated['cfs_apply'] ?? ($currentSettings['cfs_apply'] ?? null),
                'cfs_button' => $validated['cfs_button'] ?? ($currentSettings['cfs_button'] ?? null),
                'cfs_image' => $cfsImage,

                // Section 2
                'core_title' => $validated['core_title'] ?? ($currentSettings['core_title'] ?? null),
                'core_intro' => $validated['core_intro'] ?? ($currentSettings['core_intro'] ?? null),

                // Section 3
                'global_title' => $validated['global_title'] ?? ($currentSettings['global_title'] ?? null),
                'global_p1' => $validated['global_p1'] ?? ($currentSettings['global_p1'] ?? null),
                'global_p2' => $validated['global_p2'] ?? ($currentSettings['global_p2'] ?? null),
                'global_p3' => $validated['global_p3'] ?? ($currentSettings['global_p3'] ?? null),
                'global_p4' => $validated['global_p4'] ?? ($currentSettings['global_p4'] ?? null),
                'global_quote' => $validated['global_quote'] ?? ($currentSettings['global_quote'] ?? null),
                'global_image' => $globalImage,

                // Theme cards
                'themes' => array_values($themes),
                'global_cards' => array_values($globalCards),
            ]
        );

        Storage::put('admin/programme-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'Programme & Experience updated successfully.');
    }

    private function getProgrammeSettings()
    {
        if (Storage::exists('admin/programme-settings.json')) {
            return json_decode(Storage::get('admin/programme-settings.json'), true);
        }

        return [
            // Section 1 – A Transformative Summit Experience
            'transform_title' => 'A Transformative Summit Experience',
            'transform_p1' => 'The Summit is a call to imagine beyond what is—towards redesigning food systems through creativity, courage, and a shared higher vision. This Summit also creates a space to pause, reflect, and reconnect with what truly matters.',
            'transform_p2' => 'Together, we will explore how food systems can become sources of regeneration, justice, and wellbeing—for people, communities, and the planet.',
            'transform_p3' => 'We invite you to bring your whole self: your lived experience, innovative practices, ancestral wisdom, research, policy insights, entrepreneurial ideas, and inner knowing.',
            'transform_p4' => 'The Summit will combine case studies with interactive workshops involving learning, co-creation and deepening consciousness-based practices, fostering both personal transformation and systemic change.',
            'transform_image' => '/brand/img34.jpg',
            'transform_slides' => ['/brand/img34.jpg'],
            'programme_highlights_subtitle' => 'Programme Highlights: Exploring the Summit Day by Day',
            'programme_highlights' => [
                [
                    'title' => 'Monday, 31 August 2026. Stepping into Bhutan through immersive experiences (optional)',
                    'body' => 'Take advantage of this day to connect with the Bhutanese nature, people, spirituality and the philosophy of Gross National Happiness through immersive experiences exploring conscious food systems. Meet farmers, learn how food, culture, and ecology are deeply interwoven in everyday life, or visit the iconic Tiger’s Nest Monastery.',
                ],
                [
                    'title' => 'Tuesday, 01 September 2026. Igniting Consciousness for Systemic Transformation (High level segment)',
                    'body' => 'Kick off the summit with visionary leaders and changemakers exploring how consciousness can catalyze systemic transformation. Through the opening ceremony, inspiring dialogues and experiential activities, participants will reflect on conscious leadership and connect with the deeper purpose and values driving the global conscious food systems movement.',
                ],
                [
                    'title' => 'Wednesday, 02 September 2026. Exploring Conscious Food Systems in practice',
                    'body' => 'Celebrate the release of our collective vision for conscious food systems and travel across continents through pioneering stories, models and practices. In interactive sessions and sharing circles, realize the power of healing for food systems transformation and the role of inner capacities in the agroecological transition.',
                ],
                [
                    'title' => 'Thursday, 03 September, 2026. Bhutan: A Living Laboratory for Conscious Food Systems',
                    'body' => 'Immerse yourself in Bhutan’s unique approach to food systems, where culture, spirituality, and the philosophy of Gross National Happiness guide nature-friendly agriculture. Experience local practices and products firsthand and explore how indigenous knowledge, faith-based perspectives and consciousness practices can foster regenerative food systems and conscious consumption across the world.',
                ],
                [
                    'title' => 'Friday, 04 September, 2026. From Inspiration to Collective Action',
                    'body' => 'Conclude the summit by exploring pathways to scale conscious food systems through enabling policy, effective governance, and collaborative strategies that turn ideas into action. Share and listen to personal insights and commitments in circles and co-create collective recommendations before celebrating achievements with a vibrant cultural show and festive closing party.',
                ],
            ],

            // Section 1a – Call for Submissions
            'cfs_title' => 'Call for Submissions',
            'cfs_body' => 'The organizers welcome submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.',
            'cfs_apply' => 'Apply by 31st of March!',
            'cfs_button' => 'Help shape the Summit! Respond to our Call for Submissions',
            'cfs_image' => '/brand/img34.jpg',

            // Section 2 – Core Themes intro
            'core_title' => 'Core Themes',
            'core_intro' => 'The Summit will weave together the following core themes. Rather than isolated tracks, they are intended to intersect and inform one another, reflecting the interconnected nature of conscious food systems.',

            // Section 3 – Building a Global Movement
            'global_title' => 'Building a Global Movement',
            'global_p1' => 'Policymakers, farmers, Indigenous leaders, researchers, businesses, youth, philanthropies, academia, civil society actors, and consciousness practitioners will assemble in one circle for a deeply transformative experience.',
            'global_p2' => 'High-level dialogues will include the participation of the Prime Minister of Bhutan, as well as heads of state and ministers from other countries.',
            'global_p3' => 'Participants will also have the opportunity to take part in immersive site visits to local initiatives and conscious food projects.',
            'global_p4' => 'Together, these exchanges will help catalyse connections and contribute to a growing global movement for conscious food systems.',
            'global_quote' => "The Summit will advance Bhutan's National Food Systems Transformation Pathways by cultivating a conscious inner mindset, while highlighting Bhutan's leadership in driving conscious food systems transformation globally.",
            'global_image' => '/brand/img30.jpg',

            'global_cards' => [
                [
                    'title' => 'Connect & Collaborate',
                    'body' => 'Bring together 350–500 changemakers — governments, farmers, youth, civil society, researchers, and private sector — in meaningful dialogue and co-creation.',
                ],
                [
                    'title' => 'Amplify Impact',
                    'body' => 'Share knowledge, innovations, and stories that inspire systemic change — bridging local wisdom with global action to advance conscious food systems worldwide.',
                ],
                [
                    'title' => 'Drive Transformation',
                    'body' => 'Forge commitments, partnerships, and pathways that outlast the Summit — embedding conscious values into food policy, practice, and culture at every level.',
                ],
            ],

            // Core theme cards
            'themes' => [
                ['number' => 1, 'title' => "Farmers' wellbeing and the inner dimension of agroecology", 'description' => "This area focuses on the wellbeing of farmers and producers (including foresters, livestock herders and fisherworkers) and the inner dimensions of agroecology. It explores how awareness, care, dignity, and connection to Nature influence sustainable farming practices, resilience, decision making, and regenerative land stewardship.", 'image' => '/brand/themes/1.jpg', 'contact' => 'jimena.gomez@undp.org', 'lead' => 'Jimena Gomez'],
                ['number' => 2, 'title' => "Conscious food policy and governance", 'description' => "This workstream examines how values, awareness, mindsets and inner capacities can shape food related policies, governance mechanisms, and investment flows. It includes reflections on equitable and ethical decision-making, participatory governance, long-term thinking, and aligning public and private investments with regenerative and sustainable food systems.", 'image' => '/brand/themes/2.jpg', 'contact' => 'charlotte.dufour@undp.org', 'lead' => 'Charlotte Dufour'],
                ['number' => 3, 'title' => "Conscious consumption", 'description' => "This theme explores the relationship between consumers and food, emphasizing responsibility, awareness, and connection. It includes themes such as farmer–consumer relationships, farmers' markets and short food chains, public procurement and institutional food systems, conscious food choices and food citizenship, school-based approaches to conscious food, the role of chefs and catering.", 'image' => '/brand/themes/3.jpg', 'contact' => 'charlotte.dufour@undp.org', 'lead' => 'Charlotte Dufour'],
                ['number' => 4, 'title' => "Farmers-consumers relations & mindful markets", 'description' => "This sub-theme focuses on strengthening direct, transparent, and values-based relationships between farmers and consumers, including intermediaries in the relationship. It explores mindful markets, short food chains, fair pricing, trust-building, and community-supported models that support farmer livelihoods while fostering consumer awareness, responsibility, and connection to food systems.", 'image' => '/brand/themes/4.jpg', 'contact' => 'jimena.gomez@undp.org', 'lead' => 'Jimena Gomez'],
                ['number' => 5, 'title' => "Traditional wisdom for conscious food systems", 'description' => "This area highlights traditional and Indigenous wisdom, ancestral practices, and traditional food cultures that embody deep relationships with land, community, and spirit. It explores how Indigenous and traditional wisdom informs sustainable, ethical, and regenerative food systems today.", 'image' => '/brand/themes/5.jpg', 'contact' => 'luz.navarro@undp.org', 'lead' => 'Luz Elena Navarro'],
                ['number' => 6, 'title' => "Regenerative local cultures", 'description' => "This workstream focuses on place-based food systems and the cultural, social, and relational dimensions that sustain them. It includes community-led initiatives, local food cultures, collective care, and cultural regeneration as foundations for resilient food systems.", 'image' => '/brand/themes/6.jpg', 'contact' => 'luz.navarro@undp.org', 'lead' => 'Luz Elena Navarro'],
                ['number' => 7, 'title' => "Conscious organisational culture and leadership", 'description' => "This area explores how inner capacities and leadership practices—such as self-awareness, empathy, trust, and purpose—can transform organizations working in food systems. It includes conscious leadership, organizational wellbeing, collaborative cultures, and values-driven governance.", 'image' => '/brand/themes/7.jpg', 'contact' => 'thomas.legrand@undp.org', 'lead' => 'Thomas Legrand'],
                ['number' => 8, 'title' => "Youth engagement in conscious food systems", 'description' => "This theme centers on young people as agents of transformation, exploring how youth cultivate purpose, agency, leadership, and inner resilience within food systems. Submissions may highlight youth-led initiatives, intergenerational learning, education, and movement-building.", 'image' => '/brand/themes/8.jpg', 'contact' => 'noemi.altobelli@undp.org', 'lead' => 'Noemi Altobelli'],
                ['number' => 9, 'title' => "Conscious food systems in research and higher education", 'description' => "This workstream examines how universities, research institutions, and learning spaces can integrate inner development, ethics, reflection, and transdisciplinary approaches into food systems research and education.", 'image' => '/brand/themes/9.jpg', 'contact' => 'abigail.stein@undp.org', 'lead' => 'Abigail Stein'],
                ['number' => 10, 'title' => "Faith-based approaches to conscious food systems", 'description' => "This area explores how faith traditions, spiritual practices, and moral frameworks inform food ethics, stewardship, care for creation, and social justice, contributing to conscious and regenerative food systems.", 'image' => '/brand/themes/10.jpg', 'contact' => 'thomas.legrand@undp.org', 'lead' => 'Thomas Legrand'],
                ['number' => 11, 'title' => "Private sector engagement", 'description' => "This theme focuses on the role of business, social enterprises, impact investors, and corporations in advancing conscious food systems. It includes values-driven entrepreneurship, corporate transformation, ethical investment, and aligning profit with purpose.", 'image' => '/brand/themes/11.jpg', 'contact' => 'thomas.legrand@undp.org', 'lead' => 'Thomas Legrand'],
                ['number' => 12, 'title' => "Women's leadership in conscious food systems", 'description' => "This workstream highlights the leadership, wisdom, and lived experiences of women across food systems, emphasizing the unique contributions of feminine energy. Submissions may explore care-centered leadership, embodiment practices, rituals, womb wisdom, body listening, intuition, and empowerment, as well as women's roles in shaping sustainable, regenerative and conscious food futures that honor both people and the planet.", 'image' => '/brand/themes/12.jpg', 'contact' => 'noemi.altobelli@undp.org', 'lead' => 'Noemi Altobelli'],
                ['number' => 13, 'title' => "Conscious food systems in crisis situations", 'description' => "This area explores how consciousness-based approaches can inform food systems responses in times of crisis, including conflict, climate shocks, displacement, and humanitarian contexts. It emphasizes dignity, care, trauma awareness, and long-term regeneration beyond emergency responses and how food systems intervention can be leveraged for peace and reconciliation.", 'image' => '/brand/themes/13.jpg', 'contact' => 'charlotte.dufour@undp.org', 'lead' => 'Charlotte Dufour'],
                ['number' => 14, 'title' => "Planetary health / One health", 'description' => "This workstream focuses on the interconnected health of humans, animals, and the planet, exploring how regenerative, equitable and sustainable food systems can support ecological balance, biodiversity, and livelihoods.", 'image' => '/brand/themes/14.jpg', 'contact' => 'charlotte.dufour@undp.org', 'lead' => 'Charlotte Dufour'],
                ['number' => 15, 'title' => "Consciousness practices for food systems transformation", 'description' => "This workstream focuses on inner and collective practices that deepen awareness, connection, and transformation in relation to food systems and the living planet. Examples of practices include: rituals and ceremonies, art (music, theater, painting…), contemplative and embodied practices, non violent communication, conscious facilitation and space holding, community healing, and approaches that foster meaningful relationships between humans and nature.", 'image' => '/brand/themes/15.jpg', 'contact' => 'noemi.altobelli@undp.org', 'lead' => 'Noemi Altobelli'],
            ],
        ];
    }

    public function yourStay()
    {
        $settings = $this->getYourStaySettings();
        return Inertia::render('Admin/YourStay', ['settings' => $settings]);
    }

    public function updateYourStay(Request $request)
    {
        $allData = $request->all();
        
        $validationRules = [
            // Hero header
            'yourstay_hero_title' => 'nullable|string',
            'yourstay_hero_subtitle' => 'nullable|string',
            'yourstay_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'yourstay_hero_slides' => 'nullable|array',
            'yourstay_hero_slides.*' => 'nullable|string',

            'participation_costs_title' => 'nullable|string',
            'participation_costs_description' => 'nullable|string',
            'participation_costs_announcement' => 'nullable|string',
            'field_visits_title' => 'nullable|string',
            'field_visits_info' => 'nullable|string',
            'field_visits_pending' => 'nullable|string',
            'field_visits_expectations' => 'nullable|array',
            'air_travel_intro' => 'nullable|string',
            'air_travel_title' => 'nullable|string',
            'air_travel_guidance_title' => 'nullable|string',
            'air_travel_guidance_points' => 'nullable|array',
            'airlines_title' => 'nullable|string',
            'flight_table_note' => 'nullable|string',
            'flight_table_saarc_note' => 'nullable|string',
            'airlines' => 'nullable|array',
            'flight_routes' => 'nullable|array',
            'flight_table_title' => 'nullable|string',
            'transit_visa_title' => 'nullable|string',
            'transit_visa_description' => 'nullable|string',
            'visa_title' => 'nullable|string',
            'visa_support_title' => 'nullable|string',
            'visa_support_points' => 'nullable|array',
            'visa_important_title' => 'nullable|string',
            'visa_important_points' => 'nullable|array',
            'visa_independent_title' => 'nullable|string',
            'visa_independent_description' => 'nullable|string',
            'visa_independent_points' => 'nullable|array',
            'visa_independent_button_text' => 'nullable|string',
            'visa_independent_button_url' => 'nullable|url',
            'arrival_title' => 'nullable|string',
            'mobile_title' => 'nullable|string',
            'mobile_description' => 'nullable|string',
            'mobile_providers' => 'nullable|array',
            'mobile_cost' => 'nullable|string',
            'mobile_cost_note' => 'nullable|string',
            'mobile_tip' => 'nullable|string',
            'currency_title' => 'nullable|string',
            'currency_local' => 'nullable|string',
            'currency_note' => 'nullable|string',
            'currency_accepted' => 'nullable|array',
            'currency_tip' => 'nullable|string',
            'arrival_tips_title' => 'nullable|string',
            'arrival_tips_points' => 'nullable|array',
            'accommodation_title' => 'nullable|string',
            'accommodation_important' => 'nullable|string',
            'accommodation_booking_tips_title' => 'nullable|string',
            'accommodation_booking_tips' => 'nullable|array',
            'hotels' => 'nullable|array',
            'extend_title' => 'nullable|string',
            'extend_intro' => 'nullable|string',
            'extend_process_title' => 'nullable|string',
            'extend_process_description' => 'nullable|string',
            'extend_process_steps' => 'nullable|array',
            'extend_costs_title' => 'nullable|string',
            'extend_sdf_amount' => 'nullable|string',
            'extend_sdf_note' => 'nullable|string',
            'extend_additional_costs' => 'nullable|array',
            'extend_requirement_title' => 'nullable|string',
            'extend_requirement_description' => 'nullable|string',
            'extend_requirement_note' => 'nullable|string',
            'extend_portal_title' => 'nullable|string',
            'extend_portal_description' => 'nullable|string',
            'extend_portal_url' => 'nullable|string',
            'extend_activities_title' => 'nullable|string',
            'extend_activities' => 'nullable|array',
            'companions_title' => 'nullable|string',
            'companions_intro' => 'nullable|string',
            'companions_requirements_title' => 'nullable|string',
            'companions_requirements' => 'nullable|array',
            'companions_costs_title' => 'nullable|string',
            'companions_costs' => 'nullable|array',
            'companions_costs_note' => 'nullable|string',
            'companions_considerations_title' => 'nullable|string',
            'companions_considerations' => 'nullable|array',
            'companions_tips_title' => 'nullable|string',
            'companions_tips' => 'nullable|array',
            'companions_resources_title' => 'nullable|string',
            'companions_resources' => 'nullable|array',
            'included_title' => 'nullable|string',
            'included_items' => 'nullable|array',
            'not_included_title' => 'nullable|string',
            'not_included_items' => 'nullable|array',
        ];

        $validated = $request->validate($validationRules);
        $currentSettings = $this->getYourStaySettings();

        // Hero image handling
        $heroImage = $currentSettings['yourstay_hero_image'] ?? '/brand/img34.jpg';
        if ($request->hasFile('yourstay_hero_image')) {
            if (!empty($heroImage) && str_starts_with($heroImage, '/') && file_exists(public_path($heroImage))) {
                @unlink(public_path($heroImage));
            }
            $file = $request->file('yourstay_hero_image');
            $filename = 'yourstay_hero_' . time() . '.' . $file->getClientOriginalExtension();
            if (!is_dir(public_path('brand/yourstay'))) {
                mkdir(public_path('brand/yourstay'), 0755, true);
            }
            $file->move(public_path('brand/yourstay'), $filename);
            $heroImage = '/brand/yourstay/' . $filename;
        }

        // Hero slides handling: existing string paths + any new uploads
        $existingSlides = $validated['yourstay_hero_slides'] ?? $currentSettings['yourstay_hero_slides'] ?? [];
        if (!is_array($existingSlides)) {
            $existingSlides = [];
        }
        $heroSlides = array_values(array_filter($existingSlides, static fn ($value) => is_string($value) && $value !== ''));

        foreach ([1, 2, 3] as $i) {
            $key = 'yourstay_hero_slide_' . $i . '_file';
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = 'yourstay_hero_slide_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                if (!is_dir(public_path('brand/yourstay'))) {
                    mkdir(public_path('brand/yourstay'), 0755, true);
                }
                $file->move(public_path('brand/yourstay'), $filename);
                $heroSlides[] = '/brand/yourstay/' . $filename;
            }
        }

        $merged = array_merge(
            $currentSettings,
            $validated,
            [
                'yourstay_hero_title' => $validated['yourstay_hero_title'] ?? ($currentSettings['yourstay_hero_title'] ?? null),
                'yourstay_hero_subtitle' => $validated['yourstay_hero_subtitle'] ?? ($currentSettings['yourstay_hero_subtitle'] ?? null),
                'yourstay_hero_image' => $heroImage,
                'yourstay_hero_slides' => array_values($heroSlides),
            ]
        );

        Storage::put('admin/yourstay-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'Your Stay in Bhutan page updated successfully!');
    }

    private function getYourStaySettings()
    {
        if (Storage::exists('admin/yourstay-settings.json')) {
            return json_decode(Storage::get('admin/yourstay-settings.json'), true);
        }

        return $this->getDefaultYourStaySettings();
    }

    private function getDefaultYourStaySettings()
    {
        return [
            'yourstay_hero_title' => 'Your Stay in Bhutan',
            'yourstay_hero_subtitle' => 'Practical guidance for travel, visas, accommodation, and extending your visit.',
            'yourstay_hero_image' => '/brand/img34.jpg',
            'yourstay_hero_slides' => [],

            'participation_costs_title' => 'WHAT ARE THE PARTICIPATION COSTS?',
            'participation_costs_description' => 'Participation in the Conscious Food Systems Summit requires an entrance ticket, which helps cover the essential costs of organizing and delivering the event, including venue arrangements, meals, local logistics, and administrative coordination.',
            'participation_costs_announcement' => 'Announcement: The official ticket price will be announced following the formal launch of the Summit in early March. We kindly invite participants to stay tuned for further updates.',
            'included_title' => 'What\'s Included',
            'included_items' => [
                ['title' => 'Event Access', 'description' => 'Entrance and participation from 01–04 September'],
                ['title' => 'Daily Meals', 'description' => 'Daily lunch for summit participants\n• Two tea/coffee breaks per day\n• Between two to three group dinners', 'isList' => true],
                ['title' => 'Visa Application', 'description' => 'Visa application fee of BTN 5,000, plus full coordination of the visa entry permission process. Participants arranging their visa independently will pay USD 65 per person. Indian nationals are exempt from visa fees.'],
                ['title' => 'SDF Waiver (Special)', 'description' => 'The Royal Government of Bhutan is granting all Summit participants a waiver of the Sustainable Development Fee (SDF), normally USD 100 per adult per night, for the official event period from Friday 28 August to Sunday 06 September, 2026.', 'highlighted' => true],
                ['title' => 'Local Transportation', 'description' => 'Transportation between the hotel in Paro and the event venue from 01-04 September'],
            ],
            'not_included_title' => 'What\'s NOT Included',
            'not_included_items' => [
                ['title' => 'International Travel', 'description' => 'International travel to and from Bhutan (Paro airport)'],
                ['title' => 'Accommodation', 'description' => 'Hotel accommodation costs in Paro'],
                ['title' => 'Airport Transfers', 'description' => 'Transportation between Paro airport and your hotel'],
                ['title' => 'Additional Dinners', 'description' => 'One to two dinners outside the official program'],
                ['title' => 'Personal Expenses', 'description' => 'Any personal expenses or additional costs incurred during your stay in Bhutan', 'highlighted' => true],
            ],
            'field_visits_title' => 'Will There Be Field Visits?',
            'field_visits_info' => 'Field visits on 31 August are available at an additional cost and can be selected during registration.',
            'field_visits_pending' => '(Pending confirmation)',
            'field_visits_expectations' => [
                'Experience Bhutan\'s unique development approach firsthand',
                'Visit local communities and sustainable development projects',
                'Engage with stakeholders on the ground'
            ],
            'air_travel_intro' => 'Summit participants are responsible for booking their own international flights to Bhutan.',
            'air_travel_title' => 'Air Travel to Paro',
            'air_travel_guidance_title' => 'Important Booking Guidance',
            'air_travel_guidance_points' => [
                'Do not book flights before visa approval',
                'August is high travel season — book early once approved',
                'Limited daily flights operate to Paro'
            ],
            'airlines_title' => 'Airlines Operating to Paro',
            'airlines' => [
                [
                    'name' => 'Drukair',
                    'description' => 'Royal Bhutan Airlines',
                    'image' => '/brand/plane1.jpg',
                    'website' => 'https://www.drukair.com.bt/',
                ],
                [
                    'name' => 'Bhutan Airlines',
                    'description' => 'Private carrier',
                    'image' => '/brand/plane2.jpg',
                    'website' => 'https://www.bhutanairlines.bt/',
                ],
            ],
            'flight_table_title' => 'List of available flights to Paro',
            'flight_table_note' => 'Note: Flight schedules are subject to change. Always verify with the airline before booking.',
            'flight_table_saarc_note' => '³ SAARC Regional Fare applies to citizens of: Afghanistan, Bangladesh, Bhutan, India, Maldives, Nepal, Pakistan, and Sri Lanka.',
            'flight_routes' => [
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Bangkok-Paro-Bangkok',
                    'sector_note' => '(via Guwahati)',
                    'availability' => 'Daily',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Bangkok-Paro-Bangkok',
                    'sector_note' => '(Via Bagdogra)',
                    'availability' => 'Tuesday & Saturday\n(BKK → Bagdogra→ Paro)\nMonday & Friday\n(Paro → Bagdogra → BKK)',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Delhi-Paro-Delhi',
                    'sector_note' => '',
                    'availability' => 'Daily',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Kathmandu-Paro-Kathmandu',
                    'sector_note' => '',
                    'availability' => 'Daily',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Dhaka-Paro-Dhaka',
                    'sector_note' => '',
                    'availability' => 'Monday & Friday',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Singapore-Paro-Singapore',
                    'sector_note' => '(via Guwahati)',
                    'availability' => 'Tuesdays, Thursdays,\nFridays, Sundays',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Dubai-Paro-Dubai',
                    'sector_note' => '',
                    'availability' => 'Tuesday, Friday,\nSaturday.\nTuesdays, Sundays for\nAugust',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Drukair',
                    'airline_url' => 'https://www.drukair.com.bt/',
                    'sector' => 'Kolkata-Paro-Kolkata',
                    'sector_note' => '',
                    'availability' => 'Daily',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                ],
                [
                    'airline' => 'Bhutan Airlines',
                    'airline_url' => 'https://www.bhutanairlines.bt/',
                    'sector' => '',
                    'sector_note' => '',
                    'availability' => '',
                    'saarc_business' => '',
                    'saarc_economy' => '',
                    'international_business' => '',
                    'international_economy' => '',
                    'is_note' => true,
                    'note' => 'Please check Bhutan Airlines website for current flight schedules and availability.',
                ],
            ],
            'transit_visa_title' => 'Check Transit Visa Requirements',
            'transit_visa_description' => 'If you\'re transiting through India or Nepal, you may need a transit visa. Check requirements based on your nationality.',
            'visa_title' => 'Visa & Entry Process',
            'visa_support_title' => 'Visa support',
            'visa_support_points' => [
                'All international visitors to Bhutan require an official visa or entry permit prior to arrival. In line with Bhutan’s specific entry regulations, the visa process must be carefully coordinated in advance.',
                'For Summit participants, the visa entry permission process will be fully supported by the Summit organizers to ensure a smooth and efficient experience.'
            ],
            'visa_important_title' => 'Important Notes',
            'visa_important_points' => [
                'You cannot enter Bhutan before your visa start date',
                'Route permits may be required for travel outside Paro/Thimphu',
                'Passport must be valid for at least 6 months from entry date',
                'Keep a digital and physical copy of your visa approval letter'
            ],
            'visa_independent_title' => 'Applying Independently',
            'visa_independent_description' => 'If you\'re traveling outside the Summit dates or arranging your own visa:',
            'visa_independent_points' => [
                'Standard visa fee: USD 65',
                'Sustainable Development Fee (SDF): USD 100 per night',
                'Must be arranged through a licensed Bhutanese tour operator'
            ],
            'visa_independent_button_text' => 'Official Immigration Website',
            'visa_independent_button_url' => 'https://www.immigration.gov.bt',
            'arrival_title' => 'Upon Arrival in Bhutan',
            'mobile_title' => 'Mobile & Internet',
            'mobile_description' => 'SIM cards available at Paro International Airport upon arrival',
            'mobile_providers' => ['Bhutan Telecom', 'TashiCell'],
            'mobile_cost' => 'Cost: BTN 100–500',
            'mobile_cost_note' => 'Includes data package options',
            'mobile_tip' => 'Bring your passport and an unlocked phone',
            'currency_title' => 'Currency Exchange',
            'currency_local' => 'Bhutanese Ngultrum (BTN)',
            'currency_note' => 'Pegged 1:1 with Indian Rupee (INR)',
            'currency_accepted' => [
                'Indian Rupees (INR) widely accepted',
                'ATMs available in Paro and Thimphu',
                'Credit cards accepted at major hotels'
            ],
            'currency_tip' => 'Tip: Exchange money at the airport or banks for better rates',
            'arrival_tips_title' => 'Other Arrival Tips',
            'arrival_tips_points' => [
                'Immigration process may take 30-60 minutes during peak times',
                'Keep your visa approval letter and hotel booking details handy',
                'Summit organizers will provide transfer details closer to the event',
                'Taxis and hotel shuttles available at the airport'
            ],
            'accommodation_title' => 'Accommodation in Paro',
            'accommodation_important' => 'Important: All Summit participants must stay in Paro near the venue (Dungkar Dzong). Book early as August is peak season.',
            'accommodation_booking_tips_title' => 'Booking Tips',
            'accommodation_booking_tips' => [
                'Contact hotels directly for Summit group rates',
                'Book immediately after receiving visa approval',
                'Mention you\'re attending the Summit for potential discounts',
                'Confirm cancellation policies before booking'
            ],
            'hotels' => [
                ['name' => 'Hotel Olathang', 'email' => 'olathang@druknet.bt', 'priceRange' => '$80-120', 'distance' => '2.5 km to Dungkar Dzong', 'nearAirport' => true, 'category' => 'mid-range', 'image' => ''],
                ['name' => 'Tashi Namgay Resort', 'email' => 'reservation@tashihotels.bt', 'priceRange' => '$120-180', 'distance' => '3 km to Dungkar Dzong', 'nearAirport' => true, 'category' => 'premium', 'image' => ''],
                ['name' => 'Mandala Resort & Spa', 'email' => 'info@mandalaresorts.com', 'priceRange' => '$150-250', 'distance' => '4 km to Dungkar Dzong', 'nearAirport' => false, 'category' => 'premium', 'image' => ''],
                ['name' => 'Khangkhu Resort', 'email' => 'khangkhu@druknet.bt', 'priceRange' => '$60-90', 'distance' => '3.5 km to Dungkar Dzong', 'nearAirport' => true, 'category' => 'budget', 'image' => ''],
                ['name' => 'Rema Resort', 'email' => 'remaresort@druknet.bt', 'priceRange' => '$100-140', 'distance' => '2 km to Dungkar Dzong', 'nearAirport' => true, 'category' => 'mid-range', 'image' => ''],
                ['name' => 'Hotel Jomolhari', 'email' => 'jomolhari@druknet.bt', 'priceRange' => '$50-80', 'distance' => '3 km to Dungkar Dzong', 'nearAirport' => false, 'category' => 'budget', 'image' => ''],
                ['name' => 'Le Méridien Paro', 'email' => 'reservations.paro@lemeridien.com', 'priceRange' => '$200-350', 'distance' => '5 km to Dungkar Dzong', 'nearAirport' => false, 'category' => 'premium', 'image' => ''],
                ['name' => 'Tenzinling Resort', 'email' => 'info@tenzinling.com', 'priceRange' => '$90-130', 'distance' => '2.8 km to Dungkar Dzong', 'nearAirport' => true, 'category' => 'mid-range', 'image' => '']
            ],
            'extend_title' => 'Extend Your Stay in Bhutan',
            'extend_intro' => 'Want to explore more of Bhutan? Extend your visa to experience the Land of the Thunder Dragon beyond the Summit.',
            'extend_process_title' => 'Extension Process',
            'extend_process_description' => 'You can modify your visa dates online through the official portal.',
            'extend_process_steps' => [
                ['title' => 'Maximum stay: Up to 90 days total', 'note' => 'Subject to approval'],
                ['title' => 'Apply for extension before your current visa expires', 'note' => 'Processing takes 2-3 business days'],
                ['title' => 'Must be arranged through a licensed tour operator', 'note' => 'Cannot extend independently']
            ],
            'extend_costs_title' => 'Extension Costs',
            'extend_sdf_amount' => 'USD 100',
            'extend_sdf_note' => 'Sustainable Development Fee (SDF)',
            'extend_additional_costs' => [
                'Accommodation (varies by hotel)',
                'Licensed tour guide (mandatory)',
                'Transportation for sightseeing',
                'Meals outside hotel'
            ],
            'extend_requirement_title' => 'Important Requirement',
            'extend_requirement_description' => 'Licensed tourist guide required for all travel outside Paro/Thimphu during your extended stay.',
            'extend_requirement_note' => 'This is a Bhutan tourism regulation to ensure visitor safety and cultural preservation.',
            'extend_portal_title' => 'Ready to Extend?',
            'extend_portal_description' => 'Visit the official visa portal or contact your tour operator to arrange your extension.',
            'extend_portal_url' => 'https://www.tourism.gov.bt',
            'extend_activities_title' => 'Popular Extension Activities',
            'extend_activities' => [
                ['title' => 'Tiger\'s Nest Monastery', 'description' => 'Iconic clifftop monastery hike (4-5 hours)'],
                ['title' => 'Thimphu Exploration', 'description' => 'Capital city with dzongs and museums'],
                ['title' => 'Punakha Valley', 'description' => 'Ancient capital with stunning fortress'],
                ['title' => 'Cultural Festivals', 'description' => 'Traditional Tshechu celebrations']
            ],
            'companions_title' => 'Travel With Companions',
            'companions_intro' => 'Planning to bring family or friends? Here\'s what you need to know about traveling to Bhutan with companions.',
            'companions_requirements_title' => 'Key Requirements for Companions',
            'companions_requirements' => [
                ['title' => 'Separate Visa Application', 'description' => 'Companions must apply for their visa independently through the standard tourist visa process. They cannot be included in the Summit visa arrangement.'],
                ['title' => 'Tourism Regulations Apply', 'description' => 'Companions traveling as tourists must follow Bhutan\'s standard tourism regulations, including the Sustainable Development Fee (SDF).'],
                ['title' => 'Licensed Guide Required', 'description' => 'Companions must be accompanied by a licensed Bhutanese tour guide and travel through a registered tour operator.']
            ],
            'companions_costs_title' => 'Expected Costs for Companions',
            'companions_costs' => [
                ['item' => 'Visa Fee', 'amount' => 'USD 65'],
                ['item' => 'Sustainable Development Fee (SDF)', 'amount' => 'USD 100 / night'],
                ['item' => 'Tour Package (approx.)', 'amount' => 'USD 250-400 / day']
            ],
            'companions_costs_note' => 'Note: Tour packages typically include accommodation, meals, guide, and transportation. Prices vary by season and hotel category.',
            'companions_considerations_title' => 'Special Considerations',
            'companions_considerations' => [
                'Children under 12: May be exempt from or receive reduced SDF rates',
                'Travel timing: Companions can arrive with you or independently',
                'Accommodation: Can stay at same hotel, subject to availability',
                'Summit access: Companions may not be able to attend Summit sessions unless registered'
            ],
            'companions_tips_title' => 'Planning Tips',
            'companions_tips' => [
                'Contact a licensed Bhutanese tour operator at least 6-8 weeks before travel',
                'Coordinate visa timings so companion visas align with Summit dates',
                'Book the same hotel for convenience, mentioning you\'re with the Summit',
                'Consider arranging activities for companions during Summit sessions'
            ],
            'companions_resources_title' => 'Helpful Resources',
            'companions_resources' => [
                ['title' => 'Bhutan Tourism Council', 'description' => 'Official tourism information', 'url' => 'https://www.tourism.gov.bt'],
                ['title' => 'Immigration Portal', 'description' => 'Visa application and guidelines', 'url' => 'https://www.immigration.gov.bt']
            ]
        ];
    }
}
