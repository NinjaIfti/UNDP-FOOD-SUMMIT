<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const activeTab = ref('air-travel');
const accommodationFilter = ref('all');

const tabs = [
    { id: 'air-travel', label: 'Air Travel', icon: 'plane' },
    { id: 'visa', label: 'Visa & Entry', icon: 'document' },
    { id: 'arrival', label: 'Upon Arrival', icon: 'suitcase' },
    { id: 'field-visits', label: 'Field Visits', icon: 'map-pin' },
    { id: 'accommodation', label: 'Accommodation', icon: 'building' },
    { id: 'extend', label: 'Extend Stay', icon: 'calendar-plus' },
    { id: 'companions', label: 'Travel With Companions', icon: 'users' },
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
                    <div
                        class="relative w-full min-h-[35vh] sm:min-h-[40vh] lg:min-h-[45vh]"
                    >
                        <div
                            v-for="(slide, index) in heroSlides"
                            :key="slide + index"
                            class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                            :class="index === activeHeroSlide ? 'opacity-100' : 'opacity-0'"
                        >
                            <img
                                :src="slide"
                                alt=""
                                class="h-full w-full object-cover"
                                loading="lazy"
                            />
                                </div>
                        <div class="absolute inset-0 bg-black/45" />
                        <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-8">
                            <div class="max-w-4xl text-center">
                                <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl lg:text-5xl">
                                    {{ heroTitle }}
                                </h1>
                                <p class="mx-auto mt-4 text-center font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
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
                <div
                    class="flex flex-nowrap gap-2 sm:gap-3 overflow-x-auto pb-1 pl-2 pr-8 sm:px-0 lg:justify-center tab-scroll-container"
                >
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        type="button"
                        class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 font-body text-sm font-semibold transition sm:px-5 sm:py-2.5 sm:text-base first:ml-2 last:mr-2 sm:first:ml-4 sm:last:mr-4"
                        :class="activeTab === tab.id
                            ? 'bg-accent-2 text-white'
                            : 'bg-gray-100 text-ink hover:bg-gray-200'"
                        @click="activeTab = tab.id"
                    >
                        <svg
                            v-if="tab.icon === 'plane'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'document'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'suitcase'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'building'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'calendar-plus'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'users'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>{{ tab.label }}</span>
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
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-2 font-body text-xs font-bold text-primary-1">
                                i
                            </div>
                            <div>
                                <p class="font-body text-base text-gray-800">
                                    {{ fieldVisitsInfo || 'Field visits on 31 August are available at an additional cost and can be selected during registration.' }}
                                </p>
                                <p v-if="settings.field_visits_pending" class="mt-2 font-body text-sm italic text-gray-500">
                                    {{ settings.field_visits_pending }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.field_visits_expectations && settings.field_visits_expectations.length > 0" class="mx-auto mt-8 max-w-2xl rounded-none border border-primary-2/20 bg-white p-6">
                        <h3 class="font-title text-lg font-bold text-gray-900 sm:text-xl">
                            What to Expect
                        </h3>
                        <ul class="mt-4 list-none space-y-3 font-body text-gray-800 text-left">
                            <li v-for="(expectation, index) in settings.field_visits_expectations" :key="index" class="flex items-start justify-start gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-primary-2" />
                                <span>{{ expectation }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-show="activeTab === 'air-travel'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.air_travel_title || 'Air Travel to Paro' }}</h2>

                    <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                        {{ airTravelIntro }}
                    </p>

                    <div v-if="settings.air_travel_guidance_points && settings.air_travel_guidance_points.length > 0" class="bg-accent-1/10 border-2 border-accent-1/30 rounded-none p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-accent-1 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.air_travel_guidance_title || 'Important Booking Guidance' }}
                                </h3>
                                <ul class="space-y-2 font-body text-gray-700">
                                    <li v-for="(point, index) in settings.air_travel_guidance_points" :key="index" class="flex items-start gap-2">
                                        <span class="text-primary-1 mt-1">•</span>
                                        <span v-html="point"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.airlines && settings.airlines.length > 0">
                        <h3 class="font-title text-2xl font-semibold text-gray-900 mb-4">
                            {{ settings.airlines_title || 'Airlines Operating to Paro' }}
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div v-for="(airline, index) in settings.airlines" :key="index" class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-48 overflow-hidden">
                                    <img
                                        :src="airline.image || '/brand/plane1.jpg'"
                                        :alt="`${airline.name} - ${airline.description}`"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                                <div class="p-6">
                                    <h4 class="font-title text-xl font-bold text-gray-900 mb-2">{{ airline.name }}</h4>
                                    <p class="font-body text-gray-600 mb-4">{{ airline.description }}</p>
                                    <a
                                        v-if="airline.website"
                                        :href="airline.website"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary-1 text-white rounded-lg hover:bg-primary-1/90 transition-colors"
                                    >
                                        Visit Website
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.flight_routes && settings.flight_routes.length > 0">
                        <h3 class="font-title text-2xl font-semibold text-gray-900 mb-4">
                            {{ settings.flight_table_title || 'List of available flights to Paro' }}
                        </h3>
                        <div class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-100 border-b border-gray-300">
                                            <th class="px-4 py-3 text-left font-title text-sm font-bold text-gray-900 border-r border-gray-300">SECTOR</th>
                                            <th class="px-4 py-3 text-left font-title text-sm font-bold text-gray-900 border-r border-gray-300">Flight availability</th>
                                            <th class="px-4 py-3 text-left font-title text-sm font-bold text-gray-900 border-r border-gray-300">SAARC³ Regional Fare (USD)</th>
                                            <th class="px-4 py-3 text-left font-title text-sm font-bold text-gray-900">International Fare (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(route, index) in settings.flight_routes" :key="index">
                                            <tr v-if="route.is_note && route.airline" class="border-b border-gray-200 bg-blue-50">
                                                <td colspan="4" class="px-4 py-3 text-center">
                                                    <a v-if="route.airline_url" :href="route.airline_url" target="_blank" rel="noopener noreferrer" class="font-body text-primary-1 font-semibold hover:underline">{{ route.airline }}</a>
                                                    <span v-else class="font-body text-primary-1 font-semibold">{{ route.airline }}</span>
                                                </td>
                                            </tr>
                                            <tr v-if="route.is_note && route.note" class="border-b border-gray-200 hover:bg-gray-50">
                                                <td colspan="4" class="px-4 py-4 border-r border-gray-200 font-body text-sm text-gray-900">
                                                    <em class="font-body text-gray-600">{{ route.note }}</em>
                                                </td>
                                            </tr>
                                            <tr v-if="!route.is_note && route.sector" class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-4 py-4 border-r border-gray-200 font-body text-sm text-gray-900">
                                                    {{ route.sector }}<br v-if="route.sector_note" />
                                                    <span v-if="route.sector_note" class="text-gray-600 text-xs">{{ route.sector_note }}</span>
                                                </td>
                                                <td class="px-4 py-4 border-r border-gray-200 font-body text-sm text-gray-900">
                                                    <span v-html="route.availability ? route.availability.replace(/\n/g, '<br />') : ''"></span>
                                                </td>
                                                <td class="px-4 py-4 border-r border-gray-200 font-body text-sm text-gray-900">
                                                    <span v-if="route.saarc_business || route.saarc_economy">
                                                        <span v-if="route.saarc_business">Business class: {{ route.saarc_business }}<br /></span>
                                                        <span v-if="route.saarc_economy">Economy class: {{ route.saarc_economy }}</span>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 font-body text-sm text-gray-900">
                                                    <span v-if="route.international_business || route.international_economy">
                                                        <span v-if="route.international_business">Business class: {{ route.international_business }}<br /></span>
                                                        <span v-if="route.international_economy">Economy class: {{ route.international_economy }}</span>
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="settings.flight_table_note || settings.flight_table_saarc_note" class="flex items-start gap-3 bg-blue-50 p-4 border-t border-gray-200">
                                <span class="text-blue-600 mt-1">ℹ️</span>
                                <div class="font-body text-sm text-gray-700">
                                    <p v-if="settings.flight_table_note" class="mb-2">
                                        <strong>Note:</strong> {{ settings.flight_table_note }}
                                    </p>
                                    <p v-if="settings.flight_table_saarc_note">
                                        <strong>{{ settings.flight_table_saarc_note }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="settings.visa_support_points && settings.visa_support_points.length > 0"
                        class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-none p-6 shadow-md"
                    >
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.visa_support_title || 'Visa support' }}
                                </h3>
                                <ul class="space-y-2 font-body text-gray-800">
                                    <li
                                        v-for="(point, index) in settings.visa_support_points"
                                        :key="index"
                                        class="flex items-start gap-2"
                                    >
                                        <span class="text-accent-2 mt-1">•</span>
                                        <span>{{ point }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.transit_visa_title || settings.transit_visa_description" class="bg-accent-2/10 border-2 border-accent-2/30 rounded-none p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="font-title text-xl font-semibold text-gray-900 mb-2">
                                        {{ settings.transit_visa_title || 'Check Transit Visa Requirements' }}
                                    </h3>
                                    <p class="font-body text-gray-700">
                                        {{ settings.transit_visa_description || 'If you\'re transiting through India or Nepal, you may need a transit visa. Check requirements based on your nationality.' }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <a
                                        href="https://indianvisaonline.gov.in/evisa/tvoa.html"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-accent-2 text-gray-900 rounded-lg hover:bg-accent-2/10 transition-colors"
                                    >
                                        India Transit Visa
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <a
                                        href="https://www.immigration.gov.np/page/transit-visa"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-accent-2 text-gray-900 rounded-lg hover:bg-accent-2/10 transition-colors"
                                    >
                                        Nepal Transit Visa
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visa Tab -->
                <div v-show="activeTab === 'visa'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">
                        {{ settings.visa_title || 'Visa & Entry Process' }}
                    </h2>

                    <!-- Summit Visa Support -->
                    <div
                        v-if="settings.visa_support_points && settings.visa_support_points.length > 0"
                        class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-none p-6 shadow-md"
                    >
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="font-title text-2xl font-bold text-gray-900">
                                    {{ settings.visa_support_title || 'Summit Visa Support' }}
                                </h3>
                                <div class="space-y-3 font-body text-gray-800">
                                    <div
                                        v-for="(point, index) in settings.visa_support_points"
                                        :key="index"
                                        class="flex items-start gap-3"
                                    >
                                        <span class="text-accent-2 font-bold">✓</span>
                                        <p v-html="point"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div
                        v-if="settings.visa_important_points && settings.visa_important_points.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm"
                    >
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.visa_important_title || 'Important Notes' }}
                                </h3>
                                <ul class="space-y-3 font-body text-gray-700">
                                    <li
                                        v-for="(point, index) in settings.visa_important_points"
                                        :key="index"
                                        class="flex items-start gap-3"
                                    >
                                        <span class="text-primary-1 mt-1">•</span>
                                        <span v-html="point"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Applying Independently -->
                    <div
                        v-if="settings.visa_independent_points && settings.visa_independent_points.length > 0"
                        class="bg-white rounded-none border border-gray-200 p-6 shadow-sm"
                    >
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.visa_independent_title || 'Applying Independently' }}
                        </h3>
                        <p v-if="settings.visa_independent_description" class="font-body text-gray-700 mb-4">
                            {{ settings.visa_independent_description }}
                        </p>
                        <div class="space-y-3 mb-6">
                            <div
                                v-for="(point, index) in settings.visa_independent_points"
                                :key="index"
                                class="flex items-start gap-3 font-body text-gray-700"
                            >
                                <span class="text-primary-1 mt-1">•</span>
                                <span v-html="point"></span>
                            </div>
                        </div>
                        <a
                            v-if="settings.visa_independent_button_url && settings.visa_independent_button_text"
                            :href="settings.visa_independent_button_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-1 text-white rounded-lg hover:bg-primary-1/90 transition-colors"
                        >
                            {{ settings.visa_independent_button_text }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Arrival Tab -->
                <div v-show="activeTab === 'arrival'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.arrival_title || 'Upon Arrival in Bhutan' }}</h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-accent-2/20 rounded-none">
                                    <svg class="w-6 h-6 text-accent-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
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
                                <div v-if="settings.mobile_providers && settings.mobile_providers.length > 0" class="bg-gray-50 rounded-none p-4 space-y-2">
                                    <p class="font-medium text-gray-900">Providers:</p>
                                    <ul class="space-y-1">
                                        <li v-for="(provider, index) in settings.mobile_providers" :key="index" class="flex items-start gap-2">
                                            <span class="text-primary-1">•</span>
                                            <a
                                                v-if="providerUrl(provider)"
                                                :href="providerUrl(provider)"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="text-primary-1 font-semibold hover:underline"
                                            >
                                                {{ provider }}
                                            </a>
                                            <span v-else>{{ provider }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="settings.mobile_cost" class="bg-accent-2/10 border border-accent-2/30 rounded-none p-4">
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

                        <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-primary-2/20 rounded-none">
                                    <svg class="w-6 h-6 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
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
                                <div v-if="settings.currency_accepted && settings.currency_accepted.length > 0" class="space-y-2">
                                    <p class="font-medium text-gray-900">What's accepted:</p>
                                    <ul class="space-y-1">
                                        <li v-for="(method, index) in settings.currency_accepted" :key="index" class="flex items-start gap-2">
                                            <span class="text-primary-2">✓</span>
                                            <span>{{ method }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="settings.currency_tip" class="bg-blue-50 border border-blue-200 rounded-none p-3">
                                    <p class="text-sm text-gray-700">
                                        💡 <strong>Tip:</strong> {{ settings.currency_tip }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="settings.arrival_tips_points && settings.arrival_tips_points.length > 0"
                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-none border border-gray-200 p-6"
                    >
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.arrival_tips_title || 'Other Arrival Tips' }}
                        </h3>
                        <ul class="space-y-3 font-body text-gray-700">
                            <li
                                v-for="(tip, index) in settings.arrival_tips_points"
                                :key="index"
                                class="flex items-start gap-3"
                            >
                                <span class="text-primary-1 mt-1">•</span>
                                <span>{{ tip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Accommodation Tab -->
                <div v-show="activeTab === 'accommodation'" class="mx-auto max-w-6xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.accommodation_title || 'Accommodation in Paro' }}</h2>

                    <div v-if="settings.accommodation_important" class="bg-blue-50 border border-blue-200 rounded-none p-6">
                       <!-- <p class="font-body text-gray-800">
                            <strong>{{ settings.accommodation_important }}</strong>
                        </p> -->
			<ul class="space-y-2 font-body text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>
				While not mandatory, all Summit participants are strongly encouraged to stay in the accommodation options listed below, located in Paro and in close proximity to the event venue and official Summit logistics. Staying in these hotels will ensure access to organized transportation between hotels and Summit venues. Participants choosing to stay in alternative accommodations may not be able to access these transportation services.
				</span>
                            </li>
			    <li class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>
				Participants are responsible for selecting and booking their preferred accommodation directly with the hotel.
				</span>
                            </li>
			 <li class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>
				We strongly encourage participants to enquire early, as availability in Paro is limited during this period. However, bookings should only be confirmed after receiving official visa entry approval, to avoid any inconvenience or non-refundable costs.
				</span>
                            </li>
			 <li class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>
				The official Summit hotel options are provided below.
				</span>
                            </li>
                        </ul>

                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="accommodationFilter = 'all'"
                            :class="accommodationFilter === 'all'
                                ? 'bg-primary-2 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            All Hotels
                        </button>
                        <button
                            @click="accommodationFilter = '3-star'"
                            :class="accommodationFilter === '3-star'
                                ? 'bg-primary-2 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            3 Star
                        </button>
                        <button
                            @click="accommodationFilter = '4-star'"
                            :class="accommodationFilter === '4-star'
                                ? 'bg-primary-2 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            4 Star
                        </button>
                        <button
                            @click="accommodationFilter = '5-star'"
                            :class="accommodationFilter === '5-star'
                                ? 'bg-primary-2 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            5 Star
                        </button>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="(hotel, hotelIndex) in filteredHotels"
                            :key="hotel.name"
                            class="bg-white rounded-none border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all"
                        >
                            <div class="h-48 overflow-hidden bg-gray-200">
                                <img
                                    v-if="hotel.image"
                                    :src="hotel.image"
                                    :alt="hotel.name"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-accent-2/20 rounded-none">
                                            <svg class="w-5 h-5 text-accent-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div v-if="hotel.nearAirport" class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
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
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18" />
                                        </svg>
                                        <a
                                            v-if="hotel.website"
                                            :href="hotel.website"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="hover:text-primary-1 break-all"
                                        >
                                            {{ hotel.website }}
                                        </a>
                                        <span v-else>-</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                        </svg>
                                        <span class="font-medium">$600 per night</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ hotel.distance }}</span>
                                    </div>
                                </div>

                                <div v-if="hotelIndex === 0 && accommodationMainHotelNote" class="mt-4 rounded-none border border-accent-2/40 bg-accent-2/10 p-3">
                                    <p class="font-body text-sm text-ink">{{ accommodationMainHotelNote }}</p>
                                </div>

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

                    <div v-if="settings.accommodation_booking_tips && settings.accommodation_booking_tips.length > 0" class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-3">
                            {{ settings.accommodation_booking_tips_title || 'Booking Tips' }}
                        </h3>
                        <ul class="space-y-2 font-body text-gray-700">
                            <li v-for="(tip, index) in settings.accommodation_booking_tips" :key="index" class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span>{{ tip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Extend Stay Tab -->
                <div v-show="activeTab === 'extend'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.extend_title || 'Extend Your Stay in Bhutan' }}</h2>

                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm space-y-5">
                        <p class="font-body text-gray-800 text-lg">
                            It will be possible for participants to extend the duration of their stay in Bhutan beyond the Summit visa period.
                        </p>

                        <p class="font-body text-gray-800 text-lg">
                            After arrival in the country, participants may apply independently to extend their visa by modifying it through Bhutan’s official online visa portal (<a href="https://immi.gov.bt/home/" target="_blank" rel="noopener noreferrer" class="text-blue-600 font-semibold hover:underline">https://immi.gov.bt/home/</a>), changing the visa category from a non-tourist visa to a tourist visa.
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
                                    <span>The total duration of stay in Bhutan may be extended up to 90 days, including the initial Summit visa period.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>In the case of an extended stay, participants will be required to pay the Sustainable Development Fee (SDF), which is normally USD 100 per adult per night, for all additional nights beyond the Summit dates.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                    <span class="text-primary-1 mt-1">•</span>
                                    <span>Please also be aware that, to travel around Bhutan as a tourist, visitors are required to book an official licensed travel guide. Independent travel to touristic sites and movement around the country is not permitted without a guide.</span>
                                    </li>
                                </ul>
                    </div>

                        <p class="font-body text-gray-800 text-lg">
                            Please find the list of licensed tourist guides provided by the Department of Tourism here:
                            <a
                                href="https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf"
                            target="_blank"
                            rel="noopener noreferrer"
                                class="text-blue-600 font-semibold hover:underline break-all"
                            >
                                https://a.storyblok.com/f/171618/x/b982d907d5/01-dot-certified-tour-guide-as-of-january-2025.pdf
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Companions Tab -->
                <div v-show="activeTab === 'companions'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.companions_title || 'Travel With Companions' }}</h2>

                    <div class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <p class="font-body text-gray-800">
                            Visa web:
                            <a
                                href="https://immi.gov.bt/home/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-600 font-semibold hover:underline"
                            >
                                https://immi.gov.bt/home/
                            </a>
                        </p>
                    </div>

                    <div v-if="settings.companions_intro" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-none border-2 border-blue-300 p-6">
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
                    </div>

                    <div v-if="settings.companions_costs && settings.companions_costs.length > 0" class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_costs_title || 'Expected Costs for Companions' }}
                        </h3>
                        <div class="space-y-3">
                            <div v-for="(cost, index) in settings.companions_costs" :key="index" class="flex justify-between items-center py-3" :class="index < settings.companions_costs.length - 1 ? 'border-b border-gray-100' : ''">
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

                    <div v-if="settings.companions_considerations && settings.companions_considerations.length > 0" class="bg-accent-2/10 border border-accent-2/30 rounded-none p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_considerations_title || 'Special Considerations' }}
                        </h3>
                        <ul class="space-y-3 font-body text-gray-700">
                            <li v-for="(consideration, index) in settings.companions_considerations" :key="index" class="flex items-start gap-3">
                                <span class="text-accent-2 mt-1">•</span>
                                <span v-html="consideration"></span>
                            </li>
                        </ul>
                    </div>

                    <div v-if="settings.companions_tips && settings.companions_tips.length > 0" class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_tips_title || 'Planning Tips' }}
                        </h3>
                        <div class="space-y-3 font-body text-gray-700">
                            <div v-for="(tip, index) in settings.companions_tips" :key="index" class="flex items-start gap-3">
                                <span class="text-primary-1 font-bold">{{ index + 1 }}</span>
                                <p v-html="tip"></p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.companions_resources && settings.companions_resources.length > 0" class="bg-white rounded-none border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_resources_title || 'Helpful Resources' }}
                        </h3>
                        <div class="space-y-3">
                            <a
                                v-for="(resource, index) in settings.companions_resources"
                                :key="index"
                                :href="resource.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center justify-between rounded-none border border-blue-200 bg-blue-50/40 p-4 transition-colors hover:border-blue-300 hover:bg-blue-50"
                            >
                                <div>
                                    <p class="font-body font-medium text-blue-700">{{ resource.title }}</p>
                                    <p class="font-body text-sm text-gray-700">{{ resource.description }}</p>
                                </div>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </EventLayout>
</template>

<style scoped>
.tab-scroll-container {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.tab-scroll-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
</style>
