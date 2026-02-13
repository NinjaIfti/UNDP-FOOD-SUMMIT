<script setup>
import { ref, onMounted, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Load saved tab from localStorage or default to 'hero'
const activeTab = ref(localStorage.getItem('adminActiveTab') || 'hero');

// Save active tab to localStorage whenever it changes
watch(activeTab, (newTab) => {
    localStorage.setItem('adminActiveTab', newTab);
});

const form = useForm({
    hero_title: props.settings.hero_title,
    hero_date: props.settings.hero_date,
    hero_location: props.settings.hero_location,
    hero_description: props.settings.hero_description,
    countdown_date: props.settings.countdown_date,
    summit_intro_title: props.settings.summit_intro_title,
    summit_intro_text1: props.settings.summit_intro_text1,
    summit_intro_text2: props.settings.summit_intro_text2,
    coorganizers_title: props.settings.coorganizers_title,
    govt_logo: null,
    undp_logo: null,
    cofsa_logo: null,
    delete_govt_logo: false,
    delete_undp_logo: false,
    delete_cofsa_logo: false,
    video1_title: props.settings.video1_title,
    video1_url: props.settings.video1_url,
    video2_title: props.settings.video2_title,
    video2_url: props.settings.video2_url,
    cta_section_title: props.settings.cta_section_title,
    cta_section_subtitle: props.settings.cta_section_subtitle,
    cta_actions: props.settings.cta_actions || [
        {
            title: 'Register Here!',
            desc: 'Secure your spot at the first-ever Global Conscious Food Systems Summit in Bhutan.',
            href: '/register-summit',
            cta: 'Register Now',
            icon: 'ticket',
            color: 'bg-accent-2',
            textColor: 'text-primary-1',
            external: false,
        },
        {
            title: 'Call for Submissions',
            desc: 'Contribute your experience — respond to our Call for Submissions and help shape the Summit.',
            href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf',
            cta: 'Submit Now',
            icon: 'document',
            color: 'bg-primary-2',
            textColor: 'text-white',
            external: true,
        },
        {
            title: 'Help Us Spread the Word!',
            desc: 'Download our Communication Kit and help us disseminate the event across your networks.',
            href: '/media-kit',
            cta: 'Get the Kit',
            icon: 'megaphone',
            color: 'bg-accent-1',
            textColor: 'text-white',
            external: false,
        },
        {
            title: 'Have Any Questions?',
            desc: 'Check out the FAQs for answers to common questions about the Summit, travel, and registration.',
            href: '/#faq',
            cta: 'View FAQs',
            icon: 'question',
            color: 'bg-primary-1',
            textColor: 'text-white',
            external: false,
        },
    ],
    speakers_section_title: props.settings.speakers_section_title,
    speakers_section_subtitle: props.settings.speakers_section_subtitle,
    speakers: (props.settings.speakers || [
        { name: 'Speaker One', role: 'Policy & Governance', bio: 'Leading expert in food policy and governance with over 20 years of experience.', image: '/brand/img22.jpg' },
        { name: 'Speaker Two', role: 'Agroecology', bio: 'Pioneer in agroecological practices and sustainable farming systems.', image: '/brand/img29.jpg' },
        { name: 'Speaker Three', role: 'Indigenous Knowledge', bio: 'Advocate for indigenous food systems and traditional agricultural wisdom.', image: '/brand/img30.jpg' },
        { name: 'Speaker Four', role: 'Youth & Movement Building', bio: 'Youth leader driving food systems transformation across communities.', image: '/brand/img31.jpg' },
        { name: 'Speaker Five', role: 'Food Systems', bio: 'Researcher focused on conscious and regenerative food systems.', image: '/brand/img32.jpg' },
        { name: 'Speaker Six', role: 'Sustainability', bio: 'Environmental strategist working on sustainable food supply chains.', image: '/brand/img33.jpg' },
    ]).map(speaker => ({
        ...speaker,
        imageFile: null,
        deleteImage: false
    })),
    conscious_food_title: props.settings.conscious_food_title,
    conscious_food_description: props.settings.conscious_food_description,
    conscious_food_cofsa_text: props.settings.conscious_food_cofsa_text,
    outcomes_title: props.settings.outcomes_title,
    outcomes_description: props.settings.outcomes_description,
    outcomes_list: props.settings.outcomes_list || [
        'High-level dialogue on conscious food systems',
        'Knowledge exchange and innovation showcase',
        'Partnership-building across sectors',
        'Bhutan leadership platform for transformation',
        'Collective action and investment mobilization',
    ],
    bhutan_title: props.settings.bhutan_title,
    bhutan_text1: props.settings.bhutan_text1,
    bhutan_text2: props.settings.bhutan_text2,
    events_title: props.settings.events_title,
    events_subtitle: props.settings.events_subtitle,
    events_days: props.settings.events_days || [
        {
            date: '31 Aug',
            label: 'Day 1',
            title: 'Opening & Welcome',
            schedule: [
                { time: '08:00 – 09:00', title: 'Registration & Welcome Coffee', type: 'break', speaker: '' },
                { time: '09:00 – 09:30', title: 'Opening Ceremony', type: 'event', speaker: 'H.E. Prime Minister of Bhutan' },
            ],
        },
    ],
});

const tabs = [
    { id: 'hero', name: 'Hero', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { id: 'summit', name: 'Summit', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 'coorganizers', name: 'Co-organizers', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { id: 'videos', name: 'Videos', icon: 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z' },
    { id: 'cta', name: 'Call to Action', icon: 'M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122' },
    { id: 'speakers', name: 'Speakers', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
    { id: 'conscious', name: 'Conscious Food', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
    { id: 'outcomes', name: 'Outcomes', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 'bhutan', name: 'Bhutan', icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' },
    { id: 'events', name: 'Events', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
];

const submit = () => {
    // Create a clean copy of the form data
    const formData = { ...form.data() };
    
    // Prepare speaker image files for upload
    form.speakers.forEach((speaker, index) => {
        if (speaker.imageFile) {
            formData[`speaker_${index}_image`] = speaker.imageFile;
        }
        if (speaker.deleteImage) {
            formData[`speaker_${index}_delete_image`] = true;
        }
    });
    
    // Submit with all the data including files
    form.transform(() => formData).post(route('admin.homepage.update'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Homepage" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">Edit Homepage</h2>
                <a
                    href="/"
                    target="_blank"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-primary-1 px-4 py-2 font-body text-sm font-medium text-primary-1 transition hover:bg-primary-1/5 sm:w-auto"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Preview
                </a>
            </div>

            <div v-if="$page.props.flash.success" class="mb-4 rounded-lg bg-green-50 p-3 sm:mb-6 sm:p-4">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Tabs -->
            <div class="mb-4 rounded-xl bg-white p-2 shadow-sm sm:mb-6">
                <div class="flex overflow-x-auto">
                    <div class="flex gap-1 sm:gap-2">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            type="button"
                        @click="activeTab = tab.id"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 font-body text-xs font-medium transition sm:px-4 sm:py-3 sm:text-sm"
                        :class="activeTab === tab.id ? 'bg-accent-1 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon" />
                        </svg>
                        <span class="hidden sm:inline">{{ tab.name }}</span>
                    </button>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
                <!-- Hero Section -->
                <div v-show="activeTab === 'hero'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Hero Section</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.hero_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Date Range</label>
                            <input v-model="form.hero_date" type="text" required placeholder="e.g., 31 August – 4 September 2026" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Location</label>
                            <input v-model="form.hero_location" type="text" required placeholder="e.g., Bhutan" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Description</label>
                            <textarea v-model="form.hero_description" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Countdown Date & Time</label>
                            <input v-model="form.countdown_date" type="datetime-local" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            <p class="mt-1 font-body text-xs text-copy">Updates the countdown timer</p>
                        </div>
                    </div>
                </div>

                <!-- Summit Intro Section -->
                <div v-show="activeTab === 'summit'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Summit Introduction</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.summit_intro_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">First Paragraph</label>
                            <textarea v-model="form.summit_intro_text1" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Second Paragraph</label>
                            <textarea v-model="form.summit_intro_text2" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Conscious Food Section -->
                <div v-show="activeTab === 'conscious'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Conscious Food Systems</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.conscious_food_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Main Description</label>
                            <textarea v-model="form.conscious_food_description" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">CoFSA Description</label>
                            <textarea v-model="form.conscious_food_cofsa_text" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Outcomes Section -->
                <div v-show="activeTab === 'outcomes'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Outcomes</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.outcomes_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Description</label>
                            <textarea v-model="form.outcomes_description" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>

                        <div>
                            <label class="mb-3 block font-body text-sm font-medium text-ink">Outcome Cards</label>
                            <div class="space-y-3">
                                <div v-for="(outcome, index) in form.outcomes_list" :key="index" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent-2 font-title text-xs font-bold text-primary-1">{{ index + 1 }}</span>
                                    <input 
                                        v-model="form.outcomes_list[index]" 
                                        type="text" 
                                        placeholder="Enter outcome text"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" 
                                    />
                                    <button
                                        type="button"
                                        @click="form.outcomes_list.splice(index, 1)"
                                        class="rounded-md bg-red-100 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="form.outcomes_list.push('')"
                                class="mt-3 w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-title text-sm font-semibold text-gray-600 transition hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1"
                            >
                                + Add New Outcome
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Co-organizers Section -->
                <div v-show="activeTab === 'coorganizers'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Co-organizers</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.coorganizers_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>

                        <!-- Government Logo -->
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">Government of Bhutan Logo</h4>
                            <div v-if="settings.govt_logo" class="mb-3">
                                <img :src="settings.govt_logo" alt="Government Logo" class="h-24 w-auto object-contain" />
                                <button
                                    type="button"
                                    @click="form.delete_govt_logo = !form.delete_govt_logo"
                                    class="mt-2 rounded-md px-3 py-1 text-sm font-medium transition"
                                    :class="form.delete_govt_logo ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                >
                                    {{ form.delete_govt_logo ? 'Undo Delete' : 'Delete Logo' }}
                                </button>
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                @change="form.govt_logo = $event.target.files[0]"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-accent-1 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-1/90"
                            />
                        </div>

                        <!-- UNDP Logo -->
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">UNDP Logo</h4>
                            <div v-if="settings.undp_logo" class="mb-3">
                                <img :src="settings.undp_logo" alt="UNDP Logo" class="h-24 w-auto object-contain" />
                                <button
                                    type="button"
                                    @click="form.delete_undp_logo = !form.delete_undp_logo"
                                    class="mt-2 rounded-md px-3 py-1 text-sm font-medium transition"
                                    :class="form.delete_undp_logo ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                >
                                    {{ form.delete_undp_logo ? 'Undo Delete' : 'Delete Logo' }}
                                </button>
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                @change="form.undp_logo = $event.target.files[0]"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-accent-1 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-1/90"
                            />
                        </div>

                        <!-- CoFSA Logo -->
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">CoFSA Logo</h4>
                            <div v-if="settings.cofsa_logo" class="mb-3">
                                <img :src="settings.cofsa_logo" alt="CoFSA Logo" class="h-24 w-auto object-contain" />
                                <button
                                    type="button"
                                    @click="form.delete_cofsa_logo = !form.delete_cofsa_logo"
                                    class="mt-2 rounded-md px-3 py-1 text-sm font-medium transition"
                                    :class="form.delete_cofsa_logo ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                >
                                    {{ form.delete_cofsa_logo ? 'Undo Delete' : 'Delete Logo' }}
                                </button>
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                @change="form.cofsa_logo = $event.target.files[0]"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-accent-1 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-1/90"
                            />
                        </div>

                        <p class="font-body text-sm text-copy">Upload new logos or delete existing ones. Logos will be displayed side by side on the website.</p>
                    </div>
                </div>

                <!-- Videos Section -->
                <div v-show="activeTab === 'videos'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Videos</h3>
                    
                    <div class="space-y-6">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">Video 1</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Title</label>
                                    <input v-model="form.video1_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">YouTube Embed URL</label>
                                    <input v-model="form.video1_url" type="url" placeholder="https://www.youtube.com/embed/..." class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">Video 2</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Title</label>
                                    <input v-model="form.video2_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">YouTube Embed URL</label>
                                    <input v-model="form.video2_url" type="url" placeholder="https://www.youtube.com/embed/..." class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Section -->
                <div v-show="activeTab === 'cta'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Call to Action</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.cta_section_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Subtitle</label>
                            <textarea v-model="form.cta_section_subtitle" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>

                        <div v-for="(action, index) in form.cta_actions" :key="index" class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">Card {{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="form.cta_actions.splice(index, 1)"
                                    class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                >
                                    Delete Card
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Title</label>
                                    <input v-model="action.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Description</label>
                                    <textarea v-model="action.desc" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Link</label>
                                        <input v-model="action.href" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Button Text</label>
                                        <input v-model="action.cta" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Icon</label>
                                        <select v-model="action.icon" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                            <option value="ticket">Ticket</option>
                                            <option value="document">Document</option>
                                            <option value="megaphone">Megaphone</option>
                                            <option value="question">Question</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Link Type</label>
                                        <select v-model="action.external" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                            <option :value="false">Internal (Same Site)</option>
                                            <option :value="true">External (New Tab)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="form.cta_actions.push({ title: '', desc: '', href: '', cta: '', icon: 'ticket', color: 'bg-accent-1', textColor: 'text-white', external: false })"
                            class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-title text-sm font-semibold text-gray-600 transition hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1"
                        >
                            + Add New Card
                        </button>
                    </div>
                </div>

                <!-- Speakers Section -->
                <div v-show="activeTab === 'speakers'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Featured Speakers</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.speakers_section_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>

                        <div v-for="(speaker, index) in form.speakers" :key="index" class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">Speaker {{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="form.speakers.splice(index, 1)"
                                    class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                >
                                    Delete Speaker
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Name</label>
                                        <input v-model="speaker.name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Role</label>
                                        <input v-model="speaker.role" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Bio</label>
                                    <textarea v-model="speaker.bio" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Speaker Image</label>
                                    <div class="mt-1 space-y-2">
                                        <div v-if="speaker.image && !speaker.deleteImage" class="flex items-center gap-3">
                                            <img :src="speaker.image" alt="Current image" class="h-16 w-16 rounded-full border-2 border-gray-200 object-cover" />
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-600">Current image</p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="speaker.deleteImage = true; speaker.imageFile = null;"
                                                class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                        <div v-if="speaker.deleteImage" class="rounded-lg bg-red-50 p-3">
                                            <p class="text-xs text-red-700">Image will be removed on save</p>
                                            <button
                                                type="button"
                                                @click="speaker.deleteImage = false"
                                                class="mt-2 text-xs font-medium text-red-600 underline hover:no-underline"
                                            >
                                                Undo
                                            </button>
                                        </div>
                                        <div v-if="speaker.imageFile" class="rounded-lg bg-green-50 p-3">
                                            <p class="text-xs text-green-700">New image selected: {{ speaker.imageFile.name }}</p>
                                            <button
                                                type="button"
                                                @click="speaker.imageFile = null; $event.target.closest('div').parentElement.querySelector('input[type=file]').value = ''"
                                                class="mt-2 text-xs font-medium text-green-600 underline hover:no-underline"
                                            >
                                                Clear
                                            </button>
                                        </div>
                                        <input 
                                            type="file" 
                                            accept="image/*"
                                            @change="(e) => { speaker.imageFile = e.target.files[0]; speaker.deleteImage = false; }"
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body file:mr-4 file:rounded-md file:border-0 file:bg-accent-1 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-1/90 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                        />
                                        <p class="text-xs text-gray-500">Upload a new image or keep the current one</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="form.speakers.push({ name: '', role: '', bio: '', image: '', imageFile: null, deleteImage: false })"
                            class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-title text-sm font-semibold text-gray-600 transition hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1"
                        >
                            + Add New Speaker
                        </button>
                    </div>
                </div>

                <!-- Why Bhutan Section -->
                <div v-show="activeTab === 'bhutan'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Why Bhutan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.bhutan_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">First Paragraph</label>
                            <textarea v-model="form.bhutan_text1" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Second Paragraph</label>
                            <textarea v-model="form.bhutan_text2" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Events Section -->
                <div v-show="activeTab === 'events'" class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Events Plan</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.events_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Subtitle</label>
                            <input v-model="form.events_subtitle" type="text" placeholder="e.g., 31 August – 4 September 2026 · Thimphu, Bhutan" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>

                        <!-- Days -->
                        <div>
                            <label class="mb-3 block font-body text-sm font-medium text-ink">Event Days</label>
                            <div class="space-y-4">
                                <div v-for="(day, dayIndex) in form.events_days" :key="dayIndex" class="rounded-lg border-2 border-gray-200 p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h4 class="font-title text-sm font-bold text-primary-1">{{ day.label || `Day ${dayIndex + 1}` }}</h4>
                                        <button
                                            type="button"
                                            @click="form.events_days.splice(dayIndex, 1)"
                                            class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                        >
                                            Delete Day
                                        </button>
                                    </div>
                                    
                                    <div class="mb-4 grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Date</label>
                                            <input v-model="day.date" type="text" placeholder="31 Aug" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Label</label>
                                            <input v-model="day.label" type="text" placeholder="Day 1" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Title</label>
                                            <input v-model="day.title" type="text" placeholder="Opening & Welcome" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                    </div>

                                    <!-- Schedule Items -->
                                    <div class="space-y-2">
                                        <label class="block font-body text-xs font-medium text-ink">Schedule</label>
                                        <div v-for="(item, itemIndex) in day.schedule" :key="itemIndex" class="flex gap-2 rounded border border-gray-200 bg-gray-50 p-3">
                                            <div class="flex-1 grid grid-cols-2 gap-2">
                                                <input v-model="item.time" type="text" placeholder="09:00 – 10:00" class="rounded border border-gray-300 px-2 py-1 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20" />
                                                <input v-model="item.title" type="text" placeholder="Event title" class="rounded border border-gray-300 px-2 py-1 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20" />
                                                <select v-model="item.type" class="rounded border border-gray-300 px-2 py-1 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20">
                                                    <option value="speaker">Speaker</option>
                                                    <option value="event">Event</option>
                                                    <option value="break">Break</option>
                                                </select>
                                                <input v-model="item.speaker" type="text" placeholder="Speaker name (optional)" class="rounded border border-gray-300 px-2 py-1 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20" />
                                            </div>
                                            <button
                                                type="button"
                                                @click="day.schedule.splice(itemIndex, 1)"
                                                class="rounded bg-red-100 px-2 text-xs font-medium text-red-700 hover:bg-red-200"
                                            >
                                                ✕
                                            </button>
                                        </div>
                                        <button
                                            type="button"
                                            @click="day.schedule.push({ time: '', title: '', type: 'event', speaker: '' })"
                                            class="w-full rounded border-2 border-dashed border-gray-300 bg-gray-50 py-2 text-xs font-semibold text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1"
                                        >
                                            + Add Schedule Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="form.events_days.push({ date: '', label: '', title: '', schedule: [] })"
                                class="mt-3 w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-title text-sm font-semibold text-gray-600 transition hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1"
                            >
                                + Add New Day
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end sm:gap-4">
                    <a
                        :href="route('admin.dashboard')"
                        class="w-full rounded-lg border border-gray-300 px-6 py-3 text-center font-title font-semibold text-gray-700 transition hover:bg-gray-50 sm:w-auto"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-accent-1 px-6 py-3 font-title font-semibold text-white transition hover:bg-accent-1/90 disabled:opacity-50 sm:w-auto"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
