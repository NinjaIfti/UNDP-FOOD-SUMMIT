<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function login()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        
        return Inertia::render('Admin/Login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if ($request->password === 'undpwebsite123') {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
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
        $validated = $request->validate([
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
        ]);

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
                    'href' => '/#faq',
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
}
