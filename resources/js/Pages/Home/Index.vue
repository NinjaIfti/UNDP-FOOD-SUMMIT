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
import CallToActionsSection from '@/Components/Home/CallToActionsSection.vue';
import WhyJoinSummit from '@/Components/Home/WhyJoinSummit.vue';
import OnlineSpeakersSection from '@/Components/Home/OnlineSpeakersSection.vue';
import HostSection from '@/Components/Home/HostSection.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <Head title="Global Conscious Food Systems Summit" />
    <EventLayout>
        <HeroSection :settings="settings" />
        <SummitIntroSection :settings="settings" />
	<WhyJoinSummit :settings="settings" />
        <CallToActionsSection :settings="settings" class="-mt-10 sm:-mt-26" />
        <VideosSection :settings="settings" />
        <FeaturedSpeakersSection :settings="settings" />
       <!-- <OnlineSpeakersSection :settings="settings" /> -->
       <!-- <HostSection :settings="settings" /> -->
        <ConsciousFoodSection :settings="settings" />
        <!-- <OutcomesSection :settings="settings" /> -->
        <WhyBhutanSection :settings="settings" />
        <CoOrganizersSection :settings="settings" />
    </EventLayout>
</template>
