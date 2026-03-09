<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

const actions = computed(() => props.settings?.cta_actions || [
    {
        title: 'Register Here!',
        desc: 'Secure your spot at the first-ever Global Conscious Food Systems Summit in Bhutan.',
        href: '/register-summit',
        cta: 'Register Now',
        icon: 'ticket',
        color: 'bg-accent-2',
        textColor: 'text-primary-1',
        external: false,
    },
    {
        title: 'Call for Submissions',
        desc: 'Contribute your experience — respond to our Call for Submissions and help shape the Summit.',
        href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf',
        cta: 'Submit Now',
        icon: 'document',
        color: 'bg-primary-2',
        textColor: 'text-white',
        external: true,
    },
    {
        title: 'Help Us Spread the Word!',
        desc: 'Download our Communication Kit and help us disseminate the event across your networks.',
        href: '/media-kit',
        cta: 'Get the Kit',
        icon: 'megaphone',
        color: 'bg-accent-1',
        textColor: 'text-white',
        external: false,
    },
    {
        title: 'Have Any Questions?',
        desc: 'Check out the FAQs for answers to common questions about the Summit, travel, and registration.',
        href: '/faq',
        cta: 'View FAQs',
        icon: 'question',
        color: 'bg-primary-1',
        textColor: 'text-white',
        external: false,
    },
]);
</script>

<template>
    <section id="cta" class="scroll-mt-24 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <div class="flex items-center justify-center gap-10">
                    <div class="hidden h-[3px] w-48 bg-gradient-to-r from-transparent via-accent-2/70 to-accent-2 sm:block lg:w-80" />
                    <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                        {{ settings?.cta_section_title || 'Call to Actions' }}
                    </h2>
                    <div class="hidden h-[3px] w-48 bg-gradient-to-l from-transparent via-accent-2/70 to-accent-2 sm:block lg:w-80" />
                </div>
                <p class="mx-auto mt-3 max-w-2xl text-center font-body text-copy">
                    {{ settings?.cta_section_subtitle || 'There are many ways to get involved with the Summit. Choose how you\'d like to participate.' }}
                </p>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <template v-for="action in actions" :key="action.title">
                        <component
                            :is="action.external ? 'a' : Link"
                            :href="action.href"
                            :target="action.external ? '_blank' : undefined"
                            :rel="action.external ? 'noopener noreferrer' : undefined"
                            class="group flex flex-col rounded-2xl border border-primary-1/20 bg-white p-6 shadow-md transition hover:-translate-y-1 hover:shadow-xl"
                        >
                            <!-- Icon -->
                            <div :class="[action.color, 'flex h-12 w-12 items-center justify-center rounded-xl']">
                                <!-- Ticket -->
                                <svg v-if="action.icon === 'ticket'" :class="[action.textColor, 'h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <!-- Document -->
                                <svg v-else-if="action.icon === 'document'" :class="[action.textColor, 'h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <!-- Megaphone -->
                                <svg v-else-if="action.icon === 'megaphone'" :class="[action.textColor, 'h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                <!-- Question -->
                                <svg v-else-if="action.icon === 'question'" :class="[action.textColor, 'h-6 w-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <!-- Content -->
                            <h3 class="mt-4 font-title text-xl font-bold text-primary-1">{{ action.title }}</h3>
                            <p class="mt-2 flex-1 font-body text-base leading-relaxed text-copy">{{ action.desc }}</p>

                            <!-- CTA link -->
                            <span class="mt-4 inline-flex items-center gap-1.5 font-body text-base font-semibold text-accent-1 transition group-hover:gap-2.5">
                                {{ action.cta }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </component>
                    </template>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
