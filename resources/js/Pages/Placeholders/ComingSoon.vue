<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

defineProps({
    title: { type: String, default: 'Coming Soon' },
});
</script>

<template>
    <Head :title="title" />
    <EventLayout>
        <section class="flex min-h-[60vh] flex-col items-center justify-center px-4 py-24">
            <h1 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">{{ title }}</h1>
            <p class="mt-4 font-body text-center text-ink">This page is under development. Check back soon.</p>
            <Link href="/" class="mt-8 rounded-full bg-accent-2 px-6 py-3 font-title font-semibold text-primary-1 transition hover:bg-accent-2/90">
                Back to Home
            </Link>
        </section>
    </EventLayout>
</template>
