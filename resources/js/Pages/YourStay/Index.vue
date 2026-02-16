<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

defineProps({
    settings: { type: Object, default: () => ({}) },
});

const activeTab = ref('field-visits');

const tabs = [
    { id: 'field-visits', label: 'Field Visits', icon: 'map-pin' },
    { id: 'air-travel', label: 'Air Travel', icon: 'plane' },
    { id: 'visa-entry', label: 'Visa & Entry', icon: 'document' },
    { id: 'upon-arrival', label: 'Upon Arrival', icon: 'suitcase' },
    { id: 'accommodation', label: 'Accommodation', icon: 'building' },
    { id: 'extend-stay', label: 'Extend Stay', icon: 'calendar-plus' },
    { id: 'companions', label: 'Travel With Companions', icon: 'users' },
];
</script>

<template>
    <Head title="Your Stay in Bhutan" />
    <EventLayout>
        <section class="relative min-h-[70vh] overflow-hidden sm:min-h-[75vh]">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('/brand/img55.jpg');"
            />
            <div class="absolute inset-0 z-10 bg-black/50" />
            <div class="relative z-20 mx-auto flex min-h-[70vh] max-w-7xl flex-col items-center justify-center px-4 py-24 text-center sm:min-h-[75vh] sm:px-6 lg:px-8">
                <h1 class="font-title text-4xl font-bold leading-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                    Your Stay in Bhutan
                </h1>
                <p class="mt-6 max-w-2xl font-body text-lg leading-relaxed text-white sm:text-xl lg:text-2xl">
                    Everything you need to know about travel, visa, accommodation, and logistics for the Summit.
                </p>
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

                <div v-show="activeTab === 'air-travel'" class="mx-auto max-w-4xl text-left">
                    <h2 class="font-title text-4xl font-bold text-blue-900 sm:text-5xl lg:text-6xl">
                        Air Travel to Paro
                    </h2>

                    <div class="mt-8 rounded-lg border-2 border-red-300 bg-red-50 p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <svg class="h-8 w-8 shrink-0 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-title text-lg font-bold text-gray-900 sm:text-xl">
                                    Important Booking Guidance
                                </p>
                                <ul class="mt-4 list-none space-y-3 font-title text-base text-gray-900 sm:text-lg">
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                        <span>Do not book flights before visa approval</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                        <span>August is high travel season — book early once approved</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-red-600" />
                                        <span>Limited daily flights operate to Paro</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <h3 class="mt-12 font-title text-2xl font-bold text-gray-900 sm:text-3xl">
                        Airlines Operating to Paro
                    </h3>
                    <div class="mt-8 grid gap-8 sm:grid-cols-2">
                        <div class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:flex-row">
                            <div class="flex flex-1 flex-col justify-center p-6 sm:p-8">
                                <h4 class="font-title text-xl font-bold text-gray-900 sm:text-2xl">Drukair</h4>
                                <p class="mt-2 font-title text-base text-gray-600">Royal Bhutan Airlines</p>
                                <a
                                    href="https://www.drukair.com.bt/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-5 inline-flex w-fit items-center gap-2 rounded-lg bg-red-600 px-5 py-3 font-title text-base font-semibold text-white transition hover:bg-red-700"
                                >
                                    Visit Website
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                            <div class="relative h-48 shrink-0 overflow-hidden rounded-r-xl sm:h-auto sm:w-64">
                                <div class="absolute inset-0 z-0 flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-50" aria-hidden="true">
                                    <svg class="h-20 w-20 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </div>
                                <img
                                    src="/brand/plane.jpg"
                                    alt="Drukair - Royal Bhutan Airlines"
                                    class="absolute inset-0 z-10 h-full w-full object-cover"
                                    loading="lazy"
                                    @error="$event.target.style.display = 'none'"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:flex-row">
                            <div class="flex flex-1 flex-col justify-center p-6 sm:p-8">
                                <h4 class="font-title text-xl font-bold text-gray-900 sm:text-2xl">Bhutan Airlines</h4>
                                <p class="mt-2 font-title text-base text-gray-600">Private carrier</p>
                                <a
                                    href="https://www.bhutanairlines.bt/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-5 inline-flex w-fit items-center gap-2 rounded-lg bg-red-600 px-5 py-3 font-title text-base font-semibold text-white transition hover:bg-red-700"
                                >
                                    Visit Website
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                            <div class="relative h-48 shrink-0 overflow-hidden rounded-r-xl sm:h-auto sm:w-64">
                                <div class="absolute inset-0 z-0 flex items-center justify-center bg-gradient-to-br from-sky-100 to-sky-50" aria-hidden="true">
                                    <svg class="h-20 w-20 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </div>
                                <img
                                    src="/brand/plane2.jpg"
                                    alt="Bhutan Airlines - Private carrier"
                                    class="absolute inset-0 z-10 h-full w-full object-cover"
                                    loading="lazy"
                                    @error="$event.target.style.display = 'none'"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div v-show="activeTab !== 'field-visits' && activeTab !== 'air-travel'" class="mx-auto max-w-3xl text-center">
                    <p class="font-body text-copy">Content for {{ tabs.find(t => t.id === activeTab)?.label ?? 'this section' }} coming soon.</p>
                </div>
            </div>
        </section>
    </EventLayout>
</template>
