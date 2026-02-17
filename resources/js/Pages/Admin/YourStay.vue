<script setup>
import { ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const activeTab = ref(localStorage.getItem('adminYourStayActiveTab') || 'participation-costs');

watch(activeTab, (newTab) => {
    localStorage.setItem('adminYourStayActiveTab', newTab);
});

const tabs = [
    { id: 'participation-costs', name: 'Participation Costs', icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2zm2-2V9a2 2 0 012-2h2a2 2 0 012 2v.01' },
    { id: 'field-visits', name: 'Field Visits', icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z' },
    { id: 'air-travel', name: 'Air Travel', icon: 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8' },
    { id: 'visa', name: 'Visa & Entry', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 'arrival', name: 'Upon Arrival', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { id: 'accommodation', name: 'Accommodation', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { id: 'extend', name: 'Extend Stay', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
    { id: 'companions', name: 'Companions', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
];

const form = useForm({
    participation_costs_title: props.settings.participation_costs_title || '',
    participation_costs_description: props.settings.participation_costs_description || '',
    participation_costs_announcement: props.settings.participation_costs_announcement || '',
    field_visits_title: props.settings.field_visits_title || '',
    field_visits_info: props.settings.field_visits_info || '',
    field_visits_pending: props.settings.field_visits_pending || '',
    field_visits_expectations: props.settings.field_visits_expectations || [],
    air_travel_title: props.settings.air_travel_title || '',
    air_travel_guidance_title: props.settings.air_travel_guidance_title || '',
    air_travel_guidance_points: props.settings.air_travel_guidance_points || [],
    airlines_title: props.settings.airlines_title || '',
    airlines: props.settings.airlines || [],
    flight_table_title: props.settings.flight_table_title || '',
    flight_table_note: props.settings.flight_table_note || '',
    flight_table_saarc_note: props.settings.flight_table_saarc_note || '',
    flight_routes: props.settings.flight_routes || [],
    transit_visa_title: props.settings.transit_visa_title || '',
    transit_visa_description: props.settings.transit_visa_description || '',
    visa_title: props.settings.visa_title || '',
    visa_support_title: props.settings.visa_support_title || '',
    visa_support_points: props.settings.visa_support_points || [],
    visa_important_title: props.settings.visa_important_title || '',
    visa_important_points: props.settings.visa_important_points || [],
    visa_independent_title: props.settings.visa_independent_title || '',
    visa_independent_description: props.settings.visa_independent_description || '',
    visa_independent_points: props.settings.visa_independent_points || [],
    visa_independent_button_text: props.settings.visa_independent_button_text || '',
    visa_independent_button_url: props.settings.visa_independent_button_url || '',
    arrival_title: props.settings.arrival_title || '',
    mobile_title: props.settings.mobile_title || '',
    mobile_description: props.settings.mobile_description || '',
    mobile_providers: props.settings.mobile_providers || [],
    mobile_cost: props.settings.mobile_cost || '',
    mobile_cost_note: props.settings.mobile_cost_note || '',
    mobile_tip: props.settings.mobile_tip || '',
    currency_title: props.settings.currency_title || '',
    currency_local: props.settings.currency_local || '',
    currency_note: props.settings.currency_note || '',
    currency_accepted: props.settings.currency_accepted || [],
    currency_tip: props.settings.currency_tip || '',
    arrival_tips_title: props.settings.arrival_tips_title || '',
    arrival_tips_points: props.settings.arrival_tips_points || [],
    accommodation_title: props.settings.accommodation_title || '',
    accommodation_important: props.settings.accommodation_important || '',
    accommodation_booking_tips_title: props.settings.accommodation_booking_tips_title || '',
    accommodation_booking_tips: props.settings.accommodation_booking_tips || [],
    hotels: props.settings.hotels || [],
    extend_title: props.settings.extend_title || '',
    extend_intro: props.settings.extend_intro || '',
    extend_process_title: props.settings.extend_process_title || '',
    extend_process_description: props.settings.extend_process_description || '',
    extend_process_steps: props.settings.extend_process_steps || [],
    extend_costs_title: props.settings.extend_costs_title || '',
    extend_sdf_amount: props.settings.extend_sdf_amount || '',
    extend_sdf_note: props.settings.extend_sdf_note || '',
    extend_additional_costs: props.settings.extend_additional_costs || [],
    extend_requirement_title: props.settings.extend_requirement_title || '',
    extend_requirement_description: props.settings.extend_requirement_description || '',
    extend_requirement_note: props.settings.extend_requirement_note || '',
    extend_portal_title: props.settings.extend_portal_title || '',
    extend_portal_description: props.settings.extend_portal_description || '',
    extend_portal_url: props.settings.extend_portal_url || '',
    extend_activities_title: props.settings.extend_activities_title || '',
    extend_activities: props.settings.extend_activities || [],
    companions_title: props.settings.companions_title || '',
    companions_intro: props.settings.companions_intro || '',
    companions_requirements_title: props.settings.companions_requirements_title || '',
    companions_requirements: props.settings.companions_requirements || [],
    companions_costs_title: props.settings.companions_costs_title || '',
    companions_costs: props.settings.companions_costs || [],
    companions_costs_note: props.settings.companions_costs_note || '',
    companions_considerations_title: props.settings.companions_considerations_title || '',
    companions_considerations: props.settings.companions_considerations || [],
    companions_tips_title: props.settings.companions_tips_title || '',
    companions_tips: props.settings.companions_tips || [],
    companions_resources_title: props.settings.companions_resources_title || '',
    companions_resources: props.settings.companions_resources || [],
    included_title: props.settings.included_title || '',
    included_items: props.settings.included_items || [],
    not_included_title: props.settings.not_included_title || '',
    not_included_items: props.settings.not_included_items || [],
});

const submit = () => {
    form.post(route('admin.your-stay.update'), {
        preserveScroll: true,
    });
};

const addArrayItem = (arrayName, defaultValue = '') => {
    if (!form[arrayName]) {
        form[arrayName] = [];
    }
    form[arrayName].push(defaultValue);
};

const removeArrayItem = (arrayName, index) => {
    form[arrayName].splice(index, 1);
};

const addObjectItem = (arrayName, defaultObject = {}) => {
    if (!form[arrayName]) {
        form[arrayName] = [];
    }
    form[arrayName].push({ ...defaultObject });
};

const removeObjectItem = (arrayName, index) => {
    form[arrayName].splice(index, 1);
};
</script>

<template>
    <Head title="Edit Your Stay in Bhutan" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">Edit Your Stay in Bhutan</h2>
                <a
                    href="/your-stay-in-bhutan"
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
                <!-- Participation Costs Section -->
                <div v-show="activeTab === 'participation-costs'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Participation Costs</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.participation_costs_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Description</label>
                            <textarea v-model="form.participation_costs_description" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Announcement Text</label>
                            <textarea v-model="form.participation_costs_announcement" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        
                        <div class="border-t pt-6 mt-6">
                            <h4 class="mb-4 font-title text-base font-bold text-primary-1">What's Included Card</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Card Title</label>
                                    <input v-model="form.included_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink mb-2">Included Items</label>
                                    <div v-for="(item, index) in form.included_items" :key="index" class="mb-4 rounded-lg border border-gray-200 p-4">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="font-body text-xs font-medium text-ink">Item {{ index + 1 }}</span>
                                            <button type="button" @click="removeObjectItem('included_items', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block font-body text-xs font-medium text-ink">Title</label>
                                                <input v-model="form.included_items[index].title" type="text" placeholder="e.g., Event Access" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                            </div>
                                            <div>
                                                <label class="block font-body text-xs font-medium text-ink">Description</label>
                                                <textarea v-model="form.included_items[index].description" rows="3" placeholder="Description text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                                <p class="mt-1 font-body text-xs text-gray-500">Use newlines (Enter) to create bullet points</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <label class="flex items-center gap-2 font-body text-xs font-medium text-ink">
                                                    <input v-model="form.included_items[index].isList" type="checkbox" class="rounded border-gray-300" />
                                                    Display as bullet list
                                                </label>
                                                <label class="flex items-center gap-2 font-body text-xs font-medium text-ink">
                                                    <input v-model="form.included_items[index].highlighted" type="checkbox" class="rounded border-gray-300" />
                                                    Highlighted (special styling)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addObjectItem('included_items', { title: '', description: '', isList: false, highlighted: false })" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Included Item</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6 mt-6">
                            <h4 class="mb-4 font-title text-base font-bold text-primary-1">What's NOT Included Card</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Card Title</label>
                                    <input v-model="form.not_included_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink mb-2">Not Included Items</label>
                                    <div v-for="(item, index) in form.not_included_items" :key="index" class="mb-4 rounded-lg border border-gray-200 p-4">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="font-body text-xs font-medium text-ink">Item {{ index + 1 }}</span>
                                            <button type="button" @click="removeObjectItem('not_included_items', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block font-body text-xs font-medium text-ink">Title</label>
                                                <input v-model="form.not_included_items[index].title" type="text" placeholder="e.g., International Travel" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                            </div>
                                            <div>
                                                <label class="block font-body text-xs font-medium text-ink">Description</label>
                                                <textarea v-model="form.not_included_items[index].description" rows="2" placeholder="Description text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <label class="flex items-center gap-2 font-body text-xs font-medium text-ink">
                                                    <input v-model="form.not_included_items[index].highlighted" type="checkbox" class="rounded border-gray-300" />
                                                    Highlighted (special styling)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addObjectItem('not_included_items', { title: '', description: '', highlighted: false })" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Not Included Item</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Field Visits Section -->
                <div v-show="activeTab === 'field-visits'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Field Visits</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.field_visits_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Info Text</label>
                            <textarea v-model="form.field_visits_info" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Pending Confirmation Text</label>
                            <input v-model="form.field_visits_pending" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">What to Expect (List Items)</label>
                            <div v-for="(item, index) in form.field_visits_expectations" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.field_visits_expectations[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('field_visits_expectations', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('field_visits_expectations', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Item</button>
                        </div>
                    </div>
                </div>

                <!-- Air Travel Section -->
                <div v-show="activeTab === 'air-travel'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Air Travel</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.air_travel_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Guidance Title</label>
                            <input v-model="form.air_travel_guidance_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Guidance Points</label>
                            <div v-for="(item, index) in form.air_travel_guidance_points" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.air_travel_guidance_points[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('air_travel_guidance_points', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('air_travel_guidance_points', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Point</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Airlines Section Title</label>
                            <input v-model="form.airlines_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        
                        <div class="border-t pt-6 mt-6">
                            <h4 class="mb-4 font-title text-base font-bold text-primary-1">Airlines</h4>
                            <div class="space-y-4">
                                <div v-for="(airline, index) in form.airlines" :key="index" class="mb-4 rounded-lg border border-gray-200 p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="font-body text-xs font-medium text-ink">Airline {{ index + 1 }}</span>
                                        <button type="button" @click="removeObjectItem('airlines', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Name</label>
                                            <input v-model="form.airlines[index].name" type="text" placeholder="e.g., Drukair" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Description</label>
                                            <input v-model="form.airlines[index].description" type="text" placeholder="e.g., Royal Bhutan Airlines" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Image Path</label>
                                            <input v-model="form.airlines[index].image" type="text" placeholder="/brand/plane1.jpg" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Website URL</label>
                                            <input v-model="form.airlines[index].website" type="url" placeholder="https://www.example.com" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="addObjectItem('airlines', { name: '', description: '', image: '', website: '' })" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Airline</button>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6 mt-6">
                            <h4 class="mb-4 font-title text-base font-bold text-primary-1">Flight Routes Table</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Table Title</label>
                                    <input v-model="form.flight_table_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink mb-2">Flight Routes</label>
                                    <div v-for="(route, index) in form.flight_routes" :key="index" class="mb-4 rounded-lg border border-gray-200 p-4">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="font-body text-xs font-medium text-ink">Route {{ index + 1 }}</span>
                                            <button type="button" @click="removeObjectItem('flight_routes', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">Airline Name</label>
                                                    <input v-model="form.flight_routes[index].airline" type="text" placeholder="e.g., Drukair" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">Airline Website URL</label>
                                                    <input v-model="form.flight_routes[index].airline_url" type="url" placeholder="https://www.example.com" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">Sector</label>
                                                    <input v-model="form.flight_routes[index].sector" type="text" placeholder="e.g., Bangkok-Paro-Bangkok" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">Sector Note</label>
                                                    <input v-model="form.flight_routes[index].sector_note" type="text" placeholder="e.g., (via Guwahati)" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block font-body text-xs font-medium text-ink">Availability</label>
                                                <textarea v-model="form.flight_routes[index].availability" rows="3" placeholder="e.g., Daily or specific days" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                                <p class="mt-1 font-body text-xs text-gray-500">Use newlines for multiple lines</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">SAARC Business Class</label>
                                                    <input v-model="form.flight_routes[index].saarc_business" type="text" placeholder="USD amount" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">SAARC Economy Class</label>
                                                    <input v-model="form.flight_routes[index].saarc_economy" type="text" placeholder="USD amount" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">International Business Class</label>
                                                    <input v-model="form.flight_routes[index].international_business" type="text" placeholder="USD amount" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                                <div>
                                                    <label class="block font-body text-xs font-medium text-ink">International Economy Class</label>
                                                    <input v-model="form.flight_routes[index].international_economy" type="text" placeholder="USD amount" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <label class="flex items-center gap-2 font-body text-xs font-medium text-ink">
                                                    <input v-model="form.flight_routes[index].is_note" type="checkbox" class="rounded border-gray-300" />
                                                    This is a note row (for airline info messages)
                                                </label>
                                            </div>
                                            <div v-if="form.flight_routes[index].is_note">
                                                <label class="block font-body text-xs font-medium text-ink">Note Text</label>
                                                <textarea v-model="form.flight_routes[index].note" rows="2" placeholder="Note message" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="addObjectItem('flight_routes', { airline: '', airline_url: '', sector: '', sector_note: '', availability: '', saarc_business: '', saarc_economy: '', international_business: '', international_economy: '', is_note: false, note: '' })" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Flight Route</button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Flight Table Note</label>
                            <textarea v-model="form.flight_table_note" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">SAARC Note</label>
                            <textarea v-model="form.flight_table_saarc_note" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Transit Visa Title</label>
                            <input v-model="form.transit_visa_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Transit Visa Description</label>
                            <textarea v-model="form.transit_visa_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Visa Section -->
                <div v-show="activeTab === 'visa'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Visa & Entry</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.visa_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Support Title</label>
                            <input v-model="form.visa_support_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Support Points</label>
                            <div v-for="(item, index) in form.visa_support_points" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.visa_support_points[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('visa_support_points', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('visa_support_points', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Point</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Important Notes Title</label>
                            <input v-model="form.visa_important_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Important Points</label>
                            <div v-for="(item, index) in form.visa_important_points" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.visa_important_points[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('visa_important_points', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('visa_important_points', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Point</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Independent Application Title</label>
                            <input v-model="form.visa_independent_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Independent Description</label>
                            <textarea v-model="form.visa_independent_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Independent Points</label>
                            <div v-for="(item, index) in form.visa_independent_points" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.visa_independent_points[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('visa_independent_points', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('visa_independent_points', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Point</button>
                        </div>
                        <div class="border-t pt-4 mt-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Button</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Button Text</label>
                                    <input v-model="form.visa_independent_button_text" type="text" placeholder="e.g., Official Immigration Website" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Button URL</label>
                                    <input v-model="form.visa_independent_button_url" type="url" placeholder="https://www.example.com" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Arrival Section -->
                <div v-show="activeTab === 'arrival'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Upon Arrival</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.arrival_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Mobile & Internet</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Title</label>
                                    <input v-model="form.mobile_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Description</label>
                                    <textarea v-model="form.mobile_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink mb-2">Providers</label>
                                    <div v-for="(item, index) in form.mobile_providers" :key="index" class="mb-2 flex gap-2">
                                        <input v-model="form.mobile_providers[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        <button type="button" @click="removeArrayItem('mobile_providers', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                                    </div>
                                    <button type="button" @click="addArrayItem('mobile_providers', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Provider</button>
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Cost</label>
                                    <input v-model="form.mobile_cost" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Cost Note</label>
                                    <input v-model="form.mobile_cost_note" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Tip</label>
                                    <input v-model="form.mobile_tip" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                            </div>
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Currency Exchange</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Title</label>
                                    <input v-model="form.currency_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Local Currency</label>
                                    <input v-model="form.currency_local" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Note</label>
                                    <input v-model="form.currency_note" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink mb-2">Accepted Methods</label>
                                    <div v-for="(item, index) in form.currency_accepted" :key="index" class="mb-2 flex gap-2">
                                        <input v-model="form.currency_accepted[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        <button type="button" @click="removeArrayItem('currency_accepted', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                                    </div>
                                    <button type="button" @click="addArrayItem('currency_accepted', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Method</button>
                                </div>
                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Tip</label>
                                    <input v-model="form.currency_tip" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                            </div>
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Other Arrival Tips</h4>
                            <div>
                                <label class="block font-body text-sm font-medium text-ink">Title</label>
                                <input v-model="form.arrival_tips_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <div class="mt-3">
                                <label class="block font-body text-sm font-medium text-ink mb-2">Tips</label>
                                <div v-for="(item, index) in form.arrival_tips_points" :key="index" class="mb-2 flex gap-2">
                                    <input v-model="form.arrival_tips_points[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    <button type="button" @click="removeArrayItem('arrival_tips_points', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <button type="button" @click="addArrayItem('arrival_tips_points', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Tip</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accommodation Section -->
                <div v-show="activeTab === 'accommodation'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Accommodation</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.accommodation_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Important Notice</label>
                            <textarea v-model="form.accommodation_important" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Booking Tips Title</label>
                            <input v-model="form.accommodation_booking_tips_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Booking Tips</label>
                            <div v-for="(item, index) in form.accommodation_booking_tips" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.accommodation_booking_tips[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('accommodation_booking_tips', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('accommodation_booking_tips', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Tip</button>
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Hotels</h4>
                            <div v-for="(hotel, index) in form.hotels" :key="index" class="mb-4 rounded-lg border border-gray-200 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <h5 class="font-title text-sm font-bold text-primary-1">Hotel {{ index + 1 }}</h5>
                                    <button type="button" @click="removeObjectItem('hotels', index)" class="rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Image URL/Path</label>
                                        <input v-model="form.hotels[index].image" type="text" placeholder="/images/hotels/hotel-name.jpg" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        <p class="mt-1 font-body text-xs text-gray-500">Enter image path (e.g., /images/hotels/hotel.jpg) or full URL</p>
                                    </div>
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Name</label>
                                            <input v-model="form.hotels[index].name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Email</label>
                                            <input v-model="form.hotels[index].email" type="email" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Price Range</label>
                                            <input v-model="form.hotels[index].priceRange" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Distance</label>
                                            <input v-model="form.hotels[index].distance" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                        </div>
                                        <div>
                                            <label class="block font-body text-xs font-medium text-ink">Category</label>
                                            <select v-model="form.hotels[index].category" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                                <option value="budget">Budget</option>
                                                <option value="mid-range">Mid-Range</option>
                                                <option value="premium">Premium</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center">
                                            <label class="flex items-center gap-2 font-body text-xs font-medium text-ink">
                                                <input v-model="form.hotels[index].nearAirport" type="checkbox" class="rounded border-gray-300" />
                                                Near Airport
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" @click="addObjectItem('hotels', { name: '', email: '', priceRange: '', distance: '', category: 'mid-range', nearAirport: false, image: '' })" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Hotel</button>
                        </div>
                    </div>
                </div>

                <!-- Extend Stay Section -->
                <div v-show="activeTab === 'extend'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Extend Stay</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.extend_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Intro Text</label>
                            <textarea v-model="form.extend_intro" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Process Title</label>
                            <input v-model="form.extend_process_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Process Description</label>
                            <textarea v-model="form.extend_process_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Process Steps</label>
                            <div v-for="(step, index) in form.extend_process_steps" :key="index" class="mb-3 rounded-lg border border-gray-200 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-body text-xs font-medium text-ink">Step {{ index + 1 }}</span>
                                    <button type="button" @click="removeObjectItem('extend_process_steps', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <input v-model="form.extend_process_steps[index].title" type="text" placeholder="Title" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <input v-model="form.extend_process_steps[index].note" type="text" placeholder="Note" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <button type="button" @click="addObjectItem('extend_process_steps', { title: '', note: '' })" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Step</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Costs Title</label>
                            <input v-model="form.extend_costs_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">SDF Amount</label>
                            <input v-model="form.extend_sdf_amount" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">SDF Note</label>
                            <input v-model="form.extend_sdf_note" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Additional Costs</label>
                            <div v-for="(item, index) in form.extend_additional_costs" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.extend_additional_costs[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('extend_additional_costs', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('extend_additional_costs', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Cost</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Requirement Title</label>
                            <input v-model="form.extend_requirement_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Requirement Description</label>
                            <textarea v-model="form.extend_requirement_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Requirement Note</label>
                            <input v-model="form.extend_requirement_note" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Portal Title</label>
                            <input v-model="form.extend_portal_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Portal Description</label>
                            <textarea v-model="form.extend_portal_description" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Portal URL</label>
                            <input v-model="form.extend_portal_url" type="url" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Activities Title</label>
                            <input v-model="form.extend_activities_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Activities</label>
                            <div v-for="(activity, index) in form.extend_activities" :key="index" class="mb-3 rounded-lg border border-gray-200 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-body text-xs font-medium text-ink">Activity {{ index + 1 }}</span>
                                    <button type="button" @click="removeObjectItem('extend_activities', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <input v-model="form.extend_activities[index].title" type="text" placeholder="Title" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <input v-model="form.extend_activities[index].description" type="text" placeholder="Description" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <button type="button" @click="addObjectItem('extend_activities', { title: '', description: '' })" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Activity</button>
                        </div>
                    </div>
                </div>

                <!-- Companions Section -->
                <div v-show="activeTab === 'companions'" class="rounded-xl bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Travel With Companions</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.companions_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Intro Text</label>
                            <textarea v-model="form.companions_intro" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Requirements Title</label>
                            <input v-model="form.companions_requirements_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Requirements</label>
                            <div v-for="(req, index) in form.companions_requirements" :key="index" class="mb-3 rounded-lg border border-gray-200 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-body text-xs font-medium text-ink">Requirement {{ index + 1 }}</span>
                                    <button type="button" @click="removeObjectItem('companions_requirements', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <input v-model="form.companions_requirements[index].title" type="text" placeholder="Title" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <textarea v-model="form.companions_requirements[index].description" rows="2" placeholder="Description" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                            </div>
                            <button type="button" @click="addObjectItem('companions_requirements', { title: '', description: '' })" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Requirement</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Costs Title</label>
                            <input v-model="form.companions_costs_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Costs</label>
                            <div v-for="(cost, index) in form.companions_costs" :key="index" class="mb-3 rounded-lg border border-gray-200 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-body text-xs font-medium text-ink">Cost {{ index + 1 }}</span>
                                    <button type="button" @click="removeObjectItem('companions_costs', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <input v-model="form.companions_costs[index].item" type="text" placeholder="Item" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <input v-model="form.companions_costs[index].amount" type="text" placeholder="Amount" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <button type="button" @click="addObjectItem('companions_costs', { item: '', amount: '' })" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Cost</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Costs Note</label>
                            <textarea v-model="form.companions_costs_note" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Considerations Title</label>
                            <input v-model="form.companions_considerations_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Considerations</label>
                            <div v-for="(item, index) in form.companions_considerations" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.companions_considerations[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('companions_considerations', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('companions_considerations', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Consideration</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Tips Title</label>
                            <input v-model="form.companions_tips_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Tips</label>
                            <div v-for="(item, index) in form.companions_tips" :key="index" class="mb-2 flex gap-2">
                                <input v-model="form.companions_tips[index]" type="text" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <button type="button" @click="removeArrayItem('companions_tips', index)" class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200">Remove</button>
                            </div>
                            <button type="button" @click="addArrayItem('companions_tips', '')" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Tip</button>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Resources Title</label>
                            <input v-model="form.companions_resources_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink mb-2">Resources</label>
                            <div v-for="(resource, index) in form.companions_resources" :key="index" class="mb-3 rounded-lg border border-gray-200 p-3">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-body text-xs font-medium text-ink">Resource {{ index + 1 }}</span>
                                    <button type="button" @click="removeObjectItem('companions_resources', index)" class="rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <input v-model="form.companions_resources[index].title" type="text" placeholder="Title" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <input v-model="form.companions_resources[index].description" type="text" placeholder="Description" class="mb-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                <input v-model="form.companions_resources[index].url" type="url" placeholder="URL" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <button type="button" @click="addObjectItem('companions_resources', { title: '', description: '', url: '' })" class="mt-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:border-accent-1 hover:bg-accent-1/5 hover:text-accent-1">Add Resource</button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="sticky bottom-0 rounded-xl bg-white p-4 shadow-lg sm:p-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-accent-1 px-6 py-3 font-title text-base font-semibold text-white transition hover:bg-accent-1/90 disabled:opacity-50 sm:w-auto"
                    >
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
