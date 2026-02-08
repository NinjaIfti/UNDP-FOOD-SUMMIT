<script setup>
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import HeroSection from '@/Components/Home/HeroSection.vue';
import SummitIntroSection from '@/Components/Home/SummitIntroSection.vue';
import CoOrganizersSection from '@/Components/Home/CoOrganizersSection.vue';
import VideosSection from '@/Components/Home/VideosSection.vue';
import ConsciousFoodSection from '@/Components/Home/ConsciousFoodSection.vue';
import OutcomesSection from '@/Components/Home/OutcomesSection.vue';
import FeaturedSpeakersSection from '@/Components/Home/FeaturedSpeakersSection.vue';
import WhyBhutanSection from '@/Components/Home/WhyBhutanSection.vue';
import EventsPlanSection from '@/Components/Home/EventsPlanSection.vue';
import CallToActionsSection from '@/Components/Home/CallToActionsSection.vue';
</script>

<template>
    <Head title="Global Conscious Food Systems Summit" />
    <EventLayout>
        <HeroSection />
        <SummitIntroSection />
        <CoOrganizersSection />
        <VideosSection />
        <ConsciousFoodSection />
        <OutcomesSection />
        <FeaturedSpeakersSection />
        <WhyBhutanSection />
        <EventsPlanSection />
        <CallToActionsSection />
    </EventLayout>
</template>
