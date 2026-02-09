<script setup>
import { inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import SectionReveal from './SectionReveal.vue';

const openRegister = inject('openRegister');

const actions = [
    { label: 'Register Here', href: '#', active: true, primary: true, isRegister: true },
    { label: 'Call for Submissions', href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', active: true, primary: false, external: true },
    { label: 'Disseminate the Event', href: '/media-kit', active: true, primary: false },
    { label: 'Support the Event Financially', href: '/support', active: true, primary: false },
];
</script>

<template>
    <section id="cta" class="scroll-mt-24 border-t border-primary-2/20 bg-primary-2/5 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                    Call to Actions
                </h2>
                <div class="mt-12 flex flex-col items-center gap-4 sm:flex-row sm:flex-wrap sm:justify-center sm:gap-6">
                    <template v-for="action in actions" :key="action.label">
                        <button
                            v-if="action.isRegister"
                            @click="openRegister"
                            :class="[
                                'inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 font-title text-sm font-semibold transition sm:px-8 sm:py-4 sm:text-base',
                                'bg-accent-2 text-primary-1 hover:bg-accent-2/90 hover:shadow-lg hover:-translate-y-0.5',
                            ]"
                        >
                            {{ action.label }}
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                        <a
                            v-else-if="action.external"
                            :href="action.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            :class="[
                                'inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 font-title text-sm font-semibold transition sm:px-8 sm:py-4 sm:text-base',
                                'border-2 border-primary-1 text-primary-1 hover:bg-primary-1 hover:text-white',
                                !action.active && 'pointer-events-none opacity-60',
                            ]"
                        >
                            {{ action.label }}
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <Link
                            v-else
                            :href="action.href"
                            :class="[
                                'inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 font-title text-sm font-semibold transition sm:px-8 sm:py-4 sm:text-base',
                                action.primary
                                    ? 'bg-accent-2 text-primary-1 hover:bg-accent-2/90 hover:shadow-lg hover:-translate-y-0.5'
                                    : 'border-2 border-primary-1 text-primary-1 hover:bg-primary-1 hover:text-white',
                                !action.active && 'pointer-events-none opacity-60',
                            ]"
                        >
                            {{ action.label }}
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                    </template>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
