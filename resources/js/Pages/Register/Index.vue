<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    country: '',
    organization: '',
    job_title: '',
    website: '',
    passport_number: '',
    passport_expiry: '',
    arrival_date: '',
    departure_date: '',
    price_tier: '',
    confirm_tier: false,
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

const photoPreview = ref(null);

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
    { code: '+1', label: 'Canada / USA (+1)' },
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

function buildFullPhone() {
    const code = (phoneCountryCode.value || '').trim();
    const local = (phoneLocal.value || '').trim().replace(/\s+/g, ' ');
    if (!code && !local) return '';
    if (!local) return code;
    if (!code) return local;
    return `${code} ${local}`.trim();
}
const page = usePage();
const showSuccessModal = ref(!!page.props.flash?.success);

watch(() => page.props.flash?.success, (val) => {
    if (val) showSuccessModal.value = true;
});

function closeSuccessModal() {
    showSuccessModal.value = false;
}

function handlePhoto(e) {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (ev) => { photoPreview.value = ev.target.result; };
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    form.photo = null;
    photoPreview.value = null;
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
    phoneClientError.value = '';
    form.phone = buildFullPhone();
    if (!form.phone.trim()) {
        phoneClientError.value = 'Please choose a country code and enter your phone number.';
        return;
    }
    form.post('/register-summit', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
            phoneCountryCode.value = '+975';
            phoneLocal.value = '';
            phoneClientError.value = '';
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
                    <button
                        type="button"
                        @click="closeSuccessModal"
                        class="mt-6 w-full rounded-lg bg-primary-2 px-4 py-3 font-body text-sm font-semibold text-white transition hover:bg-primary-2/90"
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
                :style="{
                    backgroundImage: `
                        radial-gradient(1200px 600px at 20% 20%, rgba(245, 156, 14, 0.22), transparent 60%),
                        radial-gradient(900px 500px at 80% 35%, rgba(114, 148, 48, 0.18), transparent 60%),
                        radial-gradient(900px 600px at 50% 90%, rgba(13, 56, 90, 0.12), transparent 65%),
                        linear-gradient(to bottom, rgba(13, 56, 90, 0.06), rgba(245, 156, 14, 0.06))
                    `,
                }"
            />
            <div class="relative mx-auto w-full max-w-[70rem] px-4 text-center sm:px-6 lg:px-8">
                <h1 class="font-title text-3xl font-bold text-primary-1 sm:text-4xl lg:text-5xl">Join the Global Conscious Food Systems Summit 2026!</h1>
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
                <h2 class="font-title text-center text-2xl font-bold text-primary-1 sm:text-3xl">
                    Step-by-step registration <span style="font-variant: all-small-caps;">Process</span>
                </h2>
                <div class="mt-12 space-y-6">
                    <div v-for="step in steps" :key="step.number" class="flex gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent-2 font-body text-sm font-bold text-primary-1">
                            {{ step.number }}
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="font-title text-lg font-bold text-primary-1">
                                Step <span class="font-body">{{ step.number }}</span> — {{ step.title }}
                            </h3>
                            <p class="mt-1 font-body text-base leading-relaxed text-copy">{{ step.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="bg-[#F4F1EA] py-12 sm:py-16">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <p class="text-center font-body text-base text-copy sm:text-lg">
                    For any questions or additional clarifications, please contact the organizers at
                    <a href="mailto:cofsa@undp.org" class="font-semibold text-accent-1 hover:underline">cofsa@undp.org</a>
                </p>
            </div>
        </section>

        <!-- Registration Form -->
        <section id="form" class="bg-[#F4F1EA] py-16 sm:py-20">
            <div class="mx-auto w-full max-w-[70rem] px-4 sm:px-6 lg:px-8">
                <h2 class="text-center font-title text-2xl font-bold text-primary-1 sm:text-3xl">Registration Application</h2>
                <p class="mt-2 text-center font-body text-lg text-copy sm:text-xl">Fill out the form below to begin your registration process</p>

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
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Last name as in passport"
                                />
                                <p v-if="form.errors.last_name" class="mt-1 text-base text-red-400">{{ form.errors.last_name }}</p>
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
                                        autocomplete="tel-national"
                                        class="min-w-0 flex-1 rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                        placeholder="e.g. 234 567 8900"
                                    />
                                </div>
                                <p v-if="phoneClientError || form.errors.phone" class="mt-1 text-base text-red-400">
                                    {{ phoneClientError || form.errors.phone }}
                                </p>
                            </div>
                            <div>
                                <label for="country" class="mb-1 block text-base font-medium text-primary-1">Country of Residence</label>
                                <select
                                    id="country"
                                    v-model="form.country"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select country</option>
                                    <option v-for="c in countries" :key="c" :value="c" class="text-gray-800">{{ c }}</option>
                                </select>
                                <p v-if="form.errors.country" class="mt-1 text-base text-red-400">{{ form.errors.country }}</p>
                            </div>
                            <div>
                                <label for="organization" class="mb-1 block text-base font-medium text-primary-1">Organization / Affiliation</label>
                                <p class="mb-2 font-body text-base text-copy">Please provide the full legal name of your organization. If you are not affiliated with an organization, please write "Independent".</p>
                                <input
                                    id="organization"
                                    v-model="form.organization"
                                    type="text"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder='e.g. Independent'
                                />
                                <p v-if="form.errors.organization" class="mt-1 text-base text-red-400">{{ form.errors.organization }}</p>
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
                                Visa arrangements for this event will be fully coordinated by the organizing team. Upon approval of your registration, the organizers will guide you through the visa process and provide the necessary official documentation required for your application. Please ensure that the passport details submitted below are accurate and match your travel documents.
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
                                <label for="passport_expiry" class="mb-1 block text-base font-medium text-primary-1">Passport Expiration Date</label>
                                <input
                                    id="passport_expiry"
                                    v-model="form.passport_expiry"
                                    type="date"
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
                        <p class="mb-2 mt-6 font-title text-xl font-bold text-primary-1">Choose your Ticket option</p>
                        <p class="font-body text-base leading-relaxed text-copy">
                            Participants are invited to select the tier that best reflects their organization's resources and available budgets.
                            Participants whose organizations have the means are warmly encouraged to select the Supporter Price to help widen participation.
                        </p>
                        <div class="mt-4 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                            <p class="font-body text-base font-semibold text-primary-1">Ticket options</p>
                            <div class="mt-3 space-y-2 font-body text-base text-copy">
                                <p>Standard Price — USD ~970</p>
                                <p>Social/subsidized Price — USD ~580</p>
                                <p>Supporter Price — USD ~1,550</p>
                            </div>
                        </div>
                        <p v-if="!packages.length" class="rounded-xl border border-primary-2/20 bg-white px-5 py-4 text-base text-copy">No packages are currently available. Please contact the organizers at cofsa@undp.org.</p>
                        <div v-else class="space-y-3">
                            <label
                                v-for="pkg in packages"
                                :key="pkg.id"
                                class="flex cursor-pointer gap-4 rounded-xl border px-5 py-4 transition"
                                :class="form.price_tier === pkg.id ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'"
                            >
                                <input
                                    type="radio"
                                    v-model="form.price_tier"
                                    :value="pkg.id"
                                    class="mt-1 h-4 w-4 accent-[#F59C0E]"
                                />
                                <div class="flex-1">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <span class="font-body font-semibold text-primary-1">{{ pkg.name }}</span>
                                        <span class="shrink-0 font-body text-lg font-bold text-accent-2">USD {{ Number(pkg.price).toLocaleString() }}</span>
                                    </div>
                                    <p class="mt-0.5 text-base text-ink">{{ pkg.description }}</p>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.price_tier" class="mt-1 text-base text-red-400">{{ form.errors.price_tier }}</p>

                        <div class="mt-6 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" v-model="form.confirm_tier" class="mt-1 h-4 w-4 accent-[#F59C0E]" />
                                <span class="font-body text-base text-ink">
                                    I confirm that the ticket tier selected reflects the financial capacity of my organization.
                                    <span class="mt-1 block text-copy">The organizing team may review ticket selections and contact applicants if a different tier appears more appropriate.</span>
                                </span>
                            </label>
                            <p v-if="form.errors.confirm_tier" class="mt-2 text-base text-red-400">{{ form.errors.confirm_tier }}</p>
                        </div>

                        <!-- Field Visit Add-on -->
                        <div class="mt-6">
                            <label class="flex cursor-pointer gap-4 rounded-xl border px-5 py-4 transition" :class="form.field_visit ? 'border-primary-2 bg-primary-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                <input
                                    type="checkbox"
                                    v-model="form.field_visit"
                                    class="mt-1 h-4 w-4 accent-[#729430]"
                                />
                                <div>
                                    <span class="font-body font-semibold text-primary-1">Field Visits on 31 August</span>
                                    <span class="ml-2 font-body text-base font-bold text-primary-2">(+ additional cost)</span>
                                    <p class="mt-0.5 text-base text-copy">Guided visits to organic farms in the Paro Valley, including farm-to-table lunch experience and hands-on workshops with local farmers. Exact pricing to be confirmed.</p>
                                </div>
                            </label>
                        </div>

                        <!-- Accessibility -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-base font-medium text-primary-1">Do you require any accessibility support? *</p>
                            <div class="flex gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.accessibility_needed === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.accessibility_needed" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-base text-white">Yes</span>
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

                        <!-- Document Uploads -->
                        <h4 class="mt-8 font-body text-base font-semibold uppercase tracking-wide text-primary-1">Document Uploads</h4>
                        <div class="mt-3">
                            <p class="mb-3 font-body text-base text-copy">Please upload a personal photo of yourself for visa purposes. The photo should be a recent passport-style photograph with a plain background.</p>
                            <div v-if="!photoPreview" class="flex items-center justify-center">
                                <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-primary-2/30 bg-white py-10 transition hover:border-accent-2 hover:bg-accent-2/5">
                                    <svg class="mb-3 h-10 w-10 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-body text-base font-medium text-primary-1">Click to upload photo</span>
                                    <span class="mt-1 text-base text-copy">JPG, PNG — max 5MB</span>
                                    <input type="file" accept="image/jpeg,image/png" class="hidden" @change="handlePhoto" />
                                </label>
                            </div>
                            <div v-else class="flex items-center gap-4">
                                <img :src="photoPreview" alt="Photo preview" class="h-24 w-20 rounded-lg border border-primary-2/20 object-cover" />
                                <div>
                                    <p class="text-base text-primary-1">Photo uploaded</p>
                                    <button type="button" @click="removePhoto" class="mt-1 text-base text-accent-2 hover:underline">Remove</button>
                                </div>
                            </div>
                            <p v-if="form.errors.photo" class="mt-1 text-base text-red-400">{{ form.errors.photo }}</p>
                        </div>

                        <!-- Contact note -->
                        <div class="mt-8 rounded-xl border border-primary-2/15 bg-white px-5 py-4 text-center">
                            <p class="font-body text-base text-copy">
                                For any questions or additional clarifications, please contact the organizers at
                                <a href="mailto:cofsa@undp.org" class="font-semibold text-accent-2 hover:underline">cofsa@undp.org</a>
                            </p>
                        </div>
                    </fieldset>

                    <!-- Submit -->
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
    </EventLayout>
</template>
