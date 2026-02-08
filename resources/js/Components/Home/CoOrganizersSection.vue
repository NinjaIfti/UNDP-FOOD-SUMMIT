<script setup>
import SectionReveal from './SectionReveal.vue';

const organizers = [
    { name: 'Royal Government of Bhutan', url: 'https://www.gov.bt', desc: 'Host' },
    { name: 'Conscious Food Systems Alliance (CoFSA)', url: 'https://consciousfoodsystems.org', desc: 'Co-organizer' },
    { name: 'UNDP Bhutan', url: 'https://www.undp.org/bhutan', desc: 'Partner' },
    { name: 'Bhutan Global Mindfulness & MICE (BGMM)', url: '#', desc: 'Event management partner' },
];
</script>

<template>
    <section class="relative overflow-hidden border-y border-primary-2/20 bg-white py-8 sm:py-10">
        <div class="absolute inset-0 opacity-40">
            <img src="/brand/img34.jpg" alt="" class="h-full w-full object-cover" aria-hidden="true" />
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-xl font-bold uppercase tracking-wide text-primary-1 sm:text-2xl">
                    Co-organizers
                </h2>
                <div class="mt-6 flex flex-wrap justify-center gap-4 sm:gap-6">
                    <a
                        v-for="org in organizers"
                        :key="org.name"
                        :href="org.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group rounded-lg bg-white/80 px-4 py-3 text-center backdrop-blur-sm transition hover:bg-white"
                    >
                        <span class="font-title text-lg font-bold text-primary-1 transition group-hover:text-accent-1 sm:text-xl">
                            {{ org.name }}
                        </span>
                        <span class="mt-0.5 block text-base font-bold text-copy">{{ org.desc }}</span>
                    </a>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
