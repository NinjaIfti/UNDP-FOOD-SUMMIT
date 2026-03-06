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
    field_visit: false,
    accessibility_needed: '',
    accessibility_details: '',
    dietary: '',
    dietary_other: '',
    heard_from: [],
    heard_from_other: '',
    additional_members: [
        { first_name: '', last_name: '', passport_number: '' },
        { first_name: '', last_name: '', passport_number: '' },
    ],
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
});

const photoPreview = ref(null);
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

function addMember() {
    form.additional_members.push({ first_name: '', last_name: '', passport_number: '' });
}

function removeMember(index) {
    if (form.additional_members.length > 1) {
        form.additional_members.splice(index, 1);
    }
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
    form.post('/register-summit', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
        },
    });
}

const steps = [
    {
        number: 1,
        title: 'Submit Your Registration Application',
        desc: 'Complete the form with your personal details, selected participation package, and any optional preferences.',
    },
    {
        number: 2,
        title: 'Review and Confirmation by Organizers',
        desc: 'All applications will be reviewed by the organizing team. Submission does not automatically confirm participation.',
    },
    {
        number: 3,
        title: 'Approval Notification and Payment Instructions',
        desc: 'Once your registration is approved, you will receive an email confirming your participation and providing instructions to proceed with payment.',
    },
    {
        number: 4,
        title: 'Visa Coordination After Payment',
        desc: 'After payment is completed, the organizers will begin the full visa entry permission process and prepare the necessary documentation for your travel to Bhutan.',
    },
    {
        number: 5,
        title: 'Visa Approval and Travel Planning',
        desc: 'Once your visa clearance is confirmed, you will be informed immediately and may then proceed with booking your flights, accommodation, and final travel arrangements.',
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
        <section class="relative bg-primary-2/10 py-20 sm:py-28">
            <div class="absolute inset-0 opacity-20">
                <img src="/brand/img34.jpg" alt="" class="h-full w-full object-cover" aria-hidden="true" />
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-primary-1/70 via-primary-1/30 to-primary-2/60" />
            <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <h1 class="font-title text-3xl font-bold text-primary-1 sm:text-4xl lg:text-5xl">Register Now!</h1>
                <p class="mt-4 font-body text-lg font-medium text-accent-2 sm:text-xl">Global Conscious Food Systems Summit · 31 Aug – 4 Sep 2026 · Bhutan</p>
            </div>
        </section>

        <!-- Intro -->
        <section class="bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <p class="font-body text-lg leading-relaxed text-ink">
                    We are delighted to hear about your interest in joining the first-ever Conscious Food Systems Summit in Bhutan. Please note that space is limited, and we encourage timely submission of registration applications to support an efficient review and coordination process.
                </p>
                <p class="mt-4 font-body text-lg leading-relaxed text-ink">
                    Participation in an event of this nature requires a carefully coordinated registration and travel process, particularly due to the specific entry regulations and visa procedures for visiting Bhutan.
                </p>
                <div class="mt-6 rounded-xl border-l-4 border-accent-2 bg-accent-2/10 px-6 py-4">
                    <p class="font-body text-sm font-semibold uppercase tracking-wide text-accent-2">Important Note</p>
                    <p class="mt-1 font-body text-base text-ink">
                        We strongly recommend that participants do not book international travel or accommodation arrangements until their visa entry permission has been officially approved.
                    </p>
                </div>
                <p class="mt-6 font-body text-lg leading-relaxed text-ink">
                    To ensure a smooth experience for all participants, registration will follow the steps outlined below:
                </p>
            </div>
        </section>

        <!-- Steps -->
        <section class="border-y border-primary-2/20 bg-primary-2/5 py-16 sm:py-20">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <h2 class="font-title text-center text-2xl font-bold text-primary-1 sm:text-3xl">Registration Process</h2>
                <div class="mt-12 space-y-6">
                    <div v-for="step in steps" :key="step.number" class="flex gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent-2 font-body text-sm font-bold text-primary-1">
                            {{ step.number }}
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="font-title text-lg font-bold text-primary-1">Step {{ step.number }} — {{ step.title }}</h3>
                            <p class="mt-1 font-body text-base leading-relaxed text-copy">{{ step.desc }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Closing Note -->
        <section class="bg-white py-12 sm:py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                    We appreciate your understanding and cooperation as we coordinate these steps with care. Please rest assured that our team will be spearheading the process closely to ensure timely approvals and a smooth, efficient experience. We look forward to accompanying you every step of the way toward an inspiring gathering in Bhutan.
                </p>
                <p class="mt-4 font-body text-base text-copy sm:text-lg">
                    For any questions or additional clarifications, please contact the organizers at
                    <a href="mailto:cofsa@undp.org" class="font-semibold text-accent-1 hover:underline">cofsa@undp.org</a>
                </p>
            </div>
        </section>

        <!-- Registration Form -->
        <section id="form" class="bg-primary-2/5 py-16 sm:py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-center font-title text-2xl font-bold text-primary-1 sm:text-3xl">Registration Application</h2>
                <p class="mt-2 text-center font-body text-copy">Fill out the form below to begin your registration process</p>

                <form @submit.prevent="submit" class="mt-10 space-y-6">
                    <!-- Section 1: Personal Information -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 1 — Personal Information</legend>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="mb-1 block text-sm font-medium text-primary-1">First Name (as in Passport/ID) *</label>
                                <input
                                    id="first_name"
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="First name as in passport"
                                />
                                <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-400">{{ form.errors.first_name }}</p>
                            </div>
                            <div>
                                <label for="last_name" class="mb-1 block text-sm font-medium text-primary-1">Last Name (as in Passport/ID) *</label>
                                <input
                                    id="last_name"
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Last name as in passport"
                                />
                                <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-400">{{ form.errors.last_name }}</p>
                            </div>
                            <div>
                                <label for="reg-email" class="mb-1 block text-sm font-medium text-primary-1">Email Address *</label>
                                <input
                                    id="reg-email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="you@example.com"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label for="phone" class="mb-1 block text-sm font-medium text-primary-1">Phone Number (incl. international code) *</label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="+1 234 567 8900"
                                />
                                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-400">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label for="country" class="mb-1 block text-sm font-medium text-primary-1">Country of Residence</label>
                                <select
                                    id="country"
                                    v-model="form.country"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                >
                                    <option value="" class="text-gray-800">Select country</option>
                                    <option v-for="c in countries" :key="c" :value="c" class="text-gray-800">{{ c }}</option>
                                </select>
                                <p v-if="form.errors.country" class="mt-1 text-sm text-red-400">{{ form.errors.country }}</p>
                            </div>
                            <div>
                                <label for="organization" class="mb-1 block text-sm font-medium text-primary-1">Organization / Affiliation *</label>
                                <input
                                    id="organization"
                                    v-model="form.organization"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Your organization"
                                />
                                <p v-if="form.errors.organization" class="mt-1 text-sm text-red-400">{{ form.errors.organization }}</p>
                            </div>
                            <div>
                                <label for="job_title" class="mb-1 block text-sm font-medium text-primary-1">Job Title / Role *</label>
                                <input
                                    id="job_title"
                                    v-model="form.job_title"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Your role or title"
                                />
                                <p v-if="form.errors.job_title" class="mt-1 text-sm text-red-400">{{ form.errors.job_title }}</p>
                            </div>
                            <div>
                                <label for="website" class="mb-1 block text-sm font-medium text-primary-1">Website / Links</label>
                                <input
                                    id="website"
                                    v-model="form.website"
                                    type="url"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="https://yourwebsite.com"
                                />
                                <p v-if="form.errors.website" class="mt-1 text-sm text-red-400">{{ form.errors.website }}</p>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 2: Visa Information -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 2 — Visa Information</legend>
                        <div class="mt-3 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                            <p class="font-body text-sm leading-relaxed text-copy">
                                Visa arrangements for this event will be fully coordinated by the organizing team. Upon approval of your registration, the organizers will guide you through the visa process and provide the necessary official documentation required for your application. Please ensure that the passport details submitted below are accurate and match your travel documents.
                            </p>
                            <p class="mt-2 font-body text-sm leading-relaxed text-copy">
                                The Summit's official dates are <span class="font-semibold text-primary-1">31 August – 4 September 2026</span>. Participants covered under the Sustainable Development Service (SDS) fee may stay for a limited number of days around the event period. If you wish to extend your stay beyond the covered dates, additional arrangements and fees may apply — the organizing team will advise you on the process after registration approval.
                            </p>
                        </div>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="passport_number" class="mb-1 block text-sm font-medium text-primary-1">Passport Number *</label>
                                <input
                                    id="passport_number"
                                    v-model="form.passport_number"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="e.g. AB1234567"
                                />
                                <p v-if="form.errors.passport_number" class="mt-1 text-sm text-red-400">{{ form.errors.passport_number }}</p>
                            </div>
                            <div>
                                <label for="passport_expiry" class="mb-1 block text-sm font-medium text-primary-1">Passport Expiration Date</label>
                                <input
                                    id="passport_expiry"
                                    v-model="form.passport_expiry"
                                    type="date"
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p class="mt-1 text-xs text-copy">Must be valid for at least 6 months from travel date</p>
                                <p v-if="form.errors.passport_expiry" class="mt-1 text-sm text-red-400">{{ form.errors.passport_expiry }}</p>
                            </div>
                            <div>
                                <label for="arrival_date" class="mb-1 block text-sm font-medium text-primary-1">Preferred Arrival Date *</label>
                                <input
                                    id="arrival_date"
                                    v-model="form.arrival_date"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p v-if="form.errors.arrival_date" class="mt-1 text-sm text-red-400">{{ form.errors.arrival_date }}</p>
                            </div>
                            <div>
                                <label for="departure_date" class="mb-1 block text-sm font-medium text-primary-1">Preferred Departure Date *</label>
                                <input
                                    id="departure_date"
                                    v-model="form.departure_date"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                />
                                <p v-if="form.errors.departure_date" class="mt-1 text-sm text-red-400">{{ form.errors.departure_date }}</p>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Section 3: Select Your Price -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 3 — Select Your Price</legend>

                        <!-- What's included -->
                        <div class="mt-3 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                            <p class="font-body text-sm font-semibold text-primary-1">The ticket price includes:</p>
                            <ul class="mt-2 space-y-1.5 text-sm text-copy">
                                <li class="flex gap-2">
                                    <span class="mt-1 text-primary-2">&#10003;</span>
                                    Entrance and participation in the event from 01–04 September
                                </li>
                                <li class="flex gap-2">
                                    <span class="mt-1 text-primary-2">&#10003;</span>
                                    <span>Daily meals at the venue: lunch each day, two tea/coffee breaks per day, and two to three group dinners <span class="text-copy/70">(to be confirmed)</span></span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="mt-1 text-primary-2">&#10003;</span>
                                    Visa application fee of BTN 5,000 and full coordination of the visa entry permission process
                                </li>
                                <li class="flex gap-2">
                                    <span class="mt-1 text-primary-2">&#10003;</span>
                                    <span><strong class="text-primary-1">Sustainable Development Fee (SDF) waiver:</strong> The Royal Government of Bhutan has kindly granted all summit participants a waiver of the SDF (normally USD 100/adult/night) for the event period from 31 August to 05 September <span class="text-copy/70">(dates to be confirmed)</span></span>
                                </li>
                                <li class="flex gap-2">
                                    <span class="mt-1 text-primary-2">&#10003;</span>
                                    Local transportation between your hotel in Paro and the event venue from 01–04 September
                                </li>
                            </ul>

                            <p class="mt-4 font-body text-sm font-semibold text-primary-1">Please note the ticket price does not include:</p>
                            <ul class="mt-2 space-y-1.5 text-sm text-copy">
                                <li class="flex gap-2"><span class="mt-1 text-accent-2">&#10007;</span> International travel to and from Bhutan</li>
                                <li class="flex gap-2"><span class="mt-1 text-accent-2">&#10007;</span> Accommodation costs in Paro</li>
                                <li class="flex gap-2"><span class="mt-1 text-accent-2">&#10007;</span> Airport transfers (transportation between Paro airport and your hotel)</li>
                                <li class="flex gap-2"><span class="mt-1 text-accent-2">&#10007;</span> One to two dinners outside the official program <span class="text-copy/70">(to be confirmed)</span></li>
                                <li class="flex gap-2"><span class="mt-1 text-accent-2">&#10007;</span> Any personal expenses or costs incurred outside the official event dates (before 01 Sep or after 04 Sep)</li>
                            </ul>

                            <p class="mt-4 text-sm text-accent-2">
                                <strong>Optional add-on:</strong> Field visits on 31 August are available at an additional cost and can be selected separately below.
                            </p>
                        </div>

                        <!-- Price Tier Selection -->
                        <p class="mb-2 mt-6 font-body text-sm font-medium text-primary-1">Select your price tier *</p>
                        <p v-if="!packages.length" class="rounded-xl border border-primary-2/20 bg-white px-5 py-4 text-sm text-copy">No packages are currently available. Please contact the organizers at cofsa@undp.org.</p>
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
                                    <p class="mt-0.5 text-sm text-white/70">{{ pkg.description }}</p>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.price_tier" class="mt-1 text-sm text-red-400">{{ form.errors.price_tier }}</p>

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
                                    <span class="ml-2 font-body text-sm font-bold text-primary-2">(+ additional cost)</span>
                                    <p class="mt-0.5 text-sm text-copy">Guided visits to organic farms in the Paro Valley, including farm-to-table lunch experience and hands-on workshops with local farmers. Exact pricing to be confirmed.</p>
                                </div>
                            </label>
                        </div>

                        <!-- Accessibility -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">Do you require any accessibility support? *</p>
                            <div class="flex gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.accessibility_needed === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.accessibility_needed" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-white">Yes</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.accessibility_needed === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.accessibility_needed" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">No</span>
                                </label>
                            </div>
                            <div v-if="form.accessibility_needed === 'yes'" class="mt-3">
                                <label for="accessibility_details" class="mb-1 block text-sm font-medium text-primary-1">Please describe your needs</label>
                                <textarea
                                    id="accessibility_details"
                                    v-model="form.accessibility_details"
                                    rows="3"
                                    class="w-full resize-y rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Wheelchair access, sign language interpretation, etc."
                                />
                            </div>
                            <p v-if="form.errors.accessibility_needed" class="mt-1 text-sm text-red-400">{{ form.errors.accessibility_needed }}</p>
                        </div>

                        <!-- Dietary Preferences -->
                        <div class="mt-6">
                            <label for="dietary" class="mb-2 block font-body text-sm font-medium text-primary-1">Dietary Preferences *</label>
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
                            <p v-if="form.errors.dietary" class="mt-1 text-sm text-red-400">{{ form.errors.dietary }}</p>
                        </div>

                        <!-- How did you hear about this event -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">How did you hear about this event?</p>
                            <div class="grid gap-2 sm:grid-cols-2">
                                <label
                                    v-for="source in ['Email Newsletter', 'Social Media', 'Referral', 'Partner Organization', 'Website Search']"
                                    :key="source"
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-2.5 transition"
                                    :class="form.heard_from.includes(source) ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'"
                                >
                                    <input type="checkbox" :value="source" v-model="form.heard_from" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">{{ source }}</span>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-2.5 transition"
                                    :class="form.heard_from.includes('Other') ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'"
                                >
                                    <input type="checkbox" value="Other" v-model="form.heard_from" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">Other</span>
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

                    <!-- Section 4: Additional Participants -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 4 — Additional Participants</legend>
                        <div class="mt-3 rounded-xl border border-primary-2/15 bg-white px-5 py-4">
                            <p class="font-body text-sm leading-relaxed text-copy">
                                If you are traveling with a family member, companion, or guest, please provide their information below. Passport details are collected solely to allow the organizing team to fully coordinate the visa entry permission process for accompanying persons.
                            </p>
                        </div>

                        <div class="mt-4 space-y-6">
                            <div v-for="(member, i) in form.additional_members" :key="i" class="rounded-xl border border-primary-2/20 bg-white px-5 py-5">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-body text-sm font-semibold text-primary-1">Additional Member {{ i + 1 }}</h4>
                                    <button
                                        v-if="form.additional_members.length > 1"
                                        type="button"
                                        @click="removeMember(i)"
                                        class="flex h-7 w-7 items-center justify-center rounded-full text-copy transition hover:bg-accent-2/20 hover:text-accent-2"
                                        title="Remove member"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-3 grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <label :for="'member_first_' + i" class="mb-1 block text-sm font-medium text-primary-1">First Name (as in Passport/ID)</label>
                                        <input
                                            :id="'member_first_' + i"
                                            v-model="member.first_name"
                                            type="text"
                                            class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                            placeholder="First name"
                                        />
                                    </div>
                                    <div>
                                        <label :for="'member_last_' + i" class="mb-1 block text-sm font-medium text-primary-1">Last Name (as in Passport/ID)</label>
                                        <input
                                            :id="'member_last_' + i"
                                            v-model="member.last_name"
                                            type="text"
                                            class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                            placeholder="Last name"
                                        />
                                    </div>
                                    <div>
                                        <label :for="'member_passport_' + i" class="mb-1 block text-sm font-medium text-primary-1">Passport Number</label>
                                        <input
                                            :id="'member_passport_' + i"
                                            v-model="member.passport_number"
                                            type="text"
                                            class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                            placeholder="e.g. AB1234567"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="addMember"
                            class="mt-4 inline-flex items-center gap-2 rounded-lg border border-dashed border-primary-2/40 px-4 py-2.5 text-sm text-primary-1 transition hover:border-accent-2 hover:text-accent-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Another Member
                        </button>
                    </fieldset>

                    <!-- Section 5: Others -->
                    <fieldset>
                        <legend class="font-body text-lg font-semibold text-accent-2">Section 5 — Others</legend>

                        <!-- Engage with CoFSA -->
                        <h4 class="mt-4 font-body text-sm font-semibold uppercase tracking-wide text-primary-1">Engage with CoFSA</h4>

                        <!-- CoFSA Membership -->
                        <div class="mt-4">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">Would you like to become a Member of CoFSA? *</p>
                            <div class="rounded-xl border border-primary-2/15 bg-white px-5 py-3 mb-3">
                                <p class="font-body text-sm leading-relaxed text-copy">
                                    The <a href="https://consciousfoodsystems.org/" target="_blank" rel="noopener noreferrer" class="font-semibold text-accent-2 hover:underline">Conscious Food Systems Alliance (CoFSA)</a> is a growing community of practitioners united to integrate consciousness into food systems transformation. As a member, you gain access to resources, events, and a network of changemakers working toward regenerative food futures.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_member === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_member" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">Yes, I'd like to join</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_member === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_member" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">No thanks</span>
                                </label>
                            </div>
                            <p v-if="form.errors.cofsa_member" class="mt-1 text-sm text-red-400">{{ form.errors.cofsa_member }}</p>
                        </div>

                        <!-- CoFSA Newsletter -->
                        <div class="mt-6">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">Would you like to subscribe to CoFSA's Newsletter to receive resources and connect with the community? *</p>
                            <div class="flex flex-col gap-2 sm:flex-row sm:gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_newsletter === 'yes' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_newsletter" value="yes" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">Yes, please subscribe me to the Newsletter</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition" :class="form.cofsa_newsletter === 'no' ? 'border-accent-2 bg-accent-2/10' : 'border-primary-2/20 bg-white hover:border-primary-2/40'">
                                    <input type="radio" v-model="form.cofsa_newsletter" value="no" class="h-4 w-4 accent-[#F59C0E]" />
                                    <span class="text-sm text-primary-1">No thanks</span>
                                </label>
                            </div>
                            <p v-if="form.errors.cofsa_newsletter" class="mt-1 text-sm text-red-400">{{ form.errors.cofsa_newsletter }}</p>
                        </div>

                        <!-- Emergency Contact -->
                        <h4 class="mt-8 font-body text-sm font-semibold uppercase tracking-wide text-primary-1">Emergency Contact *</h4>
                        <div class="mt-3 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="emergency_name" class="mb-1 block text-sm font-medium text-primary-1">Full Name *</label>
                                <input
                                    id="emergency_name"
                                    v-model="form.emergency_name"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="Contact person's full name"
                                />
                                <p v-if="form.errors.emergency_name" class="mt-1 text-sm text-red-400">{{ form.errors.emergency_name }}</p>
                            </div>
                            <div>
                                <label for="emergency_phone" class="mb-1 block text-sm font-medium text-primary-1">Phone Number *</label>
                                <input
                                    id="emergency_phone"
                                    v-model="form.emergency_phone"
                                    type="tel"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="+1 234 567 8900"
                                />
                                <p v-if="form.errors.emergency_phone" class="mt-1 text-sm text-red-400">{{ form.errors.emergency_phone }}</p>
                            </div>
                            <div>
                                <label for="emergency_email" class="mb-1 block text-sm font-medium text-primary-1">Email *</label>
                                <input
                                    id="emergency_email"
                                    v-model="form.emergency_email"
                                    type="email"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="contact@example.com"
                                />
                                <p v-if="form.errors.emergency_email" class="mt-1 text-sm text-red-400">{{ form.errors.emergency_email }}</p>
                            </div>
                            <div>
                                <label for="emergency_relation" class="mb-1 block text-sm font-medium text-primary-1">Relation *</label>
                                <input
                                    id="emergency_relation"
                                    v-model="form.emergency_relation"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-primary-2/20 bg-white px-4 py-3 text-ink focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                    placeholder="e.g. Spouse, Parent, Sibling"
                                />
                                <p v-if="form.errors.emergency_relation" class="mt-1 text-sm text-red-400">{{ form.errors.emergency_relation }}</p>
                            </div>
                        </div>

                        <!-- Document Uploads -->
                        <h4 class="mt-8 font-body text-sm font-semibold uppercase tracking-wide text-primary-1">Document Uploads</h4>
                        <div class="mt-3">
                            <p class="mb-3 font-body text-sm text-copy">Please upload a personal photo of yourself for visa purposes. The photo should be a recent passport-style photograph with a plain background.</p>
                            <div v-if="!photoPreview" class="flex items-center justify-center">
                                <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-primary-2/30 bg-white py-10 transition hover:border-accent-2 hover:bg-accent-2/5">
                                    <svg class="mb-3 h-10 w-10 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-body text-sm font-medium text-primary-1">Click to upload photo</span>
                                    <span class="mt-1 text-xs text-copy">JPG, PNG — max 5MB</span>
                                    <input type="file" accept="image/jpeg,image/png" class="hidden" @change="handlePhoto" />
                                </label>
                            </div>
                            <div v-else class="flex items-center gap-4">
                                <img :src="photoPreview" alt="Photo preview" class="h-24 w-20 rounded-lg border border-primary-2/20 object-cover" />
                                <div>
                                    <p class="text-sm text-primary-1">Photo uploaded</p>
                                    <button type="button" @click="removePhoto" class="mt-1 text-sm text-accent-2 hover:underline">Remove</button>
                                </div>
                            </div>
                            <p v-if="form.errors.photo" class="mt-1 text-sm text-red-400">{{ form.errors.photo }}</p>
                        </div>

                        <!-- Contact note -->
                        <div class="mt-8 rounded-xl border border-primary-2/15 bg-white px-5 py-4 text-center">
                            <p class="font-body text-sm text-copy">
                                For any questions or additional clarifications, please contact the organizers at
                                <a href="mailto:cofsa@undp.org" class="font-semibold text-accent-2 hover:underline">cofsa@undp.org</a>
                            </p>
                        </div>
                    </fieldset>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-xl bg-accent-2 py-4 font-body text-lg font-bold text-primary-1 transition hover:bg-accent-2/90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Submitting...' : 'Submit Registration Application' }}
                        </button>
                        <p class="mt-3 text-center text-sm text-copy">
                            By submitting, you agree that your information will be processed by the organizing team for event coordination purposes.
                        </p>
                    </div>
                </form>
            </div>
        </section>
    </EventLayout>
</template>
