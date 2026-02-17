<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const activeTab = ref('field-visits');
const accommodationFilter = ref('all');

const tabs = [
    { id: 'field-visits', label: 'Field Visits', icon: 'map-pin' },
    { id: 'air-travel', label: 'Air Travel', icon: 'plane' },
    { id: 'visa', label: 'Visa & Entry', icon: 'document' },
    { id: 'arrival', label: 'Upon Arrival', icon: 'suitcase' },
    { id: 'accommodation', label: 'Accommodation', icon: 'building' },
    { id: 'extend', label: 'Extend Stay', icon: 'calendar-plus' },
    { id: 'companions', label: 'Travel With Companions', icon: 'users' },
];

const hotels = computed(() => props.settings?.hotels || []);

const filteredHotels = computed(() => {
    if (accommodationFilter.value === 'all') {
        return hotels.value;
    }
    return hotels.value.filter(hotel => hotel.category === accommodationFilter.value);
});
</script>

<template>
    <Head title="Your Stay in Bhutan" />
    <EventLayout>
        <!-- Participation Costs Section -->
        <section class="bg-gradient-to-br from-amber-50 to-red-50 py-16 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="font-title text-4xl font-bold text-gray-900 mb-4">
                        {{ settings.participation_costs_title || 'WHAT ARE THE PARTICIPATION COSTS?' }}
                    </h2>
                    <div class="max-w-3xl mx-auto space-y-4">
                        <p class="font-body text-lg text-gray-700 leading-relaxed">
                            {{ settings.participation_costs_description || 'Participation in the Conscious Food Systems Summit requires an entrance ticket, which helps cover the essential costs of organizing and delivering the event, including venue arrangements, meals, local logistics, and administrative coordination.' }}
                        </p>
                        <div class="bg-accent-2/20 border-l-4 border-primary-1 p-4 rounded-r-lg">
                            <p class="font-body text-gray-800">
                                <strong>
                                    {{ settings.participation_costs_announcement || 'Announcement: The official ticket price will be announced following the formal launch of the Summit in early March. We kindly invite participants to stay tuned for further updates.' }}
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- What's Included Card -->
                    <div v-if="settings.included_items && settings.included_items.length > 0" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-1 to-primary-1/90 p-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 class="font-title text-xl font-bold text-white">
                                    {{ settings.included_title || 'What\'s Included' }}
                                </h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <div v-for="(item, index) in settings.included_items" :key="index" :class="['flex gap-4', item.highlighted ? 'bg-accent-2/10 p-4 rounded-lg border border-accent-2/30' : '']">
                                <div class="flex-shrink-0 mt-1">
                                    <div :class="[
                                        'p-2 rounded-lg',
                                        item.highlighted ? 'bg-accent-2/30' : index % 5 === 0 ? 'bg-primary-2/20' : index % 5 === 1 ? 'bg-accent-2/20' : index % 5 === 2 ? 'bg-blue-100' : index % 5 === 3 ? 'bg-purple-100' : 'bg-gray-100'
                                    ]">
                                        <svg class="w-5 h-5" :class="[
                                            item.highlighted ? 'text-accent-2' : index % 5 === 0 ? 'text-primary-2' : index % 5 === 1 ? 'text-accent-2' : index % 5 === 2 ? 'text-blue-600' : index % 5 === 3 ? 'text-purple-600' : 'text-gray-500'
                                        ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-title font-semibold text-ink mb-1">{{ item.title }}</h4>
                                    <ul v-if="item.isList && item.description" class="font-body text-sm text-copy space-y-1">
                                        <li v-for="(line, lineIndex) in item.description.split('\n').filter(l => l.trim())" :key="lineIndex">
                                            <span v-if="line.trim().startsWith('•')">{{ line.trim() }}</span>
                                            <span v-else>• {{ line.trim() }}</span>
                                        </li>
                                    </ul>
                                    <p v-else class="font-body text-sm text-copy">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- What's NOT Included Card -->
                    <div v-if="settings.not_included_items && settings.not_included_items.length > 0" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-800 p-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <h3 class="font-title text-xl font-bold text-white">
                                    {{ settings.not_included_title || 'What\'s NOT Included' }}
                                </h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <div v-for="(item, index) in settings.not_included_items" :key="index" :class="['flex gap-4', item.highlighted ? 'bg-gray-50 p-4 rounded-lg border border-gray-200' : '']">
                                <div class="flex-shrink-0 mt-1">
                                    <div :class="[
                                        'p-2 rounded-lg',
                                        item.highlighted ? 'bg-gray-200' : 'bg-gray-100'
                                    ]">
                                        <svg class="w-5 h-5" :class="[
                                            item.highlighted ? 'text-gray-600' : 'text-gray-500'
                                        ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-title font-semibold text-ink mb-1">{{ item.title }}</h4>
                                    <p class="font-body text-sm text-copy">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-gray-200 bg-white">
            <div class="mx-auto px-4 py-4 sm:py-6 sm:px-6 lg:px-8">
                <div
                    class="flex flex-nowrap gap-2 sm:gap-3 overflow-x-auto pb-1 pl-2 pr-8 sm:px-0 lg:justify-center tab-scroll-container"
                >
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        type="button"
                        class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 font-title text-sm font-semibold transition sm:px-5 sm:py-2.5 sm:text-base first:ml-2 last:mr-2 sm:first:ml-4 sm:last:mr-4"
                        :class="activeTab === tab.id
                            ? 'bg-primary-1 text-white'
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

        <section class="bg-white py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-show="activeTab === 'field-visits'" class="mx-auto max-w-3xl text-center">
                    <h2 class="font-title text-4xl font-bold text-gray-900 sm:text-5xl">
                        {{ settings.field_visits_title || 'Will There Be Field Visits?' }}
                    </h2>

                    <div class="mx-auto mt-6 max-w-2xl rounded-lg border border-accent-2 bg-accent-2/10 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-accent-2 font-title text-xs font-bold text-primary-1">
                                i
                            </div>
                            <div>
                                <p class="font-body text-base text-gray-800">
                                    {{ settings.field_visits_info || 'Optional add-on: Field visits on 31 August are available at an additional cost and can be selected during registration.' }}
                                </p>
                                <p v-if="settings.field_visits_pending" class="mt-2 font-body text-sm italic text-gray-500">
                                    {{ settings.field_visits_pending }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.field_visits_expectations && settings.field_visits_expectations.length > 0" class="mx-auto mt-8 max-w-2xl rounded-lg border border-gray-200 bg-white p-6">
                        <h3 class="font-title text-lg font-bold text-gray-900 sm:text-xl">
                            What to Expect
                        </h3>
                        <ul class="mt-4 list-none space-y-3 font-body text-gray-800">
                            <li v-for="(expectation, index) in settings.field_visits_expectations" :key="index" class="flex items-center justify-center gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-primary-1" />
                                <span>{{ expectation }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-show="activeTab === 'air-travel'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.air_travel_title || 'Air Travel to Paro' }}</h2>

                    <div v-if="settings.air_travel_guidance_points && settings.air_travel_guidance_points.length > 0" class="bg-accent-1/10 border-2 border-accent-1/30 rounded-xl p-6">
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
                            <div v-for="(airline, index) in settings.airlines" :key="index" class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
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
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
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
                                                    <a v-if="route.airline_url" :href="route.airline_url" target="_blank" rel="noopener noreferrer" class="font-title text-primary-1 font-semibold hover:underline">{{ route.airline }}</a>
                                                    <span v-else class="font-title text-primary-1 font-semibold">{{ route.airline }}</span>
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

                    <div v-if="settings.transit_visa_title || settings.transit_visa_description" class="bg-accent-2/10 border-2 border-accent-2/30 rounded-xl p-6">
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
                        class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-xl p-6 shadow-md"
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
                        class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm"
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
                        class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm"
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
                        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-accent-2/20 rounded-lg">
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
                                <div v-if="settings.mobile_providers && settings.mobile_providers.length > 0" class="bg-gray-50 rounded-lg p-4 space-y-2">
                                    <p class="font-medium text-gray-900">Providers:</p>
                                    <ul class="space-y-1">
                                        <li v-for="(provider, index) in settings.mobile_providers" :key="index" class="flex items-start gap-2">
                                            <span class="text-primary-1">•</span>
                                            <span>{{ provider }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div v-if="settings.mobile_cost" class="bg-accent-2/10 border border-accent-2/30 rounded-lg p-4">
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

                        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-primary-2/20 rounded-lg">
                                    <svg class="w-6 h-6 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                    </svg>
                                </div>
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.currency_title || 'Currency Exchange' }}
                                </h3>
                            </div>
                            <div class="space-y-3 font-body text-gray-700">
                                <div v-if="settings.currency_local" class="bg-gray-50 rounded-lg p-4">
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
                                <div v-if="settings.currency_tip" class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-gray-700">
                                        💡 <strong>Tip:</strong> {{ settings.currency_tip }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="settings.arrival_tips_points && settings.arrival_tips_points.length > 0"
                        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6"
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

                    <div v-if="settings.accommodation_important" class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <p class="font-body text-gray-800">
                            <strong>{{ settings.accommodation_important }}</strong>
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="accommodationFilter = 'all'"
                            :class="accommodationFilter === 'all'
                                ? 'bg-primary-1 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            All Hotels
                        </button>
                        <button
                            @click="accommodationFilter = 'budget'"
                            :class="accommodationFilter === 'budget'
                                ? 'bg-primary-1 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Budget ($50-90)
                        </button>
                        <button
                            @click="accommodationFilter = 'mid-range'"
                            :class="accommodationFilter === 'mid-range'
                                ? 'bg-primary-1 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Mid-Range ($90-150)
                        </button>
                        <button
                            @click="accommodationFilter = 'premium'"
                            :class="accommodationFilter === 'premium'
                                ? 'bg-primary-1 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Premium ($150+)
                        </button>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="hotel in filteredHotels"
                            :key="hotel.name"
                            class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all"
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
                                        <div class="p-2 bg-accent-2/20 rounded-lg">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a
                                            :href="`mailto:${hotel.email}`"
                                            class="hover:text-primary-1 break-all"
                                        >
                                            {{ hotel.email }}
                                        </a>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                        </svg>
                                        <span class="font-medium">{{ hotel.priceRange }} per night</span>
                                    </div>

                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ hotel.distance }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <span :class="{
                                        'bg-primary-2/20 text-primary-2': hotel.category === 'budget',
                                        'bg-accent-1/20 text-accent-1': hotel.category === 'mid-range',
                                        'bg-primary-1/20 text-primary-1': hotel.category === 'premium'
                                    }" class="inline-block px-3 py-1 rounded-full text-xs font-medium">
                                        {{ hotel.category.charAt(0).toUpperCase() + hotel.category.slice(1).replace('-', ' ') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.accommodation_booking_tips && settings.accommodation_booking_tips.length > 0" class="bg-accent-2/10 border border-accent-2/30 rounded-xl p-6">
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

                    <div v-if="settings.extend_intro" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-300 p-6">
                        <p class="font-body text-lg text-gray-800">
                            {{ settings.extend_intro }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="p-3 bg-amber-100 rounded-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-title text-xl font-semibold text-gray-900 mb-2">
                                    {{ settings.extend_process_title || 'Extension Process' }}
                                </h3>
                                <p v-if="settings.extend_process_description" class="font-body text-gray-700">
                                    {{ settings.extend_process_description }}
                                </p>
                            </div>
                        </div>

                        <div v-if="settings.extend_process_steps && settings.extend_process_steps.length > 0" class="space-y-4">
                            <div v-for="(step, index) in settings.extend_process_steps" :key="index" class="flex items-start gap-3 font-body text-gray-700">
                                <span class="text-primary-1 font-bold mt-1">{{ index + 1 }}.</span>
                                <div>
                                    <p class="font-medium">{{ step.title }}</p>
                                    <p v-if="step.note" class="text-sm text-gray-600">{{ step.note }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-title text-xl font-semibold text-gray-900 mb-2">
                                    {{ settings.extend_costs_title || 'Extension Costs' }}
                                </h3>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div v-if="settings.extend_sdf_amount" class="bg-accent-2/10 border border-accent-2/30 rounded-lg p-4">
                                <p class="font-body text-gray-900 mb-1">
                                    <strong class="text-xl">{{ settings.extend_sdf_amount }}</strong> per night
                                </p>
                                <p v-if="settings.extend_sdf_note" class="font-body text-sm text-gray-600">
                                    {{ settings.extend_sdf_note }}
                                </p>
                            </div>

                            <div v-if="settings.extend_additional_costs && settings.extend_additional_costs.length > 0" class="space-y-2 font-body text-gray-700">
                                <p class="font-medium">Additional costs to consider:</p>
                                <ul class="space-y-2 ml-4">
                                    <li class="flex items-start gap-2">
                                        <span class="text-primary-1">•</span>
                                        <span>Accommodation (varies by hotel)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-primary-1">•</span>
                                        <span>Licensed tour guide (mandatory)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-primary-1">•</span>
                                        <span>Transportation for sightseeing</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-primary-1">•</span>
                                        <span>Meals outside hotel</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.air_travel_guidance_points && settings.air_travel_guidance_points.length > 0" class="bg-accent-1/10 border-2 border-accent-1/30 rounded-xl p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-accent-1 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="font-title text-xl font-semibold text-gray-900">
                                    {{ settings.extend_requirement_title || 'Important Requirement' }}
                                </h3>
                                <div class="font-body text-gray-700 space-y-2">
                                    <p v-if="settings.extend_requirement_description">
                                        {{ settings.extend_requirement_description }}
                                    </p>
                                    <p v-if="settings.extend_requirement_note" class="text-sm">
                                        {{ settings.extend_requirement_note }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.extend_portal_title || settings.extend_portal_url" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.extend_portal_title || 'Ready to Extend?' }}
                        </h3>
                        <p v-if="settings.extend_portal_description" class="font-body text-gray-700 mb-4">
                            {{ settings.extend_portal_description }}
                        </p>
                        <a
                            v-if="settings.extend_portal_url"
                            :href="settings.extend_portal_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-1 text-white rounded-lg hover:bg-primary-1/90 transition-colors"
                        >
                            Official Visa Portal
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>

                    <div v-if="settings.extend_activities && settings.extend_activities.length > 0" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.extend_activities_title || 'Popular Extension Activities' }}
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div v-for="(activity, index) in settings.extend_activities" :key="index" class="bg-white rounded-lg p-4">
                                <h4 class="font-title font-semibold text-gray-900 mb-2">{{ activity.title }}</h4>
                                <p class="font-body text-sm text-gray-600">
                                    {{ activity.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Companions Tab -->
                <div v-show="activeTab === 'companions'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="font-title text-3xl font-bold text-gray-900">{{ settings.companions_title || 'Travel With Companions' }}</h2>

                    <div v-if="settings.companions_intro" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-300 p-6">
                        <div class="flex gap-4">
                            <svg class="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="font-body text-lg text-gray-800">
                                {{ settings.companions_intro }}
                            </p>
                        </div>
                    </div>

                    <div v-if="settings.companions_requirements && settings.companions_requirements.length > 0" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
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
                                <div class="p-2 bg-accent-1/20 rounded-lg flex-shrink-0">
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

                    <div v-if="settings.companions_costs && settings.companions_costs.length > 0" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-title text-xl font-semibold text-gray-900 mb-4">
                            {{ settings.companions_costs_title || 'Expected Costs for Companions' }}
                        </h3>
                        <div class="space-y-3">
                            <div v-for="(cost, index) in settings.companions_costs" :key="index" class="flex justify-between items-center py-3" :class="index < settings.companions_costs.length - 1 ? 'border-b border-gray-100' : ''">
                                <span class="font-body text-gray-700">{{ cost.item }}</span>
                                <span class="font-title font-semibold text-gray-900">{{ cost.amount }}</span>
                            </div>
                            <div v-if="settings.companions_costs_note" class="bg-blue-50 rounded-lg p-4 mt-4">
                                <p class="font-body text-sm text-gray-700">
                                    <strong>{{ settings.companions_costs_note }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="settings.companions_considerations && settings.companions_considerations.length > 0" class="bg-accent-2/10 border border-accent-2/30 rounded-xl p-6">
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

                    <div v-if="settings.companions_tips && settings.companions_tips.length > 0" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
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

                    <div v-if="settings.companions_resources && settings.companions_resources.length > 0" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
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
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                <div>
                                    <p class="font-title font-medium text-gray-900">{{ resource.title }}</p>
                                    <p class="font-body text-sm text-gray-600">{{ resource.description }}</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
