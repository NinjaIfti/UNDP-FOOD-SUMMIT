<script setup>
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import FaqSection from '@/Components/Home/FaqSection.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <Head title="FAQs - Global Conscious Food Systems Summit" />
    <EventLayout>
        <FaqSection :settings="settings" />
    </EventLayout>
</template>
