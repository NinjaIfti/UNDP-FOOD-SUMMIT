<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

defineProps({
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

const hotels = [
    {
        name: 'Hotel Olathang',
        email: 'olathang@druknet.bt',
        priceRange: '$80-120',
        distance: '2.5 km to Dungkar Dzong',
        nearAirport: true,
        category: 'mid-range',
        image: 'https://images.unsplash.com/photo-1663583942499-f8cff8e49e04?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsdXh1cnklMjBCaHV0YW4lMjBob3RlbCUyMHJlc29ydHxlbnwxfHx8fDE3NzEyMjIyNjF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Tashi Namgay Resort',
        email: 'reservation@tashihotels.bt',
        priceRange: '$120-180',
        distance: '3 km to Dungkar Dzong',
        nearAirport: true,
        category: 'premium',
        image: 'https://images.unsplash.com/photo-1761048802383-ecade27b00fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcmVtaXVtJTIwcmVzb3J0JTIwQmh1dGFuJTIwbW91bnRhaW5zfGVufDF8fHx8MTc3MTIyMjI2MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Mandala Resort & Spa',
        email: 'info@mandalaresorts.com',
        priceRange: '$150-250',
        distance: '4 km to Dungkar Dzong',
        nearAirport: false,
        category: 'premium',
        image: 'https://images.unsplash.com/photo-1578658503040-8d375c771a37?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxCaHV0YW4lMjByZXNvcnQlMjBzcGElMjBsdXh1cnl8ZW58MXx8fHwxNzcxMjIyMjYxfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Khangkhu Resort',
        email: 'khangkhu@druknet.bt',
        priceRange: '$60-90',
        distance: '3.5 km to Dungkar Dzong',
        nearAirport: true,
        category: 'budget',
        image: 'https://images.unsplash.com/photo-1590761044169-b9ad903fca4d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxidWRnZXQlMjBob3RlbCUyMGNsZWFuJTIwbW9kZXJufGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Rema Resort',
        email: 'remaresort@druknet.bt',
        priceRange: '$100-140',
        distance: '2 km to Dungkar Dzong',
        nearAirport: true,
        category: 'mid-range',
        image: 'https://images.unsplash.com/photo-1733111248500-fe2ff4d3ccff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFkaXRpb25hbCUyMEJodXRhbiUyMGhvdGVsfGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Hotel Jomolhari',
        email: 'jomolhari@druknet.bt',
        priceRange: '$50-80',
        distance: '3 km to Dungkar Dzong',
        nearAirport: false,
        category: 'budget',
        image: 'https://images.unsplash.com/photo-1766852287368-01975b8ad3d9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb3VudGFpbiUyMHJlc29ydCUyMGhvdGVsJTIwYnVpbGRpbmd8ZW58MXx8fHwxNzcxMjIyMjYzfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Le Méridien Paro',
        email: 'reservations.paro@lemeridien.com',
        priceRange: '$200-350',
        distance: '5 km to Dungkar Dzong',
        nearAirport: false,
        category: 'premium',
        image: 'https://images.unsplash.com/photo-1663583942499-f8cff8e49e04?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxib3V0aXF1ZSUyMGhvdGVsJTIwQmh1dGFufGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    },
    {
        name: 'Tenzinling Resort',
        email: 'info@tenzinling.com',
        priceRange: '$90-130',
        distance: '2.8 km to Dungkar Dzong',
        nearAirport: true,
        category: 'mid-range',
        image: 'https://images.unsplash.com/photo-1761048802383-ecade27b00fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcmVtaXVtJTIwcmVzb3J0JTIwQmh1dGFuJTIwbW91bnRhaW5zfGVufDF8fHx8MTc3MTIyMjI2MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
    }
];

const filteredHotels = computed(() => {
    if (accommodationFilter.value === 'all') {
        return hotels;
    }
    return hotels.filter(hotel => hotel.category === accommodationFilter.value);
});
</script>

<template>
    <Head title="Your Stay in Bhutan" />
    <EventLayout>
        <!-- Participation Costs Section -->
        <section class="bg-gradient-to-br from-amber-50 to-red-50 py-16 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        WHAT ARE THE PARTICIPATION COSTS?
                    </h2>
                    <div class="max-w-3xl mx-auto space-y-4">
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Participation in the Conscious Food Systems Summit requires an entrance ticket, which helps cover the essential costs of organizing and delivering the event, including venue arrangements, meals, local logistics, and administrative coordination.
                        </p>
                        <div class="bg-amber-100 border-l-4 border-[#B91C1C] p-4 rounded-r-lg">
                            <p class="text-gray-800">
                                <strong>Announcement:</strong> The official ticket price will be announced following the formal launch of the Summit in early March. We kindly invite participants to stay tuned for further updates.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- What's Included Card -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-[#B91C1C] to-red-700 p-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">
                                    What's Included
                                </h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Event Access</h4>
                                    <p class="text-sm text-gray-600">Entrance and participation from 01–04 September</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-orange-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Daily Meals</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>• Daily lunch for summit participants</li>
                                        <li>• Two tea/coffee breaks per day</li>
                                        <li>• Between two to three group dinners</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Visa Application</h4>
                                    <p class="text-sm text-gray-600">
                                        Visa application fee of BTN 5,000, plus full coordination of the visa entry permission process. Participants arranging their visa independently will pay USD 65 per person. Indian nationals are exempt from visa fees.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4 bg-amber-50 p-4 rounded-lg border border-amber-200">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-amber-200 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">SDF Waiver (Special)</h4>
                                    <p class="text-sm text-gray-600">
                                        The Royal Government of Bhutan is granting all Summit participants a waiver of the Sustainable Development Fee (SDF), normally USD 100 per adult per night, for the official event period from Friday 28 August to Sunday 06 September, 2026.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Local Transportation</h4>
                                    <p class="text-sm text-gray-600">
                                        Transportation between the hotel in Paro and the event venue from 01-04 September
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- What's NOT Included Card -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-800 p-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white">
                                    What's NOT Included
                                </h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-5">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">International Travel</h4>
                                    <p class="text-sm text-gray-600">International travel to and from Bhutan (Paro airport)</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Accommodation</h4>
                                    <p class="text-sm text-gray-600">Hotel accommodation costs in Paro</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Airport Transfers</h4>
                                    <p class="text-sm text-gray-600">Transportation between Paro airport and your hotel</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Additional Dinners</h4>
                                    <p class="text-sm text-gray-600">One to two dinners outside the official program</p>
                                </div>
                            </div>

                            <div class="flex gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-gray-200 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Personal Expenses</h4>
                                    <p class="text-sm text-gray-600">
                                        Any personal expenses or additional costs incurred during your stay in Bhutan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-nowrap justify-center gap-2 sm:gap-3">
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        type="button"
                        class="inline-flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 font-title text-sm font-semibold transition sm:px-5 sm:text-base"
                        :class="activeTab === tab.id
                            ? 'bg-red-600 text-white'
                            : 'bg-gray-100 text-ink hover:bg-gray-200'"
                        @click="activeTab = tab.id"
                    >
                        <svg
                            v-if="tab.icon === 'map-pin'"
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg
                            v-else-if="tab.icon === 'plane'"
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
                        Will There Be Field Visits?
                    </h2>

                    <div class="mx-auto mt-6 max-w-2xl rounded-lg border border-yellow-400 bg-yellow-50 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-600 font-title text-xs font-bold text-white">
                                i
                            </div>
                            <div>
                                <p class="font-title text-base text-gray-800">
                                    <span class="font-bold">Optional add-on:</span> Field visits on 31 August are available at an additional cost and can be selected during registration.
                                </p>
                                <p class="mt-2 font-title text-sm italic text-gray-500">
                                    (Pending confirmation)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mx-auto mt-8 max-w-2xl rounded-lg border border-gray-200 bg-white p-6">
                        <h3 class="font-title text-lg font-bold text-gray-900 sm:text-xl">
                            What to Expect
                        </h3>
                        <ul class="mt-4 list-none space-y-3 font-title text-gray-800">
                            <li class="flex items-center justify-center gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                <span>Experience Bhutan's unique development approach firsthand</span>
                            </li>
                            <li class="flex items-center justify-center gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                <span>Visit local communities and sustainable development projects</span>
                            </li>
                            <li class="flex items-center justify-center gap-3">
                                <span class="h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                <span>Engage with stakeholders on the ground</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-show="activeTab === 'air-travel'" class="mx-auto max-w-4xl space-y-8">
                    <h2 class="text-3xl font-bold text-gray-900">Air Travel to Paro</h2>

                    <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Important Booking Guidance
                                </h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-600 mt-1">•</span>
                                        <span><strong>Do not book flights before visa approval</strong></span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-600 mt-1">•</span>
                                        <span>August is high travel season — book early once approved</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-600 mt-1">•</span>
                                        <span>Limited daily flights operate to Paro</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                            Airlines Operating to Paro
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-48 overflow-hidden">
                                    <img
                                        src="/brand/plane1.jpg"
                                        alt="Drukair - Royal Bhutan Airlines"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">Drukair</h4>
                                    <p class="text-gray-600 mb-4">Royal Bhutan Airlines</p>
                                    <a
                                        href="https://www.drukair.com.bt/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
                                    >
                                        Visit Website
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-48 overflow-hidden">
                                    <img
                                        src="/brand/plane2.jpg"
                                        alt="Bhutan Airlines - Private carrier"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">Bhutan Airlines</h4>
                                    <p class="text-gray-600 mb-4">Private carrier</p>
                                    <a
                                        href="https://www.bhutanairlines.bt/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
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

                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                            List of available flights to Paro
                        </h3>
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-100 border-b border-gray-300">
                                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">SECTOR</th>
                                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">Flight availability</th>
                                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">SAARC³ Regional Fare (USD)</th>
                                            <th class="px-4 py-3 text-left text-sm font-bold text-gray-900">International Fare (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b border-gray-200 bg-blue-50">
                                            <td colspan="4" class="px-4 py-3 text-center">
                                                <a href="https://www.drukair.com.bt/" target="_blank" rel="noopener noreferrer" class="text-[#B91C1C] font-semibold hover:underline">Drukair</a>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                Bangkok-Paro-Bangkok<br />
                                                <span class="text-gray-600 text-xs">(via Guwahati)</span>
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Daily</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                Business class:<br />
                                                Economy class:
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                Business class:<br />
                                                Economy class:
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                Bangkok-Paro-Bangkok<br />
                                                <span class="text-gray-600 text-xs">(Via Bagdogra)</span>
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                <strong>Tuesday & Saturday</strong><br />
                                                (BKK → Bagdogra→ Paro)<br />
                                                <strong>Monday & Friday</strong><br />
                                                (Paro → Bagdogra → BKK)
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Delhi-Paro-Delhi</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Daily</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Kathmandu-Paro-Kathmandu</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Daily</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Dhaka-Paro-Dhaka</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Monday & Friday</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                Singapore-Paro-Singapore
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                Tuesdays, Thursdays,<br />
                                                Fridays, Sundays<br />
                                                <span class="text-gray-600 text-xs">(via Guwahati)</span>
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Dubai-Paro-Dubai</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                <strong>Tuesday, Friday,<br />
                                                Saturday</strong>.<br />
                                                <strong>Tuesdays, Sundays for<br />
                                                August</strong>
                                            </td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Kolkata-Paro-Kolkata</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">Daily</td>
                                            <td class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900"></td>
                                            <td class="px-4 py-4 text-sm text-gray-900"></td>
                                        </tr>
                                        <tr class="border-b border-gray-200 bg-blue-50">
                                            <td colspan="4" class="px-4 py-3 text-center">
                                                <a href="https://www.bhutanairlines.bt/" target="_blank" rel="noopener noreferrer" class="text-[#B91C1C] font-semibold hover:underline">Bhutan Airlines</a>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td colspan="4" class="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                                                <em class="text-gray-600">Please check Bhutan Airlines website for current flight schedules and availability.</em>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-start gap-3 bg-blue-50 p-4 border-t border-gray-200">
                                <span class="text-blue-600 mt-1">ℹ️</span>
                                <div class="text-sm text-gray-700">
                                    <p class="mb-2">
                                        <strong>Note:</strong> Flight schedules are subject to change. Always verify with the airline before booking.
                                    </p>
                                    <p>
                                        <strong>³ SAARC Regional Fare</strong> applies to citizens of: Afghanistan, Bangladesh, Bhutan, India, Maldives, Nepal, Pakistan, and Sri Lanka.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 border-2 border-orange-300 rounded-xl p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-orange-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        Check Transit Visa Requirements
                                    </h3>
                                    <p class="text-gray-700">
                                        If you're transiting through India or Nepal, you may need a transit visa. Check requirements based on your nationality.
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <a
                                        href="https://indianvisaonline.gov.in/evisa/tvoa.html"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-orange-400 text-gray-900 rounded-lg hover:bg-orange-50 transition-colors"
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
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-orange-400 text-gray-900 rounded-lg hover:bg-orange-50 transition-colors"
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
                    <h2 class="text-3xl font-bold text-gray-900">Visa & Entry Process</h2>

                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-xl p-6 shadow-md">
                        <div class="flex items-start gap-4">
                            <svg class="w-8 h-8 text-amber-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    Summit Visa Support
                                </h3>
                                <div class="space-y-3 text-gray-800">
                                    <div class="flex items-start gap-3">
                                        <span class="text-amber-600 font-bold">✓</span>
                                        <p><strong>Visa fee included:</strong> BTN 5,000 (covered in Summit registration)</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-amber-600 font-bold">✓</span>
                                        <p><strong>Validity:</strong> 10 days to cover the Summit period</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-amber-600 font-bold">✓</span>
                                        <p><strong>Organized by:</strong> Summit organizing team</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="text-amber-600 font-bold">✓</span>
                                        <p><strong>SDF waived:</strong> Sustainable Development Fee waived during Summit dates</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-4">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Important Notes
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start gap-3">
                                        <span class="text-[#B91C1C] mt-1">•</span>
                                        <span>You <strong>cannot enter Bhutan before</strong> your visa start date</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="text-[#B91C1C] mt-1">•</span>
                                        <span>Route permits may be required for travel outside Paro/Thimphu</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="text-[#B91C1C] mt-1">•</span>
                                        <span>Passport must be valid for at least 6 months from entry date</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="text-[#B91C1C] mt-1">•</span>
                                        <span>Keep a digital and physical copy of your visa approval letter</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Applying Independently
                        </h3>
                        <p class="text-gray-700 mb-4">
                            If you're traveling outside the Summit dates or arranging your own visa:
                        </p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Standard visa fee: <strong>USD 65</strong></span>
                            </div>
                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Sustainable Development Fee (SDF): <strong>USD 100 per night</strong></span>
                            </div>
                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Must be arranged through a licensed Bhutanese tour operator</span>
                            </div>
                        </div>
                        <a
                            href="https://www.immigration.gov.bt"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
                        >
                            Official Immigration Website
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Arrival Tab -->
                <div v-show="activeTab === 'arrival'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900">Upon Arrival in Bhutan</h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-amber-100 rounded-lg">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Mobile & Internet
                                </h3>
                            </div>
                            <div class="space-y-3 text-gray-700">
                                <p>
                                    <strong>SIM cards available</strong> at Paro International Airport upon arrival
                                </p>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                    <p class="font-medium text-gray-900">Providers:</p>
                                    <ul class="space-y-1">
                                        <li class="flex items-start gap-2">
                                            <span class="text-[#B91C1C]">•</span>
                                            <span>Bhutan Telecom</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-[#B91C1C]">•</span>
                                            <span>TashiCell</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <p class="font-medium text-gray-900">Cost: BTN 100–500</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Includes data package options
                                    </p>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Bring your passport and an unlocked phone
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Currency Exchange
                                </h3>
                            </div>
                            <div class="space-y-3 text-gray-700">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="font-medium text-gray-900 mb-2">Local Currency:</p>
                                    <p class="text-lg font-bold text-[#B91C1C]">
                                        Bhutanese Ngultrum (BTN)
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Pegged 1:1 with Indian Rupee (INR)
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <p class="font-medium text-gray-900">What's accepted:</p>
                                    <ul class="space-y-1">
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-600">✓</span>
                                            <span>Indian Rupees (INR) widely accepted</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-600">✓</span>
                                            <span>ATMs available in Paro and Thimphu</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-green-600">✓</span>
                                            <span>Credit cards accepted at major hotels</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm text-gray-700">
                                        💡 <strong>Tip:</strong> Exchange money at the airport or banks for better rates
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Other Arrival Tips
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Immigration process may take 30-60 minutes during peak times</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Keep your visa approval letter and hotel booking details handy</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Summit organizers will provide transfer details closer to the event</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-[#B91C1C] mt-1">•</span>
                                <span>Taxis and hotel shuttles available at the airport</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Accommodation Tab -->
                <div v-show="activeTab === 'accommodation'" class="mx-auto max-w-6xl space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900">Accommodation in Paro</h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <p class="text-gray-800">
                            <strong>Important:</strong> All Summit participants must stay in Paro near the venue (Dungkar Dzong). Book early as August is peak season.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="accommodationFilter = 'all'"
                            :class="accommodationFilter === 'all'
                                ? 'bg-[#B91C1C] text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            All Hotels
                        </button>
                        <button
                            @click="accommodationFilter = 'budget'"
                            :class="accommodationFilter === 'budget'
                                ? 'bg-[#B91C1C] text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Budget ($50-90)
                        </button>
                        <button
                            @click="accommodationFilter = 'mid-range'"
                            :class="accommodationFilter === 'mid-range'
                                ? 'bg-[#B91C1C] text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Mid-Range ($90-150)
                        </button>
                        <button
                            @click="accommodationFilter = 'premium'"
                            :class="accommodationFilter === 'premium'
                                ? 'bg-[#B91C1C] text-white'
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
                            <div class="h-48 overflow-hidden">
                                <img
                                    :src="hotel.image"
                                    :alt="hotel.name"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                />
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-amber-100 rounded-lg">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <h3 class="text-lg font-bold text-gray-900 mb-3">
                                    {{ hotel.name }}
                                </h3>

                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start gap-2 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a
                                            :href="`mailto:${hotel.email}`"
                                            class="hover:text-[#B91C1C] break-all"
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
                                        'bg-green-100 text-green-700': hotel.category === 'budget',
                                        'bg-blue-100 text-blue-700': hotel.category === 'mid-range',
                                        'bg-purple-100 text-purple-700': hotel.category === 'premium'
                                    }" class="inline-block px-3 py-1 rounded-full text-xs font-medium">
                                        {{ hotel.category.charAt(0).toUpperCase() + hotel.category.slice(1).replace('-', ' ') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">
                            Booking Tips
                        </h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>Contact hotels directly for Summit group rates</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>Book immediately after receiving visa approval</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>Mention you're attending the Summit for potential discounts</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>Confirm cancellation policies before booking</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Extend Stay Tab -->
                <div v-show="activeTab === 'extend'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900">Extend Your Stay in Bhutan</h2>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-300 p-6">
                        <p class="text-lg text-gray-800">
                            Want to explore more of Bhutan? Extend your visa to experience the Land of the Thunder Dragon beyond the Summit.
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Extension Process
                                </h3>
                                <p class="text-gray-700">
                                    You can modify your visa dates online through the official portal.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] font-bold mt-1">1.</span>
                                <div>
                                    <p class="font-medium">Maximum stay: Up to 90 days total</p>
                                    <p class="text-sm text-gray-600">Subject to approval</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] font-bold mt-1">2.</span>
                                <div>
                                    <p class="font-medium">Apply for extension before your current visa expires</p>
                                    <p class="text-sm text-gray-600">Processing takes 2-3 business days</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 text-gray-700">
                                <span class="text-[#B91C1C] font-bold mt-1">3.</span>
                                <div>
                                    <p class="font-medium">Must be arranged through a licensed tour operator</p>
                                    <p class="text-sm text-gray-600">Cannot extend independently</p>
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Extension Costs
                                </h3>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <p class="text-gray-900 mb-1">
                                    <strong class="text-xl">USD 100</strong> per night
                                </p>
                                <p class="text-sm text-gray-600">
                                    Sustainable Development Fee (SDF)
                                </p>
                            </div>

                            <div class="space-y-2 text-gray-700">
                                <p class="font-medium">Additional costs to consider:</p>
                                <ul class="space-y-2 ml-4">
                                    <li class="flex items-start gap-2">
                                        <span class="text-[#B91C1C]">•</span>
                                        <span>Accommodation (varies by hotel)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[#B91C1C]">•</span>
                                        <span>Licensed tour guide (mandatory)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[#B91C1C]">•</span>
                                        <span>Transportation for sightseeing</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-[#B91C1C]">•</span>
                                        <span>Meals outside hotel</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                        <div class="flex gap-4">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="space-y-3">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Important Requirement
                                </h3>
                                <div class="text-gray-700 space-y-2">
                                    <p>
                                        <strong>Licensed tourist guide required</strong> for all travel outside Paro/Thimphu during your extended stay.
                                    </p>
                                    <p class="text-sm">
                                        This is a Bhutan tourism regulation to ensure visitor safety and cultural preservation.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Ready to Extend?
                        </h3>
                        <p class="text-gray-700 mb-4">
                            Visit the official visa portal or contact your tour operator to arrange your extension.
                        </p>
                        <a
                            href="https://www.tourism.gov.bt"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
                        >
                            Official Visa Portal
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Popular Extension Activities
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Tiger's Nest Monastery</h4>
                                <p class="text-sm text-gray-600">
                                    Iconic clifftop monastery hike (4-5 hours)
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Thimphu Exploration</h4>
                                <p class="text-sm text-gray-600">
                                    Capital city with dzongs and museums
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Punakha Valley</h4>
                                <p class="text-sm text-gray-600">
                                    Ancient capital with stunning fortress
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Cultural Festivals</h4>
                                <p class="text-sm text-gray-600">
                                    Traditional Tshechu celebrations
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Companions Tab -->
                <div v-show="activeTab === 'companions'" class="mx-auto max-w-4xl space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900">Travel With Companions</h2>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-300 p-6">
                        <div class="flex gap-4">
                            <svg class="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-lg text-gray-800">
                                Planning to bring family or friends? Here's what you need to know about traveling to Bhutan with companions.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Key Requirements for Companions
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                                <div class="p-2 bg-red-100 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">
                                        Separate Visa Application
                                    </h4>
                                    <p class="text-gray-700">
                                        Companions must apply for their visa <strong>independently</strong> through the standard tourist visa process. They cannot be included in the Summit visa arrangement.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                                <div class="p-2 bg-amber-100 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">
                                        Tourism Regulations Apply
                                    </h4>
                                    <p class="text-gray-700">
                                        Companions traveling as tourists must follow Bhutan's standard tourism regulations, including the Sustainable Development Fee (SDF).
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-purple-100 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">
                                        Licensed Guide Required
                                    </h4>
                                    <p class="text-gray-700">
                                        Companions must be accompanied by a licensed Bhutanese tour guide and travel through a registered tour operator.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Expected Costs for Companions
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700">Visa Fee</span>
                                <span class="font-semibold text-gray-900">USD 65</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700">Sustainable Development Fee (SDF)</span>
                                <span class="font-semibold text-gray-900">USD 100 / night</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700">Tour Package (approx.)</span>
                                <span class="font-semibold text-gray-900">USD 250-400 / day</span>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 mt-4">
                                <p class="text-sm text-gray-700">
                                    <strong>Note:</strong> Tour packages typically include accommodation, meals, guide, and transportation. Prices vary by season and hotel category.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Special Considerations
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>
                                    <strong>Children under 12:</strong> May be exempt from or receive reduced SDF rates
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>
                                    <strong>Travel timing:</strong> Companions can arrive with you or independently
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>
                                    <strong>Accommodation:</strong> Can stay at same hotel, subject to availability
                                </span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-amber-600 mt-1">•</span>
                                <span>
                                    <strong>Summit access:</strong> Companions may not be able to attend Summit sessions unless registered
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Planning Tips
                        </h3>
                        <div class="space-y-3 text-gray-700">
                            <div class="flex items-start gap-3">
                                <span class="text-[#B91C1C] font-bold">1</span>
                                <p>
                                    Contact a licensed Bhutanese tour operator at least <strong>6-8 weeks before travel</strong>
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-[#B91C1C] font-bold">2</span>
                                <p>
                                    Coordinate visa timings so companion visas align with Summit dates
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-[#B91C1C] font-bold">3</span>
                                <p>
                                    Book the same hotel for convenience, mentioning you're with the Summit
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-[#B91C1C] font-bold">4</span>
                                <p>
                                    Consider arranging activities for companions during Summit sessions
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Helpful Resources
                        </h3>
                        <div class="space-y-3">
                            <a
                                href="https://www.tourism.gov.bt"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">Bhutan Tourism Council</p>
                                    <p class="text-sm text-gray-600">Official tourism information</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            <a
                                href="https://www.immigration.gov.bt"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                <div>
                                    <p class="font-medium text-gray-900">Immigration Portal</p>
                                    <p class="text-sm text-gray-600">Visa application and guidelines</p>
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
