<script setup>
import { ref, watch, computed, nextTick } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

const form = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    gender: '',
    email: '',
    phone: '',
    country: '',
    country_of_residence: '',
    organization: '',
    organization_type: '',
    organization_type_other: '',
    organization_size: '',
    participation_budget_support: '',
    job_title: '',
    website: '',
    passport_number: '',
    passport_expiry: '',
    arrival_date: '',
    departure_date: '',
    price_tier: '',
    confirm_tier: false,
    subsidized_price_justification: '',
    flexible_support_amount: '',
    field_visit: false,
    accessibility_needed: '',
    accessibility_details: '',
    dietary: '',
    dietary_other: '',
    heard_from: [],
    heard_from_other: '',
    cofsa_member: '',
    cofsa_newsletter: '',
    emergency_name: '',
    emergency_phone: '',
    emergency_email: '',
    emergency_relation: '',
    photo: null,
});

const props = defineProps({
    packages: {
        type: Array,
        default: () => [],
    },
    yourStaySettings: {
        type: Object,
        default: () => ({}),
    },
});

const yourStay = props.yourStaySettings || {};

const selectedPackage = () => props.packages?.find?.((p) => String(p?.id) === String(form.price_tier)) || null;
const packageById = (tierId) => props.packages?.find?.((p) => String(p?.id) === String(tierId)) || null;
const isSubsidizedSelected = () => {
    const pkg = selectedPackage();
    const name = (pkg?.name || '').toString().toLowerCase();
    return name.includes('subsid');
};

const isSponsoredTier = (tierId) => {
    const id = String(tierId ?? '');
    if (id === 'sponsored') return true;
    const pkg = packageById(id);
    const name = (pkg?.name || '').toString().toLowerCase();
    return name.includes('sponsored participant');
};

const isSponsoredSelected = () => isSponsoredTier(form.price_tier);

const formatPackagePrice = (pkg) => {
    const price = Number(pkg?.price ?? 0);
    if (price === 0) {
        return '0.00';
    }
    return price.toLocaleString();
};

const showFlexibleAmountInput = ref(false);

const hardcodedFlexibleId = 'flexible_hardcoded';
const flexibleTierIds = ['flexible', 'flexible_any', hardcodedFlexibleId];
const isFlexibleTier = (tierId) => {
    const id = String(tierId);
    if (flexibleTierIds.includes(id)) return true;
    const pkg = packageById(id);
    const name = (pkg?.name || '').toString().toLowerCase();
    return name.includes('flexible');
};

const isFlexibleAmountInvalid = () => {
    const v = Number(form.flexible_support_amount);
    if (!form.flexible_support_amount || Number.isNaN(v)) return false;
    return v < 970;
};

watch(() => form.price_tier, (newVal) => {
    // Keep the UI aligned with the screenshot: only hide when switching away from flexible tiers.
    if (!isFlexibleTier(newVal)) {
        showFlexibleAmountInput.value = false;
    }
});

const phoneCountryCodes = [
    { code: '+93', label: 'Afghanistan (+93)' },
    { code: '+355', label: 'Albania (+355)' },
    { code: '+213', label: 'Algeria (+213)' },
    { code: '+376', label: 'Andorra (+376)' },
    { code: '+244', label: 'Angola (+244)' },
    { code: '+54', label: 'Argentina (+54)' },
    { code: '+374', label: 'Armenia (+374)' },
    { code: '+61', label: 'Australia (+61)' },
    { code: '+43', label: 'Austria (+43)' },
    { code: '+880', label: 'Bangladesh (+880)' },
    { code: '+32', label: 'Belgium (+32)' },
    { code: '+975', label: 'Bhutan (+975)' },
    { code: '+55', label: 'Brazil (+55)' },
    { code: '+1', label: 'Canada (+1)' },
    { code: '+56', label: 'Chile (+56)' },
    { code: '+86', label: 'China (+86)' },
    { code: '+57', label: 'Colombia (+57)' },
    { code: '+506', label: 'Costa Rica (+506)' },
    { code: '+385', label: 'Croatia (+385)' },
    { code: '+420', label: 'Czech Republic (+420)' },
    { code: '+45', label: 'Denmark (+45)' },
    { code: '+20', label: 'Egypt (+20)' },
    { code: '+251', label: 'Ethiopia (+251)' },
    { code: '+358', label: 'Finland (+358)' },
    { code: '+33', label: 'France (+33)' },
    { code: '+49', label: 'Germany (+49)' },
    { code: '+30', label: 'Greece (+30)' },
    { code: '+852', label: 'Hong Kong (+852)' },
    { code: '+36', label: 'Hungary (+36)' },
    { code: '+354', label: 'Iceland (+354)' },
    { code: '+91', label: 'India (+91)' },
    { code: '+62', label: 'Indonesia (+62)' },
    { code: '+98', label: 'Iran (+98)' },
    { code: '+964', label: 'Iraq (+964)' },
    { code: '+353', label: 'Ireland (+353)' },
    { code: '+972', label: 'Israel (+972)' },
    { code: '+39', label: 'Italy (+39)' },
    { code: '+81', label: 'Japan (+81)' },
    { code: '+962', label: 'Jordan (+962)' },
    { code: '+7', label: 'Kazakhstan / Russia (+7)' },
    { code: '+254', label: 'Kenya (+254)' },
    { code: '+965', label: 'Kuwait (+965)' },
    { code: '+856', label: 'Laos (+856)' },
    { code: '+961', label: 'Lebanon (+961)' },
    { code: '+218', label: 'Libya (+218)' },
    { code: '+370', label: 'Lithuania (+370)' },
    { code: '+352', label: 'Luxembourg (+352)' },
    { code: '+60', label: 'Malaysia (+60)' },
    { code: '+960', label: 'Maldives (+960)' },
    { code: '+356', label: 'Malta (+356)' },
    { code: '+52', label: 'Mexico (+52)' },
    { code: '+377', label: 'Monaco (+377)' },
    { code: '+976', label: 'Mongolia (+976)' },
    { code: '+212', label: 'Morocco (+212)' },
    { code: '+95', label: 'Myanmar (+95)' },
    { code: '+977', label: 'Nepal (+977)' },
    { code: '+31', label: 'Netherlands (+31)' },
    { code: '+64', label: 'New Zealand (+64)' },
    { code: '+234', label: 'Nigeria (+234)' },
    { code: '+47', label: 'Norway (+47)' },
    { code: '+968', label: 'Oman (+968)' },
    { code: '+92', label: 'Pakistan (+92)' },
    { code: '+507', label: 'Panama (+507)' },
    { code: '+595', label: 'Paraguay (+595)' },
    { code: '+51', label: 'Peru (+51)' },
    { code: '+63', label: 'Philippines (+63)' },
    { code: '+48', label: 'Poland (+48)' },
    { code: '+351', label: 'Portugal (+351)' },
    { code: '+974', label: 'Qatar (+974)' },
    { code: '+40', label: 'Romania (+40)' },
    { code: '+966', label: 'Saudi Arabia (+966)' },
    { code: '+65', label: 'Singapore (+65)' },
    { code: '+421', label: 'Slovakia (+421)' },
    { code: '+27', label: 'South Africa (+27)' },
    { code: '+82', label: 'South Korea (+82)' },
    { code: '+34', label: 'Spain (+34)' },
    { code: '+94', label: 'Sri Lanka (+94)' },
    { code: '+46', label: 'Sweden (+46)' },
    { code: '+41', label: 'Switzerland (+41)' },
    { code: '+886', label: 'Taiwan (+886)' },
    { code: '+255', label: 'Tanzania (+255)' },
    { code: '+66', label: 'Thailand (+66)' },
    { code: '+228', label: 'Togo (+228)' },
    { code: '+216', label: 'Tunisia (+216)' },
    { code: '+90', label: 'Turkey (+90)' },
    { code: '+256', label: 'Uganda (+256)' },
    { code: '+380', label: 'Ukraine (+380)' },
    { code: '+971', label: 'United Arab Emirates (+971)' },
    { code: '+44', label: 'United Kingdom (+44)' },
    { code: '+598', label: 'Uruguay (+598)' },
    { code: '+1', label: 'USA (+1)' },
    { code: '+998', label: 'Uzbekistan (+998)' },
    { code: '+58', label: 'Venezuela (+58)' },
    { code: '+84', label: 'Vietnam (+84)' },
    { code: '+967', label: 'Yemen (+967)' },
    { code: '+260', label: 'Zambia (+260)' },
    { code: '+263', label: 'Zimbabwe (+263)' },
];
const phoneCountryCode = ref('+975');
const phoneLocal = ref('');
const phoneClientError = ref('');
const submitErrorSummary = ref('');

function sanitizePhoneLocal(value) {
    // Keep phone local part digits only (no letters, symbols).
    return String(value ?? '').replace(/\D/g, '');
}

function buildFullPhone() {
    const code = (phoneCountryCode.value || '').trim();
    const local = (phoneLocal.value || '').trim().replace(/\s+/g, ' ');
    if (!code && !local) return '';
    if (!local) return code;
    if (!code) return local;
    return `${code} ${local}`.trim();
}

function stripDigits(value) {
    return String(value || '').replace(/\d+/g, '');
}
const page = usePage();
const showSuccessModal = ref(!!page.props.flash?.success);
const registerHeroImage = computed(() => page.props?.site_settings?.register_hero_image || '');
const registerHeroStyle = computed(() => {
    const overlays = [
        'radial-gradient(1200px 600px at 20% 20%, rgba(245, 156, 14, 0.22), transparent 60%)',
        'radial-gradient(900px 500px at 80% 35%, rgba(114, 148, 48, 0.18), transparent 60%)',
        'radial-gradient(900px 600px at 50% 90%, rgba(13, 56, 90, 0.12), transparent 65%)',
        'linear-gradient(to bottom, rgba(13, 56, 90, 0.06), rgba(245, 156, 14, 0.06))',
    ];

    if (registerHeroImage.value) {
        return {
            backgroundImage: `${overlays.join(', ')}, url('${registerHeroImage.value}')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
        };
    }

    return {
        backgroundImage: overlays.join(', '),
    };
});

watch(() => page.props.flash?.success, (val) => {
    if (val) showSuccessModal.value = true;
});

function closeSuccessModal() {
    showSuccessModal.value = false;
}

const countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Argentina', 'Armenia', 'Australia',
    'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium',
    'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
    'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic',
    'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus',
    'Czech Republic', 'Denmark', 'Djibouti', 'Dominican Republic', 'DR Congo', 'Ecuador', 'Egypt',
    'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland',
    'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Guatemala', 'Guinea',
    'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran',
    'Iraq', 'Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan',
    'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia',
    'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives',
    'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova',
    'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal',
    'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia',
    'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru',
    'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
    'Saint Lucia', 'Samoa', 'San Marino', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
    'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa',
    'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland',
    'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga',
    'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine',
    'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu',
    'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe',
];

function submit() {
    submitErrorSummary.value = '';
    phoneClientError.value = '';
    form.phone = buildFullPhone();
    if (!form.phone.trim()) {
        phoneClientError.value = 'Please choose a country code and enter your phone number.';
        return;
    }
    if (form.field_visit && !String(form.price_tier || '').trim()) {
        submitErrorSummary.value = 'Please select a ticket option. The optional immersive experience add-on must be selected together with a ticket.';
        nextTick(() => {
            const ticketSection = document.querySelector('[data-ticket-options]');
            if (ticketSection && typeof ticketSection.scrollIntoView === 'function') {
                ticketSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
        return;
    }
    if (isFlexibleTier(form.price_tier) && isFlexibleAmountInvalid()) {
        return;
    }
    form.post('/register-summit', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showSuccessModal.value = true;
            form.reset();
            phoneCountryCode.value = '+975';
            phoneLocal.value = '';
            phoneClientError.value = '';
            submitErrorSummary.value = '';
        },
        onError: (errors) => {
            const labels = {
                first_name: 'First Name',
                middle_name: 'Middle Name',
                last_name: 'Last Name',
                gender: 'Gender',
                email: 'Email Address',
                phone: 'Phone Number',
                country: 'Nationality',
                country_of_residence: 'Country of residence',
                job_title: 'Job Title / Role',
                arrival_date: 'Preferred Arrival Date',
                departure_date: 'Preferred Departure Date',
                price_tier: 'Ticket Type',
                confirm_tier: 'Ticket Confirmation',
                accessibility_needed: 'Accessibility Support',
                dietary: 'Dietary Preference',
                cofsa_member: 'CoFSA Membership',
                cofsa_newsletter: 'Newsletter Preference',
                emergency_name: 'Emergency Contact Name',
                emergency_phone: 'Emergency Contact Phone',
                emergency_email: 'Emergency Contact Email',
                emergency_relation: 'Emergency Contact Relation',
            };
            const missing = Object.keys(errors || {})
                .filter((key) => labels[key])
                .map((key) => labels[key]);
            if (missing.length) {
                submitErrorSummary.value = `Please complete required fields: ${missing.join(', ')}`;
            } else if (errors?.form) {
                submitErrorSummary.value = errors.form;
            } else {
                submitErrorSummary.value = 'Please review the form and fix the highlighted fields.';
            }
            nextTick(() => {
                const firstErrorElement = document.querySelector('.text-red-400, .border-red-400');
                if (firstErrorElement && typeof firstErrorElement.scrollIntoView === 'function') {
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        },
    });
}

const steps = [
    {
        number: 1,
        title: 'Submit Your Registration Application',
        desc: 'Complete the registration form with your personal details, selected participation package, and any optional preferences.',
    },
    {
        number: 2,
        title: 'Review and Confirmation by Organizers',
        desc: 'All applications will be reviewed by the organizing team. Submission of the form does not automatically confirm participation.',
    },
    {
        number: 3,
        title: 'Approval Notification and Payment Instructions',
        desc: 'If your application is approved, you will receive an email confirming your participation and providing instructions to proceed with payment.',
    },
    {
        number: 4,
        title: 'Visa Coordination After Payment',
        desc: 'Once payment is completed, the organizers will initiate the visa process and prepare the required documentation for travel to Bhutan. Participants are therefore encouraged to begin checking flight schedules, as travel details will be required to process the visa. If all documents are submitted on time, the visa process takes around 2 weeks.',
    },
    {
        number: 5,
        title: 'Visa Approval and Travel Planning',
        desc: 'When your visa is approved, you will be notified immediately. At this stage, you may proceed with booking your flights, accommodation, and final travel arrangements.',
    },
];
</script>

<template>
    <Head title="Register — Global Conscious Food Systems Summit" />
    <EventLayout>
        <div class="register-page">
        <!-- Success Modal -->
        <div
            v-if="showSuccessModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            @click.self="closeSuccessModal"
        >
            <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary-2/20">
                        <svg class="h-8 w-8 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="font-title text-xl font-bold text-primary-1">Registration Submitted</h3>
                    <p class="mt-3 font-body text-base text-copy">
                        Thank you for registering with the GCFSS, our team will get back to you soon!
                    </p>
                    <strong>PLEASE CHECK YOUR SPAM FOLDER FOR THE CONFIRMATION EMAIL</strong>

                    <button
                        type="button"
                        @click="closeSuccessModal"
                        class="mt-6 inline-flex rounded-lg bg-primary-2 px-6 py-3 font-body text-base font-semibold text-white transition hover:bg-primary-2/90"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Hero Banner -->
        <section class="relative bg-[#F4F1EA] py-20 sm:py-28">
            <div
                class="absolute inset-0"
                :style="registerHeroStyle"
            />
            <div class="relative mx-auto w-full max-w-[70rem] px-4 text-center sm:px-6 lg:px-8">
                <h1 class="font-title text-3xl font-bold text-white sm:text-4xl lg:text-5xl">Join the Global Conscious Food Systems Summit 2026!</h1>
                <p class="mx-auto mt-4 max-w-2xl text-center font-body text-lg font-medium text-accent-2 sm:text-xl">31st August – 4 September 2026 · Paro, Bhutan</p>
            </div>
        </section>

        <!-- Intro -->
        <section class="bg-[#F4F1EA] py-16 sm:py-20">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <p class="font-body text-xl leading-relaxed text-ink">
                    We are delighted to hear about your interest in joining the first-ever Conscious Food Systems Summit, to be held from 31st August to 4 September, in Paro, Bhutan.
                </p>

                <div class="mt-6 rounded-xl border border-primary-1/10 bg-white px-6 py-5 shadow-sm">
                    <h2 class="text-center font-title text-3xl font-bold text-primary-1 sm:text-4xl">Good to know before registering</h2>

                    <div class="mt-5 space-y-6">
                        <div>
                            <p class="font-title text-lg font-bold text-primary-1">1. Participation requires a Summit entrance ticket</p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                Participation in the Summit requires the purchase of an entrance ticket. Ticket revenues are used exclusively to cover the essential costs of delivering the event, including the venue, meals during the Summit, local logistics, and coordination.. They do not cover any human resources costs related to the preparation and execution of the Summit.
                            </p>
                        </div>

                        <div>
                            <p class="font-title text-lg font-bold text-primary-1">2. Limited space — Early Registration Highly Recommended</p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                The Summit can accommodate a limited number of participants. We therefore encourage interested guests to submit their registration applications as early as possible, as applications will be reviewed on a rolling basis and early submissions may increase the likelihood of securing a place at the Summit.
                            </p>
                        </div>

                        <div>
                            <p class="font-title text-lg font-bold text-primary-1">3. Applications will be approved by the organizers</p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                Submitting a registration form does not automatically confirm participation. All applications will be reviewed by the organizing team to ensure alignment with the Summit’s objectives and to maintain a diverse and balanced group of participants.
                            </p>

                            <p class="mt-4 font-body text-lg font-semibold text-primary-1">Selection will consider factors such as:</p>
                            <ul class="mt-2 space-y-2 font-body text-lg text-copy">
                                <li class="flex items-start gap-2"><span class="mt-1 text-accent-2">•</span><span>Relevance to the core themes of the Summit</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1 text-accent-2">•</span><span>Geographical diversity</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1 text-accent-2">•</span><span>Representation from different sectors (policy, research, private sector, civil society, philanthropy, Indigenous and community leadership)</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1 text-accent-2">•</span><span>Gender balance and inclusive representation</span></li>
                                <li class="flex items-start gap-2"><span class="mt-1 text-accent-2">•</span><span>Potential to contribute to meaningful cross-sector dialogue and collaboration</span></li>
                            </ul>

                            <p class="mt-4 font-body text-lg leading-relaxed text-copy">
                                Our intention is to make this inaugural summit as inclusive as possible, while recognizing the practical constraints of organizing an international gathering in the current context.
                            </p>
                        </div>

                        <div>
                            <p class="font-title text-lg font-bold text-primary-1">4. Participation Costs</p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                All participants — including speakers and session contributors — are expected to cover the full costs of participation, including the Summit ticket, travel, accommodation, and related expenses.
                            </p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                More details can be found in Section: Participation Costs
                            </p>
                        </div>

                        <div>
                            <p class="font-title text-lg font-bold text-primary-1">5. Important Travel Advisory</p>
                            <p class="mt-2 font-body text-lg leading-relaxed text-copy">
                                Entry to Bhutan requires a visa coordinated with the organizers. We strongly recommend that participants do not book flights or accommodation until their visa entry permission has been officially approved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Steps -->
        <section class="border-y border-primary-1/10 bg-[#F4F1EA] py-16 sm:py-20">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <h2 class="font-title text-center text-3xl font-bold text-primary-1 sm:text-4xl">
                    Step-by-step registration <span style="font-variant: all-small-caps;">Process</span>
                </h2>
                <div class="mt-12 space-y-6">
                    <div v-for="step in steps" :key="step.number" class="flex gap-5">
                        <div class="step-number-circle flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-accent-2 font-bold text-primary-1 sm:h-14 sm:w-14">
                            {{ step.number }}
                        </div>
                        <div class="flex-1 pt-0.5 sm:pt-1">
                            <h3 class="step-row-heading font-bold text-primary-1">
                                Step {{ step.number }} — {{ step.title }}
                            </h3>
                            <p class="mt-1 font-body text-base leading-relaxed text-copy">{{ step.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Detailed Ticket Options Section -->
        <section class="bg-[#F4F1EA] pt-16 pb-5 sm:pt-20 sm:pb-6">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <h2 class="font-title text-center text-3xl font-bold text-primary-1 sm:text-4xl">Ticket options</h2>
                <p class="mt-4 font-body text-lg leading-relaxed text-copy">
                    To support an inclusive and diverse gathering, the Summit offers three participation tiers. These tiers are designed to make participation accessible to a wide range of actors working across food
                    systems while ensuring the financial sustainability of the Summit.
                </p>
                <p class="mt-3 font-body text-lg leading-relaxed text-copy">
                    Participants are invited to select the tier that best reflects the nature of their organization and available resources.
                </p>

                <div class="mt-8 space-y-8">
                    <!-- Standard Price -->
                    <div class="rounded-xl border border-primary-2/15 bg-white px-6 py-5 shadow-sm">
                        <h3 class="font-title text-xl font-bold text-primary-1">Standard Price</h3>
                        <p class="mt-1 font-body text-lg font-semibold text-accent-2">USD 970</p>
                        <p class="mt-3 font-body text-base leading-relaxed text-copy">
                            The Standard Price reflects the actual cost of participation.
                        </p>
                    </div>

                    <!-- Subsidised Price -->
                    <div class="rounded-xl border border-primary-2/15 bg-white px-6 py-5 shadow-sm">
                        <h3 class="font-title text-xl font-bold text-primary-1">Subsidised Price</h3>
                        <p class="mt-1 font-body text-lg font-semibold text-accent-2">USD 580</p>
                        <p class="mt-3 font-body text-base leading-relaxed text-copy">
                            The Subsidised Price is designed to support participation from individuals and organizations working in contexts where financial resources for international events may be limited but whose
                            participation would be critical.
                        </p>
                        <p class="mt-4 font-body text-base font-semibold text-primary-1">Typical eligible participants</p>
                        <p class="mt-2 font-body text-base leading-relaxed text-copy">
                            Participants selecting the Subsidised Price should be based in low- and middle-income countries, and affiliated to the following type of organizations:
                        </p>
                        <ul class="mt-3 list-disc list-inside space-y-1.5 font-body text-base text-copy">
                            <li>Non-governmental organizations (NGOs) and non-profit organizations (including national NGOs)</li>
                            <li>Social enterprises and social businesses</li>
                            <li>Grassroots organizations and community initiatives</li>
                            <li>Farmer organizations and cooperatives</li>
                            <li>Small start-ups and early-stage ventures working on food systems innovation</li>
                            <li>Independent researchers or practitioners without institutional funding</li>
                            <li>Eligibility does not guarantee access to the Subsidised Price, which will be subject to resource availability.</li>
                        </ul>
                    </div>

                    <!-- Supporter Price -->
                    <div class="rounded-xl border border-primary-2/15 bg-white px-6 py-5 shadow-sm">
                        <h3 class="font-title text-xl font-bold text-primary-1">Supporter Price</h3>
                        <p class="mt-1 font-body text-lg font-semibold text-accent-2">USD 1,550</p>
                        <p class="mt-3 font-body text-base leading-relaxed text-copy">
                            Participants who are able to do so are warmly encouraged to select the Supporter Price. This option helps sustain the Summit and supports a more inclusive gathering by enabling lower
                            participation fees for community organizations, social enterprises, and emerging food system innovators. Your contribution helps ensure that diverse voices can be present in the conversation.
                        </p>
                        <p class="mt-4 font-body text-base font-semibold text-primary-1">
                            We particularly encourage the following types of organizations to consider the Supporter Price:
                        </p>
                        <ul class="mt-3 list-disc list-inside space-y-1.5 font-body text-base text-copy">
                            <li>Philanthropic foundations</li>
                            <li>High-net-worth individuals</li>
                            <li>Private sector companies, consultancies and advisory firms</li>
                            <li>Investors and investment companies</li>
                            <li>International organizations</li>
                            <li>Participants attending with institutional budgets for international events</li>
                        </ul>
                    </div>

                    <!-- Flexible Supporter Price -->
                    <div class="rounded-xl border border-primary-2/15 bg-white px-6 py-5 shadow-sm">
                        <h3 class="font-title text-xl font-bold text-primary-1">Flexible Supporter Price — Above USD 970</h3>
                        <p class="mt-3 font-body text-base leading-relaxed text-copy">
                            For participants who are willing and able to contribute beyond the Standard Price, this option allows you to choose an amount above USD 970 based on your capacity and commitment to supporting an inclusive Summit
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration Form -->
        <section id="form" class="bg-[#F4F1EA] pt-2 pb-16 sm:pt-3 sm:pb-20">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <div class="mb-9 rounded-2xl border-2 border-accent-2/40 bg-gradient-to-r from-accent-2/15 via-white to-primary-2/10 px-6 py-6 shadow-md sm:mb-10">
                    <div class="mx-auto mb-3 inline-flex items-center rounded-full bg-accent-2 px-3 py-1">
                        <span class="font-body text-sm font-bold uppercase tracking-wide text-primary-1">Special Note</span>
                    </div>
                    <h3 class="text-center font-title text-3xl font-bold text-primary-1 sm:text-4xl">Applying from Bhutan</h3>
                    <p class="mt-3 text-center font-body text-lg leading-relaxed text-copy">
                        Participation for Bhutan-based applicants is coordinated through a separate Expression of Interest process. We kindly invite you to complete the Bhutan-specific form
                        <a
                            href="https://forms.office.com/Pages/ResponsePage.aspx?id=Xtvls0QpN0iZ9XSIrOVDGafTbyXTmiZBtTV-dGiKculUQzdIWlBZQTlNQ0VEWDk0Wkg3U0tQQTBOQi4u"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="ml-1 inline-flex items-center gap-1 font-semibold text-primary-2 underline hover:no-underline"
                        >
                            here
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-1.414 1.414a4 4 0 01-5.656 0l-1.414-1.414a4 4 0 010-5.656m7.07-7.07a4 4 0 015.656 0l1.414 1.414a4 4 0 010 5.656l-1.414 1.414a4 4 0 01-5.656 0m-1.414-1.414a4 4 0 010-5.656l1.414-1.414" />
                            </svg>
                        </a>
                        .
                    </p>
                </div>
		<br><br><br>

                <h2 class="text-center font-title text-3xl font-bold text-primary-1 sm:text-4xl">Applying from outside Bhutan - Register here</h2>
                <p class="mt-2 text-center font-body text-lg text-copy sm:text-xl">
                    <strong class="font-bold text-copy">If you are an international participant, please fill out the form below to begin your registration process</strong>
                </p>

                <form @submit.prevent="submit" class="mt-10 space-y-6 text-lg">
                    <!-- Section 1: Personal Information -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 1 — Personal Information</legend>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="mb-1 block text-base font-medium text-primary-1">First Name (as in Passport/ID) *</label>
                                <input
                                    id="first_name"
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    @input="form.first_name = stripDigits($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="First name as in passport"
                                />
                                <p v-if="form.errors.first_name" class="mt-1 text-base text-red-400">{{ form.errors.first_name }}</p>
                            </div>
                            <div>
                                <label for="last_name" class="mb-1 block text-base font-medium text-primary-1">Last Name (as in Passport/ID) *</label>
                                <input
                                    id="last_name"
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    @input="form.last_name = stripDigits($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Last name as in passport"
                                />
                                <p v-if="form.errors.last_name" class="mt-1 text-base text-red-400">{{ form.errors.last_name }}</p>
                            </div>
                            <div>
                                <label for="middle_name" class="mb-1 block text-base font-medium text-primary-1">Middle Name (as in Passport/ID)</label>
                                <input
                                    id="middle_name"
                                    v-model="form.middle_name"
                                    type="text"
                                    @input="form.middle_name = stripDigits($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Middle name as in passport (optional)"
                                />
                                <p v-if="form.errors.middle_name" class="mt-1 text-base text-red-400">{{ form.errors.middle_name }}</p>
                            </div>
                            <div>
                                <label for="reg-email" class="mb-1 block text-base font-medium text-primary-1">Email Address *</label>
                                <input
                                    id="reg-email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="you@example.com"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-base text-red-400">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label for="gender" class="mb-1 block text-base font-medium text-primary-1">Gender *</label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select gender</option>
                                    <option value="female" class="text-gray-800">Female</option>
                                    <option value="male" class="text-gray-800">Male</option>
                                    <option value="non_binary" class="text-gray-800">Non-binary</option>
                                    <option value="prefer_not_to_say" class="text-gray-800">Prefer not to say</option>
                                </select>
                                <p v-if="form.errors.gender" class="mt-1 text-base text-red-400">{{ form.errors.gender }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-base font-medium text-primary-1">Phone Number *</label>
                                <p class="mb-2 font-body text-base text-copy">Choose your country code, then enter your number (no need to type the code again).</p>
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-stretch">
                                    <select
                                        v-model="phoneCountryCode"
                                        class="w-full shrink-0 rounded-lg border border-primary-2/20 bg-white px-3 py-3 text-ink sm:w-52 focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    >
                                        <option v-for="item in phoneCountryCodes" :key="item.code" :value="item.code" class="text-gray-800">
                                            {{ item.label }}
                                        </option>
                                    </select>
                                    <input
                                        id="phone"
                                        v-model="phoneLocal"
                                        type="tel"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        autocomplete="tel-national"
                                        class="min-w-0 flex-1 rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                        placeholder="e.g. 234 567 8900"
                                        @input="phoneLocal = sanitizePhoneLocal($event.target.value)"
                                    />
                                </div>
                                <p v-if="phoneClientError || form.errors.phone" class="mt-1 text-base text-red-400">
                                    {{ phoneClientError || form.errors.phone }}
                                </p>
                            </div>
                            <div>
                                <label for="country" class="mb-1 block text-base font-medium text-primary-1">Nationality *</label>
                                <select
                                    id="country"
                                    v-model="form.country"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select nationality</option>
                                    <option v-for="c in countries" :key="c" :value="c" class="text-gray-800">{{ c }}</option>
                                </select>
                                <p v-if="form.errors.country" class="mt-1 text-base text-red-400">{{ form.errors.country }}</p>
                            </div>
                            <div>
                                <label for="country_of_residence" class="mb-1 block text-base font-medium text-primary-1">Country of residence *</label>
                                <select
                                    id="country_of_residence"
                                    v-model="form.country_of_residence"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select country of residence</option>
                                    <option v-for="c in countries" :key="'res-' + c" :value="c" class="text-gray-800">{{ c }}</option>
                                </select>
                                <p v-if="form.errors.country_of_residence" class="mt-1 text-base text-red-400">{{ form.errors.country_of_residence }}</p>
                            </div>
                            <div>
                                <label for="organization" class="mb-1 block text-base font-medium text-primary-1">Organization / Affiliation</label>
                                <p class="mb-2 font-body text-base text-copy">Please provide the full legal name of your organization. If you are not affiliated with an organization, please write "Independent".</p>
                                <input
                                    id="organization"
                                    v-model="form.organization"
                                    type="text"
                                    @input="form.organization = stripDigits($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder='e.g. Independent'
                                />
                                <p v-if="form.errors.organization" class="mt-1 text-base text-red-400">{{ form.errors.organization }}</p>
                            </div>
                            <div>
                                <label for="organization_type" class="mb-1 block text-base font-medium text-primary-1">Organization type</label>
                                <p class="mb-2 font-body text-base text-copy">Please select the type of organization you are affiliated with.</p>
                                <select
                                    id="organization_type"
                                    v-model="form.organization_type"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select an option</option>
                                    <option value="Government institution or public agency" class="text-gray-800">Government institution or public agency</option>
                                    <option value="University or research institution" class="text-gray-800">University or research institution</option>
                                    <option value="Non-governmental organization (NGO) or non-profit organization" class="text-gray-800">Non-governmental organization (NGO) or non-profit organization</option>
                                    <option value="International organization or development agency" class="text-gray-800">International organization or development agency</option>
                                    <option value="Philanthropic foundation" class="text-gray-800">Philanthropic foundation</option>
                                    <option value="Private sector company or corporate organization" class="text-gray-800">Private sector company or corporate organization</option>
                                    <option value="Social enterprise or social business" class="text-gray-800">Social enterprise or social business</option>
                                    <option value="Start-up or early-stage venture" class="text-gray-800">Start-up or early-stage venture</option>
                                    <option value="Grassroots organization or community initiative" class="text-gray-800">Grassroots organization or community initiative</option>
                                    <option value="Farmer organization or cooperative" class="text-gray-800">Farmer organization or cooperative</option>
                                    <option value="Consultancy or advisory firm" class="text-gray-800">Consultancy or advisory firm</option>
                                    <option value="Impact investor or investment organization" class="text-gray-800">Impact investor or investment organization</option>
                                    <option value="Independent researcher or practitioner" class="text-gray-800">Independent researcher or practitioner</option>
                                    <option value="Other" class="text-gray-800">Other (please specify)</option>
                                </select>
                                <p v-if="form.errors.organization_type" class="mt-1 text-base text-red-400">{{ form.errors.organization_type }}</p>
                            </div>
                            <div v-if="form.organization_type === 'Other'">
                                <label for="organization_type_other" class="mb-1 block text-base font-medium text-primary-1">Other (please specify)</label>
                                <input
                                    id="organization_type_other"
                                    v-model="form.organization_type_other"
                                    type="text"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Please specify"
                                />
                                <p v-if="form.errors.organization_type_other" class="mt-1 text-base text-red-400">{{ form.errors.organization_type_other }}</p>
                            </div>
                            <div>
                                <label for="organization_size" class="mb-1 block text-base font-medium text-primary-1">Organization Size</label>
                                <p class="mb-2 font-body text-base text-copy">Please indicate the approximate size of your organization.</p>
                                <select
                                    id="organization_size"
                                    v-model="form.organization_size"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select size</option>
                                    <option value="1–5 people" class="text-gray-800">1–5 people</option>
                                    <option value="6–20 people" class="text-gray-800">6–20 people</option>
                                    <option value="21–100 people" class="text-gray-800">21–100 people</option>
                                    <option value="100+ people" class="text-gray-800">100+ people</option>
                                </select>
                                <p v-if="form.errors.organization_size" class="mt-1 text-base text-red-400">{{ form.errors.organization_size }}</p>
                            </div>
                            <div>
                                <label for="job_title" class="mb-1 block text-base font-medium text-primary-1">Job Title / Role *</label>
                                <input
                                    id="job_title"
                                    v-model="form.job_title"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Your role or title"
                                />
                                <p v-if="form.errors.job_title" class="mt-1 text-base text-red-400">{{ form.errors.job_title }}</p>
                            </div>
                            <div>
                                <label for="website" class="mb-1 block text-base font-medium text-primary-1">Website/ Link</label>
                                <p class="mb-2 font-body text-base text-copy">Please provide your organization’s website or relevant links. If unaffiliated, you may include a personal link</p>
                                <input
                                    id="website"
                                    v-model="form.website"
                                    type="url"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="https://yourwebsite.com"
                                />
                                <p v-if="form.errors.website" class="mt-1 text-base text-red-400">{{ form.errors.website }}</p>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 2: Visa Information -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 2 — Visa Information</legend>
                        <div class="mt-3 rounded-xl border border-primary-2/15 bg-white px-5 py-4 text-base">
                            <p class="font-body text-base leading-relaxed text-copy">
                                Visa arrangements for this event will be fully coordinated by the organizing team. Upon approval of your registration, the organizers will guide you through the visa process and provide the necessary official documentation required for your application.
                            </p>
                            <p class="mt-2 font-body text-base leading-relaxed text-copy">
                                Please ensure your passport is valid for at least 6 months from your travel date.
                            </p>
                        </div>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="passport_number" class="mb-1 block text-base font-medium text-primary-1">Passport Number *</label>
                                <input
                                    id="passport_number"
                                    v-model="form.passport_number"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="e.g. AB1234567"
                                />
                                <p v-if="form.errors.passport_number" class="mt-1 text-base text-red-400">{{ form.errors.passport_number }}</p>
                            </div>
                            <div>
                                <label for="passport_expiry" class="mb-1 block text-base font-medium text-primary-1">Passport Expiration Date *</label>
                                <input
                                    id="passport_expiry"
                                    v-model="form.passport_expiry"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p class="mt-1 text-base text-copy">Must be valid for at least 6 months from travel date</p>
                                <p v-if="form.errors.passport_expiry" class="mt-1 text-base text-red-400">{{ form.errors.passport_expiry }}</p>
                            </div>
                            <div>
                                <label for="arrival_date" class="mb-1 block text-base font-medium text-primary-1">Preferred Arrival Date *</label>
                                <input
                                    id="arrival_date"
                                    v-model="form.arrival_date"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p v-if="form.errors.arrival_date" class="mt-1 text-base text-red-400">{{ form.errors.arrival_date }}</p>
                            </div>
                            <div>
                                <label for="departure_date" class="mb-1 block text-base font-medium text-primary-1">Preferred Departure Date *</label>
                                <input
                                    id="departure_date"
                                    v-model="form.departure_date"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p v-if="form.errors.departure_date" class="mt-1 text-base text-red-400">{{ form.errors.departure_date }}</p>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 3: Select Your Price -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 3 — Select Your Price</legend>

                        <div class="mt-4 space-y-6">
                            <div class="mt-2 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                                <p class="mb-2 font-body text-base font-semibold text-primary-1">Will your participation be supported by an organizational or project budget?</p>
                                <div class="flex flex-wrap gap-3">
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.participation_budget_support === 'Yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                        <input type="radio" v-model="form.participation_budget_support" value="Yes" class="h-4 w-4 accent-[#F59C0E]" />
                                        <span class="text-base text-primary-1">Yes</span>
                                    </label>
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.participation_budget_support === 'No' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                        <input type="radio" v-model="form.participation_budget_support" value="No" class="h-4 w-4 accent-[#F59C0E]" />
                                        <span class="text-base text-primary-1">No</span>
                                    </label>
                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.participation_budget_support === 'Partially' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                        <input type="radio" v-model="form.participation_budget_support" value="Partially" class="h-4 w-4 accent-[#F59C0E]" />
                                        <span class="text-base text-primary-1">Partially</span>
                                    </label>
                                </div>
                                <p v-if="form.errors.participation_budget_support" class="mt-2 text-base text-red-400">{{ form.errors.participation_budget_support }}</p>
                            </div>

                            <div class="bg-white rounded-none shadow-xl overflow-hidden border border-primary-2/15">
                                <div class="bg-gradient-to-r from-primary-2 to-primary-2/80 p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-white/20 p-2 rounded-none">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <h3 class="font-title text-xl font-bold text-white">What’s included in the Price</h3>
                                    </div>
                                </div>
                                <div class="p-6 space-y-3 font-body text-base text-copy">
                                    <p class="font-body text-base font-semibold text-primary-1">The Summit ticket price INCLUDES:</p>
                                    <ul class="mt-2 space-y-1.5 text-base text-copy">
                                        <li class="flex gap-2"><span class="mt-1 text-primary-2">•</span>Entrance and participation in the event from 1-4 September</li>
                                        <li class="flex gap-2"><span class="mt-1 text-primary-2">•</span><span>Daily meals provided at the venue, including:</span></li>
                                        <li class="ml-6 flex gap-2"><span class="mt-1 text-primary-2">•</span>Daily lunch for summit participants</li>
                                        <li class="ml-6 flex gap-2"><span class="mt-1 text-primary-2">•</span>Two tea/coffee breaks per day</li>
                                        <li class="ml-6 flex gap-2"><span class="mt-1 text-primary-2">•</span>Two dinners</li>
                                        <li class="flex gap-2"><span class="mt-1 text-primary-2">•</span><span>Visa application: Visa application fee of BTN 5,000 (approx. USD 55), as well as full coordination of the visa entry permission process. Please note that participants who choose to arrange their visa independently will be required to pay the standard visa fee of USD 65 per person. Indian nationals are exempt from visa fees.</span></li>
                                        <li class="flex gap-2"><span class="mt-1 text-primary-2">•</span><span><strong class="text-primary-1">Sustainable Development Fee (SDF):</strong> As part of their contribution to the Summit, the Royal Government of Bhutan is granting all Summit participants a waiver of the Sustainable Development Fee (SDF), normally USD 100 per adult per night, for the Summit period and associated days.</span></li>
                                        <li class="flex gap-2"><span class="mt-1 text-primary-2">•</span>Local transportation between the list of selected  hotels in Paro and the event venue from 1-4 September.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white rounded-none shadow-xl overflow-hidden border border-primary-2/15">
                                <div class="bg-gradient-to-r from-gray-600 to-gray-800 p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-white/20 p-2 rounded-none">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <h3 class="font-title text-xl font-bold text-white">What’s not included in the Price</h3>
                                    </div>
                                </div>
                                <div class="p-6 space-y-3 font-body text-base text-copy">
                                    <p class="font-body text-base font-semibold text-primary-1">Please note that the Summit ticket price DOES NOT INCLUDE:</p>
                                    <ul class="mt-2 space-y-1.5 text-base text-copy">
                                        <li class="flex gap-2"><span class="mt-1 text-accent-2">•</span>International travel to and from Bhutan (Paro Airport).</li>
                                        <li class="flex gap-2"><span class="mt-1 text-accent-2">•</span>Accommodation costs in Paro</li>
                                        <li class="flex gap-2"><span class="mt-1 text-accent-2">•</span>Airport transfers (transportation between Paro Airport and hotels in Paro)</li>
                                        <li class="flex gap-2"><span class="mt-1 text-accent-2">•</span>Two dinners outside the official Summit programme</li>
                                        <li class="flex gap-2"><span class="mt-1 text-accent-2">•</span>Any personal expenses or additional costs incurred during your stay in Bhutan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Price Tier Selection -->
                        <div data-ticket-options>
                        <p class="mb-2 mt-6 font-title text-xl font-bold text-primary-1">Choose your Ticket option</p>
                        <p class="font-body text-base leading-relaxed text-copy">
                            Participants are invited to select the tier that best reflects their organization's resources and available budgets. Participants whose organizations have the means are warmly encouraged
                            to select the Supporter Price to help widen participation.
                        </p>
                        <p
                            v-if="isSponsoredSelected()"
                            class="mt-3 rounded-xl border border-primary-2/20 bg-primary-2/5 px-5 py-4 font-body text-base text-copy"
                        >
                            You selected <strong>Sponsored Participant</strong>. This option is for UNDP-sponsored registrations only and requires organizer approval.
                            If approved, you will not receive payment login details — your participation is recorded in our system only.
                        </p>
                        <div class="space-y-4">
                            <p v-if="!packages.length" class="rounded-xl border border-primary-2/20 bg-white px-5 py-4 text-base text-copy">No packages are currently available. Please contact the organizers at cofsa@undp.org.</p>
                            <template v-for="pkg in packages" :key="pkg.id">
                                <label
                                    class="block cursor-pointer w-full rounded-xl px-6 py-5 shadow-sm transition hover:border-primary-1/20"
                                    :class="
                                        String(form.price_tier) === String(pkg.id)
                                            ? 'border-2 border-accent-2 bg-accent-2/15'
                                            : 'border border-primary-1/10 bg-white'
                                    "
                                >
                                    <div class="flex items-start gap-5">
                                        <div class="pt-1">
                                            <input
                                                type="radio"
                                                v-model="form.price_tier"
                                                :value="pkg.id"
                                                class="sr-only"
                                            />
                                            <div
                                                class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-[#D21F1F]"
                                                :class="String(form.price_tier) === String(pkg.id) ? 'bg-[#D21F1F]' : 'bg-transparent'"
                                            >
                                                <div class="h-2.5 w-2.5 rounded-full bg-white"></div>
                                            </div>
                                        </div>

                                        <div class="flex-1 pt-0.5">
                                            <div class="flex items-start justify-between gap-4">
                                                <h3 class="font-title text-base font-bold text-primary-1">
                                                    {{ pkg.name }}
                                                </h3>

                                                <p
                                                    v-if="!isFlexibleTier(pkg.id)"
                                                    class="font-body text-base font-semibold text-accent-2"
                                                >
                                                    USD {{ formatPackagePrice(pkg) }}
                                                </p>

                                                <button
                                                    v-if="isFlexibleTier(pkg.id) && !(String(form.price_tier) === String(pkg.id) && showFlexibleAmountInput)"
                                                    type="button"
                                                    class="inline-flex items-center justify-center rounded-full border border-black/50 bg-white px-4 py-2 font-body text-sm font-semibold text-accent-2 transition hover:bg-accent-2/10"
                                                    @click.stop="showFlexibleAmountInput = true; form.price_tier = pkg.id"
                                                >
                                                    Enter Amount
                                                </button>
                                            </div>

                                            <div
                                                class="mt-2 font-body text-base leading-relaxed text-copy [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mb-1 [&_p]:mb-2 [&_p]:last:mb-0 [&_strong]:font-bold [&_b]:font-bold [&_em]:italic [&_i]:italic [&_u]:underline"
                                                v-html="pkg.description"
                                            />

                                            <div
                                                v-if="
                                                    showFlexibleAmountInput
                                                    && String(form.price_tier) === String(pkg.id)
                                                        && isFlexibleTier(pkg.id)
                                                "
                                                class="mt-4"
                                            >
                                                <label for="flexible_support_amount" class="mb-1 block font-body text-base font-semibold text-primary-1">
                                                    Enter amount (minimum USD 970)
                                                </label>
                                                <div class="flex items-center gap-3">
                                                    <span class="font-body text-base font-semibold text-primary-1">USD</span>
                                                    <input
                                                        id="flexible_support_amount"
                                                        v-model.number="form.flexible_support_amount"
                                                        type="number"
                                                            min="970"
                                                        step="1"
                                                        class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:outline-none focus:ring-1"
                                                        :class="isFlexibleAmountInvalid() ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'focus:border-accent-2 focus:ring-accent-2'"
                                                        placeholder="Enter amount above 970"
                                                    />
                                                </div>
                                                <p v-if="isFlexibleAmountInvalid() && !form.errors.flexible_support_amount" class="mt-2 text-base text-red-400">
                                                    Amount must be at least USD 970
                                                </p>
                                                <p v-if="form.errors.flexible_support_amount" class="mt-2 text-base text-red-400">
                                                    {{ form.errors.flexible_support_amount }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Only show confirmation + justification directly under the selected Subsidised Price -->
                                <div v-if="isSubsidizedSelected() && String(form.price_tier) === String(pkg.id)" class="rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                                    <p class="mb-3 font-body text-base font-semibold text-primary-1">Confirmation</p>
                                    <label class="flex items-start gap-3">
                                        <input type="checkbox" v-model="form.confirm_tier" class="mt-1 h-4 w-4 accent-[#F59C0E]" />
                                        <span class="font-body text-base text-ink">
                                            I confirm that the ticket tier selected reflects the financial capacity of my organization.
                                        </span>
                                    </label>
                                    <p v-if="form.errors.confirm_tier" class="mt-2 text-base text-red-400">{{ form.errors.confirm_tier }}</p>

                                    <div class="mt-5">
                                        <label for="subsidized_price_justification" class="mb-2 block font-body text-base font-semibold text-primary-1">Subsidised Price Justification</label>
                                        <p class="mb-3 font-body text-base text-copy">
                                            If requesting the Subsidised Price, please briefly explain why this tier is most appropriate for your participation, in line with the eligibility criteria provided in the Ticket Options section (maximum 120 words).
                                        </p>
                                        <textarea
                                            id="subsidized_price_justification"
                                            v-model="form.subsidized_price_justification"
                                            rows="4"
                                            class="w-full resize-y rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                            placeholder="Write up to 120 words"
                                        />
                                        <p v-if="form.errors.subsidized_price_justification" class="mt-2 text-base text-red-400">{{ form.errors.subsidized_price_justification }}</p>
                                    </div>
                                </div>
                            </template>
                            <!-- Hardcoded flexible option -->
                            <label
                                class="block cursor-pointer w-full rounded-xl px-6 py-5 shadow-sm transition hover:border-primary-1/20"
                                :class="
                                    String(form.price_tier) === String(hardcodedFlexibleId)
                                        ? 'border-2 border-accent-2 bg-accent-2/15'
                                        : 'border border-primary-1/10 bg-white'
                                "
                            >
                                <div class="flex items-start gap-5">
                                    <div class="pt-1">
                                        <input
                                            type="radio"
                                            v-model="form.price_tier"
                                            :value="hardcodedFlexibleId"
                                            class="sr-only"
                                        />
                                        <div
                                            class="flex h-5 w-5 items-center justify-center rounded-full border-2"
                                            :class="String(form.price_tier) === String(hardcodedFlexibleId) ? 'border-[#D21F1F] bg-[#D21F1F]' : 'border-[#D21F1F] bg-transparent'"
                                        >
                                            <div
                                                v-if="String(form.price_tier) === String(hardcodedFlexibleId)"
                                                class="h-2.5 w-2.5 rounded-full bg-white"
                                            ></div>
                                        </div>
                                    </div>

                                    <div class="flex-1 pt-0.5">
                                        <div class="flex items-start justify-between gap-4">
                                            <h3 class="font-title text-base font-bold text-primary-1">
                                            Flexible Supporter Price — Above USD 970
                                            </h3>

                                            <p
                                                v-if="String(form.price_tier) !== String(hardcodedFlexibleId)"
                                                class="font-body text-base font-semibold text-accent-2"
                                            >
                                                Enter Amount
                                            </p>
                                        </div>

                                        <p class="mt-2 font-body text-base leading-relaxed text-copy">
                                            For participants who are willing and able to contribute beyond the Standard Price, this option allows you to choose an amount above USD 970 based on your capacity and commitment to supporting an inclusive Summit
                                        </p>

                                        <div v-if="String(form.price_tier) === String(hardcodedFlexibleId)" class="mt-4">
                                            <label for="flexible_support_amount_hardcoded" class="mb-1 block font-body text-base font-semibold text-primary-1">
                                                Enter amount (minimum USD 970)
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <span class="font-body text-base font-semibold text-primary-1">USD</span>
                                                <input
                                                    id="flexible_support_amount_hardcoded"
                                                    v-model.number="form.flexible_support_amount"
                                                    type="number"
                                                    min="970"
                                                    step="1"
                                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:outline-none focus:ring-1"
                                                    :class="isFlexibleAmountInvalid() ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'focus:border-accent-2 focus:ring-accent-2'"
                                                    placeholder="Enter amount above 970"
                                                />
                                            </div>
                                            <p v-if="isFlexibleAmountInvalid() && !form.errors.flexible_support_amount" class="mt-2 text-base text-red-400">
                                                Amount must be at least USD 970
                                            </p>
                                            <p v-if="form.errors.flexible_support_amount" class="mt-2 text-base text-red-400">
                                                {{ form.errors.flexible_support_amount }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.price_tier" class="mt-1 text-base text-red-400">{{ form.errors.price_tier }}</p>
                        </div>

                        <!-- (Moved into the selected Subsidised tier card above) -->

                        <!-- Optional Immersive Experience Add-on -->
                        <div class="mt-6">
                            <label class="flex cursor-pointer gap-4 rounded-xl border px-5 py-4 transition" :class="form.field_visit ? 'border-primary-2 bg-primary-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                <input
                                    type="checkbox"
                                    v-model="form.field_visit"
                                    class="mt-1 h-4 w-4 accent-[#729430]"
                                />
                                <div>
                                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                        <span class="font-title text-xl font-bold text-primary-1">Optional Immersive Experience - 31 August, 2026</span>
                                        <span class="font-body text-lg font-semibold text-accent-2">USD 95</span>
                                    </div>
                                    <p class="mt-1 text-base text-copy">
                                        On 31st August, step into Bhutan's living landscapes to connect with Bhutanese nature, people, spirituality and the philosophy of Gross National Happiness through immersive experiences exploring conscious food systems. Visit pioneering initiatives - from organic farms and traditional paddy fields to regenerative mountain hazelnut orchards and circular local markets - where agroecology, biodiversity, and community stewardship shape resilient food futures. Meet farmers, learn how food, culture, and ecology are deeply interwoven in everyday life, or visit the iconic Tiger's Nest Monastery along with other cultural heritage sites and places of significance that offer a window into Bhutan's rich history, traditions, and ways of life. Each experience integrates guided conscious practices - mindful walks, meditation in the fields, mindful eating and cooking, and reflections on farming rituals, water, and spiritual traditions - alongside gratitude circles and personal journey prompts to deepen connection and insight. The cost includes transportation, lunch, guided activities, and all on-site experience costs.
                                    </p>
                                    <p class="mt-2 font-body text-base font-semibold text-primary-1">Full-day immersive journey</p>
                                </div>
                            </label>
                        </div>

                        <!-- Accessibility -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-base font-medium text-primary-1">Do you require any accessibility support? *</p>
                            <div class="flex gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.accessibility_needed === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.accessibility_needed" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">Yes</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.accessibility_needed === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.accessibility_needed" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">No</span>
                                </label>
                            </div>
                            <div v-if="form.accessibility_needed === 'yes'" class="mt-3">
                                <label for="accessibility_details" class="mb-1 block text-base font-medium text-primary-1">Please describe your needs</label>
                                <textarea
                                    id="accessibility_details"
                                    v-model="form.accessibility_details"
                                    rows="3"
                                    class="w-full resize-y rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Wheelchair access, sign language interpretation, etc."
                                />
                            </div>
                            <p v-if="form.errors.accessibility_needed" class="mt-1 text-base text-red-400">{{ form.errors.accessibility_needed }}</p>
                        </div>

                        <!-- Dietary Preferences -->
                        <div class="mt-6">
                            <label for="dietary" class="mb-2 block font-body text-base font-medium text-primary-1">Dietary Preferences *</label>
                            <select
                                id="dietary"
                                v-model="form.dietary"
                                required
                                class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                            >
                                <option value="" disabled class="text-gray-800">Select your preference</option>
                                <option value="no-restrictions" class="text-gray-800">No restrictions</option>
                                <option value="vegetarian" class="text-gray-800">Vegetarian</option>
                                <option value="vegan" class="text-gray-800">Vegan</option>
                                <option value="halal" class="text-gray-800">Halal</option>
                                <option value="gluten-free" class="text-gray-800">Gluten-Free</option>
                                <option value="other" class="text-gray-800">Other</option>
                            </select>
                            <div v-if="form.dietary === 'other'" class="mt-3">
                                <input
                                    v-model="form.dietary_other"
                                    type="text"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Please specify your dietary requirements"
                                />
                            </div>
                            <p v-if="form.errors.dietary" class="mt-1 text-base text-red-400">{{ form.errors.dietary }}</p>
                        </div>

                        <!-- How did you hear about this event -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-base font-medium text-primary-1">How did you hear about this event?</p>
                            <div class="grid gap-2">
                                <label
                                    v-for="source in ['Email Newsletter', 'Social Media', 'Referral', 'Partner Organization', 'Website Search']"
                                    :key="source"
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-2.5 transition"
                                    :class="form.heard_from.includes(source) ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'"
                                >
                                    <input type="checkbox" :value="source" v-model="form.heard_from" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">{{ source }}</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-2.5 transition"
                                    :class="form.heard_from.includes('Other') ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'"
                                >
                                    <input type="checkbox" value="Other" v-model="form.heard_from" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">Other</span>
                                </label>
                            </div>
                            <div v-if="form.heard_from.includes('Other')" class="mt-3">
                                <input
                                    v-model="form.heard_from_other"
                                    type="text"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Please specify"
                                />
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 4: Others -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 4 — Others</legend>

                        <!-- Engage with CoFSA -->
                        <h4 class="mt-4 font-body text-base font-semibold uppercase tracking-wide text-primary-1">Engage with CoFSA</h4>

                        <!-- CoFSA Membership -->
                        <div class="mt-4">
                            <p class="mb-2 font-body text-base font-medium text-primary-1">Would you like to become a Member of CoFSA? *</p>
                            <div class="rounded-xl border border-primary-2/15 bg-white px-5 py-3 mb-3">
                                <p class="font-body text-base leading-relaxed text-copy">
                                    The <a href="https://consciousfoodsystems.org/" target="_blank" rel="noopener noreferrer" class="font-semibold text-accent-2 hover:underline">Conscious Food Systems Alliance (CoFSA)</a> is a growing community of practitioners united to integrate consciousness into food systems transformation. As a member, you gain access to resources, events, and a network of changemakers working toward regenerative food futures.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_member === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_member" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">Yes, I'd like to join</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_member === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_member" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">No thanks</span>
                                </label>
                            </div>
                            <p v-if="form.errors.cofsa_member" class="mt-1 text-base text-red-400">{{ form.errors.cofsa_member }}</p>
                        </div>

                        <!-- CoFSA Newsletter -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-base font-medium text-primary-1">Would you like to subscribe to CoFSA's Newsletter to receive resources and connect with the community? *</p>
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_newsletter === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_newsletter" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">Yes, please subscribe me to the Newsletter</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_newsletter === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_newsletter" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-primary-1">No thanks</span>
                                </label>
                            </div>
                            <p v-if="form.errors.cofsa_newsletter" class="mt-1 text-base text-red-400">{{ form.errors.cofsa_newsletter }}</p>
                        </div>

                        <!-- Emergency Contact -->
                        <h4 class="mt-8 font-body text-base font-semibold uppercase tracking-wide text-primary-1">Emergency Contact *</h4>
                        <div class="mt-3 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="emergency_name" class="mb-1 block text-base font-medium text-primary-1">Full Name *</label>
                                <input
                                    id="emergency_name"
                                    v-model="form.emergency_name"
                                    type="text"
                                    required
                                    @input="form.emergency_name = stripDigits($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Contact person's full name"
                                />
                                <p v-if="form.errors.emergency_name" class="mt-1 text-base text-red-400">{{ form.errors.emergency_name }}</p>
                            </div>
                            <div>
                                <label for="emergency_phone" class="mb-1 block text-base font-medium text-primary-1">Phone Number *</label>
                                <input
                                    id="emergency_phone"
                                    v-model="form.emergency_phone"
                                    type="tel"
                                    required
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    @input="form.emergency_phone = sanitizePhoneLocal($event.target.value)"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="+1 234 567 8900"
                                />
                                <p v-if="form.errors.emergency_phone" class="mt-1 text-base text-red-400">{{ form.errors.emergency_phone }}</p>
                            </div>
                            <div>
                                <label for="emergency_email" class="mb-1 block text-base font-medium text-primary-1">Email *</label>
                                <input
                                    id="emergency_email"
                                    v-model="form.emergency_email"
                                    type="email"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="contact@example.com"
                                />
                                <p v-if="form.errors.emergency_email" class="mt-1 text-base text-red-400">{{ form.errors.emergency_email }}</p>
                            </div>
                            <div>
                                <label for="emergency_relation" class="mb-1 block text-base font-medium text-primary-1">Relation *</label>
                                <input
                                    id="emergency_relation"
                                    v-model="form.emergency_relation"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="e.g. Spouse, Parent, Sibling"
                                />
                                <p v-if="form.errors.emergency_relation" class="mt-1 text-base text-red-400">{{ form.errors.emergency_relation }}</p>
                            </div>
                        </div>

                        <!-- Photo upload is hidden for now -->

                        <!-- Contact note -->
                        <div class="mt-8 rounded-xl border border-primary-2/15 bg-white px-5 py-4 text-center">
                            <p class="font-body text-base text-copy">
                                For any questions or additional clarifications, please contact the organizers at
                                <a href="mailto:cofsa@undp.org" class="font-semibold text-accent-2 hover:underline">cofsa@undp.org</a>
                            </p>
                        </div>
                    </fieldset>

                    <!-- Submit -->
                    <div v-if="submitErrorSummary || form.errors.form" class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        <p class="font-body text-base text-red-700">{{ submitErrorSummary || form.errors.form }}</p>
                    </div>
                    <div class="flex flex-col items-center pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center rounded-xl bg-accent-2 px-8 py-2.5 font-body text-base font-bold text-primary-1 transition hover:bg-accent-2/90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Submitting...' : 'Submit Registration Application' }}
                        </button>
                        <p class="mt-3 text-center text-base text-copy">
                            By submitting, you agree that your information will be processed by the organizing team for event coordination purposes.
                        </p>
                    </div>
                </form>
            </div>
        </section>
        
        </div>
    </EventLayout>
</template>

<style scoped>
/* Keep register page typography consistent and readable */
.register-page :deep(.font-title) {
    font-size: 2rem !important;
    line-height: 2.5rem !important;
}

.register-page :deep(.font-body) {
    font-size: 1.125rem !important;
    line-height: 1.75rem !important;
}

.register-page :deep(.step-row-heading) {
    font-family: var(--font-title), ui-sans-serif, system-ui, sans-serif !important;
    font-size: 1.125rem !important;
    line-height: 1.5rem !important;
}

.register-page :deep(.step-number-circle) {
    font-family: var(--font-title), ui-sans-serif, system-ui, sans-serif !important;
    font-size: 1.25rem !important;
    line-height: 1 !important;
}

@media (min-width: 640px) {
    .register-page :deep(.step-number-circle) {
        font-size: 1.375rem !important;
    }
}
</style>
