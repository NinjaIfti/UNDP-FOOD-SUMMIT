<script setup>
import { computed } from 'vue';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

const organizers = computed(() => [
    { 
        name: 'Royal Government of Bhutan', 
        logo: props.settings.govt_logo || '/brand/govt.png', 
        url: 'https://www.gov.bt' 
    },
    { 
        name: 'United Nations Development Programme', 
        logo: props.settings.undp_logo || '/brand/undp.png', 
        url: 'https://www.undp.org/bhutan' 
    },
    { 
        name: 'Conscious Food Systems Alliance', 
        logo: props.settings.cofsa_logo || '/brand/cofsa.png', 
        url: 'https://consciousfoodsystems.org' 
    }
]);
</script>

<template>
    <section class="relative overflow-hidden border-y border-primary-1/20 bg-[#F4F1EA] py-16 sm:py-20 lg:py-24">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <div class="flex items-center justify-center gap-10">
                    <div class="hidden h-[3px] w-48 bg-gradient-to-r from-transparent via-accent-2/70 to-accent-2 sm:block lg:w-80" />
                    <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                        Co-Organizers
                    </h2>
                    <div class="hidden h-[3px] w-48 bg-gradient-to-l from-transparent via-accent-2/70 to-accent-2 sm:block lg:w-80" />
                </div>
                <p class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg">
                    Our work and impact would not be possible without the dedicated financial support of numerous individuals, foundations and companies who are committed to its success.
                </p>
                
                <div class="mt-8 flex items-center justify-center gap-3 sm:gap-4 lg:gap-5">
                    <a
                        v-for="org in organizers"
                        :key="org.name"
                        :href="org.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex items-center justify-center"
                    >
                        <div class="flex h-16 items-center justify-center sm:h-20 lg:h-24">
                            <img 
                                :src="org.logo" 
                                :alt="org.name" 
                                class="max-h-full max-w-[350px] object-contain transition duration-300 group-hover:scale-105"
                            />
                        </div>
                    </a>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
