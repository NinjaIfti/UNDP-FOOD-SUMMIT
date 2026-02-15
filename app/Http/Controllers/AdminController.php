<?php

namespace App\Http\Controllers;

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
        if (Auth::check() && Auth::user()->hasRole('admin')) {
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

            if (Auth::user()->hasRole('admin')) {
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
        return Inertia::render('Admin/Dashboard');
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
            'hero_date' => 'nullable|string',
            'hero_location' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'countdown_date' => 'nullable|date',
            'summit_intro_title' => 'nullable|string',
            'summit_intro_text1' => 'nullable|string',
            'summit_intro_text2' => 'nullable|string',
            'coorganizers_title' => 'nullable|string',
            'govt_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'undp_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cofsa_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_govt_logo' => 'nullable|boolean',
            'delete_undp_logo' => 'nullable|boolean',
            'delete_cofsa_logo' => 'nullable|boolean',
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
            'outcomes_title' => 'nullable|string',
            'outcomes_description' => 'nullable|string',
            'outcomes_list' => 'nullable|array',
            'bhutan_title' => 'nullable|string',
            'bhutan_text1' => 'nullable|string',
            'bhutan_text2' => 'nullable|string',
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
        }
        
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

        // Remove delete flags from validated data
        unset($validated['delete_govt_logo'], $validated['delete_undp_logo'], $validated['delete_cofsa_logo']);

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
        
        // Remove all speaker image upload fields from validated data (they're already processed)
        foreach ($validated as $key => $value) {
            if (preg_match('/^speaker_\d+_(image|delete_image)$/', $key)) {
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
            'hero_date' => '31 August – 4 September 2026',
            'hero_location' => 'Bhutan',
            'hero_description' => 'Let\'s co-create a food future rooted in wisdom, nature connection, compassion, and deep collaboration.',
            'countdown_date' => '2026-08-31T09:00:00',
            'summit_intro_title' => 'A New Kind of Food Systems Summit',
            'summit_intro_text1' => 'This landmark global gathering aims to facilitate high-level dialogue, knowledge exchange, and partnership-building around advancing conscious, inclusive, and sustainable food systems. Key themes include food consciousness, agroecology, and the role of spirituality and culture in supporting nature-friendly agriculture.',
            'summit_intro_text2' => 'The Summit will convene 350–500 national and global changemakers—governments, indigenous communities, farmers, youth leaders, civil society, the private sector, and academia—prioritizing gender balance, inclusion, and meaningful participation of underrepresented voices.',
            'coorganizers_title' => 'Co-organizers',
            'govt_logo' => '/brand/govt.png',
            'undp_logo' => '/brand/undp.png',
            'cofsa_logo' => '/brand/cofsa.png',
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

        $merged = array_merge($this->getGetInvolvedSettings(), $validated);
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
        $settings = $this->getProgrammeSettings();
        return Inertia::render('Programme/Index', ['settings' => $settings]);
    }

    public function updateProgramme(Request $request)
    {
        $validationRules = [
            'expect_section_title' => 'nullable|string',
            'expect_subtitle' => 'nullable|string',
            'expectations' => 'nullable|array',
            'expectations.*' => 'nullable|string',
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

        foreach ($themes as $index => &$theme) {
            if (!isset($theme['image']) && isset($currentSettings['themes'][$index]['image'])) {
                $theme['image'] = $currentSettings['themes'][$index]['image'];
            }
            if ($request->hasFile("theme_{$index}_image")) {
                if (!empty($theme['image']) && str_starts_with($theme['image'], '/') && file_exists(public_path($theme['image']))) {
                    @unlink(public_path($theme['image']));
                }
                $file = $request->file("theme_{$index}_image");
                $filename = 'theme_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                if (!is_dir(public_path('brand/themes'))) {
                    mkdir(public_path('brand/themes'), 0755, true);
                }
                $file->move(public_path('brand/themes'), $filename);
                $theme['image'] = '/brand/themes/' . $filename;
            }
            if ($request->input("theme_{$index}_delete_image")) {
                if (!empty($theme['image']) && str_starts_with($theme['image'], '/') && file_exists(public_path($theme['image']))) {
                    @unlink(public_path($theme['image']));
                }
                $theme['image'] = '';
            }
        }

        $merged = array_merge($currentSettings, [
            'expect_section_title' => $validated['expect_section_title'] ?? $currentSettings['expect_section_title'] ?? null,
            'expect_subtitle' => $validated['expect_subtitle'] ?? $currentSettings['expect_subtitle'] ?? null,
            'expectations' => $validated['expectations'] ?? $currentSettings['expectations'] ?? null,
            'themes' => array_values($themes),
        ]);

        Storage::put('admin/programme-settings.json', json_encode($merged, JSON_PRETTY_PRINT));

        return back()->with('success', 'Programme & Experience updated successfully.');
    }

    private function getProgrammeSettings()
    {
        if (Storage::exists('admin/programme-settings.json')) {
            return json_decode(Storage::get('admin/programme-settings.json'), true);
        }

        return [
            'expect_section_title' => 'WHAT TO EXPECT',
            'expect_subtitle' => 'A Transformative Experience',
            'expectations' => [
                'High-level dialogues, including the participation of the Prime Minister of Bhutan, heads of state and ministers from other countries',
                'Participatory and transformative knowledge exchanges, including through dialogue, storytelling and creative or embodied methods.',
                'Strategic and interactive discussions and space for personal connections to foster collaboration',
                'Experiential practices such as meditation, nature connection and mindful food rituals, engaging hearts, minds, and multiple senses',
                'Immersive site visits to local initiatives and conscious food projects',
                'A journey of personal and collective transformation and inspiration',
            ],
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
}
