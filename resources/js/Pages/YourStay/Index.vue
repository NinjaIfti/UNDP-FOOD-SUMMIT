<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const activeTab = ref('air-travel');
// for the tabs for window
const tabsContainerRef = ref(null);

const scrollTabs = (amount) => {
    if (tabsContainerRef.value) {
        tabsContainerRef.value.scrollBy({ left: amount, behavior: 'smooth' });
    }
};
const accommodationFilter = ref('all');

const tabs = [
    { id: 'air-travel', label: 'Air Travel', icon: 'plane' },
    { id: 'visa', label: 'Visa & Entry', icon: 'document' },
    { id: 'arrival', label: 'Upon Arrival', icon: 'suitcase' },
    { id: 'field-visits', label: 'Field Visits', icon: 'map-pin' },
    { id: 'accommodation', label: 'Accommodation', icon: 'building' },
    { id: 'extend', label: 'Extend Stay', icon: 'calendar-plus' },
    { id: 'companions', label: 'Travel With Companions', icon: 'users' },
    { id: 'cancellation', label: 'Cancellation & Refund', icon: 'document' },
    // { id: 'flight-booking', label: 'Flight Booking', icon: 'plane' },
    { id: 'code-of-conduct', label: 'Code of Conduct', icon: 'document' },
    { id: 'liability-disclaimer', label: 'Liability Disclaimer', icon: 'document' },
    { id: 'programme-changes', label: 'Changes to the Programme', icon: 'calendar-plus' },
    { id: 'weather-packing', label: 'Weather & Packing', icon: 'suitcase' },

];

const hotels = computed(() => props.settings?.hotels || []);
const accommodationMainHotelNote = computed(() => props.settings?.accommodation_main_hotel_note || '');

const normalizeHotelCategory = (category) => {
    const c = String(category || '').toLowerCase();
    if (c === '3-star' || c === 'budget') return '3-star';
    if (c === '4-star' || c === 'mid-range') return '4-star';
    if (c === '5-star' || c === 'premium') return '5-star';
    return '4-star';
};

const filteredHotels = computed(() => {
    if (accommodationFilter.value === 'all') {
        return hotels.value;
    }
    return hotels.value.filter(hotel => normalizeHotelCategory(hotel.category) === accommodationFilter.value);
});

const fieldVisitsInfo = computed(() => {
    const raw = props.settings?.field_visits_info || '';
    return raw.replace(/^optional\s*add-?on\s*:\s*/i, '');
});

const airTravelIntro = computed(
    () =>
        props.settings?.air_travel_intro ||
        'Summit participants are responsible for booking their own international flights to Bhutan.'
);

const providerUrl = (name) => {
    const n = (name || '').toLowerCase();
    if (n.includes('bhutan telecom') || n === 'bt') return 'https://www.bt.bt/';
    if (n.includes('tashicell') || n.includes('tashi cell')) return 'https://www.tashicell.com/';
    return '';
};

const heroTitle = computed(() => {
    if (props.settings?.yourstay_hero_title) {
        return props.settings.yourstay_hero_title;
    }
    return props.settings?.participation_costs_title || 'WHAT ARE THE PARTICIPATION COSTS?';
});

const heroSubtitle = computed(() => {
    if (props.settings?.yourstay_hero_subtitle) {
        return props.settings.yourstay_hero_subtitle;
    }
    return (
        props.settings?.participation_costs_description ||
        'Participation in the Conscious Food Systems Summit requires an entrance ticket, which helps cover the essential costs of organizing and delivering the event, including venue arrangements, meals, local logistics, and administrative coordination.'
    );
});

const heroSlides = computed(() => {
    const base = props.settings?.yourstay_hero_image || '/brand/img34.jpg';
    const extras = Array.isArray(props.settings?.yourstay_hero_slides)
        ? props.settings.yourstay_hero_slides.filter(src => src && typeof src === 'string' && src.trim().length > 0)
        : [];
    const all = [base, ...extras];
    return all.filter((src, index) => all.indexOf(src) === index);
});

const activeHeroSlide = ref(0);

let heroInterval;

onMounted(() => {
    heroInterval = setInterval(() => {
        if (heroSlides.value.length > 1) {
            activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.value.length;
        }
    }, 7000);

    // Always land users on the Air Travel tab/navigation area when this page opens.
    nextTick(() => {
        const tabsSection = document.getElementById('yourstay-tabs');
        if (tabsSection) {
            tabsSection.scrollIntoView({ behavior: 'auto', block: 'start' });
        }
    });
});

onUnmounted(() => {
    if (heroInterval) {
        clearInterval(heroInterval);
    }
});
</script>

<template>

    <Head title="Your Stay in Bhutan" />
    <EventLayout>

        <!-- Hero header -->
        <section class="relative bg-[#F4F1EA] py-0">
            <div class="w-full">
                <div class="overflow-hidden rounded-none">
                    <div class="relative w-full min-h-[35vh] sm:min-h-[40vh] lg:min-h-[45vh]">
                        <div v-for="(slide, index) in heroSlides" :key="slide + index"
                            class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                            :class="index === activeHeroSlide ? 'opacity-100' : 'opacity-0'">
                            <img :src="slide" alt="" class="h-full w-full object-cover" loading="lazy" />
                        </div>
                        <div class="absolute inset-0 bg-black/45" />
                        <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-8">
                            <div class="max-w-4xl text-center">
                                <h1
                                    class="font-title text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl lg:text-5xl">
                                    {{ heroTitle }}
                                </h1>
                                <p
                                    class="mx-auto mt-4 text-center font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
                                    {{ heroSubtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="yourstay-tabs" class="border-b border-gray-200 bg-[#F4F1EA]">
            <div class="mx-auto px-4 py-3 sm:py-4 sm:px-6 lg:px-8">
    <div class="relative">
        <!-- Left scroll button (desktop only) -->
        <button
            v-if="tabsContainerRef && tabsContainerRef.scrollWidth > tabsContainerRef.clientWidth"
            @click="scrollTabs(-200)"
            class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-accent-2/20 rounded-full p-2 shadow-md backdrop-blur-sm transition-all hidden lg:flex"
            aria-label="Scroll left"
        >
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Tabs container (scrollable on all screens) -->
        <div
            ref="tabsContainerRef"
            class="flex flex-nowrap gap-2 sm:gap-3 overflow-x-auto pb-3 pl-2 pr-8 sm:px-0 tab-scroll-container scroll-smooth"
        >
            <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 font-body text-sm font-semibold transition sm:px-5 sm:py-2.5 sm:text-base first:ml-2 last:mr-2 sm:first:ml-4 sm:last:mr-4"
                :class="activeTab === tab.id ? 'bg-accent-2 text-white' : 'bg-gray-100 text-ink hover:bg-gray-200'"
                @click="activeTab = tab.id"
            >
                <!-- SVG icons (same as your existing code) -->
                <svg v-if="tab.icon === 'plane'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <svg v-else-if="tab.icon === 'document'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else-if="tab.icon === 'suitcase'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <svg v-else-if="tab.icon === 'building'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <svg v-else-if="tab.icon === 'calendar-plus'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <svg v-else-if="tab.icon === 'users'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>{{ tab.label }}</span>
            </button>
        </div>

        <!-- Right scroll button (desktop only) -->
        <button
            v-if="tabsContainerRef && tabsContainerRef.scrollWidth > tabsContainerRef.clientWidth"
            @click="scrollTabs(200)"
            class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-accent-2/20 rounded-full p-2 shadow-md backdrop-blur-sm transition-all hidden lg:flex"
            aria-label="Scroll right"
        >
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
        </section>

        <section class="bg-[#F4F1EA] py-10 sm:py-12 lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-show="activeTab === 'field-visits'" class="mx-auto max-w-3xl text-center">
                    <h2 class="font-title text-4xl font-bold text-gray-900 sm:text-5xl">
                        {{ settings.field_visits_title || 'Will There Be Field Visits?' }}
                    </h2>

                    <div class="mx-auto mt-6 max-w-2xl rounded-none border border-accent-2 bg-accent-2/10 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-2 font-body text-xs font-bold text-primary-1">
                                i
                            </div>
                            <div>
                                <p class="font-body text-base text-gray-800">
                                    {{ fieldVisitsInfo || 'Field visits on 31 August are available at an additional cost and can be selected during registration.' }}
                                </p>
                                <p v-if="settings.field_visits_pending"
                                    class="mt-2 font-body text-sm italic text-gray-500">
                                    {{ settings.field_visits_pending }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.field_visits_expectations && settings.field_visits_expectations.length > 0"
                        class="mx-auto mt-8 max-w-2xl rounded-none border border-primary-2/20 bg-white p-6">
                        <h3 class="font-title text-lg font-bold text-gray-900 sm:text-xl">
                            What to Expect
                        </h3>
                        <ul class="mt-4 list-none space-y-3 font-body text-gray-800 text-left">
                            <li v-for="(expectation, index) in settings.field_visits_expectations" :key="index"
                                class="flex items-start justify-start gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-primary-2" />
                                <span>{{ expectation }}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- NEW: Field Visit Cancellation -->
                    <div class="mx-auto mt-8 max-w-2xl rounded-none border border-accent-2/30 bg-accent-2/5 p-6 text-left">
                        <h3 class="font-title text-xl font-bold text-gray-900">Field Visit Cancellation</h3>
                        <p class="mt-2 font-body text-gray-700">
                            Field visits scheduled for 31 August 2026 are optional and require separate additional payment that can be processed while purchasing the ticket.
                        </p>

                        <div class="mt-4">
                            <h4 class="font-title font-semibold text-gray-900">Cancellation terms:</h4>
                            <ul class="mt-2 space-y-1 font-body text-gray-700 list-disc pl-5">
                                <li>Cancellation 30 days or more before the Summit: <strong>75% refund</strong></li>
                                <li>Cancellation less than 30 days before the Summit: <strong>No refund</strong></li>
                            </ul>
                        </div>

                        <div class="mt-4 rounded-none bg-white/50 p-4 border-l-4 border-accent-2">
                            <p class="font-body text-gray-800">
                                Field visits may be cancelled by organizers in case of:
                            </p>
                            <ul class="mt-2 space-y-1 font-body text-gray-700 list-disc pl-5">
                                <li>Severe weather conditions</li>
                                <li>Safety or security concerns</li>
                                <li>Insufficient minimum participation</li>
                            </ul>
                            <p class="mt-3 font-body text-gray-800">
                                In such cases, participants will receive a <strong>full refund</strong> of the field visit fee.
                            </p>
                        </div>
                    </div>
                    <!-- Force Majeure Tab -->
                    <div class="mx-auto mt-8 max-w-2xl rounded-none border border-accent-2/30 bg-accent-2/5 p-6 text-left">
                        <h2 class="font-title text-3xl font-bold text-gray-900">11. Force Majeure</h2>

                        <div>
                            <p class="font-body text-gray-800">
                                The organizers shall not be held liable for cancellation, postponement, or modification of the Summit due to circumstances beyond reasonable control, including but not limited to:
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <ul class="space-y-2 font-body text-gray-700 list-disc pl-5">
                                    <li>Natural disasters</li>
                                    <li>Government restrictions</li>
                                    <li>Public health emergencies</li>
                                    <li>Political unrest / armed and geopolitical conflicts / wars / terrorism</li>
                                </ul>
                                <br>
                                <ul class="space-y-2 font-body text-gray-700 list-disc pl-5">
                                    <li>Airline shutdowns</li>
                                </ul>
                            </div>

                            <div class="bg-accent-2/10 border-l-4 border-accent-2 p-4 mt-4">
                                <p class="font-body font-semibold text-gray-900">In such cases:</p>
                                <ul class="mt-2 space-y-1 font-body text-gray-700 list-disc pl-5">
                                    <li>The Summit may be postponed or converted to a hybrid/online format.</li>
                                    <li>Ticket refunds will be assessed based on non-recoverable costs already incurred.</li>
                                </ul>
                            </div>

                            <div class="mt-2 flex items-start gap-3 text-accent-2">
                                <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-body text-gray-800">The organizers will communicate transparently and promptly in such situations.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FLIGHT BOOKING TAB -->
                <div v-show="activeTab === 'air-travel'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Flight Booking to Bhutan</h2>

                    <!-- Intro / responsibility -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-4">
                        <p class="font-body text-gray-800">
                            Participants are responsible for arranging and managing their own international flights.
                            For travel to Bhutan, please book your flights through <strong>Drukair</strong> or <strong>Bhutan Airlines</strong> (see details below).
                        </p>
                        <p class="font-body text-gray-800">
                            Bhutan’s only international airport is <strong>Paro International Airport</strong>, and all international flights arrive here.
                        </p>
                        <div class="bg-accent-2/10 p-4 border-l-4 border-accent-2">
                            <p class="font-body font-semibold text-gray-900">Direct flights to Bhutan are available from:</p>
                            <ul class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-1 font-body text-gray-700 list-disc pl-5">
                                <li>Bangkok, Thailand</li>
                                <li>Singapore</li>
                                <li>Kathmandu, Nepal</li>
                                <li>Dhaka, Bangladesh</li>
                                <li>Delhi, Kolkata, Guwahati, Bagdogra, Gaya (India)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Drukair section -->
                    <div class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-primary-2/10 px-6 py-4 border-b border-gray-200">
                            <h3 class="font-title text-2xl font-semibold text-gray-900">a) Flying through Drukair</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <p class="font-body text-gray-800">
                                You may visit their official website for general information and booking support.
                                <span class="bg-yellow-50 text-yellow-800 px-2 py-0.5 rounded text-sm">Note: Online ticket purchase for Bhutanese and SAARC nationals is temporarily unavailable.</span>
                            </p>
                            <p class="font-body text-gray-800">For bookings in your applicable fare classes, please contact the airlines directly through the contact channels below.</p>

                            <!-- Contact info -->
                            <div class="bg-gray-50 p-4 rounded-none border border-gray-200 space-y-2">
                                <h4 class="font-title font-semibold text-gray-900">Contact information for Drukair:</h4>
                                <ul class="space-y-1 font-body text-gray-700">
                                    <li>🌐 Official website: <a href="https://drukair.com.bt/" target="_blank" class="text-accent-1 hover:underline">https://drukair.com.bt/</a></li>
                                    <li>📧 Reservations Email: <a href="mailto:reservation@drukair.com.bt" class="text-accent-1 hover:underline">reservation@drukair.com.bt</a></li>
                                    <li>💬 WhatsApp: <a href="https://wa.me/97517131300" target="_blank" class="text-accent-1 hover:underline">+975 17131300</a></li>
                                    <li>📞 Toll-Free (Bhutan): 1300</li>
                                    <li>🌍 International: +975 8 276430</li>
                                </ul>
                            </div>

                            <!-- Guidelines -->
                            <div class="bg-blue-50 p-4 border-l-4 border-blue-500 space-y-2">
                                <p class="font-body font-semibold text-gray-900">Important guidelines for Drukair bookings:</p>
                                <ul class="space-y-1 font-body text-gray-700 list-disc pl-5">
                                    <li>Online booking is currently not available for Bhutanese and SAARC nationals.</li>
                                    <li>All passengers, including SAARC passport holders, must contact the company's reservations email with:</li>
                                    <ul class="list-circle pl-5 space-y-0.5">
                                        <li>Passport information</li>
                                        <li>Copy of passport</li>
                                        <li>Intended travel dates</li>
                                    </ul>
                                    <li>Upon processing your request, the airline will issue a PNR (booking reference number).</li>
                                    <li>To complete your booking and proceed with ticket payment, use the “Manage Booking” option on the Drukair website and enter your PNR details.</li>
                                    <li>Alternatively, contact the Call Center or visit the nearest Sales Office.</li>
                                </ul>
                            </div>

                            <!-- Drukair Date Change Policy -->
                            <div class="border border-gray-200 rounded-none p-4">
                                <h4 class="font-title text-lg font-semibold text-gray-900 mb-3">Drukair Flight Date Change Policy</h4>
                                <div class="grid md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="font-semibold text-gray-800">Business Class</p>
                                        <ul class="mt-1 space-y-0.5 font-body text-gray-700">
                                            <li>• 2 days & above: No charges applicable</li>
                                            <li>• 1 day or less: USD 20 applicable</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Economy Class</p>
                                        <ul class="mt-1 space-y-0.5 font-body text-gray-700">
                                            <li>• 4 days and above: No charges applicable</li>
                                            <li>• 3 days or less: USD 20 applicable</li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="mt-3 font-body text-sm text-gray-600">
                                    Please note that a fare difference may apply if the airfare for your new travel dates has changed.
                                    For more information regarding flight changes and cancellations please visit their website.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bhutan Airlines section -->
                    <div class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-accent-2/10 px-6 py-4 border-b border-gray-200">
                            <h3 class="font-title text-2xl font-semibold text-gray-900">b) Flying through Bhutan Airlines</h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <p class="font-body text-gray-800">
                                It is recommended to use their official website (<a href="https://www.bhutanairlines.bt" target="_blank" class="text-accent-1 hover:underline">www.bhutanairlines.bt</a>), which allows quick and secure booking.
                                Unlike Drukair, they do not have any issue in terms of SAARC nationals booking online via their website.
                            </p>
                            <p class="font-body text-gray-800">
                                If, in rare cases, booking through the website does not work, you may send your travel sector, travel date, and passenger details to <a href="mailto:info@bhutanairlines.bt" class="text-accent-1 hover:underline">info@bhutanairlines.bt</a>, and they will assist you with the reservation.
                            </p>

                            <!-- Contact info -->
                            <div class="bg-gray-50 p-4 rounded-none border border-gray-200 space-y-2">
                                <h4 class="font-title font-semibold text-gray-900">Contact information for Bhutan Airlines:</h4>
                                <ul class="space-y-1 font-body text-gray-700">
                                    <li>🌐 Official website: <a href="https://www.bhutanairlines.bt" target="_blank" class="text-accent-1 hover:underline">www.bhutanairlines.bt</a></li>
                                    <li>📧 Reservations Email: <a href="mailto:info@bhutanairlines.bt" class="text-accent-1 hover:underline">info@bhutanairlines.bt</a> (their team responds promptly)</li>
                                    <li>📞 Call: <a href="tel:+97577106011" class="text-accent-1 hover:underline">+975-77106011</a> – Available for both international and domestic calls</li>
                                    <li>📞 Toll-Free (within Bhutan): Dial 1234</li>
                                </ul>
                                <div class="mt-2 text-sm text-gray-600 bg-white p-3 border border-gray-100">
                                    <p><strong>Regarding date changes:</strong> You must inform Bhutan Airlines at least 72 hours prior to your scheduled departure. A fare difference may apply. Any changes made within 72 hours incur a charge of USD 25 (approx. 2,100 BTN).</p>
                                </div>
                            </div>

                            <!-- Bhutan Airlines Refund/Change Policy -->
                            <div class="border border-gray-200 rounded-none p-4">
                                <h4 class="font-title text-lg font-semibold text-gray-900 mb-3">Bhutan Airlines Flight Date Change Policy</h4>
                                <ul class="space-y-2 font-body text-gray-700">
                                    <li class="flex items-start gap-2"><span class="text-green-600">✓</span> <span><strong>More than 31 days away:</strong> 100% refund after deducting admin fee of USD 25 per person.</span></li>
                                    <li class="flex items-start gap-2"><span class="text-green-600">✓</span> <span><strong>Less than 30 days but more than 11 days:</strong> 75% of base fare + taxes.</span></li>
                                    <li class="flex items-start gap-2"><span class="text-yellow-600">⚠️</span> <span><strong>Less than 10 days but more than 72 hours:</strong> 50% of base fare + taxes.</span></li>
                                    <li class="flex items-start gap-2"><span class="text-red-600">✗</span> <span><strong>Less than 72 hours or no-show:</strong> Only taxes refunded.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- General recommendations & disclaimer -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-4">
                        <div class="flex gap-3 items-start">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-body font-semibold text-gray-900">Strong recommendations:</p>
                                <ul class="mt-2 space-y-1 font-body text-gray-700 list-disc pl-5">
                                    <li>Initiate your booking process well in advance to ensure availability and smooth travel arrangements.</li>
                                    <li><strong>Do not book flights until your visa is granted.</strong></li>
                                    <li>Purchase flexible or refundable flight tickets where possible.</li>
                                    <li>Obtain comprehensive travel insurance covering cancellation, delay, medical emergencies, and repatriation.</li>
                                    <li>Verify transit visa requirements before booking flights.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Disclaimer: organizers not responsible -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-2">
                                <h3 class="font-title text-xl font-semibold text-gray-900">Flight Changes, Travel Disruptions & Cancellation</h3>
                                <p class="font-body text-gray-800">The organizers are <strong>not responsible</strong> for:</p>
                                <ul class="space-y-1 font-body text-gray-700 list-disc pl-5">
                                    <li>International airline cancellations or delays</li>
                                    <li>Missed connections</li>
                                    <li>Schedule changes</li>
                                    <li>Weather-related disruptions</li>
                                    <li>Transit visa issues</li>
                                    <li>Denied boarding by airlines</li>
                                </ul>
                                <p class="font-body text-gray-800 mt-3">
                                    Participants are strongly encouraged to:
                                </p>
                                <ul class="space-y-1 font-body text-gray-700 list-disc pl-5">
                                    <li>Purchase flexible or refundable flight tickets where possible</li>
                                    <li>Obtain comprehensive travel insurance covering cancellation, delay, medical emergencies, and repatriation</li>
                                    <li>Verify transit visa requirements before booking flights</li>
                                    
                                </ul>
                                <p class="font-body text-gray-800 mt-3">
                                    Summit ticket fees remain non-refundable in case of travel disruptions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visa Tab -->
                <div v-show="activeTab === 'visa'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">
                        {{ settings.visa_title || 'Visa & Entry Process' }}
                    </h2>
                    <div>
                        <p><strong>Participants are expected to arrive in the country between 28 and 30 August,
                                depending on flight
                                availability. Please note that arrivals prior to 28 August will not be
                                accommodated.</strong></p>
                    </div>
                    <!-- Summit Visa Support -->
                    <div v-if="settings.visa_support_points && settings.visa_support_points.length > 0"
                        class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-none p-6 shadow-md">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="font-title text-2xl font-bold text-gray-900">
                                    {{ settings.visa_support_title || 'Summit Visa Support' }}
                                </h3>
                                <div class="space-y-3 font-body text-gray-800">
                                    <div v-for="(point, index) in settings.visa_support_points" :key="index"
                                        class="flex items-start gap-3">
                                        <span class="text-accent-2 font-bold">✓</span>
                                        <p v-html="point"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div v-if="settings.visa_important_points && settings.visa_important_points.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.visa_important_title || 'Important Notes' }}
                                </h3>
                                <ul class="space-y-3 font-body text-gray-700">
                                    <li v-for="(point, index) in settings.visa_important_points" :key="index"
                                        class="flex items-start gap-3">
                                        <span class="text-primary-1 mt-1">•</span>
                                        <span v-html="point"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Applying Independently -->
                    <div v-if="settings.visa_independent_points && settings.visa_independent_points.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.visa_independent_title || 'Applying Independently' }}
                        </h3>
                        <p v-if="settings.visa_independent_description" class="font-body text-gray-700 mb-4">
                            {{ settings.visa_independent_description }}
                        </p>
                        <div class="space-y-3 mb-6">
                            <div v-for="(point, index) in settings.visa_independent_points" :key="index"
                                class="flex items-start gap-3 font-body text-gray-700">
                                <span class="text-primary-1 mt-1">•</span>
                                <span v-html="point"></span>
                            </div>
                        </div>
                        <a v-if="settings.visa_independent_button_url && settings.visa_independent_button_text"
                            :href="settings.visa_independent_button_url" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-1 text-white rounded-lg hover:bg-primary-1/90 transition-colors">
                            {{ settings.visa_independent_button_text }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Arrival Tab -->
                <div v-show="activeTab === 'arrival'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.arrival_title || 'Upon Arrival in Bhutan' }}
                    </h2>
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                      
                        <p><strong>SIM cards will be available for purchase at the airport upon arrival for participants.</strong></p>
                        <p><strong>Currency exchange services will be provided at the airport, please change before leaving the airport to be able to pay for taxis, food, and places that do not accept cards during the duration of your stay in Bhutan.</strong></p>       
                      
                   </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="bg-white rounded-none border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-accent-2/20 rounded-none">
                                    <svg class="w-6 h-6 text-accent-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.mobile_title || 'Mobile & Internet' }}
                                </h3>
                            </div>
                            <div class="space-y-3 font-body text-gray-700">
                                <p v-if="settings.mobile_description">
                                    {{ settings.mobile_description }}
                                </p>
                                <div v-if="settings.mobile_providers && settings.mobile_providers.length > 0"
                                    class="bg-gray-50 rounded-none p-4 space-y-2">
                                    <p class="font-medium text-gray-900">Providers:</p>
                                    <ul class="space-y-1">
                                        <li v-for="(provider, index) in settings.mobile_providers" :key="index"
                                            class="flex items-start gap-2">
                                            <span class="text-primary-1">•</span>
                                            <a v-if="providerUrl(provider)" :href="providerUrl(provider)"
                                                target="_blank" rel="noopener noreferrer"
                                                class="text-primary-1 font-semibold hover:underline">
                                                {{ provider }}
                                            </a>
                                            <span v-else>{{ provider }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="settings.mobile_cost"
                                    class="bg-accent-2/10 border border-accent-2/30 rounded-none p-4">
                                    <p class="font-medium text-gray-900">{{ settings.mobile_cost }}</p>
                                    <p v-if="settings.mobile_cost_note" class="text-sm text-gray-600 mt-1">
                                        {{ settings.mobile_cost_note }}
                                    </p>
                                </div>
                                <p v-if="settings.mobile_tip" class="text-sm text-gray-600">
                                    {{ settings.mobile_tip }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-white rounded-none border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-primary-2/20 rounded-none">
                                    <svg class="w-6 h-6 text-primary-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                    </svg>
                                </div>
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.currency_title || 'Currency Exchange' }}
                                </h3>
                            </div>
                            <div class="space-y-3 font-body text-gray-700">
                                <div v-if="settings.currency_local" class="bg-gray-50 rounded-none p-4">
                                    <p class="font-medium text-gray-900 mb-2">Local Currency:</p>
                                    <p class="text-lg font-bold text-primary-1">
                                        {{ settings.currency_local }}
                                    </p>
                                    <p v-if="settings.currency_note" class="text-sm text-gray-600 mt-1">
                                        {{ settings.currency_note }}
                                    </p>
                                </div>
                                <div v-if="settings.currency_accepted && settings.currency_accepted.length > 0"
                                    class="space-y-2">
                                    <p class="font-medium text-gray-900">What's accepted:</p>
                                    <ul class="space-y-1">
                                        <li v-for="(method, index) in settings.currency_accepted" :key="index"
                                            class="flex items-start gap-2">
                                            <span class="text-primary-2">✓</span>
                                            <span>{{ method }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="settings.currency_tip"
                                    class="bg-blue-50 border border-blue-200 rounded-none p-3">
                                    <p class="text-sm text-gray-700">
                                        💡 <strong>Tip:</strong> {{ settings.currency_tip }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.arrival_tips_points && settings.arrival_tips_points.length > 0"
                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-none border border-gray-200 p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.arrival_tips_title || 'Other Arrival Tips' }}
                        </h3>
                        <ul class="space-y-3 font-body text-gray-700">
                            <li v-for="(tip, index) in settings.arrival_tips_points" :key="index"
                                class="flex items-start gap-3">
                                <span class="text-primary-1 mt-1">•</span>
                                <span>{{ tip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Accommodation Tab -->
                <div v-show="activeTab === 'accommodation'" class="mx-auto max-w-6xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.accommodation_title ||
                        'Accommodation in Paro'
                    }}</h2>

                    <!-- Accommodation introduction & policies -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <p class="font-body text-gray-800">
                            Accommodation bookings in Paro will be facilitated by the organizers only for hotels included in the official <strong>Summit Partner Hotel List</strong> (see below).
                        </p>

                        <div class="bg-gray-50 border-l-4 border-accent-2 p-4">
                            <p class="font-body text-gray-800">
                                Participants who choose to stay at hotels <strong>outside</strong> the official Partner Hotel List may do so at their own discretion. However:
                            </p>
                            <ul class="mt-2 space-y-1 font-body text-gray-700 list-disc pl-5">
                                <li>Transportation between non-partner hotels and the official Summit pick-up/drop-off point must be arranged and covered independently by the participant.</li>
                                <li>The organizers will not provide customized transport arrangements for non-partner accommodations.</li>
                            </ul>
                        </div>

                        <p class="font-body text-gray-800">
                            <strong>Note:</strong> Participants are responsible for airport–hotel transfers (taxis available).
                        </p>
                    </div>
                    <!-- Accommodation Payments -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">Accommodation Payments</h3>
                        <ul class="space-y-2 font-body text-gray-700">
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> The organizers will take care of the reservations, but they will not manage hotel rooms, meals and other miscellaneous payments.</li>
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> All accommodation payments are made directly between the participant and the selected hotel.</li>
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> The organizers are not a contractual party to hotel bookings.</li>
                        </ul>
                    </div>
                    <!-- Hotel Cancellation & Refund Policy -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">Hotel Cancellation & Refund Policy</h3>
                        <ul class="space-y-2 font-body text-gray-700">
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> Hotel cancellation terms are determined solely by each individual property.</li>
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> The organizers are <strong>not responsible</strong> for hotel cancellation policies.</li>
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> The organizers cannot intervene in refund disputes between participants and hotels.</li>
                            <li class="flex items-start gap-2"><span class="text-accent-2">•</span> Any refund requests must be handled directly with the hotel.</li>
                        </ul>
                    </div>
                    <!-- Important Booking Guidance -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="font-title text-xl font-semibold text-gray-900">Important Booking Guidance</h3>
                                <p class="font-body text-gray-800">When selecting and booking their accommodations, participants are strongly encouraged to:</p>
                                <ul class="space-y-2 font-body text-gray-700 list-disc pl-5">
                                    <li>Carefully review the cancellation and refund policy of their selected hotel before confirming a booking.</li>
                                    <li>Choose flexible or refundable booking options where possible.</li>
                                    <li>Avoid booking accommodation until visa entry permission has been officially approved.</li>
                                </ul>
                                <p class="font-body text-gray-800 mt-2">
                                    Due to limited room inventory in Paro during the high travel season, participants are advised to make arrangements promptly after visa approval is confirmed.
                                </p>
                            </div>
                        </div>
                    </div>
                <!-- filter -->
                    <div class="flex flex-wrap gap-3">
                        <button @click="accommodationFilter = 'all'" :class="accommodationFilter === 'all'
                            ? 'bg-primary-2 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            All Hotels
                        </button>
                        <button @click="accommodationFilter = '3-star'" :class="accommodationFilter === '3-star'
                            ? 'bg-primary-2 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            3 Star
                        </button>
                        <button @click="accommodationFilter = '4-star'" :class="accommodationFilter === '4-star'
                            ? 'bg-primary-2 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            4 Star
                        </button>
                        <button @click="accommodationFilter = '5-star'" :class="accommodationFilter === '5-star'
                            ? 'bg-primary-2 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            5 Star
                        </button>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(hotel, hotelIndex) in filteredHotels" :key="hotel.name"
                            class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all">
                            <div class="h-48 overflow-hidden bg-gray-200">
                                <img v-if="hotel.image" :src="hotel.image" :alt="hotel.name"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                                <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-accent-2/20 rounded-none">
                                            <svg class="w-5 h-5 text-accent-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div v-if="hotel.nearAirport"
                                            class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Airport
                                        </div>
                                    </div>
                                </div>

                                <h3 class="font-title text-lg font-bold text-gray-900 mb-3">
                                    {{ hotel.name }}
                                </h3>

                                <div class="space-y-3 font-body text-sm">
                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18" />
                                        </svg>
                                        <a v-if="hotel.website" :href="hotel.website" target="_blank"
                                            rel="noopener noreferrer" class="hover:text-primary-1 break-all">
                                            {{ hotel.website }}
                                        </a>
                                        <span v-else>-</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                        </svg>
                                        <span class="font-medium">{{ (hotel.priceRange || '$600') }} per night</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ hotel.distance }}</span>
                                    </div>
                                    <div v-if="hotelIndex === 0"
                                        class="mt-4 rounded-none border border-accent-2/40 bg-accent-2/10 p-3">
                                        <p class="font-body text-sm text-ink"><strong>Email:</strong>
                                            pema.tshogyal@bhutanmindfulness.com</p>
                                        <p class="font-body text-sm text-ink"><strong>This is the main venue
                                                Hotel</strong></p>

                                    </div>
                                    <div v-else class="mt-4 rounded-none border border-accent-2/40 bg-accent-2/10 p-3">
                                        <p class="font-body text-sm text-ink"><strong>Email:</strong>
                                            neema.tamang@bhutanmindfulness.com
                                        </p>
                                    </div>
                                </div>

                                <!-- <div v-if="hotelIndex === 0 && accommodationMainHotelNote" class="mt-4 rounded-none border border-accent-2/40 bg-accent-2/10 p-3">
                                    <p class="font-body text-sm text-ink">{{ accommodationMainHotelNote }}</p>
                                </div> -->

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <span :class="{
                                        'bg-primary-2/20 text-primary-2': normalizeHotelCategory(hotel.category) === '3-star',
                                        'bg-accent-1/20 text-accent-1': normalizeHotelCategory(hotel.category) === '4-star',
                                        'bg-primary-2/10 text-primary-1': normalizeHotelCategory(hotel.category) === '5-star'
                                    }" class="inline-block px-3 py-1 rounded-full text-xs font-medium">
                                        {{ normalizeHotelCategory(hotel.category).replace('-', ' ').toUpperCase() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.accommodation_booking_tips && settings.accommodation_booking_tips.length > 0"
                        class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">
                            {{ settings.accommodation_booking_tips_title || 'Booking Tips' }}
                        </h3>
                        <ul class="space-y-2 font-body text-gray-700">
                            <li v-for="(tip, index) in settings.accommodation_booking_tips" :key="index"
                                class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>{{ tip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Extend Stay Tab -->
                <div v-show="activeTab === 'extend'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.extend_title || 'Extend Your Stay in Bhutan' }}
                    </h2>

                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <p class="font-body text-gray-800 text-lg">
                            It will be possible for participants to extend the duration of their stay in Bhutan beyond
                            the Summit visa
                            period.
                        </p>

                        <p class="font-body text-gray-800 text-lg">
                            After arrival in the country, participants may apply independently to extend their visa by
                            modifying it
                            through Bhutan’s official online visa portal (<a href="https://immi.gov.bt/home/"
                                target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 font-semibold hover:underline">https://immi.gov.bt/home/</a>),
                            changing the visa
                            category from a non-tourist visa to a tourist visa.
                        </p>

                        <div class="pt-2">
                            <p class="font-body font-semibold text-gray-900">Please note:</p>
                            <ul class="mt-3 space-y-2 font-body text-gray-800">
                                <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>Visa extensions must be arranged directly by the participant</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>The total duration of stay in Bhutan may be extended up to 90 days, including
                                        the initial
                                        Summit visa period.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>In the case of an extended stay, participants will be required to pay the
                                        Sustainable
                                        Development Fee (SDF), which is normally USD 100 per adult per night, for all
                                        additional nights
                                        beyond the Summit dates.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>Please also be aware that, to travel around Bhutan as a tourist, visitors are
                                        required to book
                                        an official licensed travel guide. Independent travel to touristic sites and
                                        movement around the
                                        country is not permitted without a guide.</span>
                                </li>
                            </ul>
                        </div>

                        <p class="font-body text-gray-800 text-lg">
                            Please find the list of licensed tourist guides provided by the Department of Tourism here:
                            <a href="https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf"
                                target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 font-semibold hover:underline break-all">
                                https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Companions Tab -->
                <div v-show="activeTab === 'companions'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.companions_title || 'Travel With Companions' }}
                    </h2>

                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <p class="font-body text-gray-800">
                            Visa web:
                            <a href="https://immi.gov.bt/home/" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 font-semibold hover:underline">
                                https://immi.gov.bt/home/
                            </a>
                        </p>
                    </div>

                    <!--  <div v-if="settings.companions_intro" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-none border-2 border-blue-300 p-6">
                        <div class="flex gap-4">
                            <svg class="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="font-body text-lg text-gray-800">
                                {{ settings.companions_intro }}
                            </p>
                        </div>
                    </div>

                    <div v-if="settings.companions_requirements && settings.companions_requirements.length > 0" class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_requirements_title || 'Key Requirements for Companions' }}
                        </h3>
                        <div class="space-y-4">
                            <div
                                v-for="(req, index) in settings.companions_requirements"
                                :key="index"
                                class="flex items-start gap-4"
                                :class="index < settings.companions_requirements.length - 1 ? 'pb-4 border-b border-gray-100' : ''"
                            >
                                <div class="p-2 bg-accent-1/20 rounded-none flex-shrink-0">
                                    <svg class="w-5 h-5 text-accent-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-title font-semibold text-gray-900 mb-2">
                                        {{ req.title }}
                                    </h4>
                                    <p class="font-body text-gray-700">
                                        {{ req.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!--New -->
                    <div v-if="settings.companions_requirements && settings.companions_requirements.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_requirements_title || 'Key Requirements for Companions' }}
                        </h3>
                        <div class="space-y-4">
                            <div v-for="(req, index) in settings.companions_requirements" :key="index"
                                class="flex items-start gap-4"
                                :class="index < settings.companions_requirements.length - 1 ? 'pb-4 border-b border-gray-100' : ''">
                                <div class="p-2 bg-accent-1/20 rounded-none flex-shrink-0">
                                    <svg class="w-5 h-5 text-accent-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-title font-semibold text-gray-900 mb-2">
                                        {{ req.title }}
                                    </h4>
                                    <p class="font-body text-gray-700">
                                        {{ req.description }}
                                    </p>

                                    <!-- Extra content only for "Licensed Guide Required" -->
                                    <div v-if="req.title === 'Licensed Guide Required'" class="mt-3">
                                        <p class="font-body text-gray-700">
                                            Please find the list of licensed tourist guides provided by the Department
                                            of Tourism here:
                                        </p>
                                        <a href="https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 font-semibold hover:underline">
                                            https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.companions_costs && settings.companions_costs.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_costs_title || 'Expected Costs for Companions' }}
                        </h3>
                        <div class="space-y-3">
                            <div v-for="(cost, index) in settings.companions_costs" :key="index"
                                class="flex justify-between items-center py-3"
                                :class="index < settings.companions_costs.length - 1 ? 'border-b border-gray-100' : ''">
                                <span class="font-body text-gray-700">{{ cost.item }}</span>
                                <span class="font-body font-semibold text-gray-900">{{ cost.amount }}</span>
                            </div>
                            <div v-if="settings.companions_costs_note" class="bg-blue-50 rounded-none p-4 mt-4">
                                <p class="font-body text-sm text-gray-700">
                                    <strong>{{ settings.companions_costs_note }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.companions_considerations && settings.companions_considerations.length > 0"
                        class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_considerations_title || 'Special Considerations' }}
                        </h3>
                        <ul class="space-y-3 font-body text-gray-700">
                            <li v-for="(consideration, index) in settings.companions_considerations" :key="index"
                                class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span v-html="consideration"></span>
                            </li>
                        </ul>
                    </div>

                    <!-- <div v-if="settings.companions_tips && settings.companions_tips.length > 0" class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_tips_title || 'Planning Tips' }}
                        </h3>
                        <div class="space-y-3 font-body text-gray-700">
                            <div v-for="(tip, index) in settings.companions_tips" :key="index" class="flex items-start gap-3">
                                <span class="text-primary-1 font-bold">{{ index + 1 }}</span>
                                <p v-html="tip"></p>
                            </div>
                        </div>
                    </div> -->
                    <!-- new -->
                    <div v-if="settings.companions_tips && settings.companions_tips.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_tips_title || 'Planning Tips' }}
                        </h3>
                        <div class="space-y-3 font-body text-gray-700">
                            <div v-for="(tip, index) in settings.companions_tips" :key="index"
                                class="flex items-start gap-3">
                                <span class="text-primary-1 font-bold">{{ index + 1 }}</span>
                                <div>
                                    <p v-html="tip"></p>

                                    <!-- Extra content only for the first tip (index 0) -->
                                    <div v-if="index === 0" class="mt-2">
                                        <p class="text-gray-700">
                                            Please find the list of licensed tourist guides provided by the Department
                                            of Tourism here:
                                        </p>
                                        <a href="https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 font-semibold hover:underline">
                                            https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end new -->

                    <div v-if="settings.companions_resources && settings.companions_resources.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_resources_title || 'Helpful Resources' }}
                        </h3>
                        <div class="space-y-3">
                            <a v-for="(resource, index) in settings.companions_resources" :key="index"
                                :href="resource.url" target="_blank" rel="noopener noreferrer"
                                class="flex items-center justify-between rounded-none border border-blue-200 bg-blue-50/40 p-4 transition-colors hover:border-blue-300 hover:bg-blue-50">
                                <div>
                                    <p class="font-body font-medium text-blue-700">{{ resource.title }}</p>
                                    <p class="font-body text-sm text-gray-700">{{ resource.description }}</p>
                                </div>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ticket Cancellation & Refund Tab -->
                <div v-show="activeTab === 'cancellation'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Summit Ticket Cancellation, Modification &
                        Refund Policies
                    </h2>

                    <!-- Email contact -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-body text-gray-800">All cancellation requests must be submitted in
                                    writing to:</p>
                                <a href="mailto:cofsa@undp.org"
                                    class="font-body text-accent-1 font-semibold hover:underline break-all">cofsa@undp.org</a>
                            </div>
                        </div>
                    </div>

                    <!-- Refund conditions -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-2xl font-semibold text-gray-900 mb-4">Refund Conditions</h3>
                        <ul class="space-y-4 font-body text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="text-green-600 font-bold">✔</span>
                                <div>
                                    <span class="font-semibold">60 days or more before the Summit start date (before 01
                                        July
                                        2026):</span>
                                    <span class="block mt-1">75% refund of the ticket fee (administrative fee
                                        retained).</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-yellow-600 font-bold">✔</span>
                                <div>
                                    <span class="font-semibold">Between 59–30 days before the Summit (01 July – 31 July
                                        2026):</span>
                                    <span class="block mt-1">50% refund of the ticket fee.</span>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-6 rounded-none bg-red-50 border-l-4 border-red-500 p-4">
                            <p class="font-body font-semibold text-red-800">No refund will be issued:</p>
                            <ul class="mt-2 space-y-1 font-body text-red-700 list-disc pl-5">
                                <li>Cancellation less than 30 days before the Summit (from 01 August 2026 onward)</li>
                                <li>No-shows</li>
                            </ul>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 border border-gray-200">
                            <p class="font-body text-gray-800">
                                <strong>Please note:</strong> The visa application fee (BTN 5,000), once processed and
                                submitted to
                                authorities, is non-refundable.
                                Any bank transfer fees or transaction costs are non-refundable and remain the
                                responsibility of the
                                participant.
                            </p>
                        </div>
                    </div>

                    <!-- Visa Rejection / Delay -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <div class="flex gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-title text-xl font-semibold text-gray-900">Visa Rejection or Delay</h3>
                                <p class="font-body text-gray-800 mt-2">
                                    In the unlikely event that a participant’s visa entry permission is not approved by
                                    Bhutanese
                                    authorities:
                                </p>
                                <p class="font-body text-gray-800 mt-2 ml-4 border-l-2 border-accent-2 pl-4">
                                    <strong>The Summit ticket fee will be refunded</strong> after deducting any
                                    non-recoverable
                                    administrative, bank processing, and visa fees already incurred.
                                </p>
                                <p class="font-body text-gray-600 mt-3 text-sm">
                                    The organizers are not responsible for visa delays resulting from incomplete
                                    documentation or late
                                    submission of required materials by the participant.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage note -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-accent-2 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                The Summit ticket fee covers venue arrangements, meals, administrative coordination,
                                visa processing,
                                and related logistical commitments made in advance.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- CODE OF CONDUCT & PARTICIPATION RIGHTS TAB -->
                <div v-show="activeTab === 'code-of-conduct'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Code of Conduct & Participation Rights</h2>

                    <!-- Main content card -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div>
                                <p class="font-body text-gray-800">
                                    The Summit aims to foster a respectful, inclusive, and safe space for dialogue and collaboration.
                                </p>
                            </div>
                        </div>

                        <div class="bg-red-50 border-l-4 border-red-500 p-5">
                            <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">The organizers reserve the right to:</h3>
                            <ul class="space-y-2 font-body text-gray-800 list-disc pl-5">
                                <li>Refuse participation</li>
                                <li>Cancel registration without refund</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-none p-5">
                            <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">In cases of:</h3>
                            <ul class="space-y-2 font-body text-gray-800 list-disc pl-5">
                                <li>Misconduct</li>
                                <li>Harassment or discriminatory behavior</li>
                                <li>Violation of Bhutanese laws</li>
                                <li>Disruption of the Summit environment</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Additional note / safe space -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                All participants are expected to adhere to this Code of Conduct throughout the Summit, including all related events, sessions, and social activities.
                            </p>
                        </div>
                    </div>
                </div>
                 <!-- LIABILITY DISCLAIMER TAB -->
                <div v-show="activeTab === 'liability-disclaimer'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Liability Disclaimer</h2>

                    <!-- Main content card -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                Participants acknowledge that attendance is voluntary and assume responsibility for their own well-being.
                            </p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-none p-5">
                            <ul class="space-y-3 font-body text-gray-800 list-disc pl-5">
                                <li>Attendance is voluntary</li>
                                <li>Participation in field visits, outdoor activities, or embodied practices is at their own risk</li>
                                <li>The organizers are not liable for personal injury, loss of property, or unforeseen personal expenses</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Insurance advisory -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div>
                                <p class="font-body font-semibold text-gray-900">Participants are strongly advised to:</p>
                                <p class="font-body text-gray-800 mt-1">Secure adequate medical and travel insurance prior to arrival.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CHANGES TO THE PROGRAMME TAB -->
                <div v-show="activeTab === 'programme-changes'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Changes to the Programme</h2>

                    <!-- Main content card -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                While every effort will be made to deliver the programme as advertised, the organizers reserve the right to make adjustments when necessary.
                            </p>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-none p-5">
                            <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">The organizers reserve the right to:</h3>
                            <ul class="space-y-2 font-body text-gray-800 list-disc pl-5">
                                <li>Modify the schedule</li>
                                <li>Change speakers</li>
                                <li>Adjust session formats</li>
                                <li>Update venues if necessary</li>
                            </ul>
                        </div>
                    </div>

                    <!-- No refund notice -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3 .895 3 2s-1.343 2-3 2m0-4c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                            </svg>
                            <p class="font-body text-gray-800">
                                <strong>Important:</strong> Such changes do not constitute grounds for refund.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- WEATHER & PACKING GUIDANCE TAB -->
                <div v-show="activeTab === 'weather-packing'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">Weather Conditions & Packing Guidance</h2>

                    <!-- Weather description -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-4">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                Early September is a transitional period from monsoon to dry autumn weather. Expect mild to warm days (17–24°C) and cool nights (8–13°C), along with short showers.
                            </p>
                        </div>
                    </div>

                    <!-- Packing Guidance card -->
                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-2xl font-semibold text-gray-900 mb-4">Packing Guidance</h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <ul class="space-y-3 font-body text-gray-800">
                                <li class="flex items-start gap-3">
                                    <span class="text-accent-2 mt-1">•</span>
                                    <span>Light layers for daytime comfort</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-accent-2 mt-1">•</span>
                                    <span>Warm layers (light jacket/fleece) for cool mornings and evenings</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-accent-2 mt-1">•</span>
                                    <span>Waterproof jacket / umbrella / raincoat or poncho</span>
                                </li>
                            </ul>
                            <ul class="space-y-3 font-body text-gray-800">
                                <li class="flex items-start gap-3">
                                    <span class="text-accent-2 mt-1">•</span>
                                    <span>Comfortable waterproof walking shoes (slippery paths possible)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-accent-2 mt-1">•</span>
                                    <span>Sunscreen & sunglasses (UV can still be strong even in cloudy weather)</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Venue-specific note about colder temperatures -->
                    <div class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-body text-gray-800">
                                <strong>Note:</strong> Our first venue, Dungkar Dzong, is at a higher altitude and may be significantly colder than our second venue, TUI BLUE Paro Taktshang. Please pack accordingly.
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </EventLayout>
</template>

<style scoped>
.tab-scroll-container {
    scrollbar-width: none;
    /* Firefox */
    -ms-overflow-style: none;
    /* IE and Edge */
}

.tab-scroll-container::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari, Opera */
}
</style>
