<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
</script>

<template>
    <Head title="Registration Closed — Global Conscious Food Systems Summit" />
    <EventLayout>
        <div class="mx-auto flex min-h-[60vh] max-w-2xl flex-col items-center justify-center px-4 py-24 text-center sm:px-6">
            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-accent-2/10 text-accent-2">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="font-title text-3xl font-bold text-primary-1 sm:text-4xl">Registration is now closed</h1>
            <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                Thank you for your interest in the Global Conscious Food Systems Summit. Registration for this
                event has closed. If you have questions about your existing application, please contact us.
            </p>
            <Link
                href="/"
                class="mt-8 inline-flex items-center gap-2 rounded-full bg-primary-2 px-6 py-3 font-body text-sm font-semibold text-white transition hover:bg-primary-2/90"
            >
                Back to Home
            </Link>
        </div>
    </EventLayout>
</template>
